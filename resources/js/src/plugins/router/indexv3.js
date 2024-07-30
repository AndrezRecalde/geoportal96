import { createWebHistory, createRouter } from "vue-router";

const routes = [
  {
    name: 'geraltestvueblade',
    path: '/geral/test/vueblade',
    props:{default: true},
    component: require('../index/App.vue').default
  },
  {
    name: 'consolidadovue',
    path: '/consolidado/vue',
    props:{default: true},
    component: require('../index/Consolidado.vue').default
  },
  {
    name: 'consolidadovue',
    path: '/lotes/comercio',
    props:{default: true},
    component: require('../lotes/LotesComercio.vue').default
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;