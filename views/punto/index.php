<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?? 'Inicio' ?> - <?= $titulo ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="public/css/pos.css">
    <link rel="shortcut icon" href="<?= APP_Logo?>" type="image/x-icon">
</head>
<body>
    <!-- Menú Lateral -->
    <?php include 'views/inc/heder.php'; ?>
    
    <!-- Contenedor Principal -->
    <div class="main-container">
        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="page-header">
                <h1><?= $titulo ?? 'Punto de Venta' ?></h1>
                <div class="header-info">
                    <h4>Hoy es: <?= APP_Date ?></h4>
                    <h4>Precio Dólar: <?= number_format(APP_Dollar, 2, ',', '.') ?></h4>
                </div>
            </div>

            <!-- Tabla del Carrito -->
            <div class="cart-section">
                <div class="cart-table-container">
                    <button class="btn-search" onclick="openProductModal()">
                        <i class="fas fa-search"></i>
                        Buscar producto
                    </button>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio $</th>
                                <th>Precio Bs</th>
                                <th>Subtotal $</th>
                                <th>Subtotal Bs</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Los productos se agregarán dinámicamente -->
                        </tbody>
                    </table>
                    <div class="empty-cart" id="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>El carrito está vacío</p>
                        <small>Busca y agrega productos para comenzar</small>
                    </div>
                </div>
            </div>
        </main>

        <!-- Panel Lateral Derecho -->
        <aside class="right-panel">
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal $:</span>
                    <span id="subtotal-usd">$0.00</span>
                </div>
                <div class="total-row">
                    <span>Subtotal Bs:</span>
                    <span id="subtotal-bs">Bs. 0.00</span>
                </div>
                <div class="total-row total-main">
                    <span>Total $:</span>
                    <span id="total-usd">0.00</span>
                </div>
                <div class="total-row total-main">
                    <span>Total Bs:</span>
                    <span id="total-bs">0.00</span>
                </div>
            </div>

            <div class="payment-section">
                <div class="section-header">
                    <i class="fas fa-credit-card"></i>
                    <span>Método de Pago</span>
                </div>
                <select id="payment-method" class="form-select">
                    <option value="">Seleccione método de pago</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>

            <div class="client-section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    <span>Cliente</span>
                </div>
                <div class="client-options">
                    <label class="radio-option">
                        <input type="radio" name="client-type" value="registered" onchange="toggleClientType()">
                        <span>Cliente Registrado</span>
                    </label>
                    <select id="registered-client" class="form-select" disabled>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['nombre_apellido']; ?>-<?php echo $cliente['cedula']; ?>"><?php echo htmlspecialchars($cliente['nombre_apellido']); ?> - <?php echo htmlspecialchars($cliente['cedula']); ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="client-options">
                    <label class="radio-option">
                        <input type="radio" name="client-type" value="new" onchange="toggleClientType()">
                        <span>Cliente no afiliado</span>
                    </label>
                    <input type="text" id="new-client" class="form-input" placeholder="Nombre completo del cliente" disabled>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-clear" onclick="clearCart()">
                    <i class="fas fa-trash"></i>
                    Limpiar Carrito
                </button>
                <button class="btn btn-process" onclick="processPayment()">
                    <i class="fas fa-credit-card"></i>
                    Procesar Pago
                </button>
                <button class="btn btn-credit" onclick="processCredit()">
                    <i class="fas fa-clock"></i>
                    Procesar Crédito
                </button>
            </div>
        </aside>
    </div>

    <!-- Modal de Búsqueda de Productos -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buscar Productos</h3>
                <span class="close" onclick="closeProductModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="search-container">
                    <input type="text" id="product-search" placeholder="Buscar por nombre o código..." onkeyup="searchProducts()">
                    <i class="fas fa-search"></i>
                </div>
                <div class="products-grid" id="products-grid">
                    <!-- Los productos se cargarán dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
        // Verificar que $datos existe y tiene contenido
        $products_js = [];
        if (isset($datos) && is_array($datos) && count($datos) > 0) {
            foreach ($datos as $producto) {
                // Verificar que todas las claves necesarias existen
                if (isset($producto['id_producto'], $producto['codigo'], $producto['nombre'], 
                        $producto['precio_venta'], $producto['un_disponibles'], $producto['medida'])) {
                    $products_js[] = [
                        'id' => (int)$producto['id_producto'],
                        'code' => $producto['codigo'],
                        'name' => $producto['nombre'],
                        'price_usd' => (float)$producto['precio_venta'],
                        'stock' => (int)$producto['un_disponibles'],
                        'measure' => $producto['medida']
                    ];
                }
            }
        }
    ?>
    <script>
        // Variables globales
        let cart = [];
        let products = <?= json_encode($products_js) ?>;
        const USD_TO_BS_RATE = <?= APP_Dollar ?>;

        // Debug: Verificar que los productos se cargaron correctamente
        console.log('Productos cargados:', products);
        console.log('Tasa de cambio USD/BS:', USD_TO_BS_RATE);

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando...');
            
            // Verificar que los elementos del DOM existen
            if (!document.getElementById('product-modal')) {
                console.error('ERROR: No se encontró el elemento #product-modal');
                return;
            }
            
            if (!document.getElementById('products-grid')) {
                console.error('ERROR: No se encontró el elemento #products-grid');
                return;
            }
            
            updateCartDisplay();
            
            // Si no hay productos, mostrar mensaje
            if (products.length === 0) {
                console.warn('ADVERTENCIA: No hay productos disponibles');
                showNotification('No hay productos disponibles en el sistema', 'warning');
            }
        });

        // ======================
        // Funciones del Modal
        // ======================
        function openProductModal() {
            console.log('Abriendo modal de productos...');
            const modal = document.getElementById('product-modal');
            if (!modal) {
                console.error('ERROR: Modal no encontrado');
                showNotification('Error: No se puede abrir el modal de productos', 'error');
                return;
            }
            
            modal.style.display = 'block';
            renderProducts();
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('product-modal');
            if (event.target === modal) {
                closeProductModal();
            }
        }

        // ======================
        // Funciones de Productos
        // ======================
        function renderProducts() {
            console.log('Renderizando productos...');
            const productsGrid = document.getElementById('products-grid');
            
            if (!productsGrid) {
                console.error('ERROR: No se encontró el elemento #products-grid');
                return;
            }
            
            productsGrid.innerHTML = '';
            
            if (products.length === 0) {
                productsGrid.innerHTML = '<div class="no-products">No hay productos disponibles</div>';
                return;
            }
            
            products.forEach(product => {
                // Validar que el producto tiene todos los datos necesarios
                if (!product.id || !product.name || product.price_usd === undefined) {
                    console.warn('Producto con datos incompletos:', product);
                    return;
                }
                
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                
                // Calcular precio en bolívares
                const priceBs = (product.price_usd * USD_TO_BS_RATE).toFixed(2);
                
                productCard.innerHTML = `
                    <h4>${product.name}</h4>
                    <div class="product-code">Código: ${product.code || 'N/A'}</div>
                    <div class="product-stock ${product.stock <= 0 ? 'out-of-stock' : ''}">
                        Disponible: ${product.stock} ${product.measure || 'unidades'}
                    </div>
                    <div class="price">$${product.price_usd.toFixed(2)} / Bs. ${priceBs}</div>
                    ${product.stock <= 0 ? '<div class="stock-warning">Sin stock</div>' : ''}
                `;
                
                productCard.onclick = () => {
                    console.log('Producto seleccionado:', product);
                    // Verificar stock antes de agregar al carrito
                    if (product.stock > 0) {
                        addToCart(product);
                    } else {
                        showNotification('Producto sin stock disponible', 'error');
                    }
                };
                
                productsGrid.appendChild(productCard);
            });
            
            console.log(`${products.length} productos renderizados`);
        }

        function searchProducts() {
            const searchTerm = document.getElementById('product-search').value.toLowerCase().trim();
            console.log('Buscando productos con término:', searchTerm);
            
            let filteredProducts = products;
            
            if (searchTerm !== '') {
                filteredProducts = products.filter(product => {
                    const nameMatch = product.name && product.name.toLowerCase().includes(searchTerm);
                    const codeMatch = product.code && product.code.toString().toLowerCase().includes(searchTerm);
                    return nameMatch || codeMatch;
                });
            }
            
            console.log(`${filteredProducts.length} productos encontrados`);
            renderFilteredProducts(filteredProducts);
        }

        function renderFilteredProducts(filteredProducts) {
            const productsGrid = document.getElementById('products-grid');
            if (!productsGrid) return;
            
            productsGrid.innerHTML = '';
            
            if (filteredProducts.length === 0) {
                productsGrid.innerHTML = '<div class="no-products">No se encontraron productos</div>';
                return;
            }
            
            filteredProducts.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                
                const priceBs = (product.price_usd * USD_TO_BS_RATE).toFixed(2);
                
                productCard.innerHTML = `
                    <h4>${product.name}</h4>
                    <div class="product-code">Código: ${product.code || 'N/A'}</div>
                    <div class="product-stock ${product.stock <= 0 ? 'out-of-stock' : ''}">
                        Disponible: ${product.stock} ${product.measure || 'unidades'}
                    </div>
                    <div class="price">$${product.price_usd.toFixed(2)} / Bs. ${priceBs}</div>
                    ${product.stock <= 0 ? '<div class="stock-warning">Sin stock</div>' : ''}
                `;
                
                productCard.onclick = () => {
                    if (product.stock > 0) {
                        addToCart(product);
                    } else {
                        showNotification('Producto sin stock disponible', 'error');
                    }
                };
                
                productsGrid.appendChild(productCard);
            });
        }

        // ======================
        // Funciones del Carrito
        // ======================
        function addToCart(product) {
            console.log('Agregando al carrito:', product);
            
            // Verificar si el producto ya está en el carrito
            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                // Verificar que no exceda el stock disponible
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity += 1;
                    console.log(`Cantidad actualizada para ${product.name}: ${existingItem.quantity}`);
                } else {
                    showNotification(`No hay suficiente stock de ${product.name}`, 'warning');
                    return;
                }
            } else {
                cart.push({
                    id: product.id,
                    code: product.code,
                    name: product.name,
                    price_usd: product.price_usd,
                    price_bs: product.price_usd * USD_TO_BS_RATE,
                    quantity: 1,
                    max_stock: product.stock,
                    measure: product.measure
                });
                console.log(`Nuevo producto agregado al carrito: ${product.name}`);
            }
            
            updateCartDisplay();
            closeProductModal();
            showNotification(`${product.name} agregado al carrito`);
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            
            // Si no existen los elementos del carrito, crear mensaje informativo
            if (!cartItems || !emptyCart) {
                console.warn('ADVERTENCIA: Elementos del carrito no encontrados en el DOM');
                return;
            }
            
            if (cart.length === 0) {
                cartItems.innerHTML = '';
                emptyCart.style.display = 'block';
            } else {
                emptyCart.style.display = 'none';
                cartItems.innerHTML = '';
                
                cart.forEach(item => {
                    const row = document.createElement('tr');
                    const subtotalUSD = item.price_usd * item.quantity;
                    const subtotalBS = item.price_bs * item.quantity;
                    
                    row.innerHTML = `
                        <td>${item.name} <small>(${item.code || 'N/A'})</small></td>
                        <td>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}"
                                    onchange="setQuantity(${item.id}, this.value)" min="1" max="${item.max_stock}">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                        </td>
                        <td>$${item.price_usd.toFixed(2)}</td>
                        <td>Bs. ${item.price_bs.toFixed(2)}</td>
                        <td>$${subtotalUSD.toFixed(2)}</td>
                        <td>Bs. ${subtotalBS.toFixed(2)}</td>
                        <td>
                            <button class="remove-btn" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    cartItems.appendChild(row);
                });
            }
            
            updateTotals();
            console.log(`Carrito actualizado. ${cart.length} productos`);
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                const newQuantity = item.quantity + change;
                
                // Validar que no exceda el stock máximo
                if (newQuantity > item.max_stock) {
                    showNotification(`No puedes agregar más de ${item.max_stock} unidades de ${item.name}`, 'warning');
                    return;
                }
                
                if (newQuantity <= 0) {
                    removeFromCart(productId);
                } else {
                    item.quantity = newQuantity;
                    updateCartDisplay();
                }
            }
        }

        function setQuantity(productId, quantity) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                const newQuantity = parseInt(quantity);
                
                // Validar el nuevo cantidad
                if (isNaN(newQuantity)) {
                    updateCartDisplay(); // Restaurar valor anterior
                    return;
                }
                
                if (newQuantity > item.max_stock) {
                    showNotification(`No puedes agregar más de ${item.max_stock} unidades de ${item.name}`, 'warning');
                    item.quantity = item.max_stock; // Ajustar al máximo permitido
                    updateCartDisplay();
                    return;
                }
                
                if (newQuantity <= 0) {
                    removeFromCart(productId);
                } else {
                    item.quantity = newQuantity;
                    updateCartDisplay();
                }
            }
        }

        function removeFromCart(productId) {
            const itemName = cart.find(item => item.id === productId)?.name || 'Producto';
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
            showNotification(`${itemName} eliminado del carrito`, 'info');
        }

        function updateTotals() {
            const subtotalUSD = cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0);
            const subtotalBS = cart.reduce((sum, item) => sum + (item.price_bs * item.quantity), 0);
            
            // Actualizar elementos si existen
            const subtotalUsdEl = document.getElementById('subtotal-usd');
            const subtotalBsEl = document.getElementById('subtotal-bs');
            const totalUsdEl = document.getElementById('total-usd');
            const totalBsEl = document.getElementById('total-bs');
            
            if (subtotalUsdEl) subtotalUsdEl.textContent = `$${subtotalUSD.toFixed(2)}`;
            if (subtotalBsEl) subtotalBsEl.textContent = `Bs. ${subtotalBS.toFixed(2)}`;
            if (totalUsdEl) totalUsdEl.textContent = subtotalUSD.toFixed(2);
            if (totalBsEl) totalBsEl.textContent = subtotalBS.toFixed(2);
        }

        function clearCart() {
            if (cart.length === 0) {
                showNotification('El carrito ya está vacío', 'warning');
                return;
            }
            
            // Verificar si SweetAlert2 está disponible
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas limpiar todo el carrito?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, limpiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        updateCartDisplay();
                        showNotification('Carrito limpiado correctamente');
                    }
                });
            } else {
                // Fallback si no está SweetAlert2
                if (confirm('¿Estás seguro de que deseas limpiar todo el carrito?')) {
                    cart = [];
                    updateCartDisplay();
                    showNotification('Carrito limpiado correctamente');
                }
            }
        }

        // ======================
        // Funciones de Clientes
        // ======================
        function toggleClientType() {
            const clientType = document.querySelector('input[name="client-type"]:checked');
            if (!clientType) return;
            
            const registeredClient = document.getElementById('registered-client');
            const newClient = document.getElementById('new-client');
            
            if (!registeredClient || !newClient) return;
            
            if (clientType.value === 'registered') {
                registeredClient.disabled = false;
                newClient.disabled = true;
                newClient.value = '';
            } else {
                registeredClient.disabled = true;
                newClient.disabled = false;
                registeredClient.value = '';
            }
        }

        // ======================
        // Funciones de Pago
        // ======================
        function processPayment() {
            if (cart.length === 0) {
                showNotification('El carrito está vacío', 'error');
                return;
            }
            
            const paymentMethod = document.getElementById('payment-method');
            if (!paymentMethod || !paymentMethod.value) {
                showNotification('Selecciona un método de pago', 'error');
                return;
            }
            
            const clientType = document.querySelector('input[name="client-type"]:checked');
            if (!clientType) {
                showNotification('Selecciona un tipo de cliente', 'error');
                return;
            }
            
            let clientInfo = '';
            if (clientType.value === 'registered') {
                const registeredClient = document.getElementById('registered-client');
                if (!registeredClient || !registeredClient.value) {
                    showNotification('Selecciona un cliente registrado', 'error');
                    return;
                }
                clientInfo = registeredClient.options[registeredClient.selectedIndex].text;
            } else {
                const newClient = document.getElementById('new-client');
                if (!newClient || !newClient.value.trim()) {
                    showNotification('Ingresa el nombre del cliente', 'error');
                    return;
                }
                clientInfo = newClient.value.trim();
            }
            
            // Simular procesamiento
            showNotification('Procesando pago...', 'info');
            
            setTimeout(() => {
                const totalUSD = cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0);
                const totalBS = cart.reduce((sum, item) => sum + (item.price_bs * item.quantity), 0);
                
                const successMessage = `
                    <strong>Cliente:</strong> ${clientInfo}<br>
                    <strong>Método:</strong> ${paymentMethod.options[paymentMethod.selectedIndex].text}<br>
                    <strong>Total USD:</strong> $${totalUSD.toFixed(2)}<br>
                    <strong>Total Bs:</strong> Bs. ${totalBS.toFixed(2)}
                `;
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Pago procesado!',
                        html: successMessage,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        resetForm();
                        showNotification('Venta completada exitosamente');
                    });
                } else {
                    alert('Pago procesado exitosamente');
                    resetForm();
                    showNotification('Venta completada exitosamente');
                }
            }, 2000);
        }

        function processCredit() {
            if (cart.length === 0) {
                showNotification('El carrito está vacío', 'error');
                return;
            }
            
            const clientType = document.querySelector('input[name="client-type"]:checked');
            if (!clientType) {
                showNotification('Selecciona un tipo de cliente', 'error');
                return;
            }
            
            let clientInfo = '';
            if (clientType.value === 'registered') {
                const registeredClient = document.getElementById('registered-client');
                if (!registeredClient || !registeredClient.value) {
                    showNotification('Selecciona un cliente registrado', 'error');
                    return;
                }
                clientInfo = registeredClient.options[registeredClient.selectedIndex].text;
            } else {
                const newClient = document.getElementById('new-client');
                if (!newClient || !newClient.value.trim()) {
                    showNotification('Ingresa el nombre del cliente', 'error');
                    return;
                }
                clientInfo = newClient.value.trim();
            }
            
            const totalUSD = cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0);
            const totalBS = cart.reduce((sum, item) => sum + (item.price_bs * item.quantity), 0);
            
            const confirmMessage = `
                <strong>Cliente:</strong> ${clientInfo}<br>
                <strong>Total USD:</strong> $${totalUSD.toFixed(2)}<br>
                <strong>Total Bs:</strong> Bs. ${totalBS.toFixed(2)}
            `;
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Confirmar venta a crédito?',
                    html: confirmMessage,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showNotification('Venta a crédito registrada exitosamente');
                        resetForm();
                    }
                });
            } else {
                if (confirm('¿Confirmar venta a crédito?')) {
                    showNotification('Venta a crédito registrada exitosamente');
                    resetForm();
                }
            }
        }

        function resetForm() {
            cart = [];
            updateCartDisplay();
            
            const paymentMethod = document.getElementById('payment-method');
            if (paymentMethod) paymentMethod.value = '';
            
            const clientRadios = document.querySelectorAll('input[name="client-type"]');
            clientRadios.forEach(radio => radio.checked = false);
            
            const registeredClient = document.getElementById('registered-client');
            const newClient = document.getElementById('new-client');
            
            if (registeredClient) {
                registeredClient.disabled = true;
                registeredClient.value = '';
            }
            
            if (newClient) {
                newClient.disabled = true;
                newClient.value = '';
            }
        }

        // ======================
        // Funciones Auxiliares
        // ======================
        function showNotification(message, type = 'success') {
            console.log(`Notificación [${type}]: ${message}`);
            
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Estilos inline para asegurar que funcione sin CSS externo
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: bold;
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
                max-width: 300px;
                word-wrap: break-word;
            `;
            
            // Colores según el tipo
            switch(type) {
                case 'success':
                    notification.style.backgroundColor = '#28a745';
                    break;
                case 'error':
                    notification.style.backgroundColor = '#dc3545';
                    break;
                case 'warning':
                    notification.style.backgroundColor = '#ffc107';
                    notification.style.color = '#212529';
                    break;
                case 'info':
                    notification.style.backgroundColor = '#17a2b8';
                    break;
                default:
                    notification.style.backgroundColor = '#6c757d';
            }
            
            document.body.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remover después de 4 segundos
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // ======================
        // Atajos de teclado
        // ======================
        document.addEventListener('keydown', function(e) {
            // Ctrl + F para abrir búsqueda de productos
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                openProductModal();
            }
            
            // Escape para cerrar modal
            if (e.key === 'Escape') {
                closeProductModal();
            }
            
            // Enter en el campo de búsqueda
            if (e.target.id === 'product-search' && e.key === 'Enter') {
                e.preventDefault();
                searchProducts();
            }
        });

        // ======================
        // Funciones de Debug
        // ======================
        function debugInfo() {
            console.log('=== DEBUG INFO ===');
            console.log('Productos disponibles:', products.length);
            console.log('Productos en carrito:', cart.length);
            console.log('Tasa USD/BS:', USD_TO_BS_RATE);
            console.log('Datos completos:', { products, cart, USD_TO_BS_RATE });
        }

        // Llamar debug info después de cargar
        setTimeout(debugInfo, 1000);
    </script>
</body>
</html>