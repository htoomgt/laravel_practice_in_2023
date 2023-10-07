import { createApp } from "vue";
import App from "./App.vue";
import "bulma/css/bulma.min.css";
import router from "./router";
import axios from "axios";
import VueAxios from "vue-axios";

Vue.use(VueRouter);

createApp(App).use(VueAxios, axios).use(router).mount("#app");
