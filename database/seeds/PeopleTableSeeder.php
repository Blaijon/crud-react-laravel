<?php

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $statusOptions = ['active', 'archived'];
        for ($i=0; $i < 100; $i++) {
            DB::table('people')->insert([
                'first_name' => Str::random(10),
                'last_name' => Str::random(10),
                'email_address' => Str::random(10).'@gmail.com',
                'status' => $statusOptions[array_rand($statusOptions)],
            ]);
        }
    }
}
