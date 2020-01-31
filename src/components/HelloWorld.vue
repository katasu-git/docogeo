<template>
  <div id="hello">
    <div class="o-background">

      <transition name="fade">
        <Delete
          v-show="flag.delete_tour"
          :tour_info="tour_info_selected"
          @close_modal="close_modal"
        ></Delete>
      </transition>

      <transition name="fade">
        <SelectCard
          v-show="flag.select_card"
          :tour_info="tour_info_selected"
          @close_modal="close_modal"
          @move_page="move_page"
        ></SelectCard>
      </transition>

      <ChangeName
        v-bind:class="{ slideIn: flag.change_tour_name, slideOut: !flag.change_tour_name }"
        :tour_info="tour_info_selected"
        @close_modal="close_modal"
      ></ChangeName>

      <transition name="fade">
        <CreateTour
          v-bind:class="{ slideIn: flag.create_tour, slideOut: !flag.create_tour }"
          :tour_info="tour_info_selected"
          @close_modal="close_modal"
        ></CreateTour>
      </transition>

      <div class="l-header_above">
        <div class="o-text_tour">Event</div>
        <div 
          class="o-image_image_button u_pointer"
          v-on:click='move_page("images")'
        ><img src="../assets/image_button.svg" /></div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">イベント</div>
        <div class="o-text_add_image">画像をみる</div>
      </div>

      <div class="o-slider">
        <div
          class="wrapper"
          v-for="info in tour_info"
          :key="info.tour_id"
        >
        <div 
          class="o-card" 
          @click="select_card(info)"
        >
          <img 
            src="../assets/kujira.svg"
            class="o-image_tour u_pointer"
          />
        </div>
        <div class="o-transparent">
            <div 
              @click="change_tour_name(info)"
              class="l_text_pen_icon u_pointer">
              <div class="o-text_tour_title">{{ info.tour_name }}</div>
              <img 
                class="u_ml5"
                src="../assets/pen_update_icon.svg" />
            </div>
            <div class="o-text_update">最終更新 {{return_updated(info.updated)}}</div>
            <img 
              @click="delete_tour(info)"
              class="delete_button u_pointer"
              src="../assets/delete_tour_icon.svg"
            />
          </div>
        </div>
      </div>

      <button class="o-button_create_geosite u_pointer"
        @click="create_tour()"
      >新しくツアーを作成する</button>

      <div class="l-footer">
        <div class="o-text_footer">DoCoGeo for <span class="u-color-green">GUIDE</span></div>
        <div
          @click="move_page('top_u')"
          class="o-text_go u-color-green u_pointer">参加者ページへ</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Delete from '../components/Edit_Event/Delete'
import ChangeName from '../components/Edit_Event/ChangeName'
import CreateTour from '../components/Edit_Event/CreateTour'
import SelectCard from '../components/Edit_Event/SelectCard'
export default {
  name: 'HelloWorld',
  data() {
    return {
      tour_info: '',
      tour_info_selected: '',
      flag: {
        select_card: false,
        create_tour: false,
        change_tour_name: false,
        delete_tour: false
      },
    }
  },
  created () {
    //初期処理
    this.init();
  },
  methods: {
    init() {
      this.fetch_tour_info();
    },
    fetch_tour_info() {
      const url = 'https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Edit_Event/fetch_tour_info.php';
      let params = new URLSearchParams();
      axios.post(url, params
      ).then(response => {
        //返ってきたデータの処理
        this.tour_info = response.data;
      }).catch(error => {
        // エラーを受け取る
        console.log(error);
      });
    },
    move_page: function(where, tour_info) {
      this.$router.push({
          name: where,
          params: {
            tour_info: tour_info,
          }
      })
    },
    close_modal: function() {
      this.flag.delete_tour = false;
      this.flag.change_tour_name = false;
      this.flag.create_tour = false;
      this.flag.select_card = false;
      this.init();
    },
    delete_tour(tour_info) {
      this.tour_info_selected = tour_info;
      this.flag.delete_tour = true;
    },
    select_card(tour_info) {
      this.tour_info_selected = tour_info;
      this.flag.select_card = true;
    },
    change_tour_name(tour_info) {
      this.tour_info_selected = tour_info;
      this.flag.select_card = false;
      this.flag.change_tour_name = true;
    },
    create_tour() {
      this.flag.create_tour = true;
    },
    return_updated(sended) {
          let month = sended.substr(5, 2) + '月';
          let day = sended.substr(8, 2) + '日';
          let time = ' ' + sended.substr(10, 6);
          return month + day + time;
    }
  },
  components: {
    Delete: Delete,
    ChangeName: ChangeName,
    CreateTour: CreateTour,
    SelectCard: SelectCard
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

  .wrapper:last-of-type {
    padding: 0 20px 0 0;
  }

  .o-card {
    padding: 0 0 0 20px;
    position: relative;
    height: calc(100% - 80px);
    width: calc(100vw - 80px);
    max-width: 400px;
  }

    .o-image_tour {
      height: 100%;
      width: calc(100vw - 80px);
      max-width: 400px;
      border-radius: 10px 10px 0 0;
      object-fit: cover;
      border: solid 1px rgba(0,0,0, .12);
      border-bottom: none;
    }

    .o-transparent {
      position: relative;
      margin-left: 20px;
      height: 80px;
      width: calc(100vw - 80px);
      max-width: 400px;
      border-radius: 0 0 10px 10px;
      background-color: rgba(0,0,0,.5);
      border-right: solid 1px rgba(0,0,0, .12);
      border-left: solid 1px rgba(0,0,0, .12);
    }

      .o-text_tour_title {
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

.delete_button {
  position: absolute;
  right: -1px;
  bottom: 0;
}

.l_text_pen_icon {
  display: flex;
  align-items: center;
  width: calc(100% - 80px);
  padding:  20px 0 0 20px;
}

</style>
