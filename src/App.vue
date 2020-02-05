<template>
  <div id="app">
    <transition>
      <router-view/>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'App',
}
</script>

<style>
html, body, #app {
  font-family: 'Noto Sans JP', sans-serif, 'Avenir', Helvetica, Arial, sans-serif;

  height: 100%;
  widows: 100%;

  background-color: #F5F5F5;

  margin: 0;
  padding: 0;
}
a, p, button {
  margin: 0;
  padding: 0;
}

.kaigyo {
    white-space: pre-line; /*改行の指定に必要*/
}

button, input {
  border: none;
  background-color: rgba(0,0,0,0);
  outline: none;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .25s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

@keyframes slideIn {
  0% {
    display:none;
    transform: translateY(100%);
  }
  100% {
    display:flex;
    transform: translateY(0);
  }
}
.slideIn, .slideOut {
  animation-name: slideIn;
  animation-duration: .2s;
  animation-timing-function: ease;
  animation-fill-mode: forwards;
}
@keyframes slideOut {
    0% {
      display:flex;
      transform: translateY(0);
    }
    100% {
      transform: translateY(100%);
      display:none;
    }
}
.slideOut {
  animation-name: slideOut;
}

@keyframes slideInRight {
  0% {
    display:none;
    transform: translateX(-100%);
  }
  100% {
    display:flex;
    transform: translateX(0);
  }
}
.slideInRight, .slideOutRight {
  animation-name: slideInRight;
  animation-duration: .2s;
  animation-timing-function: ease;
  animation-fill-mode: forwards;
}
@keyframes slideOutRight {
    0% {
      display:flex;
      transform: translateX(0);
    }
    100% {
      transform: translateX(-100%);
      display:none;
    }
}
.slideOutRight {
  animation-name: slideOutRight;
}

.v-lazy-image {
  filter: blur(2px);
  transition: filter 0.2s;
}
.v-lazy-image-loaded {
  filter: blur(0);
}

.u_pointer {
  cursor : pointer;
}

.u_ml5 {
  margin-left: 5px;
}

.u_ml10 {
  margin-left: 10px;
}

.u_ml20 {
  margin-left: 20px;
}

.u-mb20 {
  margin-bottom: 20px;  
} 

.u-mt10 {
  margin-top: 10px;
}

.u-mt20 {
  margin-top: 20px;
}

.u-mt40 {
  margin-top: 40px;
}

.u-color-green {
  color: #4B8E8D;
}

.u-color-red {
  color: #CC544D;
}

.sub-text {
  color: rgba(0,0,0,.54);
  font-size: 14px;
}

</style>
