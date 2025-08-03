<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<style>
/* Estilos generales */
.add, .viewsUser {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
tr.no-result {
    display: none;
}

/* Estilos para el formulario */
.add form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.add form h3 {
    grid-column: span 2;
}

.add input[type="text"], #buscar {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.add input[type="text"]:focus, #buscar:focus{
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

#btn-add {
    grid-column: span 2;
    background-color: #3498db;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

#btn-add:hover {
    background-color: #2980b9;
}

/* Estilos para la tabla */
.viewsUser {
    margin-top: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

thead {
    background-color: #f8f9fa;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    color: #2c3e50;
    font-weight: 600;
}

tbody tr:hover {
    background-color: #f5f5f5;
}

/* Estilos para los botones */
.btn-delete {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn-delete:hover {
    background-color: #c0392b;
}

/* Estilos para el mensaje de no hay usuarios */
.text-muted {
    color: #7f8c8d;
    text-align: center;
    padding: 30px 0;
}

.text-muted i {
    color: #bdc3c7;
}

.text-muted h5 {
    margin: 10px 0 5px;
    font-size: 18px;
}

.text-muted p {
    margin: 0;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .add form {
        grid-template-columns: 1fr;
    }
    
    .add form h3,
    #btn-add {
        grid-column: span 1;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
}
</style>
<body>
    <div class="dashboard">
        <?= include_once 'views/inc/heder.php'; ?>
        <main class="main-content">
            <div class="page-header">
                <h1><?= $titulo ?></h1>
                <h4>Hoy es: <?= APP_Date ?> </h4>
            </div>

            <div class="add">
                <form action="" method="post">
                    <h3>Agregar Nuevo cliente</h3>
                    <input type="text" id="name" name="name" placeholder="Ingrese el nombre del cliente" required>
                    <input type="text" id="cedula" name="cedula" placeholder="Ingrese la cedula del cliente" required>
                    <input type="text" id="cel" name="cel" placeholder="Ingrese el telefono del cliente">
                    <button type="submit" id="btn-add" name="btn-add"> <i class="fas fa-plus"></i> Agregar cliente</button>
                </form>
            </div>
            
            <div class="viewsUser">
                <h3>Clientes Registrados</h3>
                <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="search-input">
                <table id="tabla-clientes">
                    <thead>
                        <tr>
                            <th>Nombre Cliente</th>
                            <th>Cedula Cliente</th>
                            <th>Telefono Cliente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($Clientes)): ?>
                            <?php foreach($Clientes as $info): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($info['nombre_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($info['cedula']); ?></td>
                                    <td><?php echo htmlspecialchars($info['telefono']); ?></td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-danger btn-delete"
                                            id="btn-eliminar" 
                                            title="Eliminar cliente" 
                                            data-id="<?php echo $info['id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">
                                    <div class="text-muted">
                                        <i class="fas fa-boxes fa-3x mb-3"></i>
                                        <h5>No hay Usuarios registrados</h5>
                                        <p>No se encontraron productos registrados.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscar');
            const tabla = document.getElementById('tabla-clientes');
            const filas = Array.from(tabla.querySelectorAll('tbody tr')).filter(tr => !tr.querySelector('th'));
            
            function buscarClientes() {
                const textoBusqueda = buscarInput.value.toLowerCase().trim();
                let resultadosVisibles = false;
                
                filas.forEach(fila => {
                    const celdas = fila.querySelectorAll('td:not(:last-child)'); // Excluir columna de acciones
                    let coincide = textoBusqueda === '';
                    
                    if (!coincide) {
                        coincide = Array.from(celdas).some(celda => 
                            celda.textContent.toLowerCase().includes(textoBusqueda)
                        );
                    }
                    
                    fila.style.display = coincide ? '' : 'none';
                    if (coincide) resultadosVisibles = true;
                });
                
                // Manejar mensaje de no resultados
                const mensajeExistente = tabla.querySelector('#mensaje-no-resultados');
                const tbody = tabla.querySelector('tbody');
                
                if (!resultadosVisibles && textoBusqueda !== '') {
                    if (!mensajeExistente) {
                        const tr = document.createElement('tr');
                        tr.id = 'mensaje-no-resultados';
                        tr.innerHTML = `
                            <td colspan="4" style="text-align: center;">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3"></i>
                                    <h5>No se encontraron resultados</h5>
                                    <p>No hay clientes que coincidan con "${textoBusqueda}"</p>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    }
                } else if (mensajeExistente) {
                    mensajeExistente.remove();
                }
            }
            
            // Debounce para mejorar performance en búsquedas rápidas
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
            
            buscarInput.addEventListener('input', debounce(buscarClientes, 300));
        });
    </script>
    <!-- Eliminar -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Previene comportamiento por defecto

                const id = this.getAttribute('data-id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige con el ID al método eliminar del controlador
                        window.location.href = `?action=admin&method=EliminarUsuario&id=${id}`;
                    }
                });
            });
        });
    });
    </script>
</body>
</html>