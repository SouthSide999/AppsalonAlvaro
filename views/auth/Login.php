<h1 class="nombre-pagina">Login</h1>
<p class="descripción-pagina">Inicia sesión con tus datos: </p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>
<form class="formulario" method="post" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Ingresa Tu Email"
            name="email"
            value="<?php echo s($auth->email);?>"
            />
            
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Ingresa Tu Contraseña"
            name="password"
        />
    </div>
    <input type="submit" class="boton boton-login" value="Iniciar Sesión">

    <div class="acciones">
        <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
        <a href="/olvide">¿Olvidaste tu contraseña?</a>
    </div>

</form>