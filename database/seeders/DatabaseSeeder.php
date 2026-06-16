<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'email'      => 'admin@admin.com',
            'password'   => Hash::make('admin'),
            'role'       => 'admin'
        ]);

        // Create Staff Users
        $staff1 = User::create([
            'first_name' => 'Paul Reimond',
            'last_name'  => 'Barbosa',
            'email'      => 'staff1@lms.com',
            'password'   => Hash::make('staff1'),
            'role'       => 'staff'
        ]);

        $staff2 = User::create([
            'first_name' => 'Mikylla',
            'last_name'  => 'Coronacion',
            'email'      => 'staff2@lms.com',
            'password'   => Hash::make('staff2'),
            'role'       => 'staff'
        ]);

        $staff3 = User::create([
            'first_name' => 'Averyl Ann',
            'last_name'  => 'Abundo',
            'email'      => 'staff3@lms.com',
            'password'   => Hash::make('staff3'),
            'role'       => 'staff'
        ]);

        $staff4 = User::create([
            'first_name' => 'Jheriel',
            'last_name'  => 'Terrado',
            'email'      => 'staff4@lms.com',
            'password'   => Hash::make('staff4'),
            'role'       => 'staff'
        ]);

        // =============================
        // Leads (some unassigned for Admin to assign)
        // Status options: new, contacted, accepted, denied
        // =============================

        // Leads originally under staff1 group (but kept unassigned as your current setup)
        Lead::create([
            'name' => 'Alice Johnson',
            'company' => 'ABC Corporation',
            'email' => 'alice@abc.com',
            'phone' => '09126354854',
            'product' => 'CRM Software',
            'status' => 'new',
            'assigned_to' => null,
            'date' => '2026-01-05',
            'notes' => 'Interested in premium package'
        ]);

        Lead::create([
            'name' => 'Robert Williams',
            'company' => 'XYZ Ltd',
            'email' => 'robert@xyz.com',
            'phone' => '09123456789',
            'product' => 'Cloud Service',
            'status' => 'contacted',
            'assigned_to' => null,
            'date' => '2026-01-04',
            'notes' => 'Follow up next week'
        ]);

        Lead::create([
            'name' => 'Sarah Miller',
            'company' => 'Tech Solutions Inc',
            'email' => 'sarah@techsolutions.com',
            'phone' => '09129876543',
            'product' => 'Cybersecurity Package',
            'status' => 'accepted', // was qualified
            'assigned_to' => null,
            'date' => '2026-01-03',
            'notes' => 'Ready for proposal'
        ]);

        Lead::create([
            'name' => 'David Wilson',
            'company' => 'Marketing Pro',
            'email' => 'david@marketingpro.com',
            'phone' => '09125556677',
            'product' => 'Website Development',
            'status' => 'contacted',
            'assigned_to' => null,
            'date' => '2026-01-07',
            'notes' => 'Needs pricing details'
        ]);

        Lead::create([
            'name' => 'Chris Anderson',
            'company' => 'Startup Hub',
            'email' => 'chris@startuphub.com',
            'phone' => '09129998877',
            'product' => 'IT Support Plan',
            'status' => 'accepted', // was qualified
            'assigned_to' => null,
            'date' => '2026-01-02',
            'notes' => 'Signed contract'
        ]);

        // Leads for staff2 group
        Lead::create([
            'name' => 'Michael Brown',
            'company' => 'Global Enterprises',
            'email' => 'michael@global.com',
            'phone' => '09127778899',
            'product' => 'ERP System',
            'status' => 'new',
            'assigned_to' => null,
            'date' => '2026-01-06',
            'notes' => 'Requested demo'
        ]);

        Lead::create([
            'name' => 'Emma Thompson',
            'company' => 'Design Studio',
            'email' => 'emma@designstudio.com',
            'phone' => '09126667788',
            'product' => 'Branding Package',
            'status' => 'new',
            'assigned_to' => $staff2->id,
            'date' => '2026-01-08',
            'notes' => 'Interested in branding package'
        ]);

        Lead::create([
            'name' => 'Daniel Lee',
            'company' => 'E-Commerce PH',
            'email' => 'daniel@ecommerceph.com',
            'phone' => '09121112233',
            'product' => 'E-Commerce Platform',
            'status' => 'accepted', // was qualified
            'assigned_to' => $staff4->id,
            'date' => '2026-01-09',
            'notes' => 'Budget approved'
        ]);

        Lead::create([
            'name' => 'Sophia Garcia',
            'company' => 'Retail Express',
            'email' => 'sophia@retailexpress.com',
            'phone' => '09124445566',
            'product' => 'POS System',
            'status' => 'contacted',
            'assigned_to' => $staff1->id,
            'date' => '2026-01-01',
            'notes' => 'Chose competitor - follow up'
        ]);

        // Leads for staff3 group
        Lead::create([
            'name' => 'James Wilson',
            'company' => 'FinTech Solutions',
            'email' => 'james@fintech.com',
            'phone' => '09123334455',
            'product' => 'Payment Gateway',
            'status' => 'new',
            'assigned_to' => $staff3->id,
            'date' => '2026-01-10',
            'notes' => 'Initial inquiry'
        ]);

        Lead::create([
            'name' => 'Maria Santos',
            'company' => 'Local Restaurant Chain',
            'email' => 'maria@restaurant.com',
            'phone' => '09127776655',
            'product' => 'Restaurant POS',
            'status' => 'contacted',
            'assigned_to' => $staff1->id,
            'date' => '2026-01-11',
            'notes' => 'Sent proposal'
        ]);

        // Unassigned leads (for Admin to assign)
        Lead::create([
            'name' => 'Juan Dela Cruz',
            'company' => 'Local Business Inc',
            'email' => 'juan@localbusiness.com',
            'phone' => '09128889900',
            'product' => 'Accounting Software',
            'status' => 'new',
            'assigned_to' => null,
            'date' => '2026-01-12',
            'notes' => 'New lead from website'
        ]);

        Lead::create([
            'name' => 'Ana Reyes',
            'company' => 'Real Estate Developers',
            'email' => 'ana@realestate.com',
            'phone' => '09129990011',
            'product' => 'Property Management System',
            'status' => 'new',
            'assigned_to' => null,
            'date' => '2026-01-12',
            'notes' => 'Requested meeting'
        ]);
    }
}
