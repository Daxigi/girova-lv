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
          primary: '#000000',       
          secondary: '#424242',      
          accent: '#757575',         
          error: '#212121',
          info: '#9E9E9E',          
          success: '#616161',
          warning: '#424242',        
          background: '#FFFFFF',    
          surface: '#F5F5F5',
        },
      },
    },
  },
});

export default vuetify;
