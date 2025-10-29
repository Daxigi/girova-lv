import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
  theme: {
    defaultTheme: 'girovaTheme',
    themes: {
      girovaTheme: {
        dark: false,
        colors: {
          primary: '#000000',      // Negro
          secondary: '#424242',    // Gris oscuro
          accent: '#757575',       // Gris medio
          error: '#212121',        // Gris muy oscuro
          info: '#9E9E9E',         // Gris
          success: '#616161',      // Gris oscuro
          warning: '#424242',      // Gris oscuro
          background: '#FFFFFF',   // Blanco
          surface: '#F5F5F5',      // Gris muy claro
        },
      },
    },
  },
});

export default vuetify;
