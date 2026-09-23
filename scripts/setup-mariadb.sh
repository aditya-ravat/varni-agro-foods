#!/usr/bin/env bash
# Reset MariaDB root to socket-auth + create the varni user/database for the project.
# Safe to run multiple times. Stops MariaDB briefly (~5 seconds).
#
# Usage:
#   sudo bash scripts/setup-mariadb.sh
#
# What it does:
#   1. Stops MariaDB
#   2. Restarts it in --skip-grant-tables (no auth) on the unix socket only
#   3. Sets root@localhost to unix_socket auth (so 'sudo mariadb' just works)
#   4. Creates database 'varni_erp' and user 'varni'@'localhost' with password 'secret'
#   5. Stops the recovery instance, starts MariaDB normally

set -euo pipefail

if [[ "$(id -u)" -ne 0 ]]; then
  echo "This script must be run as root. Try: sudo bash $0" >&2
  exit 1
fi

SOCKET=/var/run/mysqld/mysqld.sock
MYSQL_USER=mysql

echo "▶ Stopping MariaDB..."
systemctl stop mariadb

echo "▶ Ensuring socket dir exists..."
mkdir -p /var/run/mysqld
chown "$MYSQL_USER":"$MYSQL_USER" /var/run/mysqld

echo "▶ Starting MariaDB in recovery mode (skip-grant-tables)..."
mariadbd --skip-grant-tables --skip-networking \
  --socket="$SOCKET" --user="$MYSQL_USER" \
  --pid-file=/var/run/mysqld/mysqld-recovery.pid \
  > /tmp/mariadb-recovery.log 2>&1 &
RECOVERY_PID=$!

# wait for socket
for i in {1..30}; do
  if [[ -S "$SOCKET" ]]; then break; fi
  sleep 0.5
done

if [[ ! -S "$SOCKET" ]]; then
  echo "✖ Could not start recovery instance. Log:"
  cat /tmp/mariadb-recovery.log
  exit 1
fi

echo "▶ Reconfiguring root + creating varni user..."
mariadb --socket="$SOCKET" -u root <<'SQL'
FLUSH PRIVILEGES;

-- Make root@localhost use unix_socket auth (sudo mariadb just works),
-- with a fallback password 'rootpw' for emergencies.
ALTER USER 'root'@'localhost' IDENTIFIED VIA unix_socket OR mysql_native_password USING PASSWORD('rootpw');

-- App database + user
CREATE DATABASE IF NOT EXISTS varni_erp
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'varni'@'localhost' IDENTIFIED BY 'secret';
ALTER USER 'varni'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL ON varni_erp.* TO 'varni'@'localhost';

FLUSH PRIVILEGES;
SQL

echo "▶ Stopping recovery instance..."
kill "$RECOVERY_PID" 2>/dev/null || true
# wait until pidfile gone
for i in {1..30}; do
  if ! kill -0 "$RECOVERY_PID" 2>/dev/null; then break; fi
  sleep 0.5
done

echo "▶ Starting MariaDB normally..."
systemctl start mariadb

# wait for service
for i in {1..30}; do
  if systemctl is-active --quiet mariadb; then break; fi
  sleep 0.5
done

echo "▶ Verifying..."
sudo -u "$MYSQL_USER" mariadb -e "SELECT 'sudo mariadb works' AS root_check;"
mariadb -uvarni -psecret -e "SELECT 'varni user works' AS varni_check; SHOW DATABASES LIKE 'varni_erp';"

echo "✓ Done."
echo
echo "  Root login:  sudo mariadb       (passwordless via unix_socket)"
echo "  App login:   mariadb -uvarni -psecret"
echo "  Database:    varni_erp"
