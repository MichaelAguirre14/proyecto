<?php
ob_start();
date_default_timezone_set("America/Bogota");
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}

//Guardar Cliente
if ($action == 'save_customer') {
    $save = $crud->save_customer();
    if ($save) {
        echo $save;
    }
}

if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}


if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
//Borrar Cliente
if($action == 'delete_customer'){
	$save = $crud->delete_customer();
	if($save)
		echo $save;
}
//Actualizar Cliente
if($action == 'update_customer'){
	$save = $crud->update_customer();
	if($save)
		echo $save;
}
//Borrar el pedido de la tabla sql
if($action == 'delete_pedido'){
    $delete = $crud->delete_pedido();
    if($delete)
        echo $delete;
}

if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_task'){
	$save = $crud->save_task();
	if($save)
		echo $save;
}
if($action == 'delete_task'){
	$save = $crud->delete_task();
	if($save)
		echo $save;
}
if($action == 'save_progress'){
	$save = $crud->save_progress();
	if($save)
		echo $save;
}
if($action == 'delete_progress'){
	$save = $crud->delete_progress();
	if($save)
		echo $save;
}
if($action == 'get_report'){
	$get = $crud->get_report();
	if($get)
		echo $get;
}
ob_end_flush();
?>
