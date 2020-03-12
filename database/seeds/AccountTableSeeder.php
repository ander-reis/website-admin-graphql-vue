<?php

use Illuminate\Database\Seeder;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\WebsiteAdmin\Models\Account::class, 1)->create([
            'user_id' => 1
        ]);
    }
}
