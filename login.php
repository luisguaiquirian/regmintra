<?
	if(!isset($_SESSION))
	{
		session_start();
	}	

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;

	echo json_encode($system->login($_POST['usuario'],$_POST['password']));

?>