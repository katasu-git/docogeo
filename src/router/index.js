import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import EditTour from '@/components/editTour'
import EditSpot from '@/components/editSpot'
import ChatGuide from '@/components/chat_g'
import ChatUser from '@/components/chat_u'
import TopU from '@/components/top_u'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
    },
    {
      path: '/top_u',
      name: 'top_u',
      component: TopU
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
    {
      path: '/chat_g',
      name: 'chat_g',
      component: ChatGuide
    },
    {
      path: '/chat_u',
      name: 'chat_u',
      component: ChatUser
    },
  ]
})
