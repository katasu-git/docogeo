<template>
  <div id="editSpot">
    <div class="o-background">

      <transition name="fade">
        <Delete
          v-show="flag.delete"
          :selected="selected"
          :isImage="isImage"
          :tour_info="tour_info"
          :spot_info="spot_info"
          @close_modal="close_modal"
        ></Delete>
      </transition>

      <EditCommnet
        v-bind:class="{ slideIn: flag.edit_ex, slideOut: !flag.edit_ex }"
        :selected="selected"
        @close_modal="close_modal"
      ></EditCommnet>

      <CreateComment
        v-bind:class="{ slideIn: flag.create, slideOut: !flag.create }"
        :tour_info="tour_info"
        :spot_info="spot_info"
        @edit_spot_ex="edit_spot_ex"
        @close_modal="close_modal"
      ></CreateComment>
      
      <div class="l-header_above">
        <div class="o-text_tour">Comment</div>
        <div class="o-image_image_button u_pointer">
          <img v-on:click="start_sort()" v-show="!flag.order" src="../assets/sort_button.svg" />
          <img v-on:click="start_sort()" v-show="flag.order" src="../assets/sort_button_active.svg" />
        </div>
      </div>
      <div class="l-header_under u-mb20">
        <div class="o-text_tour_min"><span class="u-color-green">{{spot_info.spot_name}}</span></div>
        <div class="o-text_add_image" v-bind:style="{ color: return_color()}">並べ替え</div>
      </div>

      <ImageList
        :tour_info="tour_info"
        :spot_info="spot_info"
        :image_info="image_info"
        @delete_selected="delete_selected"
      ></ImageList>


      <div class="l-border">
        <div class="o-border u-mt20"></div>
      </div>

      <List
        v-show="!flag.order"
        :spot_ex="spot_ex"
        @delete_selected="delete_selected"
        @edit_spot_ex="edit_spot_ex"
      ></List>

      <ListDrag
        v-show="flag.order"
        :spot_ex="spot_ex"  
      ></ListDrag>

      <button 
        v-show="flag.order"
        class="o-button_save_sort u_pointer"
        v-on:click="start_sort()" 
      >並び替えを終了する</button>

      <button 
        v-show="!flag.order"
        class="o-button_create_geosite u_pointer"
        v-on:click="create_spot_ex()" 
      >新しく説明を追加する</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import List from '../components/Edit_Spot/List'
import ListDrag from '../components/Edit_Spot/ListDrag'
import Delete from'../components/Edit_Spot/Delete'
import ImageList from '../components/Edit_Spot/ImageList'
import EditCommnet from '../components/Edit_Spot/EditComment'
import CreateComment from '../components/Edit_Spot/CreateCommnet'

export default {
  name: "editSpot",
  data() {
    return {
      tour_info: '',
      spot_info: '',
      spot_ex: [],
      image_info: [],
      selected: '',
      isImage: false,
      flag: {
        order: false,
        delete: false,
        edit_ex: false,
        create: false
      },
      info: '',
    };
  },
  created: function() {
    this.init();
  },
  methods: {
    init() {
      this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
      if(JSON.stringify(this.$route.params) != "{}") {
        this.spot_info = this.$route.params.spot_info;
        //ローカルストレージに登録
        this.$localStorage.set('now_spot_info',JSON.stringify(this.$route.params.spot_info));
        console.log(this.$route.params.spot_info)
      } else {
        //リロード時の対応
        this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'));
      }
      this.get_spot_ex();
      this.get_spot_image();
    },
    get_spot_ex: function() {
      const url =
        "https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Spot/get_spot_ex.php";
      let params = new URLSearchParams();
      params.append("spot_id", this.spot_info.spot_id);
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
    get_spot_image() {
        const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Spot/get_spot_img.php';
          let params = new URLSearchParams();
          params.append('tour_id', this.tour_info.tour_id);
          params.append('spot_id', this.spot_info.spot_id);
          axios.post(url, params
          ).then(response => {
            this.image_info = response.data;
            console.log(this.image_info)
          }).catch(error => {
              // エラーを受け取る
              console.log(error);
          });
    },
    move_page: function(where) {
        this.$router.push({
            name: where,
            params: {
            }
        })
    },
    close_modal: function() {
      this.flag.order = false;
      this.flag.delete = false;
      this.flag.edit_ex = false;
      this.flag.create = false;
      this.init(); //説明の更新を反映
    },
    start_sort() {
        if(this.flag.order) {
          this.flag.order = false;
        } else {
          this.close_modal();
          this.flag.order = true;
        }
    },
    return_color: function() {
        if(this.flag.order) {
          return '#4B8E8D';
        } else {
          return 'rgba(0,0,0,.26)';
        }
    },
    delete_selected(info, isImage) {
      this.selected = info;
      this.isImage = isImage;
      this.flag.delete = true;
    },
    edit_spot_ex(info) {
      console.log(info)
      this.selected = info;
      this.flag.edit_ex = true;
    },
    create_spot_ex() {
      this.flag.create = true;
    }
  },
  components: {
    List: List,
    ListDrag: ListDrag,
    Delete: Delete,
    ImageList: ImageList,
    EditCommnet: EditCommnet,
    CreateComment: CreateComment
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
