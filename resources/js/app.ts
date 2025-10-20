import '../css/app.css';

import AppLayout from '@/layouts/AppLayout.vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import vuetify from './plugins/vuetify';

// Vue Toastification - Sistema de notificaciones
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        );
        page.default.layout ??= AppLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(Toast, {
                // Configuración de las notificaciones
                position: 'top-right',           // Posición en la pantalla
                timeout: 3000,                   // Duración en ms (3 segundos)
                closeOnClick: true,              // Cerrar al hacer click
                pauseOnFocusLoss: true,         // Pausar cuando pierdes el foco
                pauseOnHover: true,             // Pausar cuando pasas el mouse
                draggable: true,                // Arrastrar para cerrar
                draggablePercent: 0.6,          // % necesario para cerrar
                showCloseButtonOnHover: false,  // Mostrar X al pasar mouse
                hideProgressBar: false,         // Mostrar barra de progreso
                closeButton: 'button',          // Tipo de botón cerrar
                icon: true,                     // Mostrar icono
                rtl: false,                     // Right to left (árabe, hebreo)
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
