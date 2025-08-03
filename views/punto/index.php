<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="public/css/pos.css">
    <link rel="shortcut icon" href="<?= APP_Logo?>" type="image/x-icon">
    <?php include_once 'public/css/pos.php';?>
</head>
<body>
    <div class="dashboard">
        <?= include_once 'views/inc/heder.php'; ?>
        <main class="main-content">
            <div class="page-header">
                <h1><?= $titulo ?></h1>
                <h4>Hoy es: <?= APP_Date ?> </h4>
                <h4>Predcio Dollar: <?= number_format( APP_Dollar,2,',') ?> </h4>
            </div>

            <div class="pos-container">
                <!-- Sección de Productos -->
                <div class="products-section">
                    <h2><i class="fas fa-box-open"></i> Productos</h2>
                    <div class="search-product">
                        <div class="search-box">
                            <input type="text" id="product-search" placeholder="Buscar producto">
                        </div>
                        <div class="product-results">
                            <!-- Mensaje inicial -->
                            <div class="no-results" id="product-no-results">
                                <i class="fas fa-search"></i>
                                <p>Ingrese un término de búsqueda para mostrar productos</p>
                            </div>
                            
                            <!-- Productos (ocultos inicialmente) -->
                            <?php if(isset($datos) && is_array($datos) && !empty($datos)): ?>
                                <?php foreach($datos as $producto): ?>
                                    <div class="product-card" style="display: none;"
                                            data-product-id="<?php echo $producto['id'] ?? ''; ?>"
                                            data-product-name="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>"
                                            data-product-code="<?php echo htmlspecialchars($producto['codigo'] ?? ''); ?>"
                                            data-product-stock="<?php echo $producto['un_disponibles'] ?? 0; ?>"
                                            data-product-price-usd="<?php echo $producto['precio_venta'] ?? 0; ?>"
                                            data-product-price-bs="<?php echo ($producto['precio_venta'] ?? 0) * (APP_Dollar ?? 1); ?>">
                                        <div class="product-details">
                                            <h3><?php echo htmlspecialchars($producto['nombre'] ?? 'Nombre del Producto'); ?></h3>
                                            <div class="product-info">
                                                <span>Código: <span><?php echo htmlspecialchars($producto['codigo'] ?? '-'); ?></span></span>
                                                <span>Stock: <span><?php echo htmlspecialchars($producto['un_disponibles'] ?? '-'); ?></span></span>
                                            </div>
                                            <div class="product-prices">
                                                <span>Precio $: <span><?php echo number_format($producto['precio_venta'] ?? 0.00, 2); ?></span></span>
                                                <span>Precio Bs: <span><?php echo number_format(($producto['precio_venta'] ?? 0) * (APP_Dollar ?? 1), 2); ?></span></span>
                                            </div>
                                        </div>
                                        <div class="product-actions">
                                            <div class="quantity-control">
                                                <button class="quantity-btn minus"><i class="fas fa-minus"></i></button>
                                                <input type="number" value="1" min="1" class="product-quantity" data-product-id="<?php echo $producto['id'] ?? ''; ?>">
                                                <button class="quantity-btn plus"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <button class="add-to-cart btn primary" type="button">
                                                <i class="fas fa-cart-plus"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sección del Carrito -->
                <div class="cart-section">
                    <form id="pos-form" method="POST" action="">
                        <h2><i class="fas fa-shopping-cart"></i> Carrito de Compra</h2>
                        <div class="cart-container">
                            <div class="cart-items">
                                <table id="cart-table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cant.</th>
                                            <th>Precio $</th>
                                            <th>Precio Bs</th>
                                            <th>Subtotal $</th>
                                            <th>Subtotal Bs</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        <!-- Aquí se agregarán los productos dinámicamente -->
                                    </tbody>
                                </table>
                                <div class="empty-cart" id="empty-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p>El carrito está vacío</p>
                                </div>
                            </div>
                            <div class="cart-totals">
                                <div class="total-row grand-total">
                                    <span>Total $:</span>
                                    <input type="text" id="total-usd" name="total-usd" value="0,00" disabled style="text-align: end; font-size: 20px; max-width: 100px;">
                                </div>
                                <div class="total-row grand-total">
                                    <span>Total Bs:</span>
                                    <input type="text" id="total-bs" name="total-bs" value="0,00" disabled style="text-align: end; font-size: 20px; max-width: 100px;">
                                </div>
                                <div class="total-row grand-total">
                                    <select name="tipo_pago" id="tipo-pago" >
                                        <option value="">Seleccione un tipo de pago</option>
                                        <option value="Pago Movil">Pago Movil</option>
                                        <option value="Punto">Punto</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="casa">Casa</option>
                                    </select>
                                </div>
                                <h4>Clientes Registrados:</h4>
                                <div class="total-row grand-total">
                                    <select name="cliente" id="cliente">
                                        <option value="">Seleccione un cliente</option>
                                        <?php foreach($clientes as $cliente): ?>
                                            <option value="<?php echo $cliente['nombre_apellido']; ?>-<?php echo $cliente['cedula']; ?>"><?php echo htmlspecialchars($cliente['nombre_apellido']); ?> - <?php echo htmlspecialchars($cliente['cedula']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <h4>Cliente No Registrados</h4>
                                <div class="total-row grand-total">
                                    <input type="text" name="cliente2" id="cliente2" placeholder="Ingrese nombre">
                                </div>
                                <div class="payment-actions">
                                    <button type="button" id="cancel-sale" class="btn danger">
                                        Cancelar
                                    </button>
                                    <button type="submit" id="process-payment" class="btn success" name="pago" value="procesar_pago">
                                        Procesar Pago
                                    </button>
                                    <button type="submit" id="process-credit" class="btn success" name="credito" value="procesar_credito">
                                        Crédito
                                    </button>
                                </div>

                                <!-- Inputs ocultos para enviar datos -->
                                <input type="hidden" id="productos-json" name="productos_json" value="">
                                <input type="hidden" id="total-usd-input" name="total_usd" value="0">
                                <input type="hidden" id="cliente-json" name="cliente_json" value="">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/pos.js"></script>
</body>
</html>