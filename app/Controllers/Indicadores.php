<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Indicador;
use \YaLinqo\Enumerable;

class Indicadores extends Controller{
    public function index() {
        $indicadoresUf = new Indicador();
        $dataIndicador = $indicadoresUf -> orderBy('fecha','desc') -> findAll();
        $cantidadValores = from($dataIndicador) -> count();
        $data['datos'] = $dataIndicador;
        $data['header'] = view('indicador/template/header');
        $data['footer'] = view('indicador/template/footer');
        if($cantidadValores == 0)
        {
            $this->guardarIndicadorFromApi();
        }
        return view('indicador/uf/mostrar_valores_uf',$data);
    }
    public function obtenerToken()
    {
        $url = 'https://postulaciones.solutoria.cl/api/acceso';
        $cUrl = curl_init($url);
        $data = array(
            'userName' => 'rodrigoceballos09_8qp@indeedemail.com',
            'flagJson' => true
        );
        $dataJson = json_encode($data);
        curl_setopt($cUrl,CURLOPT_POSTFIELDS,$dataJson);
        curl_setopt($cUrl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
            )
        );
        curl_setopt($cUrl, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($cUrl);
        $data = json_decode($result);
        curl_close($cUrl);
        $token = $data->token;
        return $token;
    }
    public function guardarIndicadorFromApi()
    {
        $token = $this-> obtenerToken();
        $url = 'https://postulaciones.solutoria.cl/api/indicadores';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result);
        //en la siguiente linea se utiliza YaLinqo que es el LINQ de php
        $uf = from($data) -> where (function ($data) { return $data -> codigoIndicador == "UF";});
        $uf = $uf -> toArrayDeep();
        $newIndicador = new Indicador();
        foreach($uf as $dato)
        {
            $newUf = array (
                'nombre' => $dato -> nombreIndicador,
                'codigo' => $dato -> codigoIndicador,
                'unidad' => $dato -> unidadMedidaIndicador,
                'valor' => $dato -> valorIndicador,
                'fecha' => $dato -> fechaIndicador
            );
            $newIndicador -> insert($newUf);
        }
        return $this->response->redirect(site_url('/listar'));
    }
    public function cargarDatosGrafico()
    {
        $indicador = new Indicador();
        $indicador = $indicador -> findAll();
        $dataGrafico = from($indicador) ->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
        $json = json_encode($dataGrafico->toArray());
        return $json;
    }
    public function filtrarDatosGrafico($cantidad,$desde,$hasta)
    {
        $indicador = new Indicador();
        $indicador = $indicador -> orderBy('fecha','asc') -> findAll();
        
        if($cantidad == 0)
        {
            if($desde != "null" && $hasta != "null")
            {
                $data = from($indicador) ->where(function($indicador) use($desde,$hasta){return $indicador['fecha'] >= $desde && $indicador['fecha'] <= $hasta;})->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }elseif($desde != "null" && $hasta == "null")
            {
                $data = from($indicador) ->where(function($indicador) use($desde){return $indicador['fecha'] >= $desde;})->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
            elseif($desde == "null" && $hasta != "null")
            {
                $data = from($indicador) ->where(function($indicador) use($hasta){return $indicador['fecha'] <= $hasta;})->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
            else{
                $data=from($indicador) ->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
        }else{
            if($desde != "null" && $hasta != "null")
            {
                $data = from($indicador) ->where(function($indicador) use($desde,$hasta){return $indicador['fecha'] >= $desde && $indicador['fecha'] <= $hasta;})->take($cantidad)->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }elseif($desde != "null" && $hasta == "null")
            {
                $data = from($indicador) ->where(function($indicador) use($desde){return $indicador['fecha'] >= $desde;})->take($cantidad)->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
            elseif($desde == "null" && $hasta != "null")
            {
                $data = from($indicador) ->where(function($indicador) use($hasta){return $indicador['fecha'] <= $hasta;})->take($cantidad)->select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
            else{
                $data = from($indicador) -> take($cantidad)-> select(function($indicador){return array('valor' =>$indicador['valor'],'fecha' =>$indicador['fecha']);});
                $json = json_encode($data -> toList());
            }
        }
        
        
        return $json;

    }
    public function grafico()
    {
        $indicador = new Indicador();
        $indicador = $indicador -> orderBy('fecha','asc') -> findAll();
        $fechaInicial = from($indicador) -> select(function($indicador){return $indicador['fecha'];})  -> firstOrDefault();
        $fechaFinal = from($indicador) -> select(function($indicador){return $indicador['fecha'];}) -> lastOrDefault();
        $data['fechaInicial'] = $fechaInicial;
        $data['fechaFinal'] = $fechaFinal;
        $data['header'] = view('indicador/template/header');
        $data['footer'] = view('indicador/template/footer');
        return view('indicador/mostrar_grafico', $data);
    }
    public function insertar()
    {
        $data['header'] = view('indicador/template/header');
        $data['footer'] = view('indicador/template/footer');
        return view('indicador/uf/insertar_valores_uf', $data);
    }
    public function nuevoIndicador()
    {
        $indicador = new Indicador();
        $nombre = $this -> request -> getVar('nombre');
        $codigo = $this -> request -> getVar('codigo');
        $unidad = $this -> request -> getVar('unidad');
        $valor = $this -> request -> getVar('valor');
        $fecha = $this -> request -> getVar('fecha');
        $datos = [
            'nombre' => $nombre,
            'codigo' => $codigo,
            'unidad' => $unidad,
            'valor' => $valor,
            'fecha' => $fecha
        ];
        $indicador -> insert($datos);
        return $this->response->redirect(site_url('/listar'));
    }
    public function editar($id = null)
    {
        $indicador = new Indicador();
        $indicador = $indicador -> findAll();
        $elemento = from($indicador)->  where (function ($indicador) use($id) { return $indicador['id'] ==$id;})->firstOrDefault();
        $data['indicador'] = $elemento;
        $data['header'] = view('indicador/template/header');
        $data['footer'] = view('indicador/template/footer');
        return view('indicador/uf/actualizar_valores_uf',$data);
    }
    public function eliminar($id = null)
    {
        $indicador = new Indicador();
        $indicador ->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('/listar'));

    }
    public function actualizarDatos()
    {
        $indicador = new Indicador();
        $id = $this -> request -> getVar('id');
        $nombre = $this -> request -> getVar('nombre');
        $codigo = $this -> request -> getVar('codigo');
        $unidad = $this -> request -> getVar('unidad');
        $valor = $this -> request -> getVar('valor');
        $fecha = $this -> request -> getVar('fecha');

        $datos = [
            'nombre' => $nombre,
            'codigo' => $codigo,
            'unidad' => $unidad,
            'valor' => $valor,
            'fecha' => $fecha
        ];
        $indicador -> update($id,$datos);
        return $this->response->redirect(site_url('/listar'));
    }
}