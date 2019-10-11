<template>
  <div id="hello">
    <div class="l-body">
      <div class="l-logo">
        <div class="o-logo-text">
          <div class="o-logo-text-l">DoCoGeo</div>
          <div class="o-logo-text-s">for Guide</div>
        </div>
        <div class="o-logo-img">
          <img src="../assets/duck.svg" />
        </div>
      </div>
      <div class="l-justify-center">
        <div class="l-cardContainer">
          <div class="o-card" v-for="info in tour_info">
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
  export default {
    name: 'HelloWorld',
    data() {
      return {
        msg: "hello!",
        numOfCard: 5,
        tour_info: JSON,
      }
    },
    created: function () {
      this.accessDb();
    },
    methods: {
      accessDb: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/api.php').then(response => {
          this.tour_info = response.data;
          console.log(this.tour_info);
          //console.log(response.data);
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
  #hello,
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
    height: calc( (50vw - 25px)/5*3 );
    width: 100%;

    background-color: #c9c9c9;
  }

  .o-card-title {
    font-size: 12px;
    /*font-weight: bold;*/
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
