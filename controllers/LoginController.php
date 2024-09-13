<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        $auth = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->sincronizar($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                //comprobar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario) {
                    //verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no Encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'auth' => $auth,
            'alertas' => $alertas,
        ]);
    }
    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario && $usuario->confirmado === "1") {
                    //generar un token 
                    $usuario->crearToken();
                    $usuario->guardar();
                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();


                    //enviar una alerta
                    Usuario::setAlerta('exito', 'Revisa tu email y sigue las instrucciones ');
                } else {
                    Usuario::setAlerta('error', 'Usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;
        $token = s($_GET['token']); //acceder a token 
        $usuario = Usuario::where('token', $token); //buscar usuario por su token 
        if (empty($usuario) || $usuario->token === '') {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido...');
            $error = true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //leer nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = '';
                $usuario->guardar();
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router)
    {
        $usuario = new Usuario;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {

                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear el password
                    $usuario->hashPassword();
                    //generar un token único
                    $usuario->crearToken();
                    //Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router)
    {
        $alertas = [];
        //sanitizer y leer token desde la url
        $token = s($_GET['token']); //acceder a token 
        $usuario = Usuario::where('token', $token);
        if (empty($usuario) || $usuario->token === '') {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido...');
        } else {
            //cambiar valor de columna confirmado
            $usuario->confirmado = '1';
            //eliminar token
            $usuario->token = '';
            //Guardar y Actualizar 
            $usuario->guardar();
            //mostrar mensaje de exito
            Usuario::setAlerta('exito', 'Cuenta verificada exitosamente...');
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
