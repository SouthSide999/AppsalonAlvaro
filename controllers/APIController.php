<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
use MVC\Router;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();;
        echo json_encode($servicios);
    }

    
    public static function guardar()
    {
        //almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        //almacena la cita y el servicio
        $citaId = $resultado['id'];
        $idServicios = explode(",", $_POST['servicios']); //volver en arreglo
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $citaId,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        echo json_encode(['resultado'=> $resultado]);
    }
    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);

            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);//redirection a la pagina anterior 
        }
    }
}
