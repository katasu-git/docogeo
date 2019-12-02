<template>
  <div id="editSpot">
    <div class="o-background">

      <ComLongPress v-show="flag_longpress"
        :ex_id_avoid="ex_id_avoid"
        :flag_press_img="flag_press_img"
        @closeModal="closeModal"
        @get_spot_ex="get_spot_ex"
        @changeCom="changeCom"
        @getSpotImage="getSpotImage"
      ></ComLongPress>

      <ComAddComment @closeModal="closeModal"
        :tour_id="tour_id" :spot_id="spot_id"
        v-show="flag_add_com"></ComAddComment>

      <ComChangeCom
        v-show="flag_change_com"
        :ex_id_avoid="ex_id_avoid"
        @closeModal="closeModal"></ComChangeCom>
      
      <div class="l-header_above">
        <div class="o-text_tour">Comment</div>
        <div class="o-image_image_button">
          <img v-on:click="startSort()" v-show="!flag_order" src="../assets/sort_button.svg" />
          <img v-on:click="startSort()" v-show="flag_order" src="../assets/sort_button_active.svg" />
        </div>
      </div>
      <div class="l-header_under u-mb20">
        <div class="o-text_tour_min"><span class="u-color-green">{{spot_name}}</span></div>
        <div class="o-text_add_image" v-bind:style="{ color: returnSortColor()}">並べ替え</div>
      </div>

      <div class="l-slider_images" v-show="!flag_order">
        <button
          class="o-button_add_img"
          @touchend="addImg()">画像を追加する</button>
        <div class="o-image" 
          v-for="image in srcArray"
          :key="image.id"
          @click="onPlusStart(image.id, true)"
        >
          <img class="img" :src="image.imgPath" :alt="image.imgName" />
        </div>
      </div>

      <draggable class="l-slider_images" :animation="150" v-show="flag_order">
        <div class="o-image" 
          v-for="image in srcArray"
          :key="image.id"
        >
          <img class="img" :src="image.imgPath" :alt="image.imgName" />
        </div>
      </draggable>

      <div class="l-border">
        <div class="o-border u-mt20"></div>
      </div>
      
      <div class="l-comment_container">
            <div class="l-comment_row" v-for="ex in spot_ex">
                <div class="l-flex_end">
                    <div
                      class="l-comment"
                      v-long-press="500"
                      @long-press-start="onPlusStart(ex.id, false)"
                    >{{ ex.spot_ex }}</div>
                    <div class="o-send_time">{{returnSended(ex.created)}}</div>
                </div>
            </div>
      </div>

      <draggable @update="update_order_spot_ex()" class="l-comment" v-model="spot_ex" :animation="150" v-show="flag_order">
        <div v-for="ex in spot_ex" :key="ex.id">
        <div class="l-image_text_burger">
            <div class="l-image_text">
              <div class="l-list_text">
                <div class="o-list_text_geosite">{{ ex.spot_ex }}</div>
                <div class="o-list_text_update">2019.11.7</div>
              </div>
            </div>
            <div class="o-burger u-mt20"><img src="../assets/burger_button.svg" /></div>
          </div>
        </div>
      </draggable>

      <button 
        class="o-button_save_sort"
        v-on:click="startSort()" 
        v-show="flag_order && !flag_add_com &&!flag_longpress"
      >並び替えを終了する</button>

      <button class="o-button_create_geosite" v-on:click="addComment()" 
        v-show="!flag_order && !flag_add_com &&!flag_longpress">新しく説明を追加する</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import draggable from 'vuedraggable';
