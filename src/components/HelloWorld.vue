<template>
  <div id="hello">
    <div class="o-background">

      <transition name="fade">
        <TopDelete
          :tour_id="tour_id_avoid"
          :tour_name="tour_name_avoid"
          @closeModal="closeModal"
          v-show="flag_delete"></TopDelete>
      </transition>

      <TopCreateTour
        @closeModal="closeModal"
        v-bind:class="{ slideIn: flag_create_tour, slideOut: !flag_create_tour }"></TopCreateTour>

      <TopChangeName
        @closeModal="closeModal"
        :tour_id="tour_id_avoid"
        :tour_name_avoid="tour_name_avoid"
        v-bind:class="{ slideIn: flag_change_name, slideOut: !flag_change_name }"
      ></TopChangeName>

      <div class="l-header_above">
        <div class="o-text_tour">Event</div>
        <div 
          class="o-image_image_button"
          v-on:click='jumpPage("images")'
        ><img src="../assets/image_button.svg" /></div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">イベント</div>
        <div class="o-text_add_image">画像をみる</div>
      </div>
      <div class="o-slider">
        <div 
          class="o-card" 
          v-for="info in tour_info"
          @click="onPlusStart(info.tour_id, info.tour_name)" 
          :key="info.tour_id"
        >
          <img 
            src="../assets/kujira.svg"
            class="o-image_tour"
          />
          <div class="o-transparent">
            <div class="o-text_tour_title">{{ info.tour_name }}</div>
            <div class="o-text_update">最終更新 {{returnUpdated(info.updated)}}</div>
          </div>
        </div>
      </div>

      <button class="o-button_create_geosite"
        v-show="!flag_create_tour"
        @click="wakeCreateTour()">新しくツアーを作成する</button>

    </div>
    <transition name="fade">
        <TopLongPress
          @conf_delete="conf_delete"
          @closeModal="closeModal"
          @wakeChangeName="wakeChangeName"
          :tour_id="tour_id_avoid"
          :tour_name_avoid="tour_name_avoid"
          v-show="flag_long_press"
        ></TopLongPress>
      </transition>

      <div class="l-footer">
        <div class="o-text_footer">DoCoGeo for <span class="u-color-green">GUIDE</span></div>
        <div
          @click="jumpPage('top_u')"
          class="o-text_go u-color-green">参加者ページへ</div>
      </div>

  </div>
</template>

<script>
  import axios from 'axios'
  import TopCreateTour from '../components/modals/topCreateTour'
  import TopLongPress from '../components/modals/toplongPress'
  import TopChangeName from '../components/modals/topChageName'
  import TopDelete from "../components/modals/topDelete"
  export default {
    name: 'HelloWorld',
    data() {
      return {
        tour_info: JSON,
        flag_create_tour: false,
        flag_long_press: false,
        flag_change_name: false,
        flag_delete: false,
        tour_id_avoid: '',
        tour_name_avoid: '',
      }
    },
    watch: {
      tour_name_avoid() {
        console.log(this.tour_name_avoid)
      }
    },
    created: function () {
      this.get_tour()
    },
    methods: {
      get_tour: function () {
        return new Promise((resolve) => {
          axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
          ).then(response => {
            this.tour_info = response.data;
            resolve()
          }).catch(error => {
            // エラーを受け取る
            console.log(error);
          });
        })
      },
      jumpPage: function(where, tour_id, tour_name) {
        //console.log(this.avoidParam.tour_id);
        this.$router.push({
            name: where,
            params: {
              tour_id: this.tour_id_avoid,
              tour_name: this.tour_name_avoid,
            }
        })
      },
      wakeCreateTour: function() {
        this.closeModal();
        this.flag_create_tour = true;
      },
      wakeChangeName() {
        this.closeModal();
        this.flag_change_name = true;
      },
      closeModal: function() {
        this.flag_create_tour = false;
        this.flag_long_press = false;
        this.flag_change_name = false;
        this.flag_delete = false;
        this.get_tour(); //更新を反映
      },
      onPlusStart(tour_id, tour_name) {
        this.tour_id_avoid = tour_id;
        this.tour_name_avoid = tour_name;
        this.flag_long_press = true;
      },
      conf_delete() {
        this.flag_delete = true;
      },
      returnUpdated(sended) {
            let month = sended.substr(5, 2) + '月';
            let day = sended.substr(8, 2) + '日';
            let time = ' ' + sended.substr(10, 6);
            return month + day + time;
      }
    },
    components: {
      TopCreateTour: TopCreateTour,
      TopLongPress: TopLongPress,
      TopChangeName: TopChangeName,
      TopDelete: TopDelete
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

    position: fixed;
    overflow: hidden;
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
    height: calc(100% - 100px - 90px - 60px);
    padding: 20px 0 0 0;
    display: flex;
    overflow-x: scroll;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-card {
    padding: 0 0 0 20px;
    position: relative;
    height: 100%;
    width: calc(100vw - 80px);
    max-width: 400px;
  }

  .o-card:last-of-type {
    padding: 0 20px 0 20px;
  }

    .o-image_tour {
      height: 100%;
      width: calc(100vw - 80px);
      max-width: 400px;
      border-radius: 30px;
      object-fit: cover;
      border: solid 1px rgba(0,0,0, .12);
    }

    .o-transparent {
      height: 80px;
      width: calc(100vw - 80px);
      max-width: 400px;
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
        bottom: 50px;
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

.l-footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  height: 30px;
  background-color: #fff;

  display: flex;
  align-items: center;
}

.o-text_footer {
  margin-left: 20px; 
  font-size: 12px;
  font-weight: bold;
}

.o-text_go {
  position: absolute;
  right: 20px;
  font-size: 12px;
}

.u-color-green {
  color: #4B8E8D;
}

</style>
