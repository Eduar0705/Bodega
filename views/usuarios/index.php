<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="shortcut icon" href="<?= APP_Logo ?>" type="image/x-icon">
</head>
<style>
* {
    box-sizing: border-box;
}

/* Estilos generales */
.add, .viewsUser {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 20px auto;
    padding: 25px;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

h3 {
    color: #2c3e50;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 3px solid #3498db;
    font-size: 24px;
    font-weight: 600;
}

/* Estilos para el formulario */
.add form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.add form h3 {
    grid-column: 1 / -1;
    margin-bottom: 10px;
}

.add input[type="text"] {
    padding: 12px 18px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
    background-color: #fff;
}

.add input[type="text"]:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
    transform: translateY(-2px);
}

#btn-add {
    grid-column: 1 / -1;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

#btn-add:hover {
    background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
}

#btn-add:active {
    transform: translateY(0);
}

/* Estilos para la tabla */
.viewsUser {
    margin-top: 30px;
}

.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.search-wrapper {
    flex: 1;
    min-width: 250px;
    position: relative;
}

#buscar {
    width: 100%;
    padding: 12px 18px 12px 45px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
    background-color: #fff;
}

#buscar:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8c8d;
    pointer-events: none;
}

.entries-control {
    display: flex;
    align-items: center;
    gap: 10px;
}

.entries-control label {
    color: #2c3e50;
    font-weight: 500;
    white-space: nowrap;
}

#entries-select {
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #fff;
}

#entries-select:focus {
    border-color: #3498db;
    outline: none;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 0;
    background-color: #fff;
}

thead {
    background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
}

th {
    padding: 16px 15px;
    text-align: left;
    color: #000000ff;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

th:first-child {
    border-top-left-radius: 8px;
}

th:last-child {
    border-top-right-radius: 8px;
}

td {
    padding: 14px 15px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
    color: #2c3e50;
    font-size: 14px;
}

tbody tr {
    transition: all 0.3s ease;
}

tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}

tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

