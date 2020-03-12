<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeders to be executed
     * @var array
     */
    private $seeders;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seeders = [
            UsersTableSeeder::class
        ];
         $this->call($this->seeders);
    }
}
