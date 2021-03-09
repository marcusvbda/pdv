<?php
$user = Auth::user();
function currentClass($routes)
{
    $routes = is_array($routes) ? $routes : [$routes];
    $class = "";
    $current = Request::server('REQUEST_URI');
    foreach($routes as $route)
    {
        $contain = strpos($route, "/*");
        if (!$contain) {
            if($current == $route) return "active";
        } else {
            $route = str_replace("/*", "", $route);
            if(strpos($current, $route) !== false) return "active";
        }
    }
    return "";
}
$user = Auth::user();
$is_super_admin = $user->isSuperAdmin();
$is_admin = $user->hasRole(["admin"]);
$is_admin_or_super_admin = $user->hasRole(["admin","super-admin"]);
$polo = $user->polo;

function getMenuClass($permission,$array_current=[]) {
	$class = "dropdown-item ".currentClass($array_current);
	if(!hasPermissionTo($permission)) $class.= " disabled ";
	return $class;
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light py-0">
	<a class="navbar-brand py-0" href="/admin">
		<text-logo size="30" />
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item {{ currentClass(['/admin']) }}">
				<a class="nav-link" href="/admin">
					<i class="el-icon-s-marketing mr-2"></i>Dashboard <span class="sr-only">(current)</span>
				</a>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/caixas/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-s-finance mr-2"></i>Caixas
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-products-groups',['/admin/caixas/*']) }}" href="/admin/caixas" data-label="Caixas fechados e abertos">
						Caixas
					</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/clientes/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-star-off mr-2"></i>Clientes
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-customers',['/admin/clientes/*']) }}" href="/admin/clientes" data-label="Cadastro de Clientes">
						Clientes
					</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/produtos/*','/admin/categorias-de-produto/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-s-flag mr-2"></i>Produtos e Serviços
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-products-groups',['/admin/categorias-de-produtos/*']) }}" 
						href="/admin/categorias-de-produto" data-label="Cadastro de Categorias de Produto">
						Categorias
					</a>
					<a class="{{ getMenuClass('viewlist-products',['/admin/produtos/*']) }}" href="/admin/produtos" data-label="Cadastro de Produtos e Serviços">
						Produtos / Serviços
					</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/relatorios/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-data-analysis mr-2"></i>Relatórios
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('report-customers',['/admin/relatorios/clientes/*']) }}" href="/admin/relatorios/clientes" data-label="Relatório de Clientes">
						Clientes
					</a>
					<a class="{{ getMenuClass('report-products',['/admin/relatorios/produtos/*']) }}" href="/admin/relatorios/produtos" data-label="Relatório de Produtos">
						Produtos
					</a>
				</div>
			</li>
			<li class="nav-item dropdown {{ currentClass(['/admin/campos-customizados/*','/admin/configuracoes/estilos/*','/admin/metodos-de-pagamento/*']) }}">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="el-icon-setting mr-2"></i>Configurações
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="{{ getMenuClass('viewlist-customfield',['/admin/campos-customizados/*']) }}" href="/admin/campos-customizados" data-label="Campos Configurações Dinamicamente">
						Campos Customizados
					</a>
					<a class="{{ getMenuClass('viewlist-paymentmethods',['/admin/metodos-de-pagamento/*']) }}" href="/admin/metodos-de-pagamento">
						Métodos de Pagamento
					</a>
					<a class="{{ getMenuClass('config-styles',['/admin/configuracoes/estilos/*']) }}" href="/admin/configuracoes/estilos" data-label="Estilos do Sistema">
						Estilos
					</a>
				</div>
			</li>
		</ul>
		<select-polo polo_name="{{ $polo->name }}" user_id="{{ $user->id }}" :logged_id='@json($polo->id)'></select-polo>
		<notification-bell polo_id="{{ $polo->id }}" :active='@json(currentClass(['/admin/notificacoes/*']))'></notification-bell>
		<ul class="navbar-nav">
			<li class="nav-item dropdown hover-color ml-0">
				<a class="nav-link dropdown-toggle py-0 d-flex flex-row align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="d-flex flex-column mr-2">
						<b>{{ $user->name }}</b>
						<small class="f-12">{{ $user->email }}</small>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
					@if($is_super_admin)
						<a class="dropdown-item" href="/admin/permissoes">Permissões</a>
					@endif
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/grupos-de-acesso">Grupos de Acesso</a>
					<a class="dropdown-item" href="/admin/usuarios/{{ $user->code }}/edit">
						<div class="d-flex justify-content-between">
							<span>Conta</span>
							<span class="badge badge-default ml-5 pt-1 px-2">ID.: {{ $user->code }}</span>
						</div>
					</a>
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/usuarios">Usuários</a>
					<a class="dropdown-item @if(!$is_admin_or_super_admin) disabled @endif" href="/admin/polos">Polos</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/login">Sair</a>
				</div>
			</li>
		</ul>	
	</div>
</nav>