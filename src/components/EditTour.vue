<template>
  <div id="editTour">
    <div class="l-body">
      <ChangeTourNameModal v-show="modalFlag"
        @closeModal="closeModal" @get_tour_name="get_tour_name" 
        :tour_id="Number(tour_id)" :tour_name="tour_name"></ChangeTourNameModal>
        <button class="o-backBtn" v-on:click='jumpPage("HelloWorld")'>
            ツアーの選択に戻る
        </button>
      <div class="l-justify-center">
        <div class="l-tour_info">
            <div class="o-tour_name-text" v-on:click="changeTourName()">{{ tour_name }}</div>
            <div class="o-group_name-text">{{ group_name }}</div>
        </div>
      </div>
      <div class="l-justify-center">
        <div class="l-cardContainer">
          <div class="o-card" v-for="info in spot_info" v-on:click='jumpPage("editSpot", info.spot_id, info.spot_name)'>
            <div class="o-card-img"></div>
            <div class="o-card-title">{{ info.spot_id }}:{{ info.spot_name }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import ChangeTourNameModal from "./ChangeTourNameModal";
  export default {
    name: 'editTour',
    data() {
      return {
          spot_info: JSON,
          tour_id: Number,
          tour_name: 'ツアー名を入力',
          group_name: 'グループ名を入力',
          modalFlag: false,
      }
    },
    created: function () {
      if(JSON.stringify(this.$route.params) == "{}") {
        // 更新されたときはトップに戻る
        this.jumpPage("HelloWorld");
      } else {
        this.tour_id = this.$route.params.tour_id;
        if(this.$route.params.tour_name != undefined) {
          this.tour_name = this.$route.params.tour_name;
        }
        if(this.$route.params.group_name != undefined) {
          this.group_name = this.$route.params.group_name;
        }
        this.accessDb();
      }
    },
    methods: {
      accessDb: function () {
        const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php';
        let params = new URLSearchParams();
        params.append('tour_id', this.tour_id);
        axios.post(url, params
        ).then(response => {
          this.spot_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      get_tour_name: function() {
        const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php';
        //let params = new URLSearchParams();
        //params.append('tour_id', this.tour_id);
        axios.post(url
        ).then(response => {
          if(response.data[this.tour_id - 1].tour_name != undefined) {
            this.tour_name = response.data[this.tour_id - 1].tour_name;
            console.log(response.data);
          }
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where, spot_id, spot_name) {
        this.$router.push({
            name: where,
            params: {
              tour_id: this.tour_id,
              spot_id: spot_id,
              spot_name: spot_name
            }
        })
      },
      changeTourName: function() {
        this.modalFlag = true;
      },
      closeModal: function() {
        this.accessDb()
        this.modalFlag = false;
      }
    },
    components: {
      ChangeTourNameModal: ChangeTourNameModal,
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

  .l-tour_info {
    padding: 40px;
  
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
