.PHONY: help setup backend frontend dev migrate seed fresh test lint

help:
	@echo "Varni Agro Foods — make targets"
	@echo "  make setup     Install backend+frontend deps"
	@echo "  make dev       Run backend (8000) and frontend (3000) concurrently"
	@echo "  make migrate   Run DB migrations"
	@echo "  make seed      Seed sample data"
	@echo "  make fresh     Drop & re-create DB with seed data (DESTRUCTIVE)"
	@echo "  make test      Run backend tests"

setup:
	@cd backend && composer install
	@cd frontend && npm install --legacy-peer-deps

backend:
	@cd backend && php artisan serve --host=0.0.0.0 --port=8000

frontend:
	@cd frontend && npm run dev

dev:
	@$(MAKE) -j2 backend frontend

migrate:
	@cd backend && php artisan migrate

seed:
	@cd backend && php artisan db:seed

fresh:
	@cd backend && php artisan migrate:fresh --seed

test:
	@cd backend && ./vendor/bin/pest

lint:
	@cd frontend && npm run lint
