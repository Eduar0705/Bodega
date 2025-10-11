// Base de datos simulada (en producción, esto vendría de tu backend PHP)
let providers = [
    {
        id: 1,
        name: 'Distribuidora Global S.A.',
        category: 'materiales',
        contact: 'María González',
        email: 'maria@distribuidora.com',
        phone: '+34 912 345 678',
        address: 'Calle Mayor 123, Madrid, España',
        status: 'activo',
        notes: 'Proveedor principal de materiales de construcción'
    },
    {
        id: 2,
        name: 'TechSupply Solutions',
        category: 'tecnologia',
        contact: 'Carlos Ruiz',
        email: 'carlos@techsupply.com',
        phone: '+34 933 456 789',
        address: 'Av. Diagonal 456, Barcelona, España',
        status: 'activo',
        notes: 'Especialistas en equipos informáticos'
    },
    {
        id: 3,
        name: 'Servicios Integrales Pro',
        category: 'servicios',
        contact: 'Ana Martínez',
        email: 'ana@servicios.com',
        phone: '+34 954 567 890',
        address: 'Plaza España 789, Sevilla, España',
        status: 'activo',
        notes: 'Servicios de mantenimiento y limpieza'
    },
    {
        id: 4,
        name: 'LogiTrans Express',
        category: 'logistica',
        contact: 'Pedro López',
        email: 'pedro@logitrans.com',
        phone: '+34 963 678 901',
        address: 'Puerto Industrial, Valencia, España',
        status: 'inactivo',
        notes: 'Transporte y logística internacional'
    }
];

// Inicializar la aplicación
document.addEventListener('DOMContentLoaded', function() {
    loadProviders();
    updateStats();
});

// Cargar proveedores en la tabla
function loadProviders() {
    const tbody = document.getElementById('providersTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (providers.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    tbody.innerHTML = providers.map(provider => `
        <tr data-id="${provider.id}" data-category="${provider.category}" data-status="${provider.status}">
            <td>
                <div class="provider-info">
                    <div class="provider-avatar">
                        ${provider.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="provider-details">
                        <h4>${provider.name}</h4>
                        <p>${provider.email}</p>
                    </div>
                </div>
            </td>
            <td>
                <span class="category-badge">${getCategoryName(provider.category)}</span>
            </td>
            <td>${provider.contact}</td>
            <td>${provider.phone}</td>
            <td>
                <span class="status-badge ${provider.status}">
                    <span class="status-dot"></span>
                    ${provider.status === 'activo' ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editProvider(${provider.id})" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M11.333 2A1.886 1.886 0 0 1 14 4.667l-9 9-3.667 1 1-3.667 9-9z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="action-btn delete" onclick="deleteProvider(${provider.id})" title="Eliminar">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M2 4h12M5.333 4V2.667a1.333 1.333 0 0 1 1.334-1.334h2.666a1.333 1.333 0 0 1 1.334 1.334V4m2 0v9.333a1.333 1.333 0 0 1-1.334 1.334H4.667a1.334 1.334 0 0 1-1.334-1.334V4h9.334z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Actualizar estadísticas
function updateStats() {
    const total = providers.length;
    const active = providers.filter(p => p.status === 'activo').length;
    
    document.getElementById('totalProviders').textContent = total;
    document.getElementById('activeProviders').textContent = active;
}

// Filtrar proveedores
function filterProviders() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#providersTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const category = row.dataset.category;
        const status = row.dataset.status;
        
        const matchesSearch = text.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesCategory && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Abrir modal
function openModal(mode, providerId = null) {
    const modal = document.getElementById('providerModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('providerForm');
    
    form.reset();
    
    if (mode === 'add') {
        modalTitle.textContent = 'Nuevo Proveedor';
        document.getElementById('providerId').value = '';
    } else if (mode === 'edit' && providerId) {
        modalTitle.textContent = 'Editar Proveedor';
        const provider = providers.find(p => p.id === providerId);
        
        if (provider) {
            document.getElementById('providerId').value = provider.id;
            document.getElementById('providerName').value = provider.name;
            document.getElementById('providerCategory').value = provider.category;
            document.getElementById('providerContact').value = provider.contact;
            document.getElementById('providerEmail').value = provider.email;
            document.getElementById('providerPhone').value = provider.phone;
            document.getElementById('providerStatus').value = provider.status;
            document.getElementById('providerAddress').value = provider.address || '';
            document.getElementById('providerNotes').value = provider.notes || '';
        }
    }
    
    modal.classList.add('active');
}

// Cerrar modal
function closeModal() {
    const modal = document.getElementById('providerModal');
    modal.classList.remove('active');
}

// Guardar proveedor
function saveProvider(event) {
    event.preventDefault();
    
    const id = document.getElementById('providerId').value;
    const providerData = {
        name: document.getElementById('providerName').value,
        category: document.getElementById('providerCategory').value,
        contact: document.getElementById('providerContact').value,
        email: document.getElementById('providerEmail').value,
        phone: document.getElementById('providerPhone').value,
        status: document.getElementById('providerStatus').value,
        address: document.getElementById('providerAddress').value,
        notes: document.getElementById('providerNotes').value
    };
    
    if (id) {
        // Editar proveedor existente
        const index = providers.findIndex(p => p.id === parseInt(id));
        if (index !== -1) {
            providers[index] = { ...providers[index], ...providerData };
        }
    } else {
        // Agregar nuevo proveedor
        const newId = providers.length > 0 ? Math.max(...providers.map(p => p.id)) + 1 : 1;
        providers.push({ id: newId, ...providerData });
    }
    
    // En producción, aquí harías una petición AJAX a tu backend PHP
    // fetch('api/proveedores.php', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(providerData)
    // });
    
    loadProviders();
    updateStats();
    closeModal();
    
    // Mostrar notificación de éxito
    showNotification(id ? 'Proveedor actualizado correctamente' : 'Proveedor agregado correctamente');
}

// Editar proveedor
function editProvider(id) {
    openModal('edit', id);
}

// Eliminar proveedor
function deleteProvider(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este proveedor?')) {
        providers = providers.filter(p => p.id !== id);
        
        // En producción, aquí harías una petición AJAX a tu backend PHP
        // fetch(`api/proveedores.php?id=${id}`, { method: 'DELETE' });
        
        loadProviders();
        updateStats();
        showNotification('Proveedor eliminado correctamente');
    }
}

// Obtener nombre de categoría
function getCategoryName(category) {
    const categories = {
        'materiales': 'Materiales',
        'servicios': 'Servicios',
        'tecnologia': 'Tecnología',
        'logistica': 'Logística'
    };
    return categories[category] || category;
}

// Mostrar notificación
function showNotification(message) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        z-index: 2000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Eliminar después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Cerrar modal al hacer clic fuera
document.getElementById('providerModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Animaciones CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);