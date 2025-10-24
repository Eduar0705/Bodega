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
/* Estilos para badges de estado */
.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-pendiente {
    background-color: #e74c3c;
    color: white;
}

.badge-parcial {
    background-color: #f39c12;
    color: white;
}

.badge-pagado {
    background-color: #27ae60;
    color: white;
}

/* Botones de acción */
.btn-descontar, .btn-info {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-descontar:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
}

.btn-info {
    background-color: #7f8c8d;
}

.btn-info:hover {
    background-color: #486466ff;
}

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

/* Contenedor de filtros */
.filter-section {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
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

th:hover:not(.no-sort) {
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
    transition: background-color 0.2s;
}

/* Fila con estado */
tbody tr.pagado {
    opacity: 0.6;
    background-color: #d5f4e6;
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

/* Estilos para el mensaje de no hay datos */
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

/* Loading spinner */
.spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-section input,
    .filter-section button {
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
    
    table {
        font-size: 12px;
    }
    
    th, td {
        padding: 8px 10px;
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
                <h3><?= $titulo?></h3>
                
                <!-- Filtros -->
                <div class="filter-section">
                    <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="search-input">
                    <input type="date" name="fechaInicio" id="fechaInicio" title="Fecha desde">
                    <input type="date" name="fechaFin" id="fechaFin" title="Fecha hasta">
                    <button class="btn-limpiar" id="btn-limpiar">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>

                <!-- Tabla -->
                <div class="table-container">
                    <table id="tabla-clientes">
                        <thead>
                            <tr>
                                <th class="sortable" data-column="0">Nombre Cliente</th>
                                <th class="sortable" data-column="1">Estado</th>
                                <th class="sortable" data-column="2">Método de Pago</th>
                                <th class="sortable" data-column="3">Total $</th>
                                <th class="no-sort">Productos</th>
                                <th class="sortable" data-column="5">Fecha</th>
                                <th class="no-sort">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($cuentas)): ?>
                                <?php foreach($cuentas as $info): ?>
                                    <?php 
                                        $total = floatval($info['total_usd']);
                                        $estado = 'pendiente';
                                        $estadoTexto = 'Pendiente';
                                        $claseFila = '';
                                        
                                        if (isset($info['tipo_venta'])) {
                                            if ($info['tipo_venta'] === 'pagado' || $total <= 0) {
                                                $estado = 'pagado';
                                                $estadoTexto = 'Pagado';
                                                $claseFila = 'pagado';
                                            } elseif ($info['tipo_venta'] === 'parcial') {
                                                $estado = 'parcial';
                                                $estadoTexto = 'Parcial';
                                            }
                                        }
                                    ?>
                                    <tr class="<?= $claseFila ?>" data-total="<?= $total ?>">
                                        <td><?php echo htmlspecialchars($info['cliente']); ?></td>
                                        <td>
                                            <span class="badge badge-<?= $estado ?>">
                                                <?= $estadoTexto ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($info['tipo_pago']); ?></td>
                                        <td data-valor="<?= $total ?>">
                                            $<?php echo number_format($total, 2, '.', ','); ?>
                                        </td>
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
                                        <td>
                                            <?php if ($total > 0): ?>
                                                <button 
                                                    class="btn btn-sm btn-warning btn-descontar"
                                                    title="Registrar pago" 
                                                    data-id="<?php echo $info['id_historial']; ?>"
                                                    data-monto="<?php echo $total; ?>"
                                                    data-cliente="<?php echo htmlspecialchars($info['cliente']); ?>">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted" title="Cuenta saldada">
                                                    <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="no-data-row">
                                    <td colspan="7" style="text-align: center;">
                                        <div class="text-muted">
                                            <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                            <h5>No hay Cuentas por Cobrar</h5>
                                            <p>No se encontraron cuentas pendientes registradas.</p>
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo leer los productos vendidos.'
                    });
                    return;
                }
                
                if (!Array.isArray(productos) || productos.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin productos',
                        text: 'No hay productos vendidos en este registro.'
                    });
                    return;
                }
                
                let html = `
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead style="position: sticky; top: 0; background: #f8f9fa;">
                                <tr style="border-bottom: 2px solid #ddd;">
                                    <th style="padding: 10px; text-align: left;">Nombre</th>
                                    <th style="padding: 10px; text-align: left;">Código</th>
                                    <th style="padding: 10px; text-align: center;">Cantidad</th>
                                    <th style="padding: 10px; text-align: right;">Precio</th>
                                    <th style="padding: 10px; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                let totalGeneral = 0;
                productos.forEach(function(p) {
                    const totalProducto = parseFloat(p.cantidad * p.precio_usd);
                    totalGeneral += totalProducto;
                    
                    html += `
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 8px;">${p.nombre}</td>
                            <td style="padding: 8px;">${p.codigo}</td>
                            <td style="padding: 8px; text-align: center;">${p.cantidad} ${p.medida || ''}</td>
                            <td style="padding: 8px; text-align: right;">$${parseFloat(p.precio_usd).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td style="padding: 8px; text-align: right; font-weight: bold;">$${totalProducto.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                            <tfoot style="border-top: 2px solid #ddd;">
                                <tr>
                                    <td colspan="4" style="padding: 10px; text-align: right; font-weight: bold;">TOTAL:</td>
                                    <td style="padding: 10px; text-align: right; font-weight: bold; color: #2c3e50;">$${totalGeneral.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding: 10px; text-align: right; font-weight: bold;">TOTAL bs:</td>
                                    <td style="padding: 10px; text-align: right; font-weight: bold; color: #2c3e50;">${(totalGeneral * precio_usd).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})} bs</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                
                Swal.fire({
                    title: 'Productos Vendidos',
                    html: html,
                    width: 700,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#3498db'
                });
            });
        });
    </script>

    <!-- Script para botón descontar -->
    <script>
        document.querySelectorAll('.btn-descontar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const idHistorial = this.getAttribute('data-id');
                const montoTotal = parseFloat(this.getAttribute('data-monto'));
                const nombreCliente = this.getAttribute('data-cliente');
                
                console.log('Iniciando pago:', {idHistorial, montoTotal, nombreCliente});
                
                Swal.fire({
                    title: 'Registrar Pago',
                    html: `
                        <div style="text-align: left; margin: 20px 0;">
                            <p style="margin: 10px 0;"><strong>Cliente:</strong> ${nombreCliente}</p>
                            <p style="margin: 10px 0;"><strong>Saldo pendiente:</strong> <span style="color: #e74c3c; font-size: 20px; font-weight: bold;">$${montoTotal.toFixed(2)}</span></p>
                            <p style="margin: 10px 0;"><strong>Saldo pendiente:</strong> <span style="color: #e74c3c; font-size: 20px; font-weight: bold;">${(montoTotal * precio_usd).toFixed(2)} Bs</span></p>
                        </div>
                        <div style="margin-top: 20px;">
                            <label style="display: block; text-align: left; margin-bottom: 10px; cursor: pointer;">
                                <input type="radio" name="tipo_pago" value="usd" checked style="margin-right: 5px;">
                                <strong>Pagar en Dólares (USD)</strong>
                            </label>
                            <input type="number" id="monto_usd" class="swal2-input" placeholder="Ingrese monto en USD" 
                                min="0.01" max="${montoTotal}" step="0.01" value="${montoTotal.toFixed(2)}" 
                                style="width: 90%; margin: 10px 0;">
                            
                            <label style="display: block; text-align: left; margin: 20px 0 10px 0; cursor: pointer;">
                                <input type="radio" name="tipo_pago" value="bs" style="margin-right: 5px;">
                                <strong>Pagar en Bolívares (Bs)</strong>
                            </label>
                            <input type="number" id="monto_bs" class="swal2-input" placeholder="Ingrese monto en Bs" 
                                min="0.01" step="0.01" disabled 
                                style="width: 90%; margin: 10px 0;">
                            
                            <p id="equivalencia" style="margin-top: 10px; color: #16a085; font-weight: bold; font-size: 15px;"></p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check"></i> Registrar Pago',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    showLoaderOnConfirm: true,
                    didOpen: () => {
                        const radioUsd = document.querySelector('input[value="usd"]');
                        const radioBs = document.querySelector('input[value="bs"]');
                        const inputUsd = document.getElementById('monto_usd');
                        const inputBs = document.getElementById('monto_bs');
                        const equivalencia = document.getElementById('equivalencia');
                        
                        function actualizarEquivalencia() {
                            if (radioUsd.checked && inputUsd.value) {
                                const usd = parseFloat(inputUsd.value);
                                if (!isNaN(usd) && usd > 0) {
                                    const bs = (usd * precio_usd).toFixed(2);
                                    equivalencia.textContent = `≈ ${bs} Bs`;
                                } else {
                                    equivalencia.textContent = '';
                                }
                            } else if (radioBs.checked && inputBs.value) {
                                const bs = parseFloat(inputBs.value);
                                if (!isNaN(bs) && bs > 0) {
                                    const usd = (bs / precio_usd).toFixed(2);
                                    equivalencia.textContent = `≈ $${usd}`;
                                } else {
                                    equivalencia.textContent = '';
                                }
                            } else {
                                equivalencia.textContent = '';
                            }
                        }
                        
                        radioUsd.addEventListener('change', function() {
                            inputUsd.disabled = false;
                            inputBs.disabled = true;
                            inputBs.value = '';
                            if (!inputUsd.value) {
                                inputUsd.value = montoTotal.toFixed(2);
                            }
                            actualizarEquivalencia();
                        });
                        
                        radioBs.addEventListener('change', function() {
                            inputUsd.disabled = true;
                            inputBs.disabled = false;
                            inputUsd.value = '';
                            if (!inputBs.value) {
                                inputBs.value = (montoTotal * precio_usd).toFixed(2);
                            }
                            actualizarEquivalencia();
                        });
                        
                        inputUsd.addEventListener('input', actualizarEquivalencia);
                        inputBs.addEventListener('input', actualizarEquivalencia);
                        
                        actualizarEquivalencia();
                    },
                    preConfirm: () => {
                        const radioUsd = document.querySelector('input[value="usd"]');
                        const inputUsd = document.getElementById('monto_usd');
                        const inputBs = document.getElementById('monto_bs');
                        
                        let montoUsd;
                        
                        if (radioUsd.checked) {
                            montoUsd = parseFloat(inputUsd.value);
                            
                            if (!inputUsd.value || isNaN(montoUsd) || montoUsd <= 0) {
                                Swal.showValidationMessage('Ingrese un monto válido en USD mayor a 0');
                                return false;
                            }
                        } else {
                            const montoBs = parseFloat(inputBs.value);
                            
                            if (!inputBs.value || isNaN(montoBs) || montoBs <= 0) {
                                Swal.showValidationMessage('Ingrese un monto válido en Bs mayor a 0');
                                return false;
                            }
                            
                            montoUsd = montoBs / precio_usd;
                        }
                        
                        if (montoUsd > (montoTotal + 0.01)) {
                            Swal.showValidationMessage(`El monto no puede ser mayor a $${montoTotal.toFixed(2)}`);
                            return false;
                        }
                        
                        const datosEnvio = { 
                            id_historial: parseInt(idHistorial),
                            monto: parseFloat(montoUsd.toFixed(2))
                        };
                        
                        console.log('=== ENVIANDO SOLICITUD ===');
                        console.log('URL:', '?action=admin&method=descontarMonto');
                        console.log('Datos:', datosEnvio);
                        
                        return fetch('?action=admin&method=descontarMonto', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(datosEnvio)
                        })
                        .then(response => {
                            console.log('=== RESPUESTA RECIBIDA ===');
                            console.log('Status:', response.status);
                            console.log('OK:', response.ok);
                            
                            return response.text().then(text => {
                                console.log('Respuesta texto:', text);
                                
                                try {
                                    const data = JSON.parse(text);
                                    console.log('Respuesta parseada:', data);
                                    
                                    if (!response.ok) {
                                        throw new Error(data.message || `Error HTTP ${response.status}`);
                                    }
                                    
                                    if (!data.success) {
                                        throw new Error(data.message || 'Error desconocido del servidor');
                                    }
                                    
                                    return data;
                                } catch (parseError) {
                                    console.error('Error parseando JSON:', parseError);
                                    console.error('Texto recibido:', text);
                                    
                                    if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                                        throw new Error('El servidor devolvió HTML en lugar de JSON. Revisa que no haya errores PHP o salidas antes del JSON.');
                                    }
                                    
                                    throw new Error('Respuesta inválida del servidor. Revisa la consola para más detalles.');
                                }
                            });
                        })
                        .catch(error => {
                            console.error('=== ERROR ===');
                            console.error('Mensaje:', error.message);
                            console.error('Stack:', error.stack);
                            
                            Swal.showValidationMessage(`Error: ${error.message}`);
                            return false;
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    console.log('=== RESULTADO FINAL ===', result);
                    
                    if (result.isConfirmed && result.value && result.value.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Pago Registrado!',
                            text: result.value.message,
                            confirmButtonColor: '#27ae60'
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>

    <!-- Script de búsqueda, filtros, ordenamiento y paginación -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buscarInput = document.getElementById('buscar');
            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const btnLimpiar = document.getElementById('btn-limpiar');
            const tabla = document.getElementById('tabla-clientes');
            const tbody = tabla.querySelector('tbody');
            const todasLasFilas = Array.from(tbody.querySelectorAll('tr:not(#no-data-row)'));
            const registrosPorPaginaSelect = document.getElementById('registrosPorPagina');
            const paginationButtons = document.getElementById('paginationButtons');
            const resultsInfo = document.getElementById('resultsInfo');
            
            let paginaActual = 1;
            let registrosPorPagina = 5;
            let filasVisibles = [];
            let ordenActual = { columna: null, direccion: 'asc' };
            
            // Función para normalizar fechas
            function normalizarFecha(fechaStr) {
                if (fechaStr.includes('/')) {
                    const partes = fechaStr.split('/');
                    return new Date(partes[2], partes[1] - 1, partes[0]);
                }
                return new Date(fechaStr);
            }
            
            // Función para filtrar la tabla
            function filtrarTabla() {
                const textoBusqueda = buscarInput.value.toLowerCase().trim();
                const fechaInicio = fechaInicioInput.value;
                const fechaFin = fechaFinInput.value;
                
                filasVisibles = todasLasFilas.filter(fila => {
                    const celdas = fila.querySelectorAll('td');
                    const nombreCliente = celdas[0].textContent.toLowerCase();
                    const fechaCliente = celdas[5].textContent.trim();
                    
                    let coincide = true;
                    
                    // Filtrar por nombre
                    if (textoBusqueda !== '') {
                        coincide = nombreCliente.includes(textoBusqueda);
                    }
                    
                    // Filtrar por rango de fechas
                    if (coincide && (fechaInicio || fechaFin)) {
                        const fecha = normalizarFecha(fechaCliente);
                        
                        if (fechaInicio && fechaFin) {
                            const inicio = new Date(fechaInicio);
                            const fin = new Date(fechaFin);
                            fin.setHours(23, 59, 59);
                            coincide = (fecha >= inicio && fecha <= fin);
                        } else if (fechaInicio) {
                            const inicio = new Date(fechaInicio);
                            coincide = fecha >= inicio;
                        } else if (fechaFin) {
                            const fin = new Date(fechaFin);
                            fin.setHours(23, 59, 59);
                            coincide = fecha <= fin;
                        }
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
                    
                    if (columna === 1) { // Columna Estado
                        const estadosOrden = { 'pendiente': 0, 'parcial': 1, 'pagado': 2 };
                        const badgeA = a.querySelectorAll('td')[columna].querySelector('.badge');
                        const badgeB = b.querySelectorAll('td')[columna].querySelector('.badge');
                        valorA = estadosOrden[badgeA.classList[1].replace('badge-', '')] || 0;
                        valorB = estadosOrden[badgeB.classList[1].replace('badge-', '')] || 0;
                    } else if (columna === 3) { // Columna Total $
                        valorA = parseFloat(a.querySelectorAll('td')[columna].getAttribute('data-valor'));
                        valorB = parseFloat(b.querySelectorAll('td')[columna].getAttribute('data-valor'));
                    } else if (columna === 5) { // Columna Fecha
                        valorA = normalizarFecha(a.querySelectorAll('td')[columna].textContent);
                        valorB = normalizarFecha(b.querySelectorAll('td')[columna].textContent);
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
                        <td colspan="7" style="text-align: center;">
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