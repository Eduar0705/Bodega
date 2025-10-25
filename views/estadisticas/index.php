<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Sistema Bodega' ?> - <?= $titulo ?? 'Estadisticas' ?></title>
    <link rel="shortcut icon" href="<?= APP_Logo ?? 'public/img/logo.png' ?>" type="image/x-icon">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1400px; margin: 0 auto; padding: 1rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #ffffff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; position: relative; overflow: hidden; border: 1px solid #f0f0f0; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end)); }
        .stat-card.blue { --gradient-start: #667eea; --gradient-end: #764ba2; }
        .stat-card.green { --gradient-start: #10b981; --gradient-end: #059669; }
        .stat-card.orange { --gradient-start: #f59e0b; --gradient-end: #d97706; }
        .stat-card.purple { --gradient-start: #8b5cf6; --gradient-end: #7c3aed; }
        .stat-card.red { --gradient-start: #ef4444; --gradient-end: #dc2626; }
        .stat-card.teal { --gradient-start: #0d9488; --gradient-end: #0f766e; }
        .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .stat-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: white; background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)); }
        .stat-value { font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; font-variant-numeric: tabular-nums; }
        .stat-label { color: #6b7280; font-size: 0.9rem; font-weight: 500; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-change { font-size: 0.85rem; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 0.4rem; }
        .stat-change.positive { background-color: #d1fae5; color: #065f46; }
        .stat-change.negative { background-color: #fee2e2; color: #991b1b; }
        .stat-change.neutral { background-color: #f3f4f6; color: #374151; }
        
        .charts-section { display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .chart-card { background: #ffffff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid #f0f0f0; }
        .chart-card.full-width { grid-column: 1 / -1; }
        .chart-title { font-size: 1.2rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.8rem; }
        .chart-title::before { content: ''; width: 4px; height: 20px; background: linear-gradient(180deg, #667eea, #764ba2); border-radius: 2px; }
        .chart-container { position: relative; height: 300px; }
        
        .table-card { background: #ffffff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid #f0f0f0; margin-bottom: 1.5rem; }
        .table-wrapper { overflow-x: auto; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(135deg, #667eea, #764ba2); }
        th { padding: 1rem; text-align: left; font-weight: 600; color: white; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px; }
        td { padding: 1rem; border-bottom: 1px solid #f3f4f6; color: #374151; }
        tbody tr:hover { background-color: #f9fafb; }
        
        .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .status-badge.active { background-color: #d1fae5; color: #065f46; }
        .status-badge.active::before { background-color: #10b981; }
        .status-badge.pending { background-color: #fef3c7; color: #92400e; }
        .status-badge.pending::before { background-color: #f59e0b; }
        .status-badge.inactive { background-color: #fee2e2; color: #991b1b; }
        .status-badge.inactive::before { background-color: #ef4444; }
        
        .loading-indicator { position: fixed; top: 20px; right: 20px; background: white; padding: 0.8rem 1.2rem; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 0.8rem; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease; z-index: 1000; border: 1px solid #f0f0f0; }
        .loading-indicator.active { opacity: 1; transform: translateY(0); }
        .loading-indicator .spinner { width: 16px; height: 16px; border: 2px solid #e5e7eb; border-top-color: #667eea; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .charts-section { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php include_once 'views/inc/heder.php'; ?>
        <main class="main-content">
            <h2><i class="fas fa-chart-line"></i> <?= $titulo ?? 'Estadísticas' ?></h2>
            
            <div class="container">
                <!-- Indicador de carga -->
                <div class="loading-indicator" id="loadingIndicator">
                    <div class="spinner"></div>
                    <span>Actualizando datos...</span>
                </div>

                <!-- Tarjetas de Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card green">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                        <div class="stat-value" id="revenue">$<?= number_format($estadisticas['ingresos_mes'], 2) ?></div>
                        <div class="stat-label">Ingresos del Mes</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> Desde historial
                        </div>
                    </div>

                    <div class="stat-card orange">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                        </div>
                        <div class="stat-value" id="orders"><?= $estadisticas['total_ventas'] ?></div>
                        <div class="stat-label">Ventas del Mes</div>
                        <div class="stat-change neutral">
                            <i class="fas fa-chart-bar"></i> Transacciones completadas
                        </div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                        <div class="stat-value" id="lowStock"><?= $estadisticas['stock_bajo'] ?></div>
                        <div class="stat-label">Stock Bajo</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i> Menos de 10 unidades
                        </div>
                    </div>

                    <div class="stat-card purple">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                        </div>
                        <div class="stat-value" id="accountsReceivable">$<?= number_format($estadisticas['cuentas_cobrar'], 2) ?></div>
                        <div class="stat-label">Por Cobrar</div>
                        <div class="stat-change pending">
                            <i class="fas fa-clock"></i> Cuentas pendientes
                        </div>
                    </div>

                    <div class="stat-card teal">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                        </div>
                        <div class="stat-value" id="clientesActivos"><?= $clientesActivos ?></div>
                        <div class="stat-label">Clientes Activos</div>
                        <div class="stat-change positive">
                            <i class="fas fa-user-check"></i> Este mes
                        </div>
                    </div>

                    <div class="stat-card blue">
                        <div class="stat-header">
                            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                        </div>
                        <div class="stat-value" id="totalProductos">
                            <?php 
                                $query = "SELECT COUNT(*) as total FROM inventario";
                                $result = (new BaseDatos())->conectar()->query($query);
                                $total = $result->fetch_assoc();
                                echo $total['total'];
                                $result->free();
                            ?>
                        </div>
                        <div class="stat-label">Total Productos</div>
                        <div class="stat-change neutral">
                            <i class="fas fa-database"></i> En inventario
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="charts-section">
                    <div class="chart-card">
                        <h3 class="chart-title">Ventas Mensuales (<?= date('Y') ?>)</h3>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h3 class="chart-title">Distribución por Valor de Inventario</h3>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- NUEVO GRÁFICO: Top 5 Productos -->
                    <div class="chart-card full-width">
                        <h3 class="chart-title">Top 5 Productos por Valor en Inventario</h3>
                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Actividad Reciente -->
                <div class="table-card">
                    <h3 class="chart-title">Actividad Reciente</h3>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Acción</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Valor USD</th>
                                </tr>
                            </thead>
                            <tbody id="activityTable">
                                <?php if (!empty($actividadReciente)): ?>
                                    <?php foreach ($actividadReciente as $actividad): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($actividad['cliente'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($actividad['accion'] ?? 'N/A') ?></td>
                                        <td><?= date('Y-m-d H:i', strtotime($actividad['fecha'])) ?></td>
                                        <td>
                                            <span class="status-badge <?= $actividad['estado'] ?? 'pending' ?>">
                                                <?= ucfirst($actividad['estado'] ?? 'Pendiente') ?>
                                            </span>
                                        </td>
                                        <td>$<?= number_format($actividad['valor'] ?? 0, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280;">
                                            <i class="fas fa-inbox"></i> No hay actividad reciente
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Productos -->
                <div class="table-card">
                    <h3 class="chart-title">Productos con Mayor Valor en Inventario</h3>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Precio Venta</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topProductos)): ?>
                                    <?php foreach ($topProductos as $producto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                        <td><?= $producto['stock'] ?> unidades</td>
                                        <td>$<?= number_format($producto['precio_venta'], 2) ?></td>
                                        <td><strong>$<?= number_format($producto['valor_inventario'], 2) ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #6b7280;">
                                            <i class="fas fa-box-open"></i> No hay productos en inventario
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Pasar datos de PHP a JavaScript
        const ventasMensuales = <?= json_encode($ventasMensuales) ?>;
        const distribucionProductos = <?= json_encode($distribucionProductos) ?>;
        const topProductos = <?= json_encode($topProductos) ?>;

        // Funciones de animación de valores
        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            if (!obj) return;
            
            const range = end - start;
            const increment = end > start ? 1 : -1;
            const stepTime = Math.abs(Math.floor(duration / range));
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (id === 'revenue' || id === 'accountsReceivable') {
                    obj.textContent = '$' + current.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                } else {
                    obj.textContent = current.toLocaleString();
                }
                
                if (current === end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        // Gráfico de ventas mensuales
        function drawSalesChart() {
            const canvas = document.getElementById('salesChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            canvas.width = canvas.offsetWidth;
            canvas.height = 300;

            const data = Object.values(ventasMensuales);
            const labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            
            const max = Math.max(...data) || 1;
            const padding = 50;
            const chartHeight = canvas.height - padding * 2;
            const chartWidth = canvas.width - padding * 2;
            const barWidth = chartWidth / data.length;

            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Fondo
            ctx.fillStyle = 'rgba(102, 126, 234, 0.02)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Líneas de guía
            ctx.strokeStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.lineWidth = 1;
            for (let i = 0; i <= 5; i++) {
                const y = padding + (chartHeight / 5) * i;
                ctx.beginPath();
                ctx.moveTo(padding, y);
                ctx.lineTo(canvas.width - padding, y);
                ctx.stroke();
                
                // Etiquetas del eje Y
                const value = max - (max / 5) * i;
                ctx.fillStyle = '#6b7280';
                ctx.font = '11px sans-serif';
                ctx.textAlign = 'right';
                ctx.fillText('$' + (value/1000).toFixed(1) + 'k', padding - 10, y + 4);
            }

            // Dibujar barras
            data.forEach((value, index) => {
                const barHeight = (value / max) * chartHeight;
                const x = padding + index * barWidth + barWidth * 0.1;
                const y = canvas.height - padding - barHeight;
                const width = barWidth * 0.8;

                // Gradiente para las barras
                const gradient = ctx.createLinearGradient(0, y, 0, canvas.height - padding);
                gradient.addColorStop(0, '#667eea');
                gradient.addColorStop(1, '#764ba2');

                ctx.fillStyle = gradient;
                ctx.fillRect(x, y, width, barHeight);

                // Valores encima de las barras
                if (value > 0) {
                    ctx.fillStyle = '#374151';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('$' + (value/1000).toFixed(1) + 'k', x + width / 2, y - 8);
                }

                // Etiquetas del eje X
                ctx.fillStyle = '#6b7280';
                ctx.font = '12px sans-serif';
                ctx.fillText(labels[index], x + width / 2, canvas.height - padding + 20);
            });
        }

        // Gráfico de distribución de productos (Donut Chart)
        function drawCategoryChart() {
            const canvas = document.getElementById('categoryChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            canvas.width = canvas.offsetWidth;
            canvas.height = 300;

            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (distribucionProductos.length === 0) {
                ctx.fillStyle = '#6b7280';
                ctx.font = '16px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos de distribución', canvas.width / 2, canvas.height / 2);
                return;
            }

            const data = distribucionProductos.map(item => parseFloat(item.valor_total));
            const labels = distribucionProductos.map(item => item.categoria);
            const colors = ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
            
            const total = data.reduce((sum, value) => sum + value, 0);
            const percentages = data.map(value => ((value / total) * 100).toFixed(1));

            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = Math.min(centerX, centerY) - 70;
            const innerRadius = radius * 0.5;

            let currentAngle = -Math.PI / 2;

            data.forEach((value, index) => {
                const sliceAngle = (value / total) * 2 * Math.PI;

                // Dibujar segmento
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
                ctx.closePath();
                ctx.fillStyle = colors[index % colors.length];
                ctx.fill();

                // Dibujar etiqueta
                const labelAngle = currentAngle + sliceAngle / 2;
                const labelX = centerX + Math.cos(labelAngle) * (radius + 35);
                const labelY = centerY + Math.sin(labelAngle) * (radius + 35);

                // Línea conectora
                ctx.strokeStyle = colors[index % colors.length];
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(centerX + Math.cos(labelAngle) * radius, centerY + Math.sin(labelAngle) * radius);
                ctx.lineTo(labelX - 10 * Math.sign(Math.cos(labelAngle)), labelY);
                ctx.stroke();

                // Etiqueta
                ctx.fillStyle = colors[index % colors.length];
                ctx.font = 'bold 12px sans-serif';
                ctx.textAlign = Math.cos(labelAngle) > 0 ? 'left' : 'right';
                ctx.fillText(labels[index], labelX, labelY - 5);
                
                ctx.font = 'bold 14px sans-serif';
                ctx.fillText(percentages[index] + '%', labelX, labelY + 12);

                currentAngle += sliceAngle;
            });

            // Texto central
            ctx.fillStyle = '#1f2937';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Total', centerX, centerY - 8);
            ctx.font = 'bold 18px sans-serif';
            ctx.fillText('$' + (total/1000).toFixed(1) + 'k', centerX, centerY + 12);
        }

        // NUEVO: Gráfico de barras horizontales para top productos
        function drawTopProductsChart() {
            const canvas = document.getElementById('topProductsChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            canvas.width = canvas.offsetWidth;
            canvas.height = 300;

            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            if (topProductos.length === 0) {
                ctx.fillStyle = '#6b7280';
                ctx.font = '16px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('No hay productos en inventario', canvas.width / 2, canvas.height / 2);
                return;
            }

            const data = topProductos.map(p => parseFloat(p.valor_inventario));
            const labels = topProductos.map(p => p.nombre);
            const max = Math.max(...data) || 1;
            
            const padding = 40;
            const leftPadding = 150;
            const chartHeight = canvas.height - padding * 2;
            const chartWidth = canvas.width - leftPadding - padding;
            const barHeight = chartHeight / data.length;

            // Fondo
            ctx.fillStyle = 'rgba(102, 126, 234, 0.02)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Dibujar barras horizontales
            data.forEach((value, index) => {
                const barWidth = (value / max) * chartWidth;
                const x = leftPadding;
                const y = padding + index * barHeight + barHeight * 0.2;
                const height = barHeight * 0.6;

                // Gradiente para las barras
                const gradient = ctx.createLinearGradient(x, 0, x + barWidth, 0);
                gradient.addColorStop(0, '#667eea');
                gradient.addColorStop(1, '#764ba2');

                // Barra de fondo
                ctx.fillStyle = '#f3f4f6';
                ctx.fillRect(x, y, chartWidth, height);

                // Barra de valor
                ctx.fillStyle = gradient;
                ctx.fillRect(x, y, barWidth, height);

                // Etiqueta del producto (izquierda)
                ctx.fillStyle = '#374151';
                ctx.font = 'bold 13px sans-serif';
                ctx.textAlign = 'right';
                const displayLabel = labels[index].length > 20 ? labels[index].substring(0, 20) + '...' : labels[index];
                ctx.fillText(displayLabel, leftPadding - 10, y + height / 2 + 4);

                // Valor (derecha de la barra)
                ctx.fillStyle = '#1f2937';
                ctx.font = 'bold 12px sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('$' + value.toLocaleString('en-US', {minimumFractionDigits: 2}), x + barWidth + 10, y + height / 2 + 4);
            });
        }

        // Función para actualizar datos en tiempo real
        function actualizarDatosTiempoReal() {
            const indicator = document.getElementById('loadingIndicator');
            indicator.classList.add('active');
            
            // Cambia 'Dashboard' por el nombre correcto de tu controlador si es diferente
            fetch('?controller=Dashboard&action=actualizarDatos')
                .then(response => response.json())
                .then(data => {
                    // Actualizar estadísticas
                    if (data.estadisticas) {
                        animateValue('revenue', 
                            Math.round(parseFloat(document.getElementById('revenue').textContent.replace(/[$,]/g, ''))), 
                            Math.round(data.estadisticas.ingresos_mes), 
                            1000
                        );
                        
                        animateValue('orders', 
                            parseInt(document.getElementById('orders').textContent.replace(/,/g, '')), 
                            data.estadisticas.total_ventas, 
                            1000
                        );
                        
                        animateValue('lowStock', 
                            parseInt(document.getElementById('lowStock').textContent), 
                            data.estadisticas.stock_bajo, 
                            1000
                        );
                        
                        animateValue('accountsReceivable', 
                            Math.round(parseFloat(document.getElementById('accountsReceivable').textContent.replace(/[$,]/g, ''))), 
                            Math.round(data.estadisticas.cuentas_cobrar), 
                            1000
                        );
                    }
                    
                    indicator.classList.remove('active');
                })
                .catch(error => {
                    console.error('Error al actualizar datos:', error);
                    indicator.classList.remove('active');
                });
        }
        
        // Inicializar al cargar la página
        window.addEventListener('load', () => {
            drawSalesChart();
            drawCategoryChart();
            drawTopProductsChart();
            
            // Actualizar cada 30 segundos
            setInterval(actualizarDatosTiempoReal, 30000);
        });

        // Redimensionar gráficos
        window.addEventListener('resize', () => {
            setTimeout(() => {
                drawSalesChart();
                drawCategoryChart();
                drawTopProductsChart();
            }, 250);
        });
    </script>
</body>
</html>