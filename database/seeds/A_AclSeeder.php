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

		Permission::create(["group" => "Produtos", "name" => "viewlist-products", "description" => "Ver Listagem de Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "create-products", "description" => "Cadastrar Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "edit-products", "description" => "Editar Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "destroy-products", "description" => "Excluir Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "report-products", "description" => "Ver Relatório de  Produtos"]);

		Permission::create(["group" => "Produtos", "name" => "viewlist-products-groups", "description" => "Ver Listagem de Grupos de Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "create-products-groups", "description" => "Cadastrar Grupos de Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "edit-products-groups", "description" => "Editar Grupos de Produtos"]);
		Permission::create(["group" => "Produtos", "name" => "destroy-products-groups", "description" => "Excluir Grupos de Produtos"]);

		Permission::create(["group" => "Clientes", "name" => "viewlist-customers", "description" => "Ver Listagem de Clientes"]);
		Permission::create(["group" => "Clientes", "name" => "create-customers", "description" => "Cadastrar Clientes"]);
		Permission::create(["group" => "Clientes", "name" => "edit-customers", "description" => "Editar Clientes"]);
		Permission::create(["group" => "Clientes", "name" => "destroy-customers", "description" => "Excluir Clientes"]);
		Permission::create(["group" => "Clientes", "name" => "report-customers", "description" => "Ver Relatório de  Clientes"]);

		Permission::create(["group" => "Configurações", "name" => "config-styles", "description" => "Configurar Estilos do Sistema"]);
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