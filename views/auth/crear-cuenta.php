<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripción-pagina">Llena el siguiente formulario para crear una cuenta: </p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form method="POST" class="formulario" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            placeholder="Ingresa Tu nombre"
            name="nombre"
            value="<?php echo s($usuario->nombre)?>"
            >
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            placeholder="Ingresa Tus Apellidos"
            name="apellido"
            value="<?php echo s($usuario->apellido)?>"
        />
    </div>
    
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            placeholder="Ingresa Tu Telefono"
            name="telefono"
            value="<?php echo s($usuario->telefono)?>"

        />
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Ingresa Tu Email"
            name="email"
            value="<?php echo s($usuario->email)?>"

        />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Ingresa Tu Password"
            name="password"
        />
    </div>

    <input 
    type="submit" 
    class="boton boton-login" 
    value="Crear Cuenta">
</form>

<div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>
    