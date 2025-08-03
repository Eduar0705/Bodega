<style>
    /* Variables de colores y estilos */
    :root {
        --primary-color: #3498db;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --dark-color: #2c3e50;
        --light-color: #ecf0f1;
        --gray-color: #95a5a6;
        --light-gray: #f8f9fa;
        --border-color: #e0e0e0;
        --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        --border-radius: 8px;
        --transition: all 0.3s ease;
    }

    .pos-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-areas: 
        "products cart";
    gap: 20px;
    padding: 20px;
    max-width: 1600px;
    margin: 0 auto;
    }

    /* Estilos para sección de productos */
    .products-section {
    grid-area: products;
    background-color: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    height: fit-content;
    }

    .search-box {
    display: flex;
    margin-bottom: 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    }

    .search-box:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .search-box input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    outline: none;
    font-size: 14px;
    }

    .search-box button {
    padding: 0 15px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    }

    .search-box button:hover {
    background-color: #2980b9;
    }

    .product-results {
    margin-top: 20px;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    }

    .no-results {
    text-align: center;
    padding: 40px 20px;
    color: var(--gray-color);
    background-color: var(--light-gray);
    border-radius: var(--border-radius);
    }

    .no-results i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #bdc3c7;
    }

    .product-card {
    display: flex;
    background-color: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 15px;
    gap: 15px;
    transition: var(--transition);
    animation: fadeIn 0.3s ease-out;
    }

    .product-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .product-details {
    flex: 1;
    }

    .product-details h3 {
    margin-bottom: 8px;
    color: var(--dark-color);
    font-size: 16px;
    }

    .product-info {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 0.85rem;
    color: var(--gray-color);
    }

    .product-info span span {
    font-weight: 600;
    color: var(--dark-color);
    }

    .product-prices {
    display: flex;
    gap: 15px;
    font-weight: 600;
    font-size: 14px;
    }

    .product-prices span span {
    color: var(--success-color);
    }

    .product-actions {
    display: flex;
    align-items: center;
    }

    /* Estilos para sección del carrito */
    .cart-section {
    grid-area: cart;
    background-color: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    }

    .cart-container {
    display: flex;
    gap: 20px;
    }

    .cart-items {
    flex: 1;
    min-height: 300px;
    position: relative;
    }

    .cart-totals {
    width: 320px;
    background-color: var(--light-gray);
    padding: 20px;
    border-radius: var(--border-radius);
    position: sticky;
    top: 20px;
    }

    .empty-cart {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: var(--gray-color);
    width: 100%;
    padding: 20px;
    }

    .empty-cart i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #bdc3c7;
    }

    /* Estilos para la tabla del carrito */
    #cart-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    }

    #cart-table th {
    background-color: var(--light-gray);
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: var(--dark-color);
    position: sticky;
    top: 0;
    }

    #cart-table td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    }

    #cart-table tr:last-child td {
    border-bottom: none;
    }

    #cart-table tr:hover {
    background-color: rgba(52, 152, 219, 0.05);
    }

    /* Estilos para los totales */
    .total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
    }

    .total-row.grand-total {
    font-weight: 600;
    font-size: 16px;
    color: var(--dark-color);
    border-bottom: none;
    margin-bottom: 20px;
    padding-bottom: 0;
    }

    .customer-info-total {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
    }

    /* Estilos para los botones */
    .btn {
    padding: 10px 15px;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: var(--transition);
    font-size: 14px;
    }

    .btn i {
    font-size: 14px;
    }

    .btn.primary {
    background-color: var(--primary-color);
    color: white;
    }

    .btn.success {
    background-color: var(--success-color);
    color: white;
    }

    .btn.danger {
    background-color: var(--danger-color);
    color: white;
    }

    .btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    }

    .btn:active {
    transform: translateY(0);
    }

    .payment-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
    }

    .payment-actions .btn {
    width: 100%;
    }

    /* Estilos para el select de tipo de pago */
    #tipo-pago, #cliente, #cliente2 {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: white;
    font-size: 14px;
    color: var(--dark-color);
    cursor: pointer;
    outline: none;
    transition: var(--transition);
    margin-bottom: 15px;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    }

    #tipo-pago:hover, #cliente:hover, #cliente2:hover {
    border-color: var(--primary-color);
    }

    #tipo-pago:focus, #cliente:focus, #cliente2:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    /* Animaciones */
    @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
    .pos-container {
        grid-template-columns: 1fr;
        grid-template-areas: 
        "products"
        "cart";
    }
    
    .cart-container {
        flex-direction: column;
    }
    
    .cart-totals {
        width: 100%;
        position: static;
    }
    }

    @media (max-width: 768px) {
    .product-card {
        flex-direction: column;
    }
    
    .product-info, .product-prices {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    #cart-table {
        display: block;
        overflow-x: auto;
    }
    
    .payment-actions {
        flex-direction: row;
    }
    
    .payment-actions .btn {
        flex: 1;
    }
    }

    /* Clases utilitarias */
    .hidden {
    display: none !important;
    }

    .text-success {
    color: var(--success-color);
    }

    .text-danger {
    color: var(--danger-color);
    }

    .text-primary {
    color: var(--primary-color);
    }

    .bg-light {
    background-color: var(--light-gray);
    }
</style>