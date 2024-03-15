import './bootstrap';
import { createApp } from 'vue';
import router from './src/routes';
import app from './src/app.vue';

createApp(app)
    .use(router) // Add router instance (if using)
    // Add Pinia store (if using)
    .mount('#app');
