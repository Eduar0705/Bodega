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
    margin-left: 10px;
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
    transition: background-color 0.2s;
}

/* Fila con estado */
tbody tr.pagado {
    opacity: 0.6;
    background-color: #d5f4e6;
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
        margin-left: 0 !important;
    }
    
    table {
        display: block;
        overflow-x: auto;
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
                    <input type="text" id="buscar" name="buscar" placeholder="🔍 Buscar por nombre" class="search-input">
                    <input type="date" name="fechaInicio" id="fechaInicio" title="Fecha desde">
                    <input type="date" name="fechaFin" id="fechaFin" title="Fecha hasta">
                    <button class="btn-limpiar" id="btn-limpiar">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>

                <!-- Tabla -->
                <table id="tabla-clientes">
                    <thead>
                        <tr>
                            <th>Nombre Cliente</th>
                            <th>Estado</th>
                            <th>Método de Pago</th>
                            <th>Total $</th>
                            <th>Productos</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
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
                            <tr>
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
                            </tfoot>
                        </table>
                    </div>
                `;
                html += `
                            </tbody>
                            <tfoot style="border-top: 2px solid #ddd;">
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
                
                Swal.fire({
                    title: 'Registrar Pago',
                    html: `
                        <div style="text-align: left; margin: 20px 0;">
                            <p style="margin: 10px 0;"><strong>Cliente:</strong> ${nombreCliente}</p>
                            <p style="margin: 10px 0;"><strong>Saldo pendiente:</strong> <span style="color: #e74c3c; font-size: 20px; font-weight: bold;">$${montoTotal.toFixed(2)}</span></p>
                        </div>
                    `,
                    input: 'number',
                    inputLabel: 'Monto a registrar',
                    inputPlaceholder: `Ingrese el monto (máximo: $${montoTotal.toFixed(2)})`,
                    inputValue: montoTotal.toFixed(2),
                    inputAttributes: {
                        min: 0.01,
                        max: montoTotal,
                        step: '0.01'
                    },
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check"></i> Registrar Pago',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    showLoaderOnConfirm: true,
                    preConfirm: (monto) => {
                        const montoNum = parseFloat(monto);
                        
                        if (!monto || montoNum <= 0) {
                            Swal.showValidationMessage('Ingrese un monto válido mayor a 0');
                            return false;
                        }
                        
                        if (montoNum > montoTotal) {
                            Swal.showValidationMessage(`El monto no puede ser mayor a $${montoTotal.toFixed(2)}`);
                            return false;
                        }
                        
                        // Realizar el fetch
                        return fetch('?action=admin&method=descontarMonto', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ 
                                id_historial: idHistorial,
                                monto: montoNum
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error: ${error.message || 'No se pudo conectar con el servidor'}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        if (result.value.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Registrado!',
                                text: result.value.message,
                                confirmButtonColor: '#27ae60'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.value.message || 'No se pudo procesar el pago',
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    }
                });
            });
        });
    </script>

    <!-- Script de búsqueda y filtros -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buscarInput = document.getElementById('buscar');
            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const btnLimpiar = document.getElementById('btn-limpiar');
            const tabla = document.getElementById('tabla-clientes');
            const filas = tabla.querySelectorAll('tbody tr:not(.no-data)');
            
            // Función para normalizar fechas (convierte DD/MM/YYYY o YYYY-MM-DD a objeto Date)
            function normalizarFecha(fechaStr) {
                // Si está en formato DD/MM/YYYY
                if (fechaStr.includes('/')) {
                    const partes = fechaStr.split('/');
                    return new Date(partes[2], partes[1] - 1, partes[0]);
                }
                // Si está en formato YYYY-MM-DD
                return new Date(fechaStr);
            }
            
            function buscarClientes() {
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
                    const fechaCliente = celdas[5].textContent.trim(); // Columna de fecha (ajustada a índice 5)
                    
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
                            fin.setHours(23, 59, 59); // Incluir todo el día final
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

                    fila.style.display = coincide ? '' : 'none';
                    if (coincide) resultadosVisibles = true;
                });
                
                // Manejar mensaje de no resultados
                const mensajeExistente = tabla.querySelector('#mensaje-no-resultados');
                const tbody = tabla.querySelector('tbody');
                
                if (!resultadosVisibles && (textoBusqueda !== '' || fechaInicio || fechaFin)) {
                    if (!mensajeExistente) {
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
                    }
                } else if (mensajeExistente) {
                    mensajeExistente.remove();
                }
            }

            function limpiarFiltros() {
                buscarInput.value = '';
                fechaInicioInput.value = '';
                fechaFinInput.value = '';
                buscarClientes();
            }

            // Eventos
            buscarInput.addEventListener('input', buscarClientes);
            fechaInicioInput.addEventListener('change', buscarClientes);
            fechaFinInput.addEventListener('change', buscarClientes);
            btnLimpiar.addEventListener('click', limpiarFiltros);
        });
    </script>
</body>
</html>