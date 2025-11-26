import { ref, computed, watch } from 'vue';
import type { CartItem, Cart } from '../types/cart';
import { useToast } from './useToast';

// CLAVE para guardar en localStorage
const CART_STORAGE_KEY = 'laravue_cart';

const toast = useToast();


const cartItems = ref<CartItem[]>([]);

const isCartOpen = ref(false);

function loadCartFromStorage() {
    try {
        const savedCart = localStorage.getItem(CART_STORAGE_KEY);

        if (savedCart) {
            cartItems.value = JSON.parse(savedCart);
            console.log('✅ Carrito cargado desde localStorage:', cartItems.value);
        }
    } catch (error) {
        console.error('❌ Error al cargar carrito:', error);
        cartItems.value = [];
    }
}

function saveCartToStorage() {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems.value));
        console.log('💾 Carrito guardado en localStorage');
    } catch (error) {
        console.error('❌ Error al guardar carrito:', error);
    }
}

watch(cartItems, saveCartToStorage, { deep: true });

const total = computed(() => {
    return cartItems.value.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
    }, 0);
});

const itemCount = computed(() => {
    return cartItems.value.reduce((count, item) => {
        return count + item.quantity;
    }, 0);
});

const cart = computed<Cart>(() => ({
    items: cartItems.value,
    total: total.value,
    itemCount: itemCount.value,
}));

function addToCart(product: Omit<CartItem, 'quantity'>, quantity: number = 1): boolean {
    if (product.stock <= 0) {
        toast.error(`${product.name} no tiene stock disponible`);
        console.log(`❌ Sin stock: ${product.name}`);
        return false;
    }

    const existingItemIndex = cartItems.value.findIndex(item => item.id === product.id);

    if (existingItemIndex !== -1) {
        const existingItem = cartItems.value[existingItemIndex];
        const newQuantity = existingItem.quantity + quantity;

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

        existingItem.quantity = newQuantity;
        toast.success(`Cantidad actualizada: ${existingItem.quantity}x ${product.name}`);
        console.log(`➕ Cantidad aumentada: ${product.name} (${existingItem.quantity})`);
    } else {

        if (quantity > product.stock) {
            toast.error(
                `Solo hay ${product.stock} unidad(es) disponibles de ${product.name}`
            );
            console.log(`⚠️ Cantidad solicitada excede stock: ${product.name}`);
            return false;
        }

        cartItems.value.push({
            ...product,
            quantity,
        });
        toast.success(`${product.name} agregado al carrito`);
        console.log(`✨ Producto agregado: ${product.name}`);
    }

    isCartOpen.value = true;
    return true;
}

function removeFromCart(productId: number | string) {
    const index = cartItems.value.findIndex(item => item.id === productId);

    if (index !== -1) {
        const productName = cartItems.value[index].name;
        cartItems.value.splice(index, 1);
        toast.info(`${productName} eliminado del carrito`);
        console.log(`🗑️ Producto eliminado: ${productName}`);
    }
}

function updateQuantity(productId: number | string, quantity: number) {
    const item = cartItems.value.find(item => item.id === productId);

    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            if (quantity > item.stock) {
                toast.warning(
                    `Solo hay ${item.stock} unidad(es) disponibles de ${item.name}`
                );
                item.quantity = item.stock;
                console.log(`⚠️ Cantidad ajustada al stock máximo: ${item.name} (${item.stock})`);
            } else {
                item.quantity = quantity;
                console.log(`🔄 Cantidad actualizada: ${item.name} (${quantity})`);
            }
        }
    }
}

function clearCart() {
    const itemCount = cartItems.value.length;
    cartItems.value = [];
    toast.success(`Carrito vaciado (${itemCount} producto${itemCount !== 1 ? 's' : ''} eliminado${itemCount !== 1 ? 's' : ''})`);
    console.log('🧹 Carrito vaciado');
}

function toggleCart() {
    isCartOpen.value = !isCartOpen.value;
}

loadCartFromStorage();

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
