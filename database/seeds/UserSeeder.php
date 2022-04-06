<?php

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
    	$admin = [
    		[
    			'name' => 'Admin',
    			'email' => 'admin@admin.com',
    			'role' => 1,
    			'password' => Hash::make('12345678'),
    			'created_at' => date('Y-m-d H:i:s'),
    			'updated_at' => date('Y-m-d H:i:s'),
    		],
    	];

        // Truncate table
    	DB::table('users')->truncate();

        // Insert users to table
    	foreach ($admin as $key => $admin) {
    		User::create([
    			'name' => $admin['name'],
    			'email' => $admin['email'],
    			'role' => $admin['role'],
    			'password' => $admin['password'],
    			'created_at' => $admin['created_at'],
    			'updated_at' => $admin['updated_at'],
    		]);
    	}
    }
    
}
