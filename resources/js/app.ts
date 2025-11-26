import '../css/app.css';

import AppLayout from '@/layouts/AppLayout.vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import vuetify from './plugins/vuetify';

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
                
                position: 'top-right',          
                timeout: 3000,                  
                closeOnClick: true,             
                pauseOnFocusLoss: true,         
                pauseOnHover: true,             
                draggable: true,                
                draggablePercent: 0.6,          
                showCloseButtonOnHover: false,  
                hideProgressBar: false,         
                closeButton: 'button',          
                icon: true,                     
                rtl: false,                     
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
