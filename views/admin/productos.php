<?php
include '../../setting/setting.php';
include 'header.php';
include 'navbar.php';
?>
    

    <form id="productoForm" enctype="multipart/form-data">
        <input type="hidden" id="prod_id" name="prod_id">

        <label for="prod_nombre">Nombre:</label>
        <input type="text" id="prod_nombre" name="prod_nombre" required>
        <div id="suggestions" class="suggestions"></div>
        <br>
        <label for="prod_descripcion">Descripción:</label>
        <textarea id="prod_descripcion" name="prod_descripcion"></textarea>
        <br>
        <label for="prod_precio">Precio:</label>
        <input type="number" step="0.01" id="prod_precio" name="prod_precio" required>
        <br>
        <label for="prod_stock">Stock:</label>
        <input type="number" id="prod_stock" name="prod_stock" required>
        <br>
        <label for="cat_id">Categoría:</label>
        <select id="cat_id" name="cat_id" required>
            <!-- Las opciones se cargarán aquí mediante JavaScript -->
        </select>
        <br>
        <label for="prod_imagen_url">Imagen:</label>
        <input class="imgfile" type="file" id="prod_imagen_url" name="prod_imagen_url">
        <br>
        <button class="frmbtn" type="button" onclick="submitForm('guardar')">Guardar</button>
        <button class="frmbtn" type="button" onclick="submitForm('actualizar')">Actualizar</button>
        <button class="frmbtn" type="button" onclick="submitForm('eliminar')">Eliminar</button>
        <button class="frmbtn" type="button" onclick="submitForm('buscar')">Buscar</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            // Cargar categorías al cargar la página
            try {
                const response = await fetch('../../controllers/cproductos.php', {
                    method: 'POST',
                    body: new URLSearchParams({ accion: 'cargar_categorias' })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    const catSelect = document.getElementById('cat_id');
                    result.data.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.cat_id;
                        option.textContent = cat.cat_nombre;
                        catSelect.appendChild(option);
                    });
                } else {
                    console.error(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Manejar la búsqueda de productos en tiempo real
        document.getElementById('prod_nombre').addEventListener('input', async () => {
            const query = document.getElementById('prod_nombre').value;
            if (query.length < 3) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            try {
                const response = await fetch('../../controllers/cproductos.php', {
                    method: 'POST',
                    body: new URLSearchParams({ accion: 'buscar_coincidencias', prod_nombre: query })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    const suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    result.data.forEach(prod => {
                        const item = document.createElement('div');
                        item.classList.add('suggestion-item');
                        item.textContent = prod.prod_nombre;
                        item.dataset.id = prod.prod_id;
                        item.addEventListener('click', () => {
                            document.getElementById('prod_id').value = prod.prod_id;
                            document.getElementById('prod_nombre').value = prod.prod_nombre;
                            document.getElementById('prod_descripcion').value = prod.prod_descripcion;
                            document.getElementById('prod_precio').value = prod.prod_precio;
                            document.getElementById('prod_stock').value = prod.prod_stock;
                            document.getElementById('cat_id').value = prod.cat_id;
                            document.getElementById('suggestions').innerHTML = '';
                        });
                        suggestions.appendChild(item);
                    });
                } else {
                    console.error(result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        async function submitForm(action) {
            const formData = new FormData(document.getElementById('productoForm'));
            formData.append('accion', action);

            try {
                const response = await fetch('../../controllers/cproductos.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                alert(result.message);
                if (result.status === 'success' && action === 'buscar') {
                    console.log(result.data); // Aquí puedes manejar los datos devueltos si haces una búsqueda
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error en la solicitud.');
            }
        }
    </script>


<?php include '../templates/footer.php';?>


   