import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();

import { createApp } from "vue";
import ExampleComponent from "./components/ExampleComponent.vue";

const app = createApp({});

// Register components
app.component("example-component", ExampleComponent);

// Mount the app
app.mount("#app");
