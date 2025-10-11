<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="shortcut icon" href="<?= APP_Logo?>" type="image/x-icon">
</head>
<style>
/* Estilos generales */
.add, .viewsUser {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
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

/* Contenedor de filtros */
.filter-section {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
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

.add input[type="text"], #buscar, #fechaInicio, #fechaFin {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
    flex: 1;
    min-width: 150px;
}

.add input[type="text"]:focus, #buscar:focus, #fechaInicio:focus, #fechaFin:focus {
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

/* Botón limpiar */
.btn-limpiar {
    background-color: #95a5a6;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn-limpiar:hover {
    background-color: #7f8c8d;
}

/* Estilos para la tabla */
.viewsUser {
    margin-top: 30px;
}

.table-container {
    overflow-x: auto;
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
    cursor: pointer;
    user-select: none;
    position: relative;
}

th:hover {
    background-color: #e9ecef;
}

th.sortable::after {
    content: ' ⇅';
    opacity: 0.3;
    font-size: 0.8em;
}

th.sort-asc::after {
    content: ' ▲';
    opacity: 1;
}

th.sort-desc::after {
    content: ' ▼';
    opacity: 1;
}

tbody tr:hover {
    background-color: #f5f5f5;
}

/* Estilos para los botones */
.btn-delete, .btn-info {
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

.btn-info {
    background-color: #7f8c8d;
}

.btn-info:hover {
    background-color: #486466ff;
}

/* Controles de paginación */
.pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 15px 0;
    border-top: 1px solid #eee;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pagination-info label {
    font-size: 14px;
    color: #2c3e50;
}

.pagination-info select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    background-color: white;
}

.pagination-info select:focus {
    border-color: #3498db;
    outline: none;
}

.pagination-buttons {
    display: flex;
    gap: 5px;
}

.pagination-buttons button {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background-color: white;
    color: #2c3e50;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.pagination-buttons button:hover:not(:disabled) {
    background-color: #3498db;
    color: white;
    border-color: #3498db;
}

.pagination-buttons button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-buttons button.active {
    background-color: #3498db;
    color: white;
    border-color: #3498db;
}

