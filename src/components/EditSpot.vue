<template>
  <div id="editSpot">
    <div class="o-background">

      <ComAddComment @closeModal="closeModal"
        :tour_id="tour_id" :spot_id="spot_id"
        v-show="flag_add_com"></ComAddComment>
      
      <div class="l-header_above">
        <div class="o-text_tour">Comment</div>
        <div class="o-image_image_button">
          <img v-on:click="startSort()" v-show="!flag_order" src="../assets/sort_button.svg" />
          <img v-on:click="startSort()" v-show="flag_order" src="../assets/sort_button_active.svg" />
        </div>
      </div>
      <div class="l-header_under u-mb20">
        <div class="o-text_tour_min">説明：<span class="u-color-green">{{spot_name}}</span></div>
        <div class="o-text_add_image" v-bind:style="{ color: returnSortColor()}">並べ替え</div>
      </div>

      <draggable class="l-slider_images" :animation="150" v-show="flag_order">
        <div class="o-image" v-for="(image, i) in images" :key="i">{{i}}</div>
      </draggable>

      <div class="o-border u-mt20"></div>

      <draggable v-model="spot_ex" :animation="150" v-show="flag_order">
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

      <button class="o-button_create_geosite" v-on:click="addComment()"
        v-show="!flag_order && !flag_add_com">新しく説明を追加する</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import draggable from 'vuedraggable';
import ComAddComment from '../components/modals/comAddComment'
export default {
  name: "editSpot",
  data() {
    return {
      tour_id: '',
      spot_id: '',
      tour_name: '',
      spot_name: '',
      spot_ex: [],
      images: 10,
      flag_order: false,
      flag_add_com: false,
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
      this.accessDb();
    }
  },
  methods: {
    accessDb: function() {
      const url =
        "https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_ex.php";
      let params = new URLSearchParams();
      params.append("spot_id", this.spot_id);
      axios
        .post(url, params)
        .then(response => {
          this.spot_ex = response.data;
          //console.log(this.spot_ex);
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
        this.flag_add_com = false;
        //this.get_spot_info(); //名前の更新を反映
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
    }
  },
  components: {
    draggable: draggable,
    ComAddComment: ComAddComment,
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

  .o-image {
    min-height: 100px;
    min-width: 100px;
    background-color: rgba(0,0,0, .05);
    border-radius: 10px;
  }

  .o-image:not(:first-of-type) {
    margin-left: 10px;
  }

  .o-border {
    height: 1px;
    width: calc(100% - 40px);
    background-color: rgba(0,0,0, .12);
  }

  .o-list {
    padding: 20px 0 0 20px;
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
    margin: 20px 0 0 20px;
    background-color: rgba(0,0,0, .05);
    border-radius: 10px;
  }

  .o-list_text_geosite {
    padding: 20px 20px 10px 20px;
    font-size: 14px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-list_text_update {
    padding: 0 20px 20px 20px;
    font-size: 12px;
    color: rgba(0,0,0, .26);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-button_create_geosite {
    position: fixed;
    bottom: 20px;
    left: 20px;
    height: 40px;
    width: calc(100% - 40px);
    border: solid 2px #4B8E8D;
    border-radius: 10px;
    background-color: rgba(0,0,0,0);
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
