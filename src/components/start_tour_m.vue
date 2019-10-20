<template>
  <div id="start_tour_m">
    <div class="l-body">
        <div class="l-img-circle">
            <div class="o-img-circle"></div>
            <div class="o-tour_name u-mt16px">{{ tour_name }}</div>
        </div>
        <div class="l-button">
            <button class="o-btn-prime" v-on:click='jumpPage("chat_g")'>ツアーを開始する</button>
            <button class="o-btn-second u-mt16px" v-on:click='jumpPage("editTour")'>ツアーを編集する</button>
        </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'start_tour_m',
    props: {
        tour_name: String, //親から受け取り
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
    color: white;
}

button {
    color: white;
}

.l-img-circle {
    width: 100%;

    display: flex;
    flex-direction: column;
    /*justify-content: center;*/
    align-items: center;
    padding-top: 100px;
}

.o-img-circle {
    height: 160px;
    width: 160px;

    border-radius: 1000px;
    background: white;
}

.o-tour_name {
    font-size: 18px;
    font-weight: bold;
}

.u-mt16px {
    margin-top: 16px;
}

.l-button {
    width: 100%;

    padding-top: 60px;

    display: flex;
    flex-direction: column;
    align-items: center;
}

.o-btn-prime {
    height: 60px;
    width: calc(100vw - 40px);
    border: solid 0 white;
    border-radius: 100px;
    background-color: #F7941E;

    font-size: 20px;
    font-weight: bold;
}

.o-btn-second {
    height: 60px;
    width: calc(100vw - 40px);
    border: solid 2px white;
    border-radius: 100px;
    background-color: rgba(0,0,0,0);

    font-size: 20px;
    font-weight: bold;
}


</style>