/* Estilos para los botones */
.btn-delete, .btn-edit {
    background: #d41c1cff;
    color: white;
    border: none;
    padding: 5px 7px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.btn-edit{
    background-color: #e6e635ff;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
}

.btn-edit:hover {
    background: linear-gradient(135deg, #c09d2bff 0%, #f9ff3eff 100%);
    box-shadow: 0 4px 12px rgba(231, 220, 60, 0.4);
}

.btn-delete:active, .btn-edit:active {
    transform: translateY(0);
}

/* Paginación */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid #ecf0f1;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    color: #7f8c8d;
    font-size: 14px;
    font-weight: 500;
}

.pagination {
    display: flex;
    gap: 8px;
    align-items: center;
}

.pagination button {
    padding: 8px 14px;
    border: 2px solid #e0e0e0;
    background-color: #fff;
    color: #2c3e50;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination button:hover:not(:disabled) {
    border-color: #3498db;
    background-color: #3498db;
    color: #fff;
    transform: translateY(-2px);
}

.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination button.active {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border-color: #3498db;
    color: #fff;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

/* Estilos para el mensaje de no hay usuarios */
.text-muted {
    color: #7f8c8d;
    text-align: center;
    padding: 50px 20px;
}

.text-muted i {
    color: #bdc3c7;
    margin-bottom: 15px;
}

.text-muted h5 {
    margin: 15px 0 8px;
    font-size: 20px;
    color: #34495e;
    font-weight: 600;
}

.text-muted p {
    margin: 0;
    font-size: 15px;
    color: #95a5a6;
}

/* Responsive */
@media (max-width: 768px) {
    .add form {
        grid-template-columns: 1fr;
    }
    
    .table-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-wrapper {
        width: 100%;
    }
    
    .entries-control {
        justify-content: space-between;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        text-align: center;
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    table {
        font-size: 13px;
    }
    
    th, td {
        padding: 10px 8px;
    }
}

@media (max-width: 480px) {
    .add, .viewsUser {
        padding: 15px;
        margin: 10px;
    }
    
    h3 {
        font-size: 20px;
    }
}
</style>
<body>
    <div class="dashboard">
        <?= include_once 'views/inc/heder.php'; ?>
        <main class="main-content">
            <div class="page-header">
                <h2><?= $titulo ?></h2>
                <h4>Hoy es: <?= APP_Date ?> </h4>
            </div>

            <div class="add">
                <form action="" method="post">
                    <h3>Agregar Nuevo Cliente</h3>
                    <input type="text" id="name" name="name" placeholder="Ingrese el nombre del cliente" required>
                    <input type="text" id="cedula" name="cedula" placeholder="Ingrese la cédula del cliente" required>
                    <input type="text" id="cel" name="cel" placeholder="Ingrese el teléfono del cliente">
                    <button type="submit" id="btn-add" name="btn-add">
                        <i class="fas fa-plus"></i> Agregar Cliente
                    </button>
                </form>
            </div>
            
            <div class="viewsUser">
                <h3>Clientes Registrados</h3>
                
                <div class="table-controls">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscar" placeholder="Buscar cliente por nombre, cédula o teléfono...">
                    </div>
                    <div class="entries-control">
                        <label for="entries-select">Mostrar:</label>
                        <select id="entries-select">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="all">Todos</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabla-clientes">
                        <thead>
                            <tr>
                                <th>Nombre Cliente</th>
                                <th>Cédula Cliente</th>
                                <th>Teléfono Cliente</th>
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
                                            class="btn btn-edit"
                                            title="Editar Cliente"
                                            data-id="<?php echo $info['id_cliente']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button 
                                                class="btn btn-delete"
                                                title="Eliminar cliente" 
                                                data-id="<?php echo $info['id_cliente']; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x"></i>
                                            <h5>No hay Clientes registrados</h5>
                                            <p>Comienza agregando tu primer cliente.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info" id="pagination-info"></div>
                    <div class="pagination" id="pagination"></div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <!-- Script de búsqueda y paginación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscar');
            const entriesSelect = document.getElementById('entries-select');
            const tabla = document.getElementById('tabla-clientes');
            const tbody = tabla.querySelector('tbody');
            const paginationInfo = document.getElementById('pagination-info');
            const paginationContainer = document.getElementById('pagination');
            
            let todasLasFilas = Array.from(tbody.querySelectorAll('tr')).filter(tr => 
                !tr.querySelector('.text-muted')
            );
            
            let filasFiltradas = [...todasLasFilas];
            let paginaActual = 1;
            let filasPorPagina = 10;

            function filtrarClientes() {
                const textoBusqueda = buscarInput.value.toLowerCase().trim();
                
                if (textoBusqueda === '') {
                    filasFiltradas = [...todasLasFilas];
                } else {
                    filasFiltradas = todasLasFilas.filter(fila => {
                        const celdas = fila.querySelectorAll('td:not(:last-child)');
                        return Array.from(celdas).some(celda => 
                            celda.textContent.toLowerCase().includes(textoBusqueda)
                        );
                    });
                }
                
                paginaActual = 1;
                mostrarPagina();
            }

            function mostrarPagina() {
                // Limpiar tbody
                tbody.innerHTML = '';
                
                // Verificar si hay filas para mostrar
                if (filasFiltradas.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="4">
                            <div class="text-muted">
                                <i class="fas fa-search fa-3x"></i>
                                <h5>No se encontraron resultados</h5>
                                <p>No hay clientes que coincidan con tu búsqueda.</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                    paginationInfo.textContent = '';
                    paginationContainer.innerHTML = '';
                    return;
                }
                
                // Calcular paginación
                const totalFilas = filasFiltradas.length;
                const totalPaginas = filasPorPagina === 'all' ? 1 : Math.ceil(totalFilas / filasPorPagina);
                
                // Ajustar página actual si es necesaria
                if (paginaActual > totalPaginas) {
                    paginaActual = totalPaginas;
                }
                
                // Calcular índices
                let inicio, fin;
                if (filasPorPagina === 'all') {
                    inicio = 0;
                    fin = totalFilas;
                } else {
                    inicio = (paginaActual - 1) * filasPorPagina;
                    fin = Math.min(inicio + filasPorPagina, totalFilas);
                }
                
                // Mostrar filas de la página actual
                for (let i = inicio; i < fin; i++) {
                    tbody.appendChild(filasFiltradas[i].cloneNode(true));
                }
                
                // Reactivar eventos de eliminar
                activarBotonesEliminar();
                
                // Actualizar información de paginación
                paginationInfo.textContent = `Mostrando ${inicio + 1} a ${fin} de ${totalFilas} clientes`;
                
                // Generar botones de paginación
                generarPaginacion(totalPaginas);
            }

            function generarPaginacion(totalPaginas) {
                paginationContainer.innerHTML = '';
                
                if (totalPaginas <= 1) return;
                
                // Botón anterior
                const btnPrev = document.createElement('button');
                btnPrev.innerHTML = '<i class="fas fa-chevron-left"></i>';
                btnPrev.disabled = paginaActual === 1;
                btnPrev.addEventListener('click', () => {
                    if (paginaActual > 1) {
                        paginaActual--;
                        mostrarPagina();
                    }
                });
                paginationContainer.appendChild(btnPrev);
                
                // Botones de páginas
                const maxBotones = 5;
                let inicioPagina = Math.max(1, paginaActual - Math.floor(maxBotones / 2));
                let finPagina = Math.min(totalPaginas, inicioPagina + maxBotones - 1);
                
                if (finPagina - inicioPagina < maxBotones - 1) {
                    inicioPagina = Math.max(1, finPagina - maxBotones + 1);
                }
                
                if (inicioPagina > 1) {
                    const btn1 = document.createElement('button');
                    btn1.textContent = '1';
                    btn1.addEventListener('click', () => {
                        paginaActual = 1;
                        mostrarPagina();
                    });
                    paginationContainer.appendChild(btn1);
                    
                    if (inicioPagina > 2) {
                        const btnDots = document.createElement('button');
                        btnDots.textContent = '...';
                        btnDots.disabled = true;
                        paginationContainer.appendChild(btnDots);
                    }
                }
                
                for (let i = inicioPagina; i <= finPagina; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    if (i === paginaActual) {
                        btn.classList.add('active');
                    }
                    btn.addEventListener('click', () => {
                        paginaActual = i;
                        mostrarPagina();
                    });
                    paginationContainer.appendChild(btn);
                }
                
                if (finPagina < totalPaginas) {
                    if (finPagina < totalPaginas - 1) {
                        const btnDots = document.createElement('button');
                        btnDots.textContent = '...';
                        btnDots.disabled = true;
                        paginationContainer.appendChild(btnDots);
                    }
                    
                    const btnLast = document.createElement('button');
                    btnLast.textContent = totalPaginas;
                    btnLast.addEventListener('click', () => {
                        paginaActual = totalPaginas;
                        mostrarPagina();
                    });
                    paginationContainer.appendChild(btnLast);
                }
                
                // Botón siguiente
                const btnNext = document.createElement('button');
                btnNext.innerHTML = '<i class="fas fa-chevron-right"></i>';
                btnNext.disabled = paginaActual === totalPaginas;
                btnNext.addEventListener('click', () => {
                    if (paginaActual < totalPaginas) {
                        paginaActual++;
                        mostrarPagina();
                    }
                });
                paginationContainer.appendChild(btnNext);
            }

            function activarBotonesEliminar() {
                tbody.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');

                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e74c3c',
                            cancelButtonColor: '#95a5a6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Realizar la eliminación por AJAX
                                fetch(`?action=admin&method=DeleteCliente&id=${id}`)
                                    .then(response => response.ok ? response.text() : Promise.reject())
                                    .then(() => {
                                        Swal.fire({
                                            title: 'Eliminado',
                                            text: 'El cliente se eliminó con éxito.',
                                            icon: 'success',
                                            confirmButtonColor: '#3498db'
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    })
                                    .catch(() => {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'No se pudo eliminar el cliente.',
                                            icon: 'error',
                                            confirmButtonColor: '#e74c3c'
                                        });
                                    });
                            }
                        });
                    });
                });
            }

            // Debounce para búsqueda
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            // Event listeners
            buscarInput.addEventListener('input', debounce(filtrarClientes, 300));
            
            entriesSelect.addEventListener('change', function() {
                filasPorPagina = this.value === 'all' ? 'all' : parseInt(this.value);
                paginaActual = 1;
                mostrarPagina();
            });

            // Inicializar
            mostrarPagina();
        });
    </script>
</body>
</html>