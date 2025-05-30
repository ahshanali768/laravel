<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // --- BEGIN: Import users from SQL dump ---
        $users = [
            ['username' => 'Shaheer', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Nomi', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Shahruk', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Ehtasham', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Ethan', 'role' => 'Admin', 'status' => 'active'],
            ['username' => 'Zafar', 'role' => 'Admin', 'status' => 'active'],
            ['username' => 'Aigppc', 'role' => 'Admin', 'status' => 'active', 'email' => 'admin@acraltech.site'],
            ['username' => 'Tausif', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'MC', 'role' => 'Admin', 'status' => 'active'],
            ['username' => 'Naushad', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Hussain', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Amraan', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Dhiraj', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Shams', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Prince', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'test', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Nitish', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Ekhlaque', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Francis', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Roshan', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Mark', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Shivam', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Muskan', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Sohail', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'David', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Farhan', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Afridi', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Alex', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Rabi', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Sourav', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Joy', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Shahid', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Omar', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Anas', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Sam', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Vicky', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Kevin', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Puja', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Akash', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Sean', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Umair', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Rehan', 'role' => 'agent', 'status' => 'active'],
            ['username' => 'Furkaan', 'role' => 'agent', 'status' => 'active'],
        ];
        $userMap = [];
        foreach ($users as $u) {
            $user = \App\Models\User::updateOrCreate(
                ['username' => $u['username']],
                [
                    'name' => $u['username'],
                    'username' => $u['username'],
                    'email' => $u['email'] ?? strtolower($u['username']) . '@imported.local',
                    'password' => Hash::make('1234'),
                    'status' => $u['status'],
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($u['role']);
            if ($u['username'] === 'Aigppc') {
                // Give super access (all permissions)
                $user->syncPermissions(\Spatie\Permission\Models\Permission::pluck('name')->toArray());
            }
            $userMap[$u['username']] = $user->id;
        }
        // --- END: Import users from SQL dump ---

        // --- BEGIN: Import leads from SQL dump ---
        $leads = [
            [
                'first_name' => 'Tomika',
                'last_name' => 'Freeman',
                'phone' => '5133282404',
                'did_number' => '6052025307',
                'campaign_name' => 'FE Inbound',
                'address' => 'NA',
                'city' => 'Cincinnati',
                'state' => 'OH',
                'zip' => '45246',
                'email' => 'TomikaFreeman@gmail.com',
                'notes' => "Agent greeted properly, confirmed consumer's interest, and conducted a needs-based discussion about life insurance. Consumer explained financial hardship and past experiences. Agent built strong rapport, explained term vs. whole life, and began qualification, but call disconnected before completion. The caller stopped answering .",
                'agent_name' => 'Tausif',
                'status' => 'Approved',
            ],
            [
                'first_name' => 'Christopher',
                'last_name' => 'Williams',
                'phone' => '8134539446',
                'did_number' => '9724330733',
                'campaign_name' => 'ACA CPL',
                'address' => '5849 park street n apt 302',
                'city' => 'Saint Petersburg',
                'state' => 'FL',
                'zip' => '33709',
                'email' => 'ChristopherWilliams@gmail.com',
                'notes' => '9724330733',
                'agent_name' => 'Tausif',
                'status' => 'Pending',
            ],
            // ... (repeat for all leads from the SQL dump) ...
        ];
        foreach ($leads as $lead) {
            \App\Models\Lead::updateOrCreate(
                [
                    'first_name' => $lead['first_name'],
                    'last_name' => $lead['last_name'],
                    'phone' => $lead['phone'],
                    'did_number' => $lead['did_number'],
                    'campaign_name' => $lead['campaign_name'],
                ],
                $lead
            );
        }
        // --- END: Import leads from SQL dump ---
    }
}
