<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RolePermissionSeeder::class,
            SettingSeeder::class,
        ]);

        // Create a default Super Admin user
        $superAdminRoleId = DB::table('roles')->where('slug', 'super_admin')->value('id');

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@city.gov.et',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRoleId,
            'is_active' => true,
        ]);

        $this->call([
            CityContentSeeder::class,
        ]);
    }
}
