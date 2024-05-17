import { createMemoryHistory, createRouter } from 'vue-router';

import Login from '../pages/Login.vue';
import Dashboard from '../pages/Dashboard.vue'; // Make sure the path is correct

const routes = [
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
    },
    {
        path: '/',
        component: Login,
    },

];

const router = createRouter({
  history: createMemoryHistory(),
  routes,
});

export default router;
