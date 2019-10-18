<template>
  <div id="app">
    <transition>
      <router-view/>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
html, body, #app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;

  height: 100%;
  widows: 100%;

  margin: 0;
  padding: 0;
}
a, p, button {
  margin: 0;
  padding: 0;
}
</style>
