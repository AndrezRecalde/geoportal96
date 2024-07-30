
const routes = [
  {
    name: 'index',
    path: '/',
    props:{default: true},
    component: require('../../../components/web/Index.vue').default
  },
  {
    name: 'dashboard',
    path: '/dashboard',
    props:{default: true},
    component: require('../../../components/dashboard/Index.vue').default
  },
  {
    name: 'proyectos',
    path: '/dashboard/proyectos',
    props:{default: true},
    component: require('../../../components/dashboard/proyecto/Index.vue').default
  },
  {
    name: 'login',
    path: '/login',
    props:{default: true},
    component: require('../../../components/dashboard/login/Index.vue').default
  },
  
];


export default routes;