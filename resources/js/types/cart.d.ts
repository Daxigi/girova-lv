/**
 * TIPOS DEL CARRITO DE COMPRAS
 *
 * Estos tipos definen la estructura de datos que usaremos
 * para el sistema de carrito.
 */

/**
 * Producto en el carrito
 *
 * Representa un producto individual que el usuario agregó al carrito.
 * Incluye la información del producto Y la cantidad seleccionada.
 */
export interface CartItem {
    id: number | string;      // ID único del producto
    name: string;             // Nombre del producto
    price: number;            // Precio unitario
    image_url: string;        // URL de la imagen
    quantity: number;         // Cantidad seleccionada por el usuario
    stock: number;            // Stock disponible del producto
    description?: string;     // Descripción (opcional)
}

/**
 * Estado completo del carrito
 *
 * Contiene toda la información del carrito de compras
 */
export interface Cart {
    items: CartItem[];        // Array de productos en el carrito
    total: number;            // Total a pagar (calculado)
    itemCount: number;        // Cantidad total de productos
}
