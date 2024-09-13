<h1 class="nombre-pagina">Olvide Mi Contraseña</h1>
<p class="descripción-pagina">Restablece Tu Contraseña Escribiendo Tu Email a Continuación</p>
<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>
<form class="formulario" method="post" action="/olvide" >
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Ingresa Tu Email"
        />
    </div>
    <input type="submit" class="boton boton-login" value="Enviar Instrucciones">

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        <a href="/crear-cuenta">¿Aun no tienes una Cuenta? Crea Una</a>
</div>

</form>