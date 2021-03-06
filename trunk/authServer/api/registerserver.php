<?php
/**
 * FEUP - SDIS - CityBomber
 * Author: CityBomber Dev Team
 * Date: 2012-05-09 22:35:00
 */

/**
 * This PHP script is an integrant part of the CityBomber AuthServer REST API.
 * The script receives server information by GET method, then proceeds to proper verifications
 * and inserts the server record in the database  .
 */

/**
 * Reponse format is shown below:
 *
 */


// TODO: 	Se o server já existir? Deve fazer update?
//			Dar uma key ao server para ele poder alterar dados?
//			Dar opçao de apagar o registo do server?
//			Verificar estado do server -> ping | usar a api (criar rest script imalive.php)
require_once('includes/database.php');
require_once('database/server.php');

$serverData['ip']   = $_GET["ip"];
$serverData['port'] = $_GET["port"];
$serverData['name'] = $_GET["name"];

// Check input data
if( $serverData['ip'] != null && $serverData['port']!= null && $serverData['name'] != null){
	$pServer = Server::getServerByIPAndPort($serverData);
	// Check server redundancy
	if($pServer == null){
		// TODO Check the server;
		//$_SERVER['REMOTE_ADDR'];
		// TODO Check server name
		$register = Server::registerServer($serverData);
		if($register == 1){
			showResult($result, $serverData);
		} else {
			showError("Critical error registering server.", $serverData);
		}
	} else if($serverData['name'] == $pServer['name']) {
		Server::updateServer($serverData);
		showResult($result, $serverData);
	} else {
		showError("Adress already in use.", $serverData);
	}

} else {
	showError("Invalid data.", $serverData);
}

function showError($error, $serverData){
	echo json_encode(Array("error" => $error));
	die;
}

function showResult($result, $serverData){
	echo json_encode(Array("srvRegister" => $serverData));
	die;
}

?>