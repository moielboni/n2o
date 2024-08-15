<?php
include '../../setting/setting.php';
include "../templates/header.php";
include "../templates/navbar.php";
include "../../controllers/conexion.php";

?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('registro-form').addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(event.target);

                try {
                    const response = await fetch('../../controllers/cregistro.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    alert(result.message);
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>

    <h1>Registro de Usuario</h1>
    <form id="registro-form" action="../../controllers/cregistro.php" method="post">
        <label for="usu_nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="usu_nombre_usuario" name="usu_nombre_usuario" required>
        <br>
        <label for="usu_correo">Correo Electrónico:</label>
        <input type="email" id="usu_correo" name="usu_correo" required>
        <br>
        <label for="usu_contraseña">Contraseña:</label>
        <input type="password" id="usu_contraseña" name="usu_contraseña" required>
        <br>
        <label for="usu_nombre">Nombre:</label>
        <input type="text" id="usu_nombre" name="usu_nombre">
        <br>
        <label for="usu_apellido">Apellido:</label>
        <input type="text" id="usu_apellido" name="usu_apellido">
        <br>
        <label for="usu_direccion">Dirección:</label>
        <textarea id="usu_direccion" name="usu_direccion"></textarea>
        <br>
        <label for="usu_telefono">Teléfono:</label>
        <input type="text" id="usu_telefono" name="usu_telefono">
        <br>
        <button type="submit" name="accion" value="registrar">Registrar</button>
    </form>
    <?php include '../templates/footer.php'?>

