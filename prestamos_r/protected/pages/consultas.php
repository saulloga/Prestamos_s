<?php
include_once('../compartidos/clases/conexion.php');
include_once('/protected/Comunes/Busquedas.php');

class consultas extends TPage
{
	var $dbConexion;

	public function onLoad($param)
	{
		parent::onLoad($param);

		$this->dbConexion = Conexion::getConexion($this->Application, "dbpr");
		Conexion::createConfiguracion();

		if(!$this->IsPostBack)
		{
			//$resultado = Busquedas::obtenerPrestamoAnteriorSinRedocumentado($this->dbConexion, 6173);
			//$resultado = Busquedas::obtenerPrestamoAnterior($this->dbConexion, 6173);
			//$resultado = Busquedas::aval_disponible($this->dbConexion, 6173);
			//$resultado = Busquedas::generaContratosAltaRedocumenta($this->dbConexion, '20150101', '20150730');
			$resultado = Busquedas::subreporte_resumen_de_importes_por_sindicato($this->dbConexion, '20150101', '20150730');
			//print_r($resultado);
			$this->dgPrueba->DataSource = $resultado;
			$this->dgPrueba->dataBind();
		}
	}
}
?>