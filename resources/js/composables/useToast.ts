/**
 * COMPOSABLE DE NOTIFICACIONES - useToast
 *
 * Proporciona funciones simples para mostrar notificaciones toast
 * en la aplicación usando Vue Toastification.
 *
 * Tipos de notificaciones:
 * - success: Acciones exitosas (verde)
 * - error: Errores (rojo)
 * - warning: Advertencias (amarillo)
 * - info: Información (azul)
 *
 * Uso:
 * const toast = useToast();
 * toast.success('¡Producto agregado!');
 */

import { useToast as useToastification } from 'vue-toastification';

/**
 * Hook para usar notificaciones toast
 *
 * @returns Objeto con funciones para mostrar notificaciones
 */
export function useToast() {
    // Obtener la instancia de toast de Vue Toastification
    const toast = useToastification();

    return {
        /**
         * Mostrar notificación de éxito (verde)
         *
         * @param message - Mensaje a mostrar
         *
         * Ejemplo:
         * toast.success('¡Producto agregado al carrito!');
         */
        success: (message: string) => {
            toast.success(message, {
                // Opciones específicas para success (opcional)
            });
        },

        /**
         * Mostrar notificación de error (rojo)
         *
         * @param message - Mensaje a mostrar
         *
         * Ejemplo:
         * toast.error('No hay suficiente stock disponible');
         */
        error: (message: string) => {
            toast.error(message, {
                timeout: 4000, // Los errores duran un poco más
            });
        },

        /**
         * Mostrar notificación de advertencia (amarillo)
         *
         * @param message - Mensaje a mostrar
         *
         * Ejemplo:
         * toast.warning('Stock limitado: solo quedan 2 unidades');
         */
        warning: (message: string) => {
            toast.warning(message);
        },

        /**
         * Mostrar notificación informativa (azul)
         *
         * @param message - Mensaje a mostrar
         *
         * Ejemplo:
         * toast.info('Tu carrito ha sido actualizado');
         */
        info: (message: string) => {
            toast.info(message);
        },

        /**
         * Limpiar todas las notificaciones visibles
         *
         * Útil antes de mostrar una nueva notificación importante
         */
        clear: () => {
            toast.clear();
        },
    };
}
