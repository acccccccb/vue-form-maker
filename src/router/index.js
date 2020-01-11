import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
        path: '/',
        name: 'FormMaker',
        component: () => import('../components/Home/FormMakerMain/FormMaker'),
    },
      {
          path: '/FormMakerList',
          name: 'FormMakerList',
          component: () => import('../components/Home/FormMakerMain/FormMakerList'),
      },
    {
        path: '/FormMakerPublish',
        name: 'FormMakerPublish',
        component: () => import('../components/Home/FormMakerMain/FormMakerPublish'),
    },
  ]
})
