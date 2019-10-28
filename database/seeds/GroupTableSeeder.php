<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // initial group will be 'uncategorized' default
        DB::table('groups')->insert([
            'group_name' => 'Uncategorized'
        ]);
        for ($i = 0; $i < 10; $i++) {
            DB::table('groups')->insert([
                'group_name' => Str::random(10)
            ]);
        }
    }
}