.results-info {
    font-size: 14px;
    color: #7f8c8d;
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
    
    .filter-section {
        flex-direction: column;
    }
    
    #buscar, #fechaInicio, #fechaFin {
        width: 100%;
    }
    
    .pagination-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .pagination-buttons {
        justify-content: center;
        flex-wrap: wrap;
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
            
            <div class="viewsUser">
                <h3>Historial de ventas</h3>
                
                <!-- Sección de filtros -->
                <div class="filter-section">
                    <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="search-input">
                    <input type="date" name="fechaInicio" id="fechaInicio" placeholder="Fecha Inicio">
                    <input type="date" name="fechaFin" id="fechaFin" placeholder="Fecha Fin">
                    <button class="btn-limpiar" id="btn-limpiar">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
                
                <div class="table-container">
                    <table id="tabla-clientes">
                        <thead>
                            <tr>
                                <th class="sortable" data-column="0">Nombre Cliente</th>
                                <th class="sortable" data-column="1">Método de Pago</th>
                                <th class="sortable" data-column="2">Pago / Crédito</th>
                                <th class="sortable" data-column="3">Total $</th>
                                <th>Productos</th>
                                <th class="sortable" data-column="5">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($historial)): ?>
                                <?php foreach($historial as $info): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($info['cliente']); ?></td>
                                        <td><?php echo htmlspecialchars($info['tipo_pago']); ?></td>
                                        <td><?php echo htmlspecialchars($info['tipo_venta']); ?></td>
                                        <td data-value="<?php echo $info['total_usd']; ?>"><?php echo number_format($info['total_usd'],2,',','.'); ?></td>
                                        <td>
                                            <button 
                                                class="btn btn-info btn-sm btn-productos" 
                                                type="button"
                                                data-productos='<?php echo htmlspecialchars($info['productos_vendidos'], ENT_QUOTES, 'UTF-8'); ?>'
                                                title="Ver productos vendidos">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                        </td>
                                        <td><?php echo htmlspecialchars($info['fecha']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="no-data-row">
                                    <td colspan="6" style="text-align: center;">
                                        <div class="text-muted">
                                            <i class="fas fa-boxes fa-3x mb-3"></i>
                                            <h5>No hay Historial de Ventas</h5>
                                            <p>No se encontró un historial registrado.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Controles de paginación -->
                <div class="pagination-controls">
                    <div class="pagination-info">
                        <label for="registrosPorPagina">Mostrar:</label>
                        <select id="registrosPorPagina">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="all">Todos</option>
                        </select>
                        <span class="results-info" id="resultsInfo"></span>
                    </div>
                    <div class="pagination-buttons" id="paginationButtons"></div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script para ver productos -->
    <script>
        const precio_usd = <?= APP_Dollar ?>;
        document.querySelectorAll('.btn-productos').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let productosJson = btn.getAttribute('data-productos');
                let productos;
                try {
                    productos = JSON.parse(productosJson);
                } catch(e) {
                    Swal.fire('Error', 'No se pudo leer los productos vendidos.', 'error');
                    return;
                }
                if (!Array.isArray(productos) || productos.length === 0) {
                    Swal.fire('Sin productos', 'No hay productos vendidos en este registro.', 'info');
                    return;
                }
                
                let html = '<table style="width:100%;text-align:left"><thead><tr><th>Nombre</th><th>Código</th><th>Medida</th><th>Cantidad</th><th>Precio USD</th><th>Total USD</th></tr></thead><tbody>';
                
                productos.forEach(function(p) {
                    html += `<tr>
                        <td>${p.nombre}</td>
                        <td>${p.codigo}</td>
                        <td>${p.medida}</td>
                        <td>${p.cantidad}</td>
                        <td>${parseFloat(p.precio_usd).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})} $</td>
                        <td>${parseFloat(p.cantidad * p.precio_usd).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})} $</td>
                    </tr>`;
                });
                
                let totalGeneral = productos.reduce((sum, p) => sum + (p.cantidad * p.precio_usd), 0);
                
                html += '</tbody>';
                html += `<tfoot style="border-top: 2px solid #ddd;">
                    <tr>
                        <td colspan="4" style="padding: 10px; text-align: right; font-weight: bold;">TOTAL bs:</td>
                        <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold; color: #2c3e50;">${(totalGeneral * precio_usd).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})} bs</td>
                    </tr>
                </tfoot>`;
                html += `<tfoot style="border-top: 2px solid #ddd;">
                    <tr>
                        <td colspan="4" style="padding: 10px; text-align: right; font-weight: bold;">TOTAL :</td>
                        <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold; color: #2c3e50;">$${(totalGeneral).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    </tr>
                </tfoot>`;
                html += '</table>';
                
                Swal.fire({
                    title: 'Productos vendidos',
                    html: html,
                    width: 600,
                    confirmButtonText: 'Cerrar'
                });
            });
        });
    </script>

    <!-- Script de búsqueda, filtros, ordenamiento y paginación -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let buscarInput = document.getElementById('buscar');
            let fechaInicioInput = document.getElementById('fechaInicio');
            let fechaFinInput = document.getElementById('fechaFin');
            let btnLimpiar = document.getElementById('btn-limpiar');
            let tabla = document.getElementById('tabla-clientes');
            let tbody = tabla.querySelector('tbody');
            let todasLasFilas = Array.from(tbody.querySelectorAll('tr:not(#no-data-row)'));
            let registrosPorPaginaSelect = document.getElementById('registrosPorPagina');
            let paginationButtons = document.getElementById('paginationButtons');
            let resultsInfo = document.getElementById('resultsInfo');
            
            let paginaActual = 1;
            let registrosPorPagina = 5;
            let filasVisibles = [];
            let ordenActual = { columna: null, direccion: 'asc' };
            
            // Función para filtrar la tabla
            function filtrarTabla() {
                const textoBusqueda = buscarInput.value.toLowerCase().trim();
                const fechaInicio = fechaInicioInput.value;
                const fechaFin = fechaFinInput.value;
                
                filasVisibles = todasLasFilas.filter(fila => {
                    const celdas = fila.querySelectorAll('td');
                    const nombreCliente = celdas[0].textContent.toLowerCase();
                    const fechaVenta = celdas[5].textContent.trim();
                    
                    let coincide = true;
                    
                    // Filtrar por nombre
                    if (textoBusqueda !== '') {
                        coincide = nombreCliente.includes(textoBusqueda);
                    }
                    
                    // Filtrar por rango de fechas
                    if (coincide && fechaInicio && fechaFin) {
                        const fecha = new Date(fechaVenta);
                        const inicio = new Date(fechaInicio);
                        const fin = new Date(fechaFin);
                        coincide = (fecha >= inicio && fecha <= fin);
                    }
                    
                    return coincide;
                });
                
                paginaActual = 1;
                mostrarPagina();
            }
            
            // Función para ordenar la tabla
            function ordenarTabla(columna) {
                const headers = tabla.querySelectorAll('th.sortable');
                
                if (ordenActual.columna === columna) {
                    ordenActual.direccion = ordenActual.direccion === 'asc' ? 'desc' : 'asc';
                } else {
                    ordenActual.columna = columna;
                    ordenActual.direccion = 'asc';
                }
                
                // Actualizar estilos de los encabezados
                headers.forEach(th => {
                    th.classList.remove('sort-asc', 'sort-desc');
                });
                const headerActual = tabla.querySelector(`th[data-column="${columna}"]`);
                headerActual.classList.add(ordenActual.direccion === 'asc' ? 'sort-asc' : 'sort-desc');
                
                filasVisibles.sort((a, b) => {
                    let valorA, valorB;
                    
                    if (columna === 3) { // Columna Total $
                        valorA = parseFloat(a.querySelectorAll('td')[columna].getAttribute('data-value'));
                        valorB = parseFloat(b.querySelectorAll('td')[columna].getAttribute('data-value'));
                    } else if (columna === 5) { // Columna Fecha
                        valorA = new Date(a.querySelectorAll('td')[columna].textContent);
                        valorB = new Date(b.querySelectorAll('td')[columna].textContent);
                    } else {
                        valorA = a.querySelectorAll('td')[columna].textContent.toLowerCase();
                        valorB = b.querySelectorAll('td')[columna].textContent.toLowerCase();
                    }
                    
                    if (valorA < valorB) return ordenActual.direccion === 'asc' ? -1 : 1;
                    if (valorA > valorB) return ordenActual.direccion === 'asc' ? 1 : -1;
                    return 0;
                });
                
                mostrarPagina();
            }
            
            // Función para mostrar la página actual
            function mostrarPagina() {
                // Ocultar todas las filas
                todasLasFilas.forEach(fila => fila.style.display = 'none');
                
                // Remover mensaje de no resultados si existe
                const mensajeNoResultados = tbody.querySelector('#mensaje-no-resultados');
                if (mensajeNoResultados) mensajeNoResultados.remove();
                
                if (filasVisibles.length === 0) {
                    // Mostrar mensaje de no resultados
                    const tr = document.createElement('tr');
                    tr.id = 'mensaje-no-resultados';
                    tr.innerHTML = `
                        <td colspan="6" style="text-align: center;">
                            <div class="text-muted">
                                <i class="fas fa-search fa-3x mb-3"></i>
                                <h5>No se encontraron resultados</h5>
                                <p>No hay registros que coincidan con los filtros aplicados</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                    paginationButtons.innerHTML = '';
                    resultsInfo.textContent = 'Mostrando 0 de 0 registros';
                    return;
                }
                
                const totalPaginas = registrosPorPagina === 'all' ? 1 : Math.ceil(filasVisibles.length / registrosPorPagina);
                const inicio = registrosPorPagina === 'all' ? 0 : (paginaActual - 1) * registrosPorPagina;
                const fin = registrosPorPagina === 'all' ? filasVisibles.length : inicio + registrosPorPagina;
                
                // Mostrar filas de la página actual
                for (let i = inicio; i < fin && i < filasVisibles.length; i++) {
                    filasVisibles[i].style.display = '';
                }
                
                // Actualizar información de resultados
                resultsInfo.textContent = `Mostrando ${inicio + 1}-${Math.min(fin, filasVisibles.length)} de ${filasVisibles.length} registros`;
                
                // Crear botones de paginación
                crearBotonesPaginacion(totalPaginas);
            }
            
            // Función para crear botones de paginación
            function crearBotonesPaginacion(totalPaginas) {
                paginationButtons.innerHTML = '';
                
                if (totalPaginas <= 1) return;
                
                // Botón anterior
                const btnAnterior = document.createElement('button');
                btnAnterior.innerHTML = '<i class="fas fa-chevron-left"></i>';
                btnAnterior.disabled = paginaActual === 1;
                btnAnterior.addEventListener('click', () => {
                    if (paginaActual > 1) {
                        paginaActual--;
                        mostrarPagina();
                    }
                });
                paginationButtons.appendChild(btnAnterior);
                
                // Botones de páginas
                let inicio = Math.max(1, paginaActual - 2);
                let fin = Math.min(totalPaginas, inicio + 4);
                
                if (fin - inicio < 4) {
                    inicio = Math.max(1, fin - 4);
                }
                
                if (inicio > 1) {
                    const btn1 = document.createElement('button');
                    btn1.textContent = '1';
                    btn1.addEventListener('click', () => {
                        paginaActual = 1;
                        mostrarPagina();
                    });
                    paginationButtons.appendChild(btn1);
                    
                    if (inicio > 2) {
                        const btnDots = document.createElement('button');
                        btnDots.textContent = '...';
                        btnDots.disabled = true;
                        paginationButtons.appendChild(btnDots);
                    }
                }
                
                for (let i = inicio; i <= fin; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    if (i === paginaActual) btn.classList.add('active');
                    btn.addEventListener('click', () => {
                        paginaActual = i;
                        mostrarPagina();
                    });
                    paginationButtons.appendChild(btn);
                }
                
                if (fin < totalPaginas) {
                    if (fin < totalPaginas - 1) {
                        const btnDots = document.createElement('button');
                        btnDots.textContent = '...';
                        btnDots.disabled = true;
                        paginationButtons.appendChild(btnDots);
                    }
                    
                    const btnUltima = document.createElement('button');
                    btnUltima.textContent = totalPaginas;
                    btnUltima.addEventListener('click', () => {
                        paginaActual = totalPaginas;
                        mostrarPagina();
                    });
                    paginationButtons.appendChild(btnUltima);
                }
                
                // Botón siguiente
                const btnSiguiente = document.createElement('button');
                btnSiguiente.innerHTML = '<i class="fas fa-chevron-right"></i>';
                btnSiguiente.disabled = paginaActual === totalPaginas;
                btnSiguiente.addEventListener('click', () => {
                    if (paginaActual < totalPaginas) {
                        paginaActual++;
                        mostrarPagina();
                    }
                });
                paginationButtons.appendChild(btnSiguiente);
            }
            
            // Función para limpiar filtros
            function limpiarFiltros() {
                buscarInput.value = '';
                fechaInicioInput.value = '';
                fechaFinInput.value = '';
                filtrarTabla();
            }
            
            // Event listeners
            buscarInput.addEventListener('input', filtrarTabla);
            fechaInicioInput.addEventListener('change', filtrarTabla);
            fechaFinInput.addEventListener('change', filtrarTabla);
            btnLimpiar.addEventListener('click', limpiarFiltros);
            
            registrosPorPaginaSelect.addEventListener('change', function() {
                registrosPorPagina = this.value === 'all' ? 'all' : parseInt(this.value);
                paginaActual = 1;
                mostrarPagina();
            });
            
            // Agregar eventos de clic a los encabezados ordenables
            tabla.querySelectorAll('th.sortable').forEach(th => {
                th.addEventListener('click', function() {
                    const columna = parseInt(this.getAttribute('data-column'));
                    ordenarTabla(columna);
                });
            });
            
            // Inicializar
            filasVisibles = [...todasLasFilas];
            mostrarPagina();
        });
    </script>
</body>
</html>