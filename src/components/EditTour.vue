<template>
  <div id="editTour">
    <div class="o-background">

      <transition name="fade">
        <Delete
          v-show="flag.delete"
          :spot_info="spot_info_selected"
          :tour_info="tour_info"
          @close_modal="close_modal"
          ></Delete>
      </transition>

      <Create
        v-bind:class="{ slideIn: flag.create, slideOut: !flag.create }"
        :tour_info="tour_info"
        @close_modal="close_modal"
      ></Create>

      <ChangeName
        v-bind:class="{ slideIn: flag.change_name, slideOut: !flag.change_name }"
        :spot_info="spot_info_selected"
        :tour_info="tour_info"
        @close_modal="close_modal"
      ></ChangeName>
      

      <div class="l-header_above">
        <div class="o-text_tour">Spot</div>
        <div class="o-image_image_button ">
          <img 
            class="u_pointer"
            v-on:click="start_sort()"
            v-show="!flag.order_start" 
            src="../assets/sort_button.svg" 
          />
          <img 
            class="u_pointer"
            v-on:click="start_sort()"
            v-show="flag.order_start"
            src="../assets/sort_button_active.svg"
          />
        </div>
      </div>
      <div class="l-header_under u-mb20">
        <div class="o-text_tour_min"><span class="u-color-green">{{tour_info.tour_name}}</span></div>
        <div class="o-text_add_image" v-bind:style="{ color: return_button_color()}">並べ替え</div>
      </div>

      <List
        v-show="!flag.order_start"
        :spot_info="spot_info"
        @delete_spot="delete_spot"
        @change_name="change_name"
        @move_page="move_page"
      ></List>

      <ListDrag
        v-show="flag.order_start"
        :spot_info="spot_info"
      ></ListDrag>

      <button 
        class="o-button_save_sort u_pointer" 
        v-on:click="start_sort()" 
        v-show="flag.order_start"
      >並び替えを終了する</button>

      <button 
        class="o-button_create_geosite u_pointer"
        v-show="!flag.order_start"
        @click="create_spot()"
      >新しくジオサイトを登録する</button>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import List from '../components/Edit_Tour/List'
import ListDrag from '../components/Edit_Tour/ListDrag'
import Delete from '../components/Edit_Tour/Delete'
import Create from '../components/Edit_Tour/CreateSpot'
import ChangeName from '../components/Edit_Tour/ChangeName'

  export default {
    name: 'editTour',
    data() {
      return {
        tour_info: '',
        spot_info: [],
        spot_info_selected: '',
        flag: {
          delete: false,
          order_start: false,
          create: false,
          change_name: false,
        }
      }
    },
    created() {
      this.init()
    },
    methods: {
      init() {


        if(JSON.stringify(this.$route.params) != "{}") {
          //ローカルストレージの初期化
          this.$localStorage.remove('now_tour_info');
          this.$localStorage.remove('now_spot_info');
          
          this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info));
          this.tour_info = this.$route.params.tour_info;
        } else {
          this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        }
        this.fetch_spot_info();
      },
      fetch_spot_info: function () {
        const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Tour/fetch_spot_info.php';
        let params = new URLSearchParams();
        params.append('tour_id', this.tour_info.tour_id);
        axios.post(url, params
        ).then(response => {
          this.spot_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      move_page: function(where, spot_info) {
        console.log(spot_info)
          this.$router.push({
              name: where,
              params: {
                tour_info: this.tour_info,
                spot_info: spot_info
              }
          });
      },
      close_modal: function() {
        this.flag.delete = false;
        this.flag.create = false;
        this.flag.change_name = false;
        this.init()
      },
      delete_spot(spot_info) {
        this.spot_info_selected = spot_info;
        this.flag.delete = true;
      },
      start_sort: function() {
        if(this.flag.order_start) {
          this.flag.order_start = false;
        } else {
          this.flag.order_start = true;
        }
        this.close_modal();
      },
      create_spot() {
        this.flag.create = true;
      },
      change_name(spot_info) {
        this.spot_info_selected = spot_info;
        this.flag.change_name = true;
      },
      return_button_color: function() {
        if(this.flag_order) {
          return '#4B8E8D';
        } else {
          return 'rgba(0,0,0,.26)';
        }
      }
    },
    components: {
      List: List,
      ListDrag: ListDrag,
      Delete: Delete,
      Create: Create,
      ChangeName: ChangeName,
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #editTour {
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);

    position: fixed;
    overflow: hidden;
  }

  .o-background {
    height: 100%;
    width: 100%;
    overflow: scroll;
  }

  .l-header_above {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-end;

    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

    .o-text_tour {
      padding: 20px 0 0 20px;

      font-size: 36px;
      font-weight: bold;
    }

    .o-image_image_button {
      padding: 0 20px 0 20px;
    }

    .o-button_sort {
      fill: #4B8E8D;
    }

  .l-header_under {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: center;
  }

    .o-text_tour_min {
      padding: 0 0 0 20px;

      font-size: 14px;
      font-weight: bold;
    }

    .o-text_add_image {
      padding: 0 15px 0 0;

      font-size: 12px;
      font-weight: bold;
      color: rgba(0,0,0, .26);
    }

  .o-image_circle {
    height: 50px;
    width: 50px;
    border-radius: 100px;
    object-fit: cover;
    border: solid 1px rgba(0,0,0, .12);
  }

  .o-list {
    padding: 20px 0 0 20px;
    width: calc(100% - 80px);
  }

  .o-list:last-of-type {
    margin-bottom: 100px;
  }

  .l-image_text_burger {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .o-burger {
    margin-right: 20px;
  }
  
  .l-image_text {
    display: flex;
  }

  .l-list_text {
    margin: 0 0 0 10px;
  }

  .o-list_text_geosite {
    font-size: 18px;
    font-weight: bold;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-list_text_update {
    font-size: 12px;
    color: rgba(0,0,0, .26);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-border {
    height: 1px;
    width: calc(100vw - 100px);
    background-color: rgba(0,0,0, .12);
  }

  .u-mt10 {
    margin-top: 10px;
  }

  .u-mb20 {
    margin-bottom: 20px;
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .54);

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-modal {
    width: 240px;
    border-radius: 30px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
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

  .o-button_save_sort:active, .o-button_create_geosite:acitve {
    opacity: .7;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
