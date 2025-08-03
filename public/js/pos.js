document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const searchInput = document.getElementById('product-search');
    const productCards = document.querySelectorAll('.product-card');
    const noResultsMsg = document.getElementById('product-no-results');
    const cartItems = document.getElementById('cart-items');
    const emptyCartMsg = document.getElementById('empty-cart');
    
    // Variables del carrito
    let cart = [];
    let cartCounter = 0;

    // Configuración inicial
    noResultsMsg.style.display = 'flex';
    productCards.forEach(card => card.style.display = 'none');
    
    // Cargar carrito desde localStorage si existe
    loadCartFromStorage();
    
    // Función para guardar carrito en localStorage
    function saveCartToStorage() {
        try {
            localStorage.setItem('pos_cart', JSON.stringify(cart));
        } catch (error) {
            console.error('Error guardando carrito:', error);
        }
    }
    
    // Función para cargar carrito desde localStorage
    function loadCartFromStorage() {
        try {
            const savedCart = localStorage.getItem('pos_cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                cartCounter = cart.length > 0 ? Math.max(...cart.map(item => item.cartId)) + 1 : 0;
                updateCart();
            }
        } catch (error) {
            console.error('Error cargando carrito:', error);
            cart = [];
        }
    }
    
    // Función para buscar productos
    function buscarProductos(termino) {
        let encontrados = 0;
        const terminoLower = termino.toLowerCase().trim();
        
        productCards.forEach(card => {
            const nombre = card.dataset.productName.toLowerCase();
            const codigo = card.dataset.productCode.toLowerCase();
            
            if (terminoLower.length > 0 && (nombre.includes(terminoLower) || codigo.includes(terminoLower))) {
                card.style.display = '';
                encontrados++;
            } else {
                card.style.display = 'none';
            }
        });
        
        noResultsMsg.style.display = encontrados > 0 ? 'none' : 'flex';
    }
    
    // Función para agregar al carrito
    function addToCart(product) {
        // Verificar si el producto ya está en el carrito
        const existingItem = cart.find(item => item.productId === product.productId);
        if (existingItem) {
            // Actualizar cantidad si ya existe
            const newQuantity = existingItem.quantity + product.quantity;
            if (newQuantity > product.stock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock insuficiente',
                    text: `No hay suficiente stock para agregar ${product.quantity} unidades más. Stock máximo: ${product.stock}`,
                    timer: 2000
                });
                return;
            }
            existingItem.quantity = newQuantity;
        } else {
            // Agregar nuevo item
            cart.push(product);
        }
        
        saveCartToStorage();
        updateCart();
        
        Swal.fire({
            icon: 'success',
            title: 'Producto agregado',
            text: `${product.quantity} ${product.quantity > 1 ? 'unidades' : 'unidad'} de ${product.name}`,
            toast: true,
            position: 'top-end',
            timer: 2000
        });
    }

    // Función para actualizar cliente JSON
    function updateClienteJson() {
        const clienteRegistrado = document.getElementById('cliente').value;
        const clienteNoRegistrado = document.getElementById('cliente2').value.trim();
        
        let clienteData = null;
        
        if (clienteRegistrado) {
            const [nombre, cedula] = clienteRegistrado.split('-');
            clienteData = {
                tipo: 'registrado',
                nombre: nombre.trim(),
                cedula: cedula.trim()
            };
        } else if (clienteNoRegistrado) {
            clienteData = {
                tipo: 'no_registrado',
                nombre: clienteNoRegistrado,
                cedula: null
            };
        }
        
        const clienteJsonInput = document.getElementById('cliente-json');
        if (clienteJsonInput) {
            clienteJsonInput.value = clienteData ? JSON.stringify(clienteData) : '';
        }
    }

    // Función para actualizar el carrito
    function updateCart() {
        cartItems.innerHTML = '';
        let totalUSD = 0;
        let totalBS = 0;

        if (cart.length === 0) {
            emptyCartMsg.style.display = 'flex';
            document.getElementById('total-usd').value = '0.00';
            document.getElementById('total-bs').value = '0.00';
            document.getElementById('total-usd-input').value = '0';
            document.getElementById('productos-json').value = '';
            updateClienteJson();
            return;
        }

        cart.forEach((item) => {
            const subtotalUSD = item.priceUsd * item.quantity;
            const subtotalBS = item.priceBs * item.quantity;
            
            totalUSD += subtotalUSD;
            totalBS += subtotalBS;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name} <small>(${item.code})</small></td>
                <td>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-cart-id="${item.cartId}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" value="${item.quantity}" min="1" max="${item.stock}" 
                            class="cart-quantity" data-cart-id="${item.cartId}">
                        <button class="quantity-btn plus" data-cart-id="${item.cartId}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td>${formatNumber(item.priceUsd)}</td>
                <td>Bs. ${formatNumber(item.priceBs)}</td>
                <td>${formatNumber(subtotalUSD)}</td>
                <td>Bs. ${formatNumber(subtotalBS)}</td>
                <td>
                    <button class="remove-item btn danger" data-cart-id="${item.cartId}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            cartItems.appendChild(row);
        });

        // Actualizar totales
        document.getElementById('total-usd').value = formatNumber(totalUSD);
        document.getElementById('total-bs').value = formatNumber(totalBS);
        document.getElementById('total-usd-input').value = totalUSD.toFixed(2);
        document.getElementById('productos-json').value = JSON.stringify(cart);
        emptyCartMsg.style.display = 'none';
        
        updateClienteJson();
        setupCartControls();
    }

    // Función para formatear números
    function formatNumber(number) {
        return parseFloat(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Configurar controles del carrito
    function setupCartControls() {
        // Botones de cantidad
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartId = parseInt(this.dataset.cartId);
                const item = cart.find(item => item.cartId === cartId);
                if (!item) return;
                
                const input = this.parentElement.querySelector('.cart-quantity');
                let value = parseInt(input.value) || 1;

                if (this.classList.contains('minus') && value > 1) {
                    input.value = value - 1;
                    item.quantity = value - 1;
                } else if (this.classList.contains('plus') && value < item.stock) {
                    input.value = value + 1;
                    item.quantity = value + 1;
                }
                
                saveCartToStorage();
                updateCart();
            });
        });

        // Cambios manuales en cantidad
        document.querySelectorAll('.cart-quantity').forEach(input => {
            input.addEventListener('change', function() {
                const cartId = parseInt(this.dataset.cartId);
                const item = cart.find(item => item.cartId === cartId);
                if (!item) return;
                
                const newQuantity = parseInt(this.value) || 1;

                if (newQuantity > item.stock) {
                    this.value = item.stock;
                    item.quantity = item.stock;
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock insuficiente',
                        text: `Solo quedan ${item.stock} unidades disponibles`,
                        timer: 2000
                    });
                } else if (newQuantity >= 1) {
                    item.quantity = newQuantity;
                } else {
                    this.value = 1;
                    item.quantity = 1;
                }
                
                saveCartToStorage();
                updateCart();
            });
        });

        // Botones de eliminar
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartId = parseInt(this.dataset.cartId);
                
                Swal.fire({
                    title: '¿Eliminar producto?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = cart.filter(item => item.cartId !== cartId);
                        saveCartToStorage();
                        updateCart();
                    }
                });
            });
        });
    }
    
    // Función para limpiar carrito
    function clearCart() {
        cart = [];
        cartCounter = 0;
        localStorage.removeItem('pos_cart');
        updateCart();
        
        // Resetear campos
        document.getElementById('cliente').value = '';
        document.getElementById('cliente2').value = '';
        document.getElementById('tipo-pago').value = '';
    }
    
    // Evento para agregar productos al carrito
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart')) {
            const btn = e.target.closest('.add-to-cart');
            const productCard = btn.closest('.product-card');
            
            if (!productCard) return;
            
            const productId = productCard.dataset.productId;
            const productName = productCard.dataset.productName;
            const productCode = productCard.dataset.productCode;
            const productStock = parseInt(productCard.dataset.productStock);
            const productPriceUsd = parseFloat(productCard.dataset.productPriceUsd);
            const productPriceBs = parseFloat(productCard.dataset.productPriceBs);
            
            if (isNaN(productStock)) {
                console.error('Stock inválido');
                return;
            }

            const quantityInput = productCard.querySelector('.product-quantity');
            const quantity = parseInt(quantityInput.value) || 1;
            
            // Verificar stock total (carrito + nueva cantidad)
            const totalInCart = cart
                .filter(item => item.productId === productId)
                .reduce((sum, item) => sum + item.quantity, 0);
            
            if (totalInCart + quantity > productStock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock insuficiente',
                    text: `No puedes agregar ${quantity} unidades. Stock disponible: ${productStock - totalInCart}`,
                    timer: 2000
                });
                return;
            }
            
            const product = {
                cartId: cartCounter++,
                productId: productId,
                name: productName,
                code: productCode,
                priceUsd: productPriceUsd,
                priceBs: productPriceBs,
                quantity: quantity,
                stock: productStock
            };
            
            addToCart(product);
        }
    });

    // Eventos de búsqueda
    searchInput.addEventListener('input', () => buscarProductos(searchInput.value));

    // Controles de cantidad en productos
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-btn') && e.target.closest('.product-card')) {
            const btn = e.target.closest('.quantity-btn');
            const input = btn.parentElement.querySelector('.product-quantity');
            let value = parseInt(input.value) || 1;
            const productCard = btn.closest('.product-card');
            const maxStock = parseInt(productCard.dataset.productStock);

            if (btn.classList.contains('minus') && value > 1) {
                input.value = value - 1;
            } else if (btn.classList.contains('plus') && value < maxStock) {
                input.value = value + 1;
            }
        }
    });
    
    // Cancelar venta
    document.getElementById('cancel-sale').addEventListener('click', function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Carrito vacío',
                text: 'No hay nada que cancelar'
            });
            return;
        }
        
        Swal.fire({
            title: '¿Cancelar venta?',
            text: 'Se perderán todos los productos del carrito',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                clearCart();
                Swal.fire({
                    icon: 'success',
                    title: 'Venta cancelada',
                    timer: 1500
                });
            }
        });
    });
    
    // Procesar pago
    document.getElementById('process-payment').addEventListener('click', function(e) {
        validateBeforeSubmit(e, false);
    });
    
    // Procesar crédito
    document.getElementById('process-credit').addEventListener('click', function(e) {
        validateBeforeSubmit(e, true);
    });
    
    // Función de validación antes de enviar
    function validateBeforeSubmit(e, isCredit) {
        // Validar carrito no vacío
        if (cart.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Carrito vacío',
                text: 'Agrega productos antes de procesar'
            });
            return;
        }
        
        // Validar método de pago
        const tipoPago = document.getElementById('tipo-pago').value;
        if (!tipoPago) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Método de pago requerido',
                text: 'Selecciona un método de pago'
            });
            return;
        }
        
        // Validar cliente
        const clienteRegistrado = document.getElementById('cliente').value;
        const clienteNoRegistrado = document.getElementById('cliente2').value.trim();
        
        if (!clienteRegistrado && !clienteNoRegistrado) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Cliente requerido',
                text: 'Selecciona un cliente registrado o ingresa uno nuevo'
            });
            return;
        }
        
        if (clienteRegistrado && clienteNoRegistrado) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Solo un cliente',
                text: 'Selecciona solo un cliente (registrado o no registrado)'
            });
            return;
        }
        
        // Confirmar procesamiento
        const total = document.getElementById('total-usd').value;
        const form = document.getElementById('pos-form');
        
        Swal.fire({
            title: `¿${isCredit ? 'Procesar crédito' : 'Procesar pago'}?`,
            text: `Total: $${total}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Sí, ${isCredit ? 'procesar crédito' : 'procesar pago'}`,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        
        e.preventDefault();
    }

    // Inicializar controles del cliente
    document.getElementById('cliente').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('cliente2').value = '';
        }
        updateClienteJson();
    });

    document.getElementById('cliente2').addEventListener('input', function() {
        if (this.value.trim()) {
            document.getElementById('cliente').value = '';
        }
        updateClienteJson();
    });
});