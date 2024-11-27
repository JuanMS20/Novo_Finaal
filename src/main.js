import { createApp } from 'vue';
import App from './App.vue';
import router from "./router";
import store from './store';

// Crea la aplicación de Vue
const app = createApp(App);

// Usa el router antes de montar la aplicación
app.use(router);

// Usa el store antes de montar la aplicación
app.use(store);

// Monta la aplicación
app.mount('#app');
