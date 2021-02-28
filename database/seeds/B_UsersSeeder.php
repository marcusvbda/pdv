<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\{
	Tenant,
	Polo
};

class B_UsersSeeder extends Seeder
{
	private $tenant = null;
	private $users = [
		[
			"name" => "Vinicius Bassalobre",
			"email" => "bassalobre.vinicius@gmail.com",
			"password" => "123mudar",
			"role" => "acl_teste"
		],
		[
			"name" => "admin",
			"email" => "admin@email.com",
			"password" => "admin",
			"role" => "admin"
		],
		[
			"name" => "root",
			"email" => "root@email.com",
			"password" => "root",
			"role" => "super-admin"
		]
	];
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createTenant();
		$this->createPolos();
		$this->createUsers();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createTenant()
	{
		DB::table("tenants")->truncate();
		$this->tenant = Tenant::create([
			"name" => "Comercio Teste",
			"data" => [
				"term" => "<b>{name}</b>, lorem ipsum dolor ipsum ...",
				"city" => "Marilia",
				"state" => "São Paulo"
			]
		]);
	}

	private function createPolos()
	{
		DB::table("polos")->truncate();
		$this->tenant->polos()->create([
			"name" => "Matriz",
			"data" => [
				"head" => true,
				"city" => "Marilia",
			],
		]);
	}

	private function createUsers()
	{
		DB::table("users")->truncate();
		$polo_ids = Polo::pluck("id")->toArray();

		foreach ($this->users as $create_user) {
			$user = new User();
			$user->name = $create_user["name"];
			$user->email = $create_user["email"];
			$user->password = $create_user["password"];;
			$user->tenant_id = $this->tenant->id;
			$user->polo_id = $polo_ids[0];
			$user->save();
			$user->polos()->sync($polo_ids);
			$user->assignRole($create_user["role"]);
		}
	}
}