<?php
	/**
	 * @author: Ing. Juan Diego Rincon Urdaneta
	 * @email:  jd.rincon021@gmail.com
	 * @descripcion: script para devolver datos del cne de las personas.
	 * */
	/**
	 * Pasos para ejecutar scripping de consulta de datos 
	 * de CNE
	 * */
	//PASO 1: incluir la clase CneApi
	require_once "class/CneApi.php";

	//PASO 2: iniciar la clase CneApi, e incluir la cedula a buscar
	$cedula = "__CEDULA__";
	$cneApi = new CneApi($cedula);
	
	//PASO 3: imprimir el objeto json con todos los datos de la persona.
	echo $cneApi->get_json_data();

?>
