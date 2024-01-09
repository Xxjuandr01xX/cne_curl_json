<?php
	/**
	 * @author: Ing. Juan Diego Rincon Urdaneta
	 * @email:  jd.rincon021@gmail.com
	 * @descripcion: script para devolver datos del cne de las personas.
	 * */
	/**
	 * Clase para realizar busqueda en la pagina de CNE y generar un objeto json mediante curl.
	 * */
	class CneApi{
		private $url_request;
		private $ced_ide;
		function __construct($cedula){
			require_once "constants/const.php";
			$this->ced_ide     = $cedula;
			$this->url_request = CNE.$this->ced_ide;
		}

		public function init_conexion(){
			/**
			 * Para inicializar la conexion y capturar los datos
			 * */
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $this->url_request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // almacene en una variable
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data_obj = curl_exec($ch);
			curl_close($ch);
			return $data_obj;
		}

		/////Funciones para limpiar los datos de etiquetas HTML y demas caracteres.
		public function limpiar_cedula($ced){
			$xpl = explode('</td>', $ced);
			return explode('<td align="left">', $xpl[1])[1];
		}

		public function limpiar_nombre($nombre){
			$xpl = explode('<td align="left"><b>', $nombre);
			return explode('</b>', $xpl[1])[0];
		}

		public function limpiar_estado($estado){
			$xpl = explode('<td align="left">', $estado);
			return explode('</td>', $xpl[1])[0];
		}

		public function limpiar_municipio($municipio){
			$xpl = explode('<td align="left">', $municipio);
			return explode('</td>', $xpl[1])[0];
		}

		public function limpiar_cv($cv){
			return explode('<', $cv)[0];
		}
		////////
		public function get_json_data(){
			/**
			 * Metodo para filtrar los datos y general el objeto JSON.
			 * */
			$obj_data       = $this->init_conexion();
			$obj_data_array = explode("</strong>", $obj_data);
			$data_persona   = explode('<font',$obj_data_array[0]);
			$cedula         = explode('color="#00387b">',$data_persona[2])[1];
			$nombre         = explode('color="#00387b">',$data_persona[3])[1];
			$estado         = explode('color="#00387b">',$data_persona[4])[1];
			$municipio      = explode('color="#00387b">',$data_persona[5])[1];
			$parroquia      = explode('color="#00387b">',$data_persona[6])[1];
			$centro         = explode('>',$data_persona[8])[1];
			$direccion      = explode('>',$data_persona[10])[1];
			$arr_data_cne = array(
				"cedula"    => $this->limpiar_cedula(explode(":",$cedula)[1]),
				"nombre"    => $this->limpiar_nombre(explode(":",$nombre)[1]),
				"estado"    => $this->limpiar_estado(explode(":",$estado)[1]),
				"municipio" => $this->limpiar_municipio(explode(":",$municipio)[1]),
				"parroquia" => $this->limpiar_municipio(explode(":",$parroquia)[1]),
				"centro"    => $this->limpiar_cv($centro),
				"direccion" => $this->limpiar_cv($direccion)
			);
			return json_encode($arr_data_cne);
		}
	}
?>
