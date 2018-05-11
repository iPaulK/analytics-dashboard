<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 2)->create(['role_id' => 1]);
        factory(App\Models\User::class, 10)->create(['role_id' => 2]);

        $faker = Faker\Factory::create();

        App\Models\User::create([
            'role_id' => 1,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'active' => true,
            'email' => 'admin@lunapark.com',
            'password' => app()->make('hash')->make('password')
        ]);

        $c = App\Models\User::create([
            'role_id' => 2,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'active' => true,
            'email' => 'employee@lunapark.com',
            'password' => app()->make('hash')->make('password')
        ]);
    }
}
