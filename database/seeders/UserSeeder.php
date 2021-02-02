<?php
namespace Database\Seeders;
use App\Models\User;
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
        User::factory()->count(1)->create(['role' => User::ROLE_ADMIN]);
        User::factory()->count(40)->create(['role' => User::ROLE_USER]);
    }
}
