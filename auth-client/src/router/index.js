import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../pages/HomePage.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "HomePage",
        component: Home,
    },
    {
        path: "/callback",
        name: "OAuthCallbackHandler",
        component: () => import("../pages/OAuthCallbackHandler.vue"),
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
});

export default router;
