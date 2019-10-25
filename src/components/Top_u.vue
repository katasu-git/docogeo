<template>
  <div id="top_u">
    <div class="l-body">
        
      <div class="l-logo">
        <div class="o-logo-text">
          <div class="o-logo-text-l">DoCoGeo</div>
          <div class="o-logo-text-s">for User</div>
        </div>
        <div class="o-logo-img">
          <img src="../assets/duck.svg" />
        </div>
      </div>
      <div class="">開催中のツアー</div>
      <div class="l-justify-center">
        <div class="l-cardContainer">
          <div class="o-card" v-for="info in tour_info" v-on:click='jumpPage("chat_u", info.tour_id)'>
            <div class="o-card-img"></div>
            <div class="o-card-title">{{ info.tour_name }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import AddNewTourModal from './addNewTourModal'
  import StartTourM from './start_tour_m'
  export default {
    name: 'top_u',
    data() {
      return {
        tour_info: [],
      }
    },
    created: function () {
      this.get_tour_info();
    },
    methods: {
      get_tour_info: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
        ).then(response => {
            for(let i=0; i<response.data.length; i++) {
                if(response.data[i].isActive == 1) {
                    this.tour_info.push(response.data[i]);
                }
            }
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where, tour_id) {
        this.$router.push({
            name: where,
            params: {
              tour_id: tour_id
            }
        })
      },
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #top_u,
  .l-body {
    height: 100%;
    width: 100%;
  }

  .l-body {
    background: #5c9982;
  }

  .l-justify-center {
    display: flex;
    justify-content: center;
    background: #5c9982;
    /*中央よせ*/
  }

  .l-cardContainer {
    width: calc(100% - 40px);

    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    /*折り返しの指定*/
  }

  .o-card, .o-card-create {
    min-height: calc(50vw - 25px);
    min-width: calc(50vw - 25px);

    border: solid 0 white;
    border-radius: 5px;
    background: white;
  }

  .o-card:nth-child(n+3) {
    margin-top: 10px;
  }

  .o-card-img {
    height: calc( (50vw - 25px)/5*3 );
    width: 100%;

    border-radius: 5px 5px 0 0;
    background-color: #c9c9c9;
  }

  .o-card-title {
    font-size: 12px;
    /*font-weight: bold;*/
  }

  .l-card-create-text {
    height: 100%;
    width: 100%;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-card-create {
    border: dashed 1px white;
    background-color: rgba(0,0,0,0);
    color: white;
    font-size: 12px;
  }

  .l-logo {
    padding: 20px;
    display: flex;
  }

  .o-logo-text {
    color: white;
  }

  .o-logo-text-l {
    font-size: 36px;
    font-weight: bold;
  }

  .o-logo-text-s {
    font-size: 12px;
    font-weight: bold;
  }

  .o-logo-img>img {
    height: 60px;
  }

</style>
