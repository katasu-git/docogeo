import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import EditTour from '@/components/editTour'
import EditSpot from '@/components/editSpot'
import ChatGuide from '@/components/chat_g'
import TopU from '@/components/top_u'
import Images from '@/components/Images.vue'
import Camera from '@/components/Camera'
import CallCanvas from '@/components/CallCanvas'
import Maps from '@/components/Map'

import UserCamera from '@/components/UserCamera'
import UserMap from '@/components/UserMap'
import ChatUser from '@/components/chat_u'

import Location from '@/components/Location'

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
      path: '/imaegs',
      name: 'images',
      component: Images
    },
    {
      path: '/camera',
      name: 'camera',
      component: Camera
    },
    {
      path: '/draw',
      name: 'draw',
      component: CallCanvas
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
    {
      path: '/userCamera',
      name: 'userCamera',
      component: UserCamera
    },
    {
      path: '/userMap',
      name: 'userMap',
      component: UserMap
    },
    {
      path: '/maps',
      name: 'maps',
      component: Maps
    },
    {
      path: '/location',
      name: 'location',
      component: Location
    },
  ]
})
