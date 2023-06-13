<?php

namespace App\Helpers;

use App\Models\Comercial\Cliente;
use App\Models\Logistica\Proveedor;
use Carbon\Carbon;

class ConfiguracionHelper
{
    public static function encode5t($str)
    {
        for ($i = 0; $i < 5; $i++) {
            $str = strrev(base64_encode($str));
        }
        return $str;
    }

    public static function decode5t($str)
    {
        for ($i = 0; $i < 5; $i++) {
            $str = base64_decode(strrev($str));
        }
        return $str;
    }

    public static function generarCodigo($serie = '', $separador = '', $cantidad = 0, $modulo, $periodo = 'NO', $valorPeriodo = 0, $tipo = 1, $id_empresa = 0, $id_grupo = 0)
    {
		$numero = ConfiguracionHelper::contadorModular($modulo);
        $correlativo = ConfiguracionHelper::leftZero($cantidad, $numero);
		$inicial = '';
		switch ($tipo) {
			case 1:
				$inicial = $serie;
			break;
			// case 2:
			// 	$inicial = ConfiguracionHelper::codigoEmpresa($id_empresa);
			// break;
			// case 3:
			// 	$inicial = ConfiguracionHelper::codigoGrupo($id_grupo);
			// break;
			// case 4:
			// 	$inicial = $serie.ConfiguracionHelper::codigoGrupo($id_grupo);
			// break;
		}
		$anio = ($periodo == 'NO') ? '' : ConfiguracionHelper::generarCodigoAnual($valorPeriodo);
        return $inicial.$anio.$separador.$correlativo;
    }

	/*
	 * Función para autogenerar un correlativo de números donde se rellena de ceros a la izquierda
	 */
    public static function leftZero($lenght, $number){
		$nLen = strlen($number);
		$zeros = '';
		for($i = 0; $i < ($lenght - $nLen); $i++){
			$zeros = $zeros.'0';
		}
		return $zeros.$number;
	}

	/*
	 * Función para extraer el último registro a nivel de módulos (tablas)
	 */
	public static function contadorModular($modulo)
	{
		$correlativo = 0;
		switch ($modulo) {
            case 'cliente':
				$correlativo = Cliente::count() + 1; //El contador inicia en 1
			break;
            case 'clienteCodigo':
				$correlativo = Cliente::where('codigo','!=',null)->count() + 1; //El contador inicia en 1
			break;
            case 'proveedores':
				$correlativo = Proveedor::count() + 1; //El contador inicia en 1
			break;
            case 'proveedoresCodigo':
				$correlativo = Proveedor::where('codigo','!=',null)->count() + 1; //El contador inicia en 1
			break;
		}
		return $correlativo;
	}

	/*
	 * Función que extrae un string del año en ejecución o periodo seleccionado (2 últimos dígitos)
	 * Si el año tiene valor 0 la función tomará el año en curso
	 * Si el año tiene un valor (Ejm: 20233) ese será el año a ejecutar
	 */
	public static function generarCodigoAnual($anio)
	{
		$valor = ($anio > 0) ? $anio : Carbon::now()->format('Y');
		return substr($valor, 2);
	}

	/*
	 * Función para extraer la abreviatura de la empresa (Tabla: administracion::empresas)
	 */
	// public static function codigoEmpresa($idEmpresa)
	// {
	// 	return Empresa::find($idEmpresa)->abreviatura;
	// }

	/*
	 * Función para generar un texto único según el grupo al que pertenece (Tabla: administracion::grupos)
	 */
	// public static function codigoGrupo($id_grupo)
	// {
	// 	$codigoGrupo = '';
	// 	$grupo=Grupo::find($id_grupo);
	// 	if(isset($grupo)){
	// 		$porciones = explode(" ", $grupo->descripcion);
	// 		foreach ($porciones as $key => $porcion) {
	// 			$codigoGrupo .=$porcion[0];
	// 		}
	// 	}

	// 	return $codigoGrupo;
	// }

	// public static function periodoActivo($tipo)
	// {
	// 	$periodos = Periodo::orderBy('descripcion', 'desc')->first();
	// 	return ($tipo == 1) ? $periodos->id : $periodos->descripcion;
	// }
    public static function mesNumero($numero='01')
    {
        $nombre_mes='enero';
        switch ($numero) {
            case '01':
                $nombre_mes='enero';
            break;

            case '02':
                $nombre_mes='febrero';
            break;
            case '03':
                $nombre_mes='marzo';
            break;
            case '04':
                $nombre_mes='abril';
            break;
            case '05':
                $nombre_mes='mayo';
            break;
            case '06':
                $nombre_mes='junio';
            break;
            case '07':
                $nombre_mes='julio';
            break;
            case '08':
                $nombre_mes='agosto';
            break;
            case '09':
                $nombre_mes='setiembre';
            break;
            case '10':
                $nombre_mes='octubre';
            break;
            case '11':
                $nombre_mes='noviembre';
            break;
            case '12':
                $nombre_mes='diciembre';
            break;
        }
        return $nombre_mes;
    }
    public static function mesNombre($mes)
    {

        switch ($mes) {
            case 'enero':
                $numero='01';
            break;

            case 'febrero':
                $numero='02';
            break;
            case 'marzo':
                $numero='03';
            break;
            case 'abril':
                $numero='04';
            break;
            case 'mayo':
                $numero='05';
            break;
            case 'junio':
                $numero='06';
            break;
            case 'julio':
                $numero='07';
            break;
            case 'agosto':
                $numero='08';
            break;
            case 'setiembre':
                $numero='09';
            break;
            case 'octubre':
                $numero='10';
            break;
            case 'noviembre':
                $numero='11';
            break;
            case 'diciembre':
                $numero='12';
            break;
            default:

                $mes = ConfiguracionHelper::leftZero(2, $mes);
                $numero=$mes;

            break;
        }
        return $numero;
    }
}
