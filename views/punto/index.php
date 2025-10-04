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
                    <h4>Precio Dólar: <?= number_format(APP_Dollar, 2, ',', '.') ?> Bs</h4>
                    <h4>Presiona "F1" para ayuda</h4>
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
                    <div class="empty-cart" id="empty-cart" style="margin-top: 40px;">
                        <img src="<?= APP_Logo ?>" alt="Logo" style="width: 100px; height: auto;">
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

        // Estados del sistema
        let isProcessingPayment = false;
        let modalOpen = false;

        // Inicialización mejorada
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando...');
            
            try {
                // Verificar elementos críticos del DOM
                const requiredElements = [
                    'product-modal',
                    'products-grid',
                    'cart-items',
                    'empty-cart',
                    'subtotal-usd',
                    'subtotal-bs',
                    'total-usd',
                    'total-bs'
                ];
                
                const missingElements = requiredElements.filter(id => !document.getElementById(id));
                
                if (missingElements.length > 0) {
                    console.error('ERROR: Elementos faltantes en el DOM:', missingElements);
                    showNotification('Error: Elementos de la interfaz no encontrados', 'error');
                    return;
                }
                
                updateCartDisplay();
                
                // Verificar productos disponibles
                if (products.length === 0) {
                    console.warn('ADVERTENCIA: No hay productos disponibles');
                    showNotification('No hay productos disponibles en el sistema', 'warning');
                } else {
                    console.log(`Sistema inicializado correctamente con ${products.length} productos`);
                }
                
            } catch (error) {
                console.error('Error durante la inicialización:', error);
                showNotification('Error al inicializar el sistema', 'error');
            }
        });

        // ======================
        // Funciones del Modal
        // ======================
        function openProductModal() {
            if (modalOpen) return;
            
            console.log('Abriendo modal de productos...');
            const modal = document.getElementById('product-modal');
            
            if (!modal) {
                console.error('ERROR: Modal no encontrado');
                showNotification('Error: No se puede abrir el modal de productos', 'error');
                return;
            }
            
            modalOpen = true;
            modal.style.display = 'block';
            
            // Limpiar búsqueda anterior y enfocar
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                searchInput.value = '';
                setTimeout(() => searchInput.focus(), 100);
            }
            
            renderProducts();
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            if (modal) {
                modal.style.display = 'none';
                modalOpen = false;
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
            
            try {
                productsGrid.innerHTML = '';
                
                if (products.length === 0) {
                    productsGrid.innerHTML = '<div class="no-products">No hay productos disponibles</div>';
                    return;
                }
                
                let validProducts = 0;
                
                products.forEach(product => {
                    // Validar que el producto tiene todos los datos necesarios
                    if (!validateProduct(product)) {
                        console.warn('Producto con datos incompletos:', product);
                        return;
                    }
                    
                    const productCard = createProductCard(product);
                    productsGrid.appendChild(productCard);
                    validProducts++;
                });
                
                console.log(`${validProducts} productos válidos renderizados de ${products.length} totales`);
                
            } catch (error) {
                console.error('Error al renderizar productos:', error);
                productsGrid.innerHTML = '<div class="error-message">Error al cargar productos</div>';
                showNotification('Error al cargar productos', 'error');
            }
        }

        function validateProduct(product) {
            const requiredFields = ['id', 'name', 'price_usd'];
            return requiredFields.every(field => 
                product[field] !== undefined && 
                product[field] !== null && 
                product[field] !== ''
            ) && 
            typeof product.price_usd === 'number' && 
            product.price_usd > 0;
        }

        function createProductCard(product) {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            
            // Calcular precio en bolívares de forma segura
            const priceBs = (product.price_usd * USD_TO_BS_RATE).toFixed(2);
            const stock = product.stock || 0;
            const isOutOfStock = stock <= 0;
            
            productCard.innerHTML = `
                <h4>${escapeHtml(product.name)}</h4>
                <div class="product-code">Código: ${escapeHtml(product.code || 'N/A')}</div>
                <div class="product-stock ${isOutOfStock ? 'out-of-stock' : ''}">
                    Disponible: ${stock} ${escapeHtml(product.measure || 'unidades')}
                </div>
                <div class="price">$${product.price_usd.toFixed(2)} / Bs. ${priceBs}</div>
                ${isOutOfStock ? '<div class="stock-warning">Sin stock</div>' : ''}
            `;
            
            // Agregar evento click de forma segura
            productCard.addEventListener('click', () => handleProductSelection(product));
            
            return productCard;
        }

        function handleProductSelection(product) {
            console.log('Producto seleccionado:', product);
            
            if (product.stock <= 0) {
                showNotification('Producto sin stock disponible', 'warning');
                return;
            }
            
            try {
                addToCart(product);
            } catch (error) {
                console.error('Error al agregar producto al carrito:', error);
                showNotification('Error al agregar producto al carrito', 'error');
            }
        }

        function searchProducts() {
            const searchInput = document.getElementById('product-search');
            if (!searchInput) return;
            
            const searchTerm = searchInput.value.toLowerCase().trim();
            console.log('Buscando productos con término:', searchTerm);
            
            try {
                let filteredProducts = products;
                
                if (searchTerm !== '') {
                    filteredProducts = products.filter(product => {
                        const nameMatch = product.name && 
                            product.name.toLowerCase().includes(searchTerm);
                        const codeMatch = product.code && 
                            product.code.toString().toLowerCase().includes(searchTerm);
                        return nameMatch || codeMatch;
                    });
                }
                
                console.log(`${filteredProducts.length} productos encontrados`);
                renderFilteredProducts(filteredProducts);
                
            } catch (error) {
                console.error('Error en búsqueda:', error);
                showNotification('Error al buscar productos', 'error');
            }
        }

        function renderFilteredProducts(filteredProducts) {
            const productsGrid = document.getElementById('products-grid');
            if (!productsGrid) return;
            
            try {
                productsGrid.innerHTML = '';
                
                if (filteredProducts.length === 0) {
                    productsGrid.innerHTML = '<div class="no-products">No se encontraron productos</div>';
                    return;
                }
                
                filteredProducts.forEach(product => {
                    if (validateProduct(product)) {
                        const productCard = createProductCard(product);
                        productsGrid.appendChild(productCard);
                    }
                });
                
            } catch (error) {
                console.error('Error al renderizar productos filtrados:', error);
                showNotification('Error al mostrar resultados', 'error');
            }
        }

        // ======================
        // Funciones del Carrito
        // ======================
        function addToCart(product) {
            console.log('Agregando al carrito:', product);
            
            try {
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
                    // Agregar nuevo producto al carrito
                    const cartItem = {
                        id: product.id,
                        code: product.code,
                        name: product.name,
                        price_usd: product.price_usd,
                        price_bs: product.price_usd * USD_TO_BS_RATE,
                        quantity: 1,
                        max_stock: product.stock,
                        measure: product.measure
                    };
                    
                    cart.push(cartItem);
                    console.log(`Nuevo producto agregado al carrito: ${product.name}`);
                }
                
                updateCartDisplay();
                closeProductModal();
                showNotification(`${product.name} agregado al carrito`, 'success');
                
            } catch (error) {
                console.error('Error al agregar producto al carrito:', error);
                showNotification('Error al agregar producto al carrito', 'error');
            }
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            
            if (!cartItems || !emptyCart) {
                console.warn('ADVERTENCIA: Elementos del carrito no encontrados en el DOM');
                return;
            }
            
            try {
                if (cart.length === 0) {
                    cartItems.innerHTML = '';
                    emptyCart.style.display = 'block';
                } else {
                    emptyCart.style.display = 'none';
                    renderCartItems(cartItems);
                }
                
                updateTotals();
                console.log(`Carrito actualizado. ${cart.length} productos`);
                
            } catch (error) {
                console.error('Error al actualizar carrito:', error);
                showNotification('Error al actualizar el carrito', 'error');
            }
        }

        function renderCartItems(cartItems) {
            cartItems.innerHTML = '';
            
            cart.forEach(item => {
                try {
                    const row = createCartItemRow(item);
                    cartItems.appendChild(row);
                } catch (error) {
                    console.error('Error al crear fila del carrito para:', item, error);
                }
            });
        }

        function createCartItemRow(item) {
            const row = document.createElement('tr');
            const subtotalUSD = item.price_usd * item.quantity;
            const subtotalBS = item.price_bs * item.quantity;
            
            row.innerHTML = `
                <td>${escapeHtml(item.name)} <small>(${escapeHtml(item.code || 'N/A')})</small></td>
                <td>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)" 
                                ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                        <input type="number" class="quantity-input" value="${item.quantity}"
                            onchange="setQuantity(${item.id}, this.value)" 
                            min="1" max="${item.max_stock}" step="1">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)"
                                ${item.quantity >= item.max_stock ? 'disabled' : ''}>+</button>
                    </div>
                </td>
                <td>$${item.price_usd.toFixed(2)}</td>
                <td>Bs. ${item.price_bs.toFixed(2)}</td>
                <td>$${subtotalUSD.toFixed(2)}</td>
                <td>Bs. ${subtotalBS.toFixed(2)}</td>
                <td>
                    <button class="remove-btn" onclick="removeFromCart(${item.id})" 
                            title="Eliminar producto">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            return row;
        }

        function updateQuantity(productId, change) {
            try {
                const item = cart.find(item => item.id === productId);
                if (!item) {
                    console.error('Producto no encontrado en el carrito:', productId);
                    return;
                }
                
                const newQuantity = item.quantity + change;
                
                if (newQuantity <= 0) {
                    removeFromCart(productId);
                    return;
                }
                
                if (newQuantity > item.max_stock) {
                    showNotification(`No puedes agregar más de ${item.max_stock} unidades de ${item.name}`, 'warning');
                    return;
                }
                
                item.quantity = newQuantity;
                updateCartDisplay();
                
            } catch (error) {
                console.error('Error al actualizar cantidad:', error);
                showNotification('Error al actualizar cantidad', 'error');
            }
        }

        function setQuantity(productId, quantity) {
            try {
                const item = cart.find(item => item.id === productId);
                if (!item) {
                    console.error('Producto no encontrado en el carrito:', productId);
                    updateCartDisplay(); // Restaurar vista
                    return;
                }
                
                const newQuantity = parseInt(quantity);
                
                if (isNaN(newQuantity) || newQuantity < 1) {
                    showNotification('La cantidad debe ser un número mayor a 0', 'warning');
                    updateCartDisplay(); // Restaurar valor anterior
                    return;
                }
                
                if (newQuantity > item.max_stock) {
                    showNotification(`No puedes agregar más de ${item.max_stock} unidades de ${item.name}`, 'warning');
                    item.quantity = item.max_stock;
                    updateCartDisplay();
                    return;
                }
                
                item.quantity = newQuantity;
                updateCartDisplay();
                
            } catch (error) {
                console.error('Error al establecer cantidad:', error);
                updateCartDisplay(); // Restaurar vista
            }
        }

        function removeFromCart(productId) {
            try {
                const item = cart.find(item => item.id === productId);
                const itemName = item ? item.name : 'Producto';
                
                cart = cart.filter(item => item.id !== productId);
                updateCartDisplay();
                showNotification(`${itemName} eliminado del carrito`, 'info');
                
            } catch (error) {
                console.error('Error al eliminar producto:', error);
                showNotification('Error al eliminar producto', 'error');
            }
        }

        function updateTotals() {
            try {
                const subtotalUSD = cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0);
                const subtotalBS = cart.reduce((sum, item) => sum + (item.price_bs * item.quantity), 0);
                
                // Actualizar elementos si existen
                const elements = {
                    'subtotal-usd': `$${subtotalUSD.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`,
                    'subtotal-bs': `Bs. ${subtotalBS.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`,
                    'total-usd': subtotalUSD.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                    'total-bs': subtotalBS.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2})
                };
                
                Object.entries(elements).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value;
                    }
                });
                
            } catch (error) {
                console.error('Error al actualizar totales:', error);
            }
        }

        function clearCart() {
            if (cart.length === 0) {
                showNotification('El carrito ya está vacío', 'warning');
                return;
            }
            
            try {
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
                            resetForm();
                            showNotification('Carrito limpiado correctamente');
                        }
                    });
                } else {
                    // Fallback si no está SweetAlert2
                    if (confirm('¿Estás seguro de que deseas limpiar todo el carrito?')) {
                        cart = [];
                        updateCartDisplay();
                        resetForm();
                        showNotification('Carrito limpiado correctamente');
                    }
                }
            } catch (error) {
                console.error('Error al limpiar carrito:', error);
                showNotification('Error al limpiar carrito', 'error');
            }
        }

        // ======================
        // Validaciones
        // ======================
        function validateCart() {
            if (cart.length === 0) {
                return { valid: false, message: 'El carrito está vacío' };
            }
            
            for (let item of cart) {
                if (!item.id || item.quantity <= 0) {
                    return { valid: false, message: `Producto inválido: ${item.name || 'desconocido'}` };
                }
                
                if (item.quantity > item.max_stock) {
                    return { valid: false, message: `Stock insuficiente para: ${item.name}` };
                }
            }
            
            return { valid: true };
        }

        function validatePaymentData() {
            const paymentMethod = document.getElementById('payment-method');
            if (!paymentMethod || !paymentMethod.value) {
                return { valid: false, message: 'Selecciona un método de pago' };
            }
            
            const clientType = document.querySelector('input[name="client-type"]:checked');
            if (!clientType) {
                return { valid: false, message: 'Selecciona un tipo de cliente' };
            }
            
            let clientInfo = '';
            if (clientType.value === 'registered') {
                const registeredClient = document.getElementById('registered-client');
                if (!registeredClient || !registeredClient.value) {
                    return { valid: false, message: 'Selecciona un cliente registrado' };
                }
                clientInfo = registeredClient.options[registeredClient.selectedIndex].text;
            } else {
                const newClient = document.getElementById('new-client');
                if (!newClient || !newClient.value.trim()) {
                    return { valid: false, message: 'Ingresa el nombre del cliente' };
                }
                clientInfo = newClient.value.trim();
                
                // Validar formato del nombre
                if (clientInfo.length < 3) {
                    return { valid: false, message: 'El nombre debe tener al menos 3 caracteres' };
                }
            }
            
            return { valid: true, clientInfo, paymentMethod: paymentMethod.value };
        }

        // ======================
        // Funciones de Clientes
        // ======================
        function toggleClientType() {
            try {
                const clientType = document.querySelector('input[name="client-type"]:checked');
                if (!clientType) return;
                
                const registeredClient = document.getElementById('registered-client');
                const newClient = document.getElementById('new-client');
                
                if (!registeredClient || !newClient) {
                    console.error('Elementos de cliente no encontrados');
                    return;
                }
                
                if (clientType.value === 'registered') {
                    registeredClient.disabled = false;
                    newClient.disabled = true;
                    newClient.value = '';
                } else {
                    registeredClient.disabled = true;
                    newClient.disabled = false;
                    registeredClient.value = '';
                }
                
            } catch (error) {
                console.error('Error al cambiar tipo de cliente:', error);
            }
        }

        // ======================
        // Funciones de Pago
        // ======================
        function processPayment() {
            if (isProcessingPayment) {
                showNotification('Ya se está procesando una venta', 'warning');
                return;
            }
            
            try {
                // Validar carrito
                const cartValidation = validateCart();
                if (!cartValidation.valid) {
                    showNotification(cartValidation.message, 'error');
                    return;
                }
                
                // Validar datos de pago
                const paymentValidation = validatePaymentData();
                if (!paymentValidation.valid) {
                    showNotification(paymentValidation.message, 'error');
                    return;
                }
                
                // Preparar datos para enviar
                const ventaData = prepareVentaData(paymentValidation.clientInfo, paymentValidation.paymentMethod, 'contado');
                
                // Mostrar confirmación
                showPaymentConfirmation(ventaData);
                
            } catch (error) {
                console.error('Error al procesar pago:', error);
                showNotification('Error al procesar el pago', 'error');
            }
        }

        function processCredit() {
            if (isProcessingPayment) {
                showNotification('Ya se está procesando una venta', 'warning');
                return;
            }
            
            try {
                // Validar carrito
                const cartValidation = validateCart();
                if (!cartValidation.valid) {
                    showNotification(cartValidation.message, 'error');
                    return;
                }
                
                // Para crédito, solo validar cliente
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
                
                // Preparar datos para crédito
                const ventaData = prepareVentaData(clientInfo, 'credito', 'credito');
                
                // Mostrar confirmación de crédito
                showCreditConfirmation(ventaData);
                
            } catch (error) {
                console.error('Error al procesar crédito:', error);
                showNotification('Error al procesar el crédito', 'error');
            }
        }

        function prepareVentaData(clientInfo, paymentMethod, ventaType) {
            const totalUSD = cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0);
            const totalBS = cart.reduce((sum, item) => sum + (item.price_bs * item.quantity), 0);
            
            const productos = cart.map(item => ({
                id: parseInt(item.id),
                nombre: item.name,
                codigo: item.code || '',
                cantidad: parseInt(item.quantity),
                precio_usd: parseFloat(item.price_usd),
                precio_bs: parseFloat(item.price_bs),
                subtotal_usd: parseFloat((item.price_usd * item.quantity).toFixed(2)),
                subtotal_bs: parseFloat((item.price_bs * item.quantity).toFixed(2)),
                medida: item.measure || 'unidad'
            }));
            
            return {
                cliente: clientInfo,
                tipo_pago: paymentMethod,
                tipo_venta: ventaType,
                total_usd: parseFloat(totalUSD.toFixed(2)),
                total_bs: parseFloat(totalBS.toFixed(2)),
                productos: productos,
                fecha: new Date().toISOString().split('T')[0],
                hora: new Date().toLocaleTimeString('es-ES', { hour12: false }),
                cantidad_productos: productos.length,
                tasa_cambio: USD_TO_BS_RATE
            };
        }

        function showPaymentConfirmation(ventaData) {
            const paymentMethodText = document.getElementById('payment-method');
            const methodName = paymentMethodText.options[paymentMethodText.selectedIndex].text;
            
            const confirmMessage = `
                <div style="text-align: left;">
                    <strong>Cliente:</strong> ${escapeHtml(ventaData.cliente)}<br>
                    <strong>Método:</strong> ${escapeHtml(methodName)}<br>
                    <strong>Total USD:</strong> $${ventaData.total_usd.toFixed(2)}<br>
                    <strong>Total BS:</strong> Bs. ${ventaData.total_bs.toFixed(2)}<br>
                    <strong>Productos:</strong> ${ventaData.productos.length}
                </div>
            `;
            console.log(ventaData)
            
            Swal.fire({
                title: '¿Confirmar venta?',
                html: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return enviarVentaAlServidor(ventaData);
                },
                //allowOutsideClick: false
            });
        }

        function showCreditConfirmation(ventaData) {
            const confirmMessage = `
                <div style="text-align: left;">
                    <strong>Cliente:</strong> ${escapeHtml(ventaData.cliente)}<br>
                    <strong>Total USD:</strong> $${ventaData.total_usd.toFixed(2)}<br>
                    <strong>Total BS:</strong> Bs. ${ventaData.total_bs.toFixed(2)}<br>
                    <strong>Productos:</strong> ${ventaData.productos.length}
                </div>
            `;
            
            Swal.fire({
                title: '¿Confirmar venta a crédito?',
                html: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return enviarVentaAlServidor(ventaData);
                },
                allowOutsideClick: false
            });
        }

        function enviarVentaAlServidor(datosVenta) {
            isProcessingPayment = true;
            
            const ventaData = {
                fecha: datosVenta.fecha,
                cliente: datosVenta.cliente,
                tipo_pago: datosVenta.tipo_pago,
                tipo_venta: datosVenta.tipo_venta,
                total_usd: datosVenta.total_usd,
                productos: datosVenta.productos.map(p => ({
                    id: p.id,
                    nombre: p.nombre,
                    codigo: p.codigo,
                    cantidad: p.cantidad,
                    precio_usd: p.precio_usd,
                    medida: p.medida
                }))
            };
            
            console.log('Enviando:', ventaData);
            
            return fetch('?action=admin&method=confirmarVenta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(ventaData),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Respuesta raw:', text);
                
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error parseando JSON:', text);
                    throw new Error('Respuesta inválida del servidor');
                }
            })
            .then(data => {
                console.log('Respuesta parseada:', data);
                
                // VERIFICAR SI FUE EXITOSO
                if (data.success) {
                    Swal.fire({
                        title: '¡Venta exitosa!',
                        text: data.message || 'La venta se procesó correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        resetForm();
                    });
                    return data;
                } else {
                    // ERROR DEL SERVIDOR
                    throw new Error(data.error || 'Error desconocido al procesar la venta');
                }
            })
            .catch(error => {
                console.log('Error completo:', error);
                Swal.fire({
                    title: 'Error al procesar',
                    text: error.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                throw error;
            })
            .finally(() => {
                isProcessingPayment = false;
            });
        }

        function resetForm() {
            try {
                cart = [];
                updateCartDisplay();
                
                // Limpiar método de pago
                const paymentMethod = document.getElementById('payment-method');
                if (paymentMethod) paymentMethod.value = '';
                
                // Limpiar selección de cliente
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
                
                console.log('Formulario reiniciado');
                
            } catch (error) {
                console.error('Error al reiniciar formulario:', error);
            }
        }

        // ======================
        // Funciones Auxiliares
        // ======================
        function showNotification(message, type = 'success') {
            console.log(`Notificación [${type}]: ${message}`);
            
            try {
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
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    font-size: 14px;
                    line-height: 1.4;
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
                
                // Agregar al DOM
                document.body.appendChild(notification);
                
                // Animar entrada
                setTimeout(() => {
                    notification.style.opacity = '1';
                    notification.style.transform = 'translateX(0)';
                }, 100);
                
                // Remover después de 4 segundos
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            if (document.body.contains(notification)) {
                                document.body.removeChild(notification);
                            }
                        }, 300);
                    }
                }, 4000);
                
            } catch (error) {
                console.error('Error al mostrar notificación:', error);
                // Fallback a alert si falla la notificación personalizada
                alert(`[${type.toUpperCase()}] ${message}`);
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            
            return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function formatCurrency(amount, currency = 'USD') {
            try {
                const num = parseFloat(amount);
                if (isNaN(num)) return currency === 'USD' ? '$0.00' : 'Bs. 0.00';
                
                if (currency === 'USD') {
                    return `${num.toFixed(2)}`;
                } else {
                    return `Bs. ${num.toFixed(2)}`;
                }
            } catch (error) {
                console.error('Error al formatear moneda:', error);
                return currency === 'USD' ? '$0.00' : 'Bs. 0.00';
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Crear versión debounced de la búsqueda
        const debouncedSearch = debounce(searchProducts, 300);

        // ======================
        // Atajos de teclado
        // ======================
        document.addEventListener('keydown', function(e) {
            try {
                // Ctrl + F para abrir búsqueda de productos
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    openProductModal();
                    return;
                }
                
                // Escape para cerrar modal
                if (e.key === 'Escape') {
                    if (modalOpen) {
                        closeProductModal();
                    }
                    return;
                }
                
                // Enter en el campo de búsqueda
                if (e.target.id === 'product-search' && e.key === 'Enter') {
                    e.preventDefault();
                    searchProducts();
                    return;
                }
                
                // Ctrl + Enter para procesar pago rápido
                if (e.ctrlKey && e.key === 'Enter' && !modalOpen) {
                    e.preventDefault();
                    processPayment();
                    return;
                }
                
                // Ctrl + Delete para limpiar carrito
                if (e.ctrlKey && e.key === 'Delete' && !modalOpen) {
                    e.preventDefault();
                    clearCart();
                    return;
                }
                
                // F1 para mostrar ayuda de atajos
                if (e.key === 'F1') {
                    e.preventDefault();
                    showKeyboardShortcuts();
                    return;
                }
                
            } catch (error) {
                console.error('Error en manejo de teclas:', error);
            }
        });

        function showKeyboardShortcuts() {
            const shortcuts = `
                <div style="text-align: left; line-height: 1.6;">
                    <strong>Atajos de teclado disponibles:</strong><br><br>
                    <strong>Ctrl + F:</strong> Buscar productos<br>
                    <strong>Escape:</strong> Cerrar modal<br>
                    <strong>Enter:</strong> Buscar (en campo de búsqueda)<br>
                    <strong>Ctrl + Enter:</strong> Procesar pago<br>
                    <strong>Ctrl + Delete:</strong> Limpiar carrito<br>
                    <strong>F1:</strong> Mostrar esta ayuda
                </div>
            `;
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Atajos de Teclado',
                    html: shortcuts,
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('Atajos disponibles:\nCtrl+F: Buscar\nEsc: Cerrar\nCtrl+Enter: Procesar pago\nF1: Ayuda');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                searchInput.removeAttribute('onkeyup');
                searchInput.addEventListener('input', debouncedSearch);
                
                searchInput.placeholder = 'Buscar por nombre o código... (Ctrl+F)';
            }
        });

        // ======================
        // Funciones de Debug
        // ======================
        function debugInfo() {
            const debugData = {
                productos_disponibles: products.length,
                productos_en_carrito: cart.length,
                tasa_usd_bs: USD_TO_BS_RATE,
                estado_modal: modalOpen,
                procesando_pago: isProcessingPayment,
                total_carrito_usd: cart.reduce((sum, item) => sum + (item.price_usd * item.quantity), 0),
                elementos_dom: {
                    modal: !!document.getElementById('product-modal'),
                    grid: !!document.getElementById('products-grid'),
                    carrito: !!document.getElementById('cart-items'),
                    totales: !!document.getElementById('total-usd')
                }
            };
            
            console.log('=== DEBUG INFO ===');
            console.table(debugData);
            console.log('Productos completos:', products);
            console.log('Carrito completo:', cart);
            
            return debugData;
        }

        function validateSystemIntegrity() {
            const issues = [];
            
            // Verificar productos
            if (products.length === 0) {
                issues.push('No hay productos disponibles');
            }
            
            // Verificar elementos del DOM
            const requiredElements = [
                'product-modal', 'products-grid', 'cart-items', 
                'empty-cart', 'subtotal-usd', 'payment-method'
            ];
            
            requiredElements.forEach(id => {
                if (!document.getElementById(id)) {
                    issues.push(`Elemento faltante: ${id}`);
                }
            });
            
            // Verificar librerías externas
            if (typeof Swal === 'undefined') {
                issues.push('SweetAlert2 no está disponible');
            }
            
            // Verificar configuración
            if (isNaN(USD_TO_BS_RATE) || USD_TO_BS_RATE <= 0) {
                issues.push('Tasa de cambio inválida');
            }
            
            if (issues.length > 0) {
                console.warn('PROBLEMAS DETECTADOS:', issues);
                showNotification(`Problemas detectados: ${issues.length}`, 'warning');
            } else {
                console.log('✅ Sistema verificado correctamente');
            }
            
            return { valid: issues.length === 0, issues };
        }

        // ======================
        // Funciones de Mantenimiento
        // ======================
        function cleanupCart() {
            // Limpiar productos inválidos del carrito
            const originalLength = cart.length;
            cart = cart.filter(item => {
                return item && 
                    item.id && 
                    item.quantity > 0 && 
                    item.price_usd > 0 && 
                    typeof item.name === 'string';
            });
            
            if (cart.length !== originalLength) {
                console.warn(`Se eliminaron ${originalLength - cart.length} productos inválidos del carrito`);
                updateCartDisplay();
            }
        }

        // Ejecutar verificaciones periódicas
        setInterval(() => {
            cleanupCart();
        }, 30000); // Cada 30 segundos

        // Llamar debug info y validación después de cargar
        setTimeout(() => {
            debugInfo();
            validateSystemIntegrity();
        }, 1000);

        // ======================
        // Manejo de errores globales
        // ======================
        window.addEventListener('error', function(e) {
            console.error('Error global capturado:', e.error);
            showNotification('Se produjo un error inesperado', 'error');
        });

        window.addEventListener('unhandledrejection', function(e) {
            console.error('Promesa rechazada no manejada:', e.reason);
            showNotification('Error en operación asíncrona', 'error');
        });

        // Prevenir pérdida de datos al cerrar la página
        window.addEventListener('beforeunload', function(e) {
            if (cart.length > 0 && !isProcessingPayment) {
                e.preventDefault();
                e.returnValue = 'Tienes productos en el carrito. ¿Estás seguro de salir?';
            }
        });
        console.log('🚀 Sistema POS inicializado correctamente');
    </script>
</body>
</html>