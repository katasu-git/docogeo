<template>
  <div class="hello">
    <button v-on:click="accessDb()">hi!</button>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: "hello!"
    }
  },
  methods: {
    accessDb: function() {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/api.php').then(response => {
        console.log(response.data.tour_name);
        console.log(response.data.group_name);
      }).catch(error => {
        // エラーを受け取る
        console.log(error);
      });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>