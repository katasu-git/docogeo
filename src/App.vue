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
</style>
