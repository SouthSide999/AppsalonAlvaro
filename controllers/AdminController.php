<?php

namespace Controllers;

use Model\Administrador;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        session_start();

        isAdmin();

        //recibe la fecha del buscador para las citas y separa la fecha para la validación
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);
        //validar fecha correcta
        if( !checkdate( $fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
        }

        //consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = Administrador::SQL($consulta);

        $router->render('admin/index', [
        'nombre'=> $_SESSION['nombre'],
        'citas'=> $citas,
        'fecha'=> $fecha
        ]);
    }
}
