<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            // facilities
            'facility.view', 'facility.create', 'facility.update', 'facility.delete',
            // chambers
            'chamber.view', 'chamber.create', 'chamber.update', 'chamber.delete',
            // commodities & catalog
            'commodity.view', 'commodity.create', 'commodity.update', 'commodity.delete',
            'tariff.view', 'tariff.create', 'tariff.update', 'tariff.delete',
            // customers
            'customer.view', 'customer.create', 'customer.update', 'customer.delete',
            // operations
            'inward.create', 'outward.create', 'movement.create', 'quality.manage',
            // billing
            'invoice.view', 'invoice.create', 'invoice.update', 'invoice.cancel',
            'payment.view', 'payment.create',
            'rent_run.execute',
            'expense.manage',
            // reports
            'report.stock.view', 'report.occupancy.view', 'report.revenue.view',
            'report.aging.view', 'report.temperature.view',
            // iot
            'iot.view', 'iot.manage',
            // cms
            'cms.manage', 'lead.manage',
            // system
            'user.manage', 'role.manage', 'settings.manage',
            // portal (clients)
            'portal.access',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $branchManager = Role::firstOrCreate(['name' => 'branch-manager', 'guard_name' => 'web']);
        $branchManager->syncPermissions([
            'facility.view', 'chamber.view', 'chamber.update',
            'commodity.view', 'tariff.view',
            'customer.view', 'customer.create', 'customer.update',
            'inward.create', 'outward.create', 'movement.create', 'quality.manage',
            'invoice.view', 'payment.view',
            'report.stock.view', 'report.occupancy.view', 'report.revenue.view', 'report.aging.view', 'report.temperature.view',
            'iot.view', 'iot.manage',
            'lead.manage',
        ]);

        $opsSupervisor = Role::firstOrCreate(['name' => 'operations-supervisor', 'guard_name' => 'web']);
        $opsSupervisor->syncPermissions([
            'facility.view', 'chamber.view',
            'customer.view', 'inward.create', 'outward.create', 'movement.create', 'quality.manage',
            'report.stock.view', 'report.occupancy.view',
            'iot.view',
        ]);

        $gateClerk = Role::firstOrCreate(['name' => 'gate-clerk', 'guard_name' => 'web']);
        $gateClerk->syncPermissions(['inward.create', 'outward.create', 'customer.view']);

        $accounts = Role::firstOrCreate(['name' => 'accounts', 'guard_name' => 'web']);
        $accounts->syncPermissions([
            'customer.view', 'customer.update',
            'invoice.view', 'invoice.create', 'invoice.update', 'invoice.cancel',
            'payment.view', 'payment.create', 'rent_run.execute',
            'expense.manage', 'tariff.view',
            'report.revenue.view', 'report.aging.view',
        ]);

        $cmsEditor = Role::firstOrCreate(['name' => 'cms-editor', 'guard_name' => 'web']);
        $cmsEditor->syncPermissions(['cms.manage', 'lead.manage']);

        $client = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $client->syncPermissions(['portal.access']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@varniagrofoods.com'],
            ['name' => 'Varni Admin', 'phone' => '+919999999999', 'password' => Hash::make('password'), 'is_active' => true]
        );
        $admin->syncRoles(['super-admin']);
    }
}
