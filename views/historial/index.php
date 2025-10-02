<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

.add input[type="text"], #buscar, #fecha {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.add input[type="text"]:focus, #buscar:focus, #fecha:focus{
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

.btn-info{
    background-color: #7f8c8d;
}
.btn-info:hover{
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
            
            <div class="viewsUser">
                <h3>Historial de ventas</h3>
                <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" class="search-input">
                <input type="date" name="fecha" id="fecha">
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
                                <td colspan="8" style="text-align: center;">
                                    <div class="text-muted">
                                        <i class="fas fa-boxes fa-3x mb-3"></i>
                                        <h5>No hay Historial de Ventas</h5>
                                        <p>No se encontro un historial registrados.</p>
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
    <script>
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
                let html = '<table style="width:800px;text-align:left"><thead><tr><th>Nombre</th><th>Código</th><th>Medida</th><th>Cantidad</th><th>Precio USD</th><th>Total USD</th></tr></thead><tbody>';
                productos.forEach(function(p) {
                    html += `<tr>
                        <td>${p.nombre}</td>
                        <td>${p.codigo}</td>
                        <td>${p.medida}</td>
                        <td>${p.cantidad}</td>
                        <td>${parseFloat(p.precio_usd).toFixed(2)} $</td>
                        <td>${parseFloat(p.cantidad * p.precio_usd).toFixed(2)} $</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                Swal.fire({
                    title: 'Productos vendidos',
                    html: html,
                    width: 600,
                    confirmButtonText: 'Cerrar'
                });
            });
        });
    </script>
</body>
</html>