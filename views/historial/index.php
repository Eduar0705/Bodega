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

/* NUEVO: Contenedor de filtros */
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

/* NUEVO: Botón limpiar */
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
            
            <div class="viewsUser">
                <h3>Historial de ventas</h3>
                
                <!-- NUEVO: Sección de filtros -->
                <div class="filter-section">
                    <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="search-input">
                    <input type="date" name="fechaInicio" id="fechaInicio" placeholder="Fecha Inicio">
                    <input type="date" name="fechaFin" id="fechaFin" placeholder="Fecha Fin">
                    <button class="btn-limpiar" id="btn-limpiar">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
                
                <table id="tabla-clientes">
                    <thead>
                        <tr>
                            <th>Nombre Cliente</th>
                            <th>Metodo de Pago</th>
                            <th>Pago / Credito</th>
                            <th>Total $</th>
                            <th>Productos</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($historial)): ?>
                            <?php foreach($historial as $info): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($info['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($info['tipo_pago']); ?></td>
                                    <td><?php echo htmlspecialchars($info['tipo_venta']); ?></td>
                                    <td><?php echo number_format($info['total_usd'],2,',','.'); ?></td>
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
                            <tr>
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

    <!-- NUEVO: Script de búsqueda y filtros -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let buscarInput = document.getElementById('buscar');
            let fechaInicioInput = document.getElementById('fechaInicio');
            let fechaFinInput = document.getElementById('fechaFin');
            let btnLimpiar = document.getElementById('btn-limpiar');
            let tabla = document.getElementById('tabla-clientes');
            let filas = tabla.querySelectorAll('tbody tr');
            
            function filtrarTabla() {
                const textoBusqueda = buscarInput.value.toLowerCase().trim();
                const fechaInicio = fechaInicioInput.value;
                const fechaFin = fechaFinInput.value;
                let resultadosVisibles = false;
                
                filas.forEach(fila => {
                    // Evitar procesar fila de "no hay datos"
                    if (fila.querySelector('.text-muted')) {
                        return;
                    }
                    
                    const celdas = fila.querySelectorAll('td');
                    const nombreCliente = celdas[0].textContent.toLowerCase();
                    const fechaVenta = celdas[5].textContent.trim(); // La fecha está en la columna 6
                    
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

                    fila.style.display = coincide ? '' : 'none';
                    if (coincide) resultadosVisibles = true;
                });
                
                // Manejar mensaje de no resultados
                const mensajeExistente = tabla.querySelector('#mensaje-no-resultados');
                const tbody = tabla.querySelector('tbody');
                
                if (!resultadosVisibles && (textoBusqueda !== '' || (fechaInicio && fechaFin))) {
                    if (!mensajeExistente) {
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
                    }
                } else if (mensajeExistente) {
                    mensajeExistente.remove();
                }
            }

            // Función para limpiar filtros
            function limpiarFiltros() {
                buscarInput.value = '';
                fechaInicioInput.value = '';
                fechaFinInput.value = '';
                filtrarTabla();
            }

            // Agregar eventos
            buscarInput.addEventListener('input', filtrarTabla);
            fechaInicioInput.addEventListener('change', filtrarTabla);
            fechaFinInput.addEventListener('change', filtrarTabla);
            btnLimpiar.addEventListener('click', limpiarFiltros);
        });
    </script>
</body>
</html>