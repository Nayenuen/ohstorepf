// Variables Globales
const DOM = {
    cards: document.getElementById('cards'),
    items: document.getElementById('items'),
    footer: document.getElementById('footer'),
    dropdownItems: document.getElementById('dropdown-items'),
    cartCount: document.getElementById('cart-count')
};

const templates = {
    card: document.getElementById('template-card').content,
    footer: document.getElementById('template-footer').content,
    carrito: document.getElementById('template-carrito').content,
    dropdownItem: document.getElementById('template-dropdown-item').content
};

const fragment = document.createDocumentFragment();
let carrito = JSON.parse(localStorage.getItem('carrito')) || {};
let productosData = [];

// Funciones Principales
async function init() {
    await cargarProductos();
    bindEvents();
    renderizarTodo();
}

async function cargarProductos() {
    try {
        const response = await fetch('products.json');
        productosData = await response.json();
        renderizarProductos(productosData);
    } catch (error) {
        console.error('Error cargando productos:', error);
    }
}

function bindEvents() {
    // Event Delegation para los botones
    document.addEventListener('click', (e) => {
        if (e.target.matches('.btn-add-to-cart, .btn-add-to-cart *')) {
            const button = e.target.closest('.btn-add-to-cart');
            agregarAlCarrito(button.dataset.id);
        }
        
        if (e.target.matches('.cart-remove-btn, .cart-remove-btn *')) {
            const button = e.target.closest('.cart-remove-btn');
            eliminarDelCarrito(button.dataset.id);
        }
        
        if (e.target.matches('.btn-info, .btn-info *')) {
            const button = e.target.closest('.btn-info');
            modificarCantidad(button.dataset.id, 1);
        }
        
        if (e.target.matches('.btn-danger, .btn-danger *')) {
            const button = e.target.closest('.btn-danger');
            modificarCantidad(button.dataset.id, -1);
        }
        
        if (e.target.matches('#vaciar-carrito, #vaciar-carrito *')) {
            vaciarCarrito();
        }
    });
}

// Funciones de Renderizado
function renderizarTodo() {
    renderizarProductos(productosData);
    renderizarCarritoPrincipal();
    renderizarDropdownCarrito();
    actualizarContador();
}

function renderizarProductos(productos) {
    DOM.cards.innerHTML = '';
    
    productos.forEach(producto => {
        const clone = templates.card.cloneNode(true);
        clone.querySelector('h5').textContent = producto.name;
        clone.querySelector('p').textContent = `$${producto.price.toFixed(2)}`;
        clone.querySelector('img').src = producto.image;
        clone.querySelector('img').alt = producto.name;
        clone.querySelector('.btn-add-to-cart').dataset.id = producto.id;
        
        DOM.cards.appendChild(clone);
    });
}

function renderizarCarritoPrincipal() {
    DOM.items.innerHTML = '';
    
    if (Object.keys(carrito).length === 0) {
        DOM.footer.innerHTML = '<th scope="row" colspan="5">Carrito vacío - comience a comprar!</th>';
        return;
    }
    
    Object.values(carrito).forEach(producto => {
        const clone = templates.carrito.cloneNode(true);
        clone.querySelector('th').textContent = producto.id;
        clone.querySelectorAll('td')[0].textContent = producto.title;
        clone.querySelectorAll('td')[1].textContent = producto.cantidad;
        clone.querySelector('.btn-info').dataset.id = producto.id;
        clone.querySelector('.btn-danger').dataset.id = producto.id;
        clone.querySelector('span').textContent = (producto.cantidad * producto.precio).toFixed(2);
        
        DOM.items.appendChild(clone);
    });
    
    renderizarFooterCarrito();
}

function renderizarFooterCarrito() {
    const clone = templates.footer.cloneNode(true);
    const totalCount = Object.values(carrito).reduce((acc, item) => acc + item.cantidad, 0);
    const totalPrice = Object.values(carrito).reduce((acc, item) => acc + (item.cantidad * item.precio), 0);
    
    clone.querySelector('#total-count').textContent = totalCount;
    clone.querySelector('#total-price').textContent = totalPrice.toFixed(2);
    
    DOM.footer.innerHTML = '';
    DOM.footer.appendChild(clone);
}

function renderizarDropdownCarrito() {
    DOM.dropdownItems.innerHTML = '';
    
    if (Object.keys(carrito).length === 0) {
        DOM.dropdownItems.innerHTML = '<li class="empty-cart">Carrito vacío</li>';
        return;
    }
    
    Object.values(carrito).forEach(producto => {
        const productoOriginal = productosData.find(p => p.id == producto.id) || producto;
        const clone = templates.dropdownItem.cloneNode(true);
        
        clone.querySelector('img').src = productoOriginal.image || 'assets/images/default-product.jpg';
        clone.querySelector('img').alt = producto.title;
        clone.querySelector('.cart-product-name').textContent = producto.title;
        clone.querySelector('.price-amount').textContent = producto.precio.toFixed(2);
        clone.querySelector('.product-quantity').textContent = producto.cantidad;
        clone.querySelector('.cart-remove-btn').dataset.id = producto.id;
        
        DOM.dropdownItems.appendChild(clone);
    });
}

// Funciones de Lógica del Carrito
function agregarAlCarrito(productoId) {
    const producto = productosData.find(p => p.id == productoId);
    if (!producto) return;
    
    if (carrito[productoId]) {
        carrito[productoId].cantidad += 1;
    } else {
        carrito[productoId] = {
            id: producto.id,
            title: producto.name,
            precio: producto.price,
            cantidad: 1,
            image: producto.image
        };
    }
    
    actualizarEstadoCarrito();
    animarIconoCarrito();
}

function eliminarDelCarrito(productoId) {
    delete carrito[productoId];
    actualizarEstadoCarrito();
}

function modificarCantidad(productoId, cambio) {
    if (carrito[productoId]) {
        carrito[productoId].cantidad += cambio;
        
        if (carrito[productoId].cantidad <= 0) {
            delete carrito[productoId];
        }
        
        actualizarEstadoCarrito();
    }
}

function vaciarCarrito() {
    carrito = {};
    actualizarEstadoCarrito();
}

// Funciones de Utilidad
function actualizarEstadoCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    renderizarCarritoPrincipal();
    renderizarDropdownCarrito();
    actualizarContador();
}

function actualizarContador() {
    const totalCount = Object.values(carrito).reduce((acc, item) => acc + item.cantidad, 0);
    DOM.cartCount.textContent = totalCount;
}

function animarIconoCarrito() {
    const cartIcon = document.querySelector('.lnr-cart');
    cartIcon.classList.add('animate-bounce');
    setTimeout(() => cartIcon.classList.remove('animate-bounce'), 1000);
}

// Inicialización
document.addEventListener('DOMContentLoaded', init);
