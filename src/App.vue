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

</style>
