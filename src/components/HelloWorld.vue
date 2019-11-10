<template>
  <div id="hello">
    <div class="o-background">
      <div class="l-header_above">
        <div class="o-text_tour">Tour</div>
        <div class="o-image_image_button"><img src="../assets/image_button.svg" /></div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">ツアー</div>
        <div class="o-text_add_image">画像を登録</div>
      </div>
      <div class="o-slider">
        <div class="o-card" v-for="num in card_num">
          <img src="../assets/sample.jpg" class="o-image_tour" />
          <div class="o-transparent">
            <div class="o-text_tour_title">ここにツアー名</div>
            <div class="o-text_update">最終更新 2019.11.7</div>
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
    name: 'HelloWorld',
    data() {
      return {
        tour_info: JSON,
        modalFlag: false,
        s_modalFlag: false,
        avoidParam: JSON,
        card_num: 3,
      }
    },
    created: function () {
      this.accessDb();
    },
    methods: {
      accessDb: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
        ).then(response => {
          this.tour_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where) {
        //console.log(this.avoidParam.tour_id);
        this.$router.push({
            name: where,
            params: {
              tour_id: this.avoidParam.tour_id,
              spot_id: 1, //1から順番に表示する
              tour_name: this.avoidParam.tour_name,
              group_name: this.avoidParam.group_name,
            }
        })
      },
      closeModal: function() {
        this.accessDb();
        this.modalFlag = false;
        this.s_modalFlag = false;
      },
      addNewTour: function() {
        this.modalFlag = true;
      },
      start_tour: function(param) {
        this.avoidParam = param;
        this.s_modalFlag = true;
      }
    },
    components: {
      AddNewTourModal: AddNewTourModal,
      StartTourM: StartTourM,
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #hello, .o-background {
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
  }

  .l-header_above {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }

    .o-text_tour {
      padding: 20px 0 0 20px;

      font-size: 36px;
      font-weight: bold;
    }

    .o-image_image_button {
      padding: 0 20px 0 20px;
    }

  .l-header_under {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }

    .o-text_tour_min {
      padding: 0 0 0 20px;

      font-size: 18px;
      font-weight: bold;
    }

    .o-text_add_image {
      padding: 0 10px 0 0;

      font-size: 12px;
      font-weight: bold;
      color: rgba(0,0,0, .26);
    }

  .o-slider {
    padding: 40px 0 0 0;
    display: flex;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-card {
    padding: 0 0 0 20px;
    position: relative;
    height: 380px;
    width: 240px;
  }

    .o-image_tour {
      height: 380px;
      width: 240px;
      border-radius: 30px;
      object-fit: cover;
    }

    .o-transparent {
      height: 80px;
      width: 240px;
      position: absolute;
      bottom: 0;
      border-radius: 0 0 30px 30px;
      background-color: rgba(0,0,0,.5);
    }

      .o-text_tour_title {
        padding: 20px 0 0 20px;

        font-size: 18px;
        font-weight: bold;
        color: #fff;
      }

      .o-text_update {
        padding: 0 0 0 20px;

        font-size: 14px;
        color: rgba(255,255,255,.70);
      }

</style>
