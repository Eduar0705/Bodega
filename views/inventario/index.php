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
    /* Estilos generales para la sección de inventario */
    .bus-add {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Estilos para la barra de búsqueda y botón */
    .bus {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .bus input[type="text"] {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .bus input[type="text"]:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .bus button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s;
    }

    .bus button:hover {
        background-color: #218838;
    }

    /* Estilos para la tabla */
    .list-Product {
        overflow-x: auto;
    }

    .list-Product table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .list-Product thead {
        background-color: #17a2b8 !important;
        color: white;
    }

    .list-Product th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }

    .list-Product td {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .list-Product tr:hover {
        background-color: #f1f1f1;
    }

    /* Estilos para los botones de acción */
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        margin-right: 5px;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    #importar {
        background-color: #de1414ff;
        color: white;
    }

    #importar:hover {
        background-color: #7d1722ff;
    }

    /* Estilos para el mensaje cuando no hay datos */
    .text-muted {
        color: #6c757d;
        padding: 30px 0;
    }

    .text-muted i {
        color: #adb5bd;
    }

    .text-muted h5 {
        margin-bottom: 10px;
        font-size: 18px;
    }

    .text-muted p {
        font-size: 14px;
        margin: 0;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .bus {
            flex-direction: column;
        }
        
        .list-Product th, 
        .list-Product td {
            padding: 8px 10px;
            font-size: 14px;
        }
    }
    /* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 25px;
    border: 1px solid #888;
    width: 50%;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 600px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: black;
}

/* Form Styles */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input, 
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-row .form-group {
    flex: 1;
}

.input-with-symbol {
    position: relative;
}

.input-with-symbol span {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.submit-btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    margin-top: 10px;
}

