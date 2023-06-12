<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $modulos = $this->viewModulos();
        $notasLanzamiento = $this->notasLanzamiento();
        return view('inicio', get_defined_vars());
    }

    public function viewModulos()
    {
        $sql = DB::table('configuracion.modulos')->where('id_padre', 0)->where('estado', '!=' ,7)->orderBy('descripcion', 'ASC')->get();
        $html = '';

        foreach ($sql as $row) {
            $id = $row->id_modulo;
            $name = $row->descripcion;
            $link = $row->ruta;

            if ($id === 169) {
                if (in_array(Auth::user()->id_usuario, [1, 31, 6, 129, 131, 26])) {
                    $html .=
                    '<div class="col-md-3">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">Módulo</div>
                            <div class="box-body">
                                <h4><a class="default-link" href="'.$link.'">'.$name.'</a></h4>
                            </div>
                        </div>
                    </div>';
                }
            }else{
                $html .=
                '<div class="col-md-3">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">Módulo</div>
                        <div class="box-body ">
                            <h4><a class="default-link" href="'.$link.'/index">'.$name.'</a></h4>
                        </div>
                    </div>
                </div>';
            }
        }
        return $html;
    }

    public function notasLanzamiento(){
		$data = DB::table('configuracion.nota_lanzamiento')
            ->select('nota_lanzamiento.*', 'detalle_nota_lanzamiento.*')
            ->join('configuracion.detalle_nota_lanzamiento', 'detalle_nota_lanzamiento.id_nota_lanzamiento', '=', 'nota_lanzamiento.id_nota_lanzamiento')
            ->where('nota_lanzamiento.estado', 1)->where('nota_lanzamiento.version_actual', true)
            ->orderBy('detalle_nota_lanzamiento.fecha_detalle_nota_lanzamiento', 'desc')->get();
		return $data;
    }
}