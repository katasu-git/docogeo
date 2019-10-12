<template>
  <div id="editTour">
    <div class="l-body">
        <button class="o-backBtn" v-on:click='jumpPage("HelloWorld")'>
            ツアーの選択に戻る
        </button>
      <div class="l-justify-center">
        <div class="l-both-center">
            <div class="o-tour_name-text">DoCoGeo</div>
            <div class="o-group_name-text">for Guide</div>
        </div>
      </div>
      <div class="l-justify-center">
        <div class="l-cardContainer">
          <div class="o-card" v-for="info in spot_info">
            <div class="o-card-img"></div>
            <div class="o-card-title">{{ info.spot_name }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'editTour',
    data() {
      return {
          spot_info: JSON,
      }
    },
    created: function () {
      this.accessDb();
    },
    methods: {
      accessDb: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php').then(response => {
          this.spot_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where) {
        this.$router.push({
            name: where,
            params: {}
        })
      },
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #editTour,
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
    /*中央よせ*/
  }

  .l-cardContainer {
    width: calc(100% - 40px);

    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    /*折り返しの指定*/
  }

  .o-card {
    min-height: calc(50vw - 25px);
    min-width: calc(50vw - 25px);

    border: solid 0 white;
    border-radius: 3px;
    background: white;
  }

  .o-card:nth-child(n+3) {
    margin-top: 10px;
  }

  .o-card-img {
    height: calc((50vw - 25px)/5*3);
    width: 100%;

    background-color: #c9c9c9;
  }

  .o-card-title {
    font-size: 12px;
    /*font-weight: bold;*/
  }

  .l-both-center {
    display: flex;
    flex-direction: column;
    justify-content: center;
    /*中央よせ*/
    align-items: center;
  }
 
  .o-tour_name-text {
      font-size: 20px;
      font-weight: bold;
      color: white;
  }

  .o-group_name-text {
      font-size: 12px;
      color: white;
  }

  .o-backBtn {
      padding: 20px;
      font-size: 12px;
      color: white;
  }

</style>
