<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$users = [
			[
				'first_name' 	=> 'John',
				'last_name' 	=> 'Doe',
				'email' 			=> 'johndoe@gmail.com',
				'password' 		=> Hash::make('P@ssw0rd123'),
			],
			[
				'first_name' 	=> 'Jane',
				'last_name' 	=> 'Smith',
				'email' 			=> 'janesmith@gmail.com',
				'password' 		=> Hash::make('P@ssw0rd123'),
			],
			[
				'first_name' 	=> 'Alice',
				'last_name' 	=> 'Johnson',
				'email' 			=> 'alicejohnson@gmail.com',
				'password' 		=> Hash::make('P@ssw0rd123'),
			],
		];

		foreach ($users as $user) {
			User::create($user);
		}
	}
}
