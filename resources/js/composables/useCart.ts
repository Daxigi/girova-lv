import { ref, computed, watch } from 'vue';
import type { CartItem, Cart } from '../types/cart';
import { useToast } from './useToast';

// CLAVE para guardar en localStorage
const CART_STORAGE_KEY = 'laravue_cart';

// Instancia de toast para notificaciones
const toast = useToast();

/**
 * Estado reactivo del carrito (compartido entre todos los componentes)
 *
 * ref() crea una variable reactiva que se actualiza automáticamente
 * en todos los componentes que la usen
 */
const cartItems = ref<CartItem[]>([]);

/**
 * Estado para controlar si el drawer está abierto
 */
const isCartOpen = ref(false);

/**
 * Cargar carrito desde localStorage al iniciar
 *
 * Esta función se ejecuta UNA VEZ cuando se carga la aplicación
 */
function loadCartFromStorage() {
    try {
        // Intentar leer del localStorage
        const savedCart = localStorage.getItem(CART_STORAGE_KEY);

        if (savedCart) {
            // Si existe, parsear el JSON y asignarlo
            cartItems.value = JSON.parse(savedCart);
            console.log('✅ Carrito cargado desde localStorage:', cartItems.value);
        }
    } catch (error) {
        console.error('❌ Error al cargar carrito:', error);
        cartItems.value = [];
    }
}

/**
 * Guardar carrito en localStorage
 *
 * Se ejecuta automáticamente cada vez que cambia cartItems
 */
function saveCartToStorage() {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems.value));
        console.log('💾 Carrito guardado en localStorage');
    } catch (error) {
        console.error('❌ Error al guardar carrito:', error);
    }
}

// Watcher: Observa cambios en cartItems y guarda automáticamente
watch(cartItems, saveCartToStorage, { deep: true });

/**
 * Computed: Cálculo del total
 *
 * Se recalcula automáticamente cada vez que cambian los items
 */
const total = computed(() => {
    return cartItems.value.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
    }, 0);
});

/**
 * Computed: Cantidad total de productos
 */
const itemCount = computed(() => {
    return cartItems.value.reduce((count, item) => {
        return count + item.quantity;
    }, 0);
});

/**
 * Computed: Objeto Cart completo
 */
const cart = computed<Cart>(() => ({
    items: cartItems.value,
    total: total.value,
    itemCount: itemCount.value,
}));

/**
 * FUNCIÓN: Agregar producto al carrito (con validación de stock)
 *
 * @param product - Producto a agregar
 * @param quantity - Cantidad (por defecto 1)
 * @returns boolean - true si se agregó, false si no hay stock
 */
function addToCart(product: Omit<CartItem, 'quantity'>, quantity: number = 1): boolean {
    // VALIDACIÓN 1: Verificar que el stock sea mayor a 0
    if (product.stock <= 0) {
        toast.error(`${product.name} no tiene stock disponible`);
        console.log(`❌ Sin stock: ${product.name}`);
        return false;
    }

    // Buscar si el producto ya existe en el carrito
    const existingItemIndex = cartItems.value.findIndex(item => item.id === product.id);

    if (existingItemIndex !== -1) {
        // El producto YA EXISTE en el carrito
        const existingItem = cartItems.value[existingItemIndex];
        const newQuantity = existingItem.quantity + quantity;

        // VALIDACIÓN 2: Verificar que no exceda el stock disponible
        if (newQuantity > product.stock) {
            const disponible = product.stock - existingItem.quantity;

            if (disponible > 0) {
                toast.warning(
                    `Solo puedes agregar ${disponible} unidad(es) más de ${product.name}. Stock disponible: ${product.stock}`
                );
            } else {
                toast.error(
                    `Ya tienes el máximo disponible de ${product.name} en el carrito (${product.stock} unidades)`
                );
            }
            console.log(`⚠️ Stock excedido: ${product.name}`);
            return false;
        }

        // Si pasa la validación, aumentar la cantidad
        existingItem.quantity = newQuantity;
        toast.success(`Cantidad actualizada: ${existingItem.quantity}x ${product.name}`);
        console.log(`➕ Cantidad aumentada: ${product.name} (${existingItem.quantity})`);
    } else {
        // El producto NO EXISTE en el carrito (primera vez)

        // VALIDACIÓN 3: Verificar que la cantidad solicitada no exceda el stock
        if (quantity > product.stock) {
            toast.error(
                `Solo hay ${product.stock} unidad(es) disponibles de ${product.name}`
            );
            console.log(`⚠️ Cantidad solicitada excede stock: ${product.name}`);
            return false;
        }

        // Si pasa la validación, agregar como nuevo item
        cartItems.value.push({
            ...product,
            quantity,
        });
        toast.success(`${product.name} agregado al carrito`);
        console.log(`✨ Producto agregado: ${product.name}`);
    }

    // Abrir el drawer del carrito
    isCartOpen.value = true;
    return true;
}

/**
 * FUNCIÓN: Eliminar producto del carrito
 *
 * @param productId - ID del producto a eliminar
 */
function removeFromCart(productId: number | string) {
    const index = cartItems.value.findIndex(item => item.id === productId);

    if (index !== -1) {
        const productName = cartItems.value[index].name;
        cartItems.value.splice(index, 1);
        toast.info(`${productName} eliminado del carrito`);
        console.log(`🗑️ Producto eliminado: ${productName}`);
    }
}

/**
 * FUNCIÓN: Actualizar cantidad de un producto (con validación de stock)
 *
 * @param productId - ID del producto
 * @param quantity - Nueva cantidad
 */
function updateQuantity(productId: number | string, quantity: number) {
    const item = cartItems.value.find(item => item.id === productId);

    if (item) {
        if (quantity <= 0) {
            // Si la cantidad es 0 o menos, eliminar el producto
            removeFromCart(productId);
        } else {
            // VALIDACIÓN: Verificar que no exceda el stock
            if (quantity > item.stock) {
                toast.warning(
                    `Solo hay ${item.stock} unidad(es) disponibles de ${item.name}`
                );
                // Establecer la cantidad al máximo disponible
                item.quantity = item.stock;
                console.log(`⚠️ Cantidad ajustada al stock máximo: ${item.name} (${item.stock})`);
            } else {
                item.quantity = quantity;
                console.log(`🔄 Cantidad actualizada: ${item.name} (${quantity})`);
            }
        }
    }
}

/**
 * FUNCIÓN: Vaciar todo el carrito
 */
function clearCart() {
    const itemCount = cartItems.value.length;
    cartItems.value = [];
    toast.success(`Carrito vaciado (${itemCount} producto${itemCount !== 1 ? 's' : ''} eliminado${itemCount !== 1 ? 's' : ''})`);
    console.log('🧹 Carrito vaciado');
}

/**
 * FUNCIÓN: Abrir/cerrar el drawer del carrito
 */
function toggleCart() {
    isCartOpen.value = !isCartOpen.value;
}

// Cargar el carrito al iniciar (solo una vez)
loadCartFromStorage();

/**
 * EXPORTAR: Funciones y estado para usar en componentes
 */
export function useCart() {
    return {
        // Estado
        cart,
        cartItems,
        isCartOpen,
        total,
        itemCount,

        // Funciones
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        toggleCart,
    };
}
