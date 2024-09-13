<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{

    public static function index(Router $router)
    {

        session_start();
        isAdmin();
        $servicios = Servicio::all();
        $resultadoOperacion = $_GET['resultado'] ?? null;
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'resultadoOperacion' => $resultadoOperacion, 
            'servicios' => $servicios
        ]);
    }
    public static function crear(Router $router)
    {
        session_start();
        isAdmin();

        $servicio = new Servicio;
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            if (empty($alertas)) {
                $servicio->guardar();
                header('location: /servicios?resultado=1');
            }
        }
        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }
    public static function actualizar(Router $router)
    {
        session_start();
        isAdmin();

        //recibir id para actualizar y auto llenado
        $id = is_numeric($_GET['id']);

        if (!$id) return;
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('location: /servicios?resultado=2');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas

        ]);
    }
    public static function eliminar()
    {
        session_start();
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('location: /servicios?resultado=3');
        }
    }
}
