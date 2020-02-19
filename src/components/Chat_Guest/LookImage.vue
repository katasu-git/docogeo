<template>
  <div id="lookImage">
      <div 
        @click="close_modal()"
        class="close">×</div>
      <img 
        :src="src"
        class="image"
      />
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'lookImage',
    props: {
        src: String
    },
    methods: {
        close_modal() {
            this.$emit('close_modal');
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#lookImage {
    position: fixed;
    z-index: 2;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.70);

    display: flex;
    justify-content: center;
    align-items: center;
}

.image {
    width: 100%;
    max-height: calc(100% - 60px);
}

.close {
    background-color: #fff;
    width: 50px;
    height: 50px;
    position: absolute;
    top: 5px;
    right: 10px;
    font-size: 40px;
    font-weight: bold;
    border-radius: 100%;
    color: rgba(0,0,0,.87);

    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
