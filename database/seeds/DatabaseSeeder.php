<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UserTableSeeder');

        $this->command->info('Table Users  filled data!');
    }

}

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
             'name' => 'admin'
            , 'email' => 'admin@admin.com'
            , 'password' => bcrypt('password')
            , 'type' => 'admin'
            ));

        User::create(array(
             'name' => 'user'
            , 'email' => 'user@user.com'
            , 'password' => bcrypt('password')
            , 'type' => 'user'
            ));
    }
}
