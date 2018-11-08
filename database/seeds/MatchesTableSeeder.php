<?php

use Illuminate\Database\Seeder;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('matches')->insert([
            'name' => "Match1",
            'next' => 2,
            'winner' => 1,
            'board' => json_encode([
                1, 0, 2,
                0, 1, 2,
                0, 2, 1,
            ])
        ]);

        DB::table('matches')->insert([
            'name' => "Match2",
            'next' => 1,
            'winner' => 0,
            'board' => json_encode([
                1, 0, 2,
                0, 1, 2,
                0, 0, 0,
            ])
        ]);

        DB::table('matches')->insert([
            'name' => "Match3",
            'next' => 1,
            'winner' => 0,
            'board' => json_encode([
                1, 0, 2,
                0, 1, 2,
                0, 2, 0,
            ])
        ]);

        DB::table('matches')->insert([
            'name' => "Match4",
            'next' => 2,
            'winner' => 0,
            'board' => json_encode([
                0, 0, 0,
                0, 0, 0,
                0, 0, 0,
            ])
        ]);
    }
}
