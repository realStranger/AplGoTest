<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => env('ADMIN_USER_NAME', 'testUser'),
            'email' => env('ADMIN_USER_EMAIL', 'test@example.com'),
            'password' => bcrypt(env('ADMIN_USER_PASS', '111111'))
        ]);

        if (config('app.debug')){
            $this->call([
                UserSeeder::class
            ]);
        }
    }
}
