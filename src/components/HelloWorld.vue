<template>
  <div id="hello">
    <div class="o-background">

      <TopCreateTour
      @closeModal="closeModal"
        v-show="flag_create_tour"></TopCreateTour>

      <div class="l-header_above">
        <div class="o-text_tour">Tour</div>
        <div 
          class="o-image_image_button"
          v-on:click='jumpPage("images")'
        ><img src="../assets/image_button.svg" /></div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">ツアー</div>
        <div class="o-text_add_image">画像をみる</div>
      </div>
      <div class="o-slider">
        <div class="o-card" v-for="info in tour_info" 
            v-on:click='jumpPage("editTour", info.tour_id, info.tour_name)' :key="info.tour_id">
          <img src="../assets/sample.jpg" class="o-image_tour" />
          <div class="o-transparent">
            <div class="o-text_tour_title">{{ info.tour_name }}</div>
            <div class="o-text_update">最終更新 2019.11.7</div>
          </div>
        </div>
      </div>

      <button class="o-button_create_geosite"
        v-show="!flag_create_tour"
        @click="wakeCreateTour()">新しくツアーを作成する</button>

    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import TopCreateTour from '../components/modals/topCreateTour'
  export default {
    name: 'HelloWorld',
    data() {
      return {
        tour_info: JSON,
        flag_create_tour: false,
      }
    },
    created: function () {
      this.get_tour();
    },
    methods: {
      get_tour: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
        ).then(response => {
          this.tour_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where, tour_id, tour_name) {
        //console.log(this.avoidParam.tour_id);
        this.$router.push({
            name: where,
            params: {
              tour_id: tour_id,
              tour_name: tour_name,
            }
        })
      },
      wakeCreateTour: function() {
        this.flag_create_tour = true;
      },
      closeModal: function() {
        this.flag_create_tour = false;
        this.get_tour(); //更新を反映
      },
    },
    components: {
      TopCreateTour: TopCreateTour,
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
    align-items: center;
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
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-card {
    padding: 0 0 0 20px;
    position: relative;
    height: 330px;
    width: 240px;
  }

  .o-card:last-of-type {
    padding: 0 20px 0 20px;
  }

    .o-image_tour {
      height: 330px;
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

      .o-button_save_sort, .o-button_create_geosite {
        position: fixed;
        bottom: 20px;
        left: 20px;
        height: 50px;
        width: calc(100% - 40px);
        border: solid 2px #4B8E8D;
        border-radius: 10px;
        background-color: #fff;
        color: #4B8E8D;
        font-size: 12px;
        font-weight: bold;
      }

      .o-button_create_geosite {
        background-color: #4B8E8D;
        color: #fff;
      }

</style>
