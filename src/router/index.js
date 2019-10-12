import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import EditTour from '@/components/editTour'
import EditSpot from '@/components/editSpot'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
    },
    {
      path: '/editTour',
      name: 'editTour',
      component: EditTour
    },
    {
      path: '/editSpot',
      name: 'editSpot',
      component: EditSpot
    },
  ]
})
