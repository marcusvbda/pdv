<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class A_AclSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createPermissions();
		$this->createRoles();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createPermissions()
	{
		DB::table("permissions")->truncate();
		Cache::flush('spatie.permission.cache');

		// Permission::create(["group" => "Respostas de Contatos", "name" => "viewlist-leadanswer", "description" => "Ver Listagem de Respostas de Contatos"]);
		// Permission::create(["group" => "Respostas de Contatos", "name" => "create-leadanswer", "description" => "Cadastrar Respostas de Contatos"]);
		// Permission::create(["group" => "Respostas de Contatos", "name" => "edit-leadanswer", "description" => "Editar Respostas de Contatos"]);
		// Permission::create(["group" => "Respostas de Contatos", "name" => "destroy-leadanswer", "description" => "Excluir Respostas de Contatos"]);
	}

	private function createRoles()
	{
		DB::table("roles")->truncate();
		DB::table("model_has_roles")->truncate();
		DB::table("role_has_permissions")->truncate();
		Cache::flush('spatie.role.cache');
		$role = Role::create(["name" => "super-admin", "description" => "Super Administrador", "tenant_id" => 1]);
		$role = Role::create(["name" => "admin", "Description" => "Administrador", "tenant_id" => 1]);
		$role->givePermissionTo(Permission::all());
		$role = Role::create(["name" => "acl_teste", "Description" => "acl_teste", "tenant_id" => 1]);
		$role->givePermissionTo(Permission::all());
	}
}