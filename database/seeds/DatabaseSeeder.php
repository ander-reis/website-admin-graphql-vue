<?php

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
        $this->call(UsersTableSeeder::class);
        $this->call(NoticiasCategoriasTableSeeder::class);
        $this->call(NoticiasTableSeeder::class);
        $this->call(AccountTableSeeder::class);
    }
}
