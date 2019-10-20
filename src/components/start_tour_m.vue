<template>
  <div id="start_tour_m">
    <div class="l-body">
        <button v-on:click='jumpPage("chat_g")'>ツアーを開始する</button>
        <button v-on:click='jumpPage("editTour")'>ツアーを編集する</button>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'start_tour_m',
    props: {
        //tour_id: Number, //親から受け取り
    },
    data() {
      return {
      }
    },
    created: function () {
        //this.tour_name_updated = this.tour_name;
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        jumpPage: function(where) {
            this.$emit('jumpPage', where);
        },
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#addNewTourModal, .l-body {
    height: 100%;
    width: 100%;
    position: fixed;
}

.l-body {
    background: rgba(0,0,0,.85);
}

button {
    color: white;
}



</style>
