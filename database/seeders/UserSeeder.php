<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::firstOrNew(['email' => 'admin@admin.com'])
            ->fill([
                'name' => 'Admin',
                'password' => password_hash('admin', PASSWORD_BCRYPT)
            ])
            ->save();
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::firstOrNew(['email' => 'user' . $i . '@user.com'])
                ->fill([
                    'name' => 'User' . $i,
                    'password' => password_hash('user', PASSWORD_BCRYPT)
                ])
                ->save();
        }
    }
}
