<?php

use Illuminate\Database\Seeder;
use App\Models\DB\User;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DrinksTableSeeder::class);
        factory(User::class, 1)->create();
        Artisan::call('passport:client --password --name=test_client -n');
    }
}