.submit-btn:hover {
    background-color: #45a049;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        width: 90%;
        margin: 10% auto;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
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
                <h4>Precio Dollar hoy: <?= number_format(APP_Dollar,'2',',','.') ?> Bs</h4>
            </div>

            <section class="bus-add">
                <div class="bus">
                    <input type="text" id="buscar" name="buscar" placeholder="Ingrese el nombre o código del producto" onkeyup="filtrarProductos()">
                    <button name="add" id="add"><i class="fas fa-plus"></i> Agregar Producto</button>
                    <button name="importar" id="importar"><i class="fas fa-file-import"></i> Importar PDF</button>
                </div>
                <div class="list-Product">
                    <table id="tablaProductos" style="background-color: aliceblue; max-width: 95%;">
                        <thead style="background-color: aqua;">
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Presentacion</th>
                                <th>disponibles</th>
                                <th>Precion Venta</th>
                                <th>Total de Ganancia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($datosInven)): ?>
                                <?php foreach($datosInven as $dato): ?>
                                    <?php 
                                    $precioVenta = $dato['precio_compra'];
                                    $precioCompra = $dato['precio_venta'];
                                    $totalUnidades = $dato['un_disponibles'];
                                    $totalGanacias = ($precioCompra - $precioVenta) * $totalUnidades;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($dato['codigo']); ?></td>
                                        <td><?php echo htmlspecialchars($dato['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($dato['medida']); ?></td>
                                        <td style="text-align: center;"><?php echo htmlspecialchars($dato['un_disponibles']); ?></td>
                                        <td style="text-align: center;">$<?php echo number_format($dato['precio_venta'], 2,',','.'); ?></td>
                                        <td style="text-align: center; color: green;">$<?php echo number_format($totalGanacias, 2,',','.'); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn-editar" 
                                                data-id="<?php echo $dato['id_producto']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button 
                                                class="btn btn-sm btn-danger btn-eliminar"
                                                id="btn-eliminar" 
                                                title="Eliminar solicitud" 
                                                data-id="<?php echo $dato['id_producto']; ?>">
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
                                            <h5>No hay productos en inventario</h5>
                                            <p>No se encontraron productos registrados.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Modal de agregar producto -->
                <div id="productModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Agregar Nuevo Producto</h2>
                        <form id="productForm"  method="post">
                            <div class="form-group">
                                <label for="productCode">Código:</label>
                                <input type="text" id="productCode" name="productCode" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="productName">Nombre del Producto:</label>
                                <input type="text" id="productName" name="productName" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="productMeasure">Unidad de Medida:</label>
                                <input type="text" id="productMeasure" name="productMeasure" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="productStock">Unidades Disponibles:</label>
                                <input type="number" id="productStock" name="productStock" min="0" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="purchasePrice">Precio Compra:</label>
                                    <input type="number" id="purchasePrice" name="purchasePrice" min="0" step="0.01" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="profitPercentage">% Ganancia:</label>
                                    <div class="input-with-symbol">
                                        <input type="number" id="profitPercentage" name="profitPercentage" min="0" value="10" required>
                                        <span>%</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="salePrice">Precio Venta:</label>
                                    <input type="number" id="salePrice" name="salePrice" min="0" step="0.01" readonly>
                                </div>
                            </div>
                            
                            <button type="submit" class="submit-btn" name="add" id="add">Guardar Producto</button>
                        </form>
                    </div>
                </div>
                <!-- Modal de editar producto -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Editar Producto</h2>
                        <form id="editForm" method="post" action="?action=admin&method=actualizarProducto">
                            <input type="hidden" id="editId" name="id_producto">
                            
                            <div class="form-group">
                                <label for="editCode">Código:</label>
                                <input type="text" id="editCode" name="codigo" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editName">Nombre del Producto:</label>
                                <input type="text" id="editName" name="nombre" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editMeasure">Unidad de Medida:</label>
                                <input type="text" id="editMeasure" name="medida" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editStock">Unidades Disponibles:</label>
                                <input type="number" id="editStock" name="un_disponibles" min="0" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="editPurchasePrice">Precio Compra:</label>
                                    <input type="number" id="editPurchasePrice" name="precio_compra" min="0" step="0.01" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="editProfitPercentage">% Ganancia:</label>
                                    <div class="input-with-symbol">
                                        <input type="number" id="editProfitPercentage" name="porcentaje_ganancia" min="0" value="10" required>
                                        <span>%</span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="editSalePrice">Precio Venta:</label>
                                    <input type="number" id="editSalePrice" name="precio_venta" min="0" step="0.01" readonly>
                                </div>
                            </div>
                            
                            <button type="submit" class="submit-btn">Actualizar Producto</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <!-- Funcion del modal de agregar -->
    <script>
        const modal = document.getElementById("productModal");
        const addBtn = document.getElementById("add");
        const closeBtn = document.querySelector(".close");
        const form = document.getElementById("productForm");
        const purchasePrice = document.getElementById("purchasePrice");
        const profitPercentage = document.getElementById("profitPercentage");
        const salePrice = document.getElementById("salePrice");

        // Abrir modal
        addBtn.addEventListener("click", function() {
            modal.style.display = "block";
        });

        // Cerrar modal
        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        // Calculo del porcentaje de Ganancia
        function calculateSalePrice() {
            const purchase = parseFloat(purchasePrice.value) || 0;
            const percentage = parseFloat(profitPercentage.value) || 0;
            
            const profit = purchase * (percentage / 100);
            const sale = purchase + profit;
            
            salePrice.value = sale.toFixed(2);
        }

        purchasePrice.addEventListener("input", calculateSalePrice);
        profitPercentage.addEventListener("input", calculateSalePrice);

        // Asegúrate de que salePrice tenga valor antes de enviar
        form.addEventListener("submit", function(e) {
            calculateSalePrice(); // Recalcula por si acaso
            
            // Validación básica
            if (!salePrice.value || salePrice.value <= 0) {
                e.preventDefault();
                alert("Por favor calcule un precio de venta válido");
                return;
            }
            
            // Si todo está bien, el formulario se enviará normalmente
            console.log("Enviando formulario...");
        });
        function filtrarProductos() {
            // Obtener el valor del input de búsqueda
            let input = document.getElementById('buscar');
            let filtro = input.value.toUpperCase();
            
            // Obtener la tabla y las filas
            let tabla = document.getElementById('tablaProductos');
            let filas = tabla.getElementsByTagName('tr');
            
            // Recorrer todas las filas de la tabla (excepto el encabezado)
            for (let i = 1; i < filas.length; i++) {
                let mostrarFila = false;
                
                // Obtener las celdas de código (primera columna) y nombre (segunda columna)
                let codigo = filas[i].getElementsByTagName('td')[0];
                let nombre = filas[i].getElementsByTagName('td')[1];
                
                if (codigo && nombre) {
                    // Comprobar si el código o el nombre coinciden con el filtro
                    let textoCodigo = codigo.textContent || codigo.innerText;
                    let textoNombre = nombre.textContent || nombre.innerText;
                    
                    if (textoCodigo.toUpperCase().indexOf(filtro) > -1 || textoNombre.toUpperCase().indexOf(filtro) > -1) {
                        mostrarFila = true;
                    }
                }
                
                // Mostrar u ocultar la fila según coincida con el filtro
                if (mostrarFila) {
                    filas[i].style.display = '';
                } else {
                    filas[i].style.display = 'none';
                }
            }
        }
    </script>
    <!-- Eliminar -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
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
                        window.location.href = `?action=admin&method=eliminarProducto&id=${id}`;
                    }
                });
            });
        });
    });
    </script>
    <!-- Funcion del modal de editar -->
    <script>
    // Manejo del modal de edición
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            // Fetch para obtener los datos del producto
            fetch(`?action=admin&method=obtenerProducto&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        const producto = data.producto;
                        
                        // Llenar el formulario con los datos
                        document.getElementById('editId').value = producto.id_producto;
                        document.getElementById('editCode').value = producto.codigo;
                        document.getElementById('editName').value = producto.nombre;
                        document.getElementById('editMeasure').value = producto.medida;
                        document.getElementById('editStock').value = producto.un_disponibles;
                        document.getElementById('editPurchasePrice').value = producto.precio_compra;
                        
                        // Calcular porcentaje de ganancia si es necesario
                        const porcentaje = ((producto.precio_venta - producto.precio_compra) / producto.precio_compra) * 100;
                        document.getElementById('editProfitPercentage').value = porcentaje.toFixed(2);
                        document.getElementById('editSalePrice').value = producto.precio_venta;
                        
                        // Mostrar el modal
                        document.getElementById('editModal').style.display = 'block';
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo cargar el producto', 'error');
                });
        });
    });

    // Cálculo automático del precio de venta (similar al de agregar)
    document.getElementById('editProfitPercentage').addEventListener('input', function() {
        calcularPrecioVenta('edit');
    });

    function calcularPrecioVenta(prefix) {
        const precioCompra = parseFloat(document.getElementById(`${prefix}PurchasePrice`).value) || 0;
        const porcentaje = parseFloat(document.getElementById(`${prefix}ProfitPercentage`).value) || 0;
        const precioVenta = precioCompra * (1 + (porcentaje / 100));
        document.getElementById(`${prefix}SalePrice`).value = precioVenta.toFixed(2);
    }
    </script>
    <!-- Exportar a PDF -->
    <script>
        document.getElementById('importar').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4');
            
            // Título del documento
            const title = "Reporte del Inventario";
            doc.setFontSize(18);
            doc.text(title, 40, 40);
            
            // Obtener datos de la tabla
            const table = document.getElementById('tablaProductos');
            const headers = [];
            const rows = [];
            
            table.querySelectorAll('thead th').forEach((th, index) => {
                if(index < 6) {
                    headers.push(th.textContent);
                }
            });
            
            // Obtener filas de datos
            table.querySelectorAll('tbody tr').forEach(tr => {
                if(tr.querySelector('td[colspan]')) return;
                
                const row = [];
                tr.querySelectorAll('td').forEach((td, index) => {
                    if(index < 6) {
                        let text = td.textContent.trim();
                        if(index === 4 || index === 5) {
                            text = text.replace('$', '').replace(/\./g, '').replace(',', '.');
                            text = parseFloat(text).toFixed(2);
                        }
                        row.push(text);
                    }
                });
                if(row.length > 0) rows.push(row);
            });

            const options = {
                startY: 60,
                head: [headers],
                body: rows,
                theme: 'grid',
                headStyles: {
                    fillColor: [50, 150, 250],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                columnStyles: {
                    0: { cellWidth: 60 }, 
                    1: { cellWidth: 'auto' },
                    2: { cellWidth: 80 },
                    3: { cellWidth: 60, halign: 'center' },
                    4: { cellWidth: 70, halign: 'right' },
                    5: { cellWidth: 80, halign: 'right' }
                },
                didDrawPage: function (data) {
                    doc.setFontSize(10);
                    const pageCount = doc.internal.getNumberOfPages();
                    doc.text(`Página ${data.pageNumber} de ${pageCount}`, data.settings.margin.left, doc.internal.pageSize.height - 20);
                    
                    const fecha = new Date().toLocaleDateString();
                    doc.text(`Generado el: ${fecha}`, doc.internal.pageSize.width - 120, doc.internal.pageSize.height - 20);
                }
            };
            
            doc.autoTable(options);
            doc.save('Reporte_Inventario.pdf');
        });
    </script>
</body>
</html>