import ComAddComment from '../components/modals/comAddComment'
import ComLongPress from '../components/modals/comLongPress'
import ComChangeCom from '../components/modals/comChangeCom'
export default {
  name: "editSpot",
  data() {
    return {
      tour_id: '',
      spot_id: '',
      tour_name: '',
      spot_name: '',
      spot_ex: [],
      srcArray: [],
      ex_id_avoid: '',
      flag_order: false,
      flag_add_com: false,
      flag_longpress: false,
      flag_change_com: false,
      flag_press_img: false,
    };
  },
  created: function() {
    if (JSON.stringify(this.$route.params) == "{}") {
      // 更新されたときはトップに戻る
      this.jumpPage("editTour");
    } else {
      this.tour_id = this.$route.params.tour_id;
      this.spot_id = this.$route.params.spot_id;
      this.spot_name = this.$route.params.spot_name;
      this.get_spot_ex();
      this.getSpotImage();
    }
  },
  methods: {
    get_spot_ex: function() {
      const url =
        "https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_ex.php";
      let params = new URLSearchParams();
      params.append("spot_id", this.spot_id);
      axios
        .post(url, params)
        .then(response => {
          this.spot_ex = response.data;
        })
        .catch(error => {
          // エラーを受け取る
          console.log(error);
        });
    },
    jumpPage: function(where) {
        this.$router.push({
            name: where,
            params: {
            }
        })
    },
    closeModal: function() {
      setTimeout(() => {
        this.flag_add_com = false;
        this.flag_longpress = false;
        this.flag_change_com = false;
        this.get_spot_ex(); //説明の更新を反映
      }, 200)
    },
    startSort: function() {
        if(this.flag_order) {
          this.flag_order = false;
        } else {
          this.flag_order = true;
        }
    },
    returnSortColor: function() {
        if(this.flag_order) {
          return '#4B8E8D';
        } else {
          return 'rgba(0,0,0,.26)';
        }
    },
    addComment: function() {
      if(this.flag_add_com) {
        this.flag_add_com = false;
      } else {
        this.flag_add_com = true;
      }
    },
    changeCom() {
      //this.closeModal();
      this.flag_change_com = true;
    },
    onPlusStart: function(id, flag_press_img) {
      this.ex_id_avoid = id;  //削除する時の判定に使う
      if(flag_press_img) {
        this.flag_press_img = true;
      } else {
        this.flag_press_img = false;
      }
      this.flag_longpress = true;
    },
    update_order_spot_ex: function() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/update_order_spot_ex.php';
            //let arr = [];
            for(let i=0; i<this.spot_ex.length; i++) {
              let params = new URLSearchParams();
              let arr= this.spot_ex[i].id;
              params.append('spot_id_arr', arr);
              params.append('order', i);
              axios.post(url, params
              ).then(response => {
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
            }
            this.get_spot_ex();
      },
      addImg() {
        this.$router.push({
            name: 'images',
            params: {
              tour_id: this.tour_id,
              spot_id: this.spot_id
            }
        })
      },
      getSpotImage() {
          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_spot_img.php';
              let params = new URLSearchParams();
              params.append('tour_id', this.tour_id);
              params.append('spot_id', this.spot_id);
              axios.post(url, params
              ).then(response => {
                this.srcArray = response.data;
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
        },
        returnSended(sended) {
            let month = sended.substr(5, 2) + '月';
            let day = sended.substr(8, 2) + '日';
            let time = ' ' + sended.substr(10, 6);
            return month + day + time;
        }
  },
  components: {
    draggable: draggable,
    ComAddComment: ComAddComment,
    ComLongPress: ComLongPress,
    ComChangeCom: ComChangeCom,
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#editSpot {
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

      font-size: 18px;
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
  }

  .l-slider_images {
    padding: 40px 0 0 20px;
    display: flex;
    overflow-x: scroll;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-image, .o-button_add_img {
    height: 100px;
    width: 100px;
    min-height: 100px;
    min-width: 100px;
    background-color: rgba(0,0,0, .05);
    border-radius: 10px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .img {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
  }

  .o-image:not(:first-of-type) {
    margin-left: 10px;
  }

  .o-button_add_img {
    margin-right: 10px;
    background-color: #4B8E8D;
    color: white;
    font-weight: bold;
  }

  .l-border {
    width: 100vw;
    display: flex;
    justify-content: flex-end;
  }

  .o-border {
    height: 1px;
    width: calc(100% - 20px);
    background-color: rgba(0,0,0, .12);
  }

  .o-button_create_geosite, .o-button_save_sort {
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

  .o-button_create_geosite:acitve {
    opacity: .7;
  }

  .l-comment_container {
      width: calc(100% - 40px);
      margin-left: 20px;
  }

  .l-comment_row {
      display: flex;
      align-items: center;
      justify-content: flex-start;
  }

  .l-comment_row:first-of-type {
      margin-top: 20px;
  }

  .l-comment_row:not(:first-of-type) {
      margin-top: 20px;
  }

  .l-comment_row:last-of-type {
      margin-bottom: 120px;
  }

  .l-flex_end {
      width: 100%;
      display: flex;
      align-items: flex-end;
  }

  .l-comment {
      padding: 10px;
      background-color: #E3E5E5;
      border-radius: 10px;
  }

  .o-send_time {
      margin: 0 25px 0 5px;
      display: flex;
      align-items: flex-end;
      white-space: nowrap;

      font-size: 10px;
      color: #A2A6A5;
  }

  .u-mt10 {
    margin-top: 10px;
  }

  .u-mt20 {
    margin-top: 20px;
  }

  .u-mb20 {
    margin-bottom: 20px;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }
</style>
