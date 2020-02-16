<template>
  <div id="chat_g">
      <div class="o-background">

        <transition name="fade">
            <ToggleSpot
                :tour_info="tour_info"
                :spot_info="spot_info"
                :spot_info_arr="spot_info_arr"
                @closeModal="closeModal"
                @change_spot_name="change_spot_name"
                v-show="flag.change_spot"
            />
        </transition>

        <transition name="fade">
            <FinishTour
                :tour_info="tour_info"
                @closeModal="closeModal"
                @move_page="move_page"
                v-show="flag.finish_tour"
            />
        </transition>

        <GuestList
            :userList="userList"
            @closeModal="closeModal"
            v-show="flag.isMounted"
            v-bind:class="{ slideInRight: flag.guest_list, slideOutRight: !flag.guest_list }"
        />

        <VueHeader
            :tour_info="tour_info"
            :spot_info="spot_info"
            @changeSpot="changeSpot"
            @finish_tour="finish_tour"
            @show_guestList="show_guestList"
        />

        <div class="l-slider_images" :animation="150">
            <div class="o-image" 
                v-for="image in img_info"
                :key="image.id"
                @click="onClickPostImage(image)"
            >
                <img 
                    class="img"
                    :id="return_image_name(image)"
                    :src="image.image_path"
                    :style="{ opacity:returnOpacity(image.isPosted) }"
                />
            </div>
        </div>

        <div class="l-border">
            <div class="o-border u-mt20"></div>
        </div>

        <div class="l-comment_container">
            <div 
                class="l-comment_row" 
                v-for="ex in spot_ex" 
                :key="ex.ex_id"
            >
                <div class="l-flex_end">
                    <div>
                        <div 
                            :class="return_hidden_style(ex)"
                            :style="{ opacity:returnOpacity(ex.isPosted) }"
                            @click="hide_message(ex)"
                        >
                            <div class="kaigyo">{{ ex.spot_ex }}</div>
                        </div>
                        <div 
                            v-show="isExist(ex)"
                            class="hidden-text"
                        >
                            この説明は見えない状態で配信されます
                        </div>
                    </div>
                    <div 
                        class="o-send_time"
                        :style="{ color:returnTimeCol(ex.isPosted) }"
                    >
                        {{returnSended(ex.sended)}}
                    </div>
                </div>
                <div
                    class="o-button_hoe"
                    :style="{ opacity:returnOpacity(ex.isPosted) }"
                    @click="onClickPostButton(ex)"
                >
                    <img src="../assets/send_button.svg" />
                </div>
            </div>
        </div>

        <Footer
            :place="place"
            :user="user"
            @move_page="move_page"
        />

        <canvas
            ref="canvas"
            id="canvas"
        />

        <img id="image_hidden" src="" />

      </div>
  </div>
</template>

<script>
import axios from 'axios'
import VueHeader from './parts/Header'
import Footer from '../components/parts/Footer'
import ToggleSpot from '../components/Chat_Guide/ToggleSpot'
import FinishTour from '../components/Chat_Guide/FinishTour'
import GuestList from '../components/Chat_Guide/GuestList'

  export default {
    name: 'chat_g',
    data() {
      return {
          place: "chat",
          user: "guide",
          tour_info: '',
          spot_info: '',
          spot_info_arr: '',
          img_info: '',
          spot_ex: JSON,
          hidden_message: [],
          userList: '',
          flag: {
              change_spot: false,
              finish_tour: false,
              guest_list: false,
              isMounted: false,
              isTourChanged: false
          },
          video_h: '',
          video_w: '',
          captures: '',
      }
    },
    created: function () {
        if(JSON.stringify(this.$route.params) != "{}") {
            
            //ローカルストレージの初期化
            this.$localStorage.remove('now_tour_info');
            //再読み込み対策のローカル値
            this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info));
            //通常の呼び出し先
            this.tour_info = this.$route.params.tour_info;

            this.flag.isTourChanged = true;

        } else {
            this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        }
        this.init()
    },
    mounted() {
        this.setCanvas(100, 100)
    },
    methods: {
        async init() {
            await this.get_spot_info_arr();
            this.get_post()
            this.get_spot_image();
            this.fetch_active_user();

            this.closeModal();
        },
        refresh() {
            this.get_post()
            this.get_spot_image();
            
            this.closeModal();
        },
        async get_spot_info_arr() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/get_spot_info.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            const response = await axios.post(url, params);

            this.spot_info_arr = response.data;
            this.spot_info = response.data[0];
            if(!this.$localStorage.get('now_spot_info') || this.flag.isTourChanged) {
                this.$localStorage.remove('now_spot_info');
                //再読み込み対策のローカル値
                this.$localStorage.set('now_spot_info',JSON.stringify(response.data[0]));
            } else {
                this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'));
            }
        },
        async get_post() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/getPost.php";
            let params = new URLSearchParams();
            params.append("spot_id", this.spot_info.spot_id);
            const response = await axios.post(url, params);

            this.spot_ex = response.data;
        },
        async get_spot_image() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/get_spot_img.php";
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_info.tour_id);
            params.append('spot_id', this.spot_info.spot_id);
            const response = await axios.post(url, params);

            this.img_info = response.data;
        },
        async fetch_active_user() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/fetch_active_user.php";
            const res = await axios.post(url);
            this.userList = res.data;
        },
        change_spot_name(spot_selected) {
            this.spot_info = spot_selected;
            this.$localStorage.remove('now_spot_info');
            this.$localStorage.set('now_spot_info',JSON.stringify(spot_selected));
            this.refresh();
        },
        move_page(where) {
            this.$router.push({
                name: where,
                params: {
                    tour_info: this.tour_info,
                    spot_info: this.spot_info
                }
            });
        },
        hide_message(ex) {
            if(ex.isPosted == 1) {
                return;
            }
            if(this.isExist(ex)) {
                this.delete_hidden_message(ex);
            } else {
                this.hidden_message.push(ex);
            }
        },
        isExist(ex) {
            if(this.hidden_message.length === 0) {
                return false;
            }
            let flag = false;
            for(let i=0; i<this.hidden_message.length; i++) {
                if(this.hidden_message[i].ex_id === ex.ex_id) {
                    flag = true;
                }
            }
            return flag;
        },
        delete_hidden_message(ex) {
            for(let i=0; i<this.hidden_message.length; i++) {
                if(this.hidden_message[i].ex_id === ex.ex_id) {
                    this.hidden_message.splice(i,1);
                    break;
                }
            }
        },
        return_hidden_style(ex) {
            if(this.isExist(ex)) {
                return 'l-comment-hidden';
            } else {
                return 'l-comment';
            }
        },

        async onClickPostButton(ex_info) {
            console.log(ex_info)
            if(ex_info.isPosted == 0) {
                await this.post_ex(ex_info)
            } else {
                await this.undo_post_ex(ex_info)
            }
            this.refresh()
        },

        async post_ex(ex_info) {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/post_ex.php";
            let params = new URLSearchParams();
            params.append("tour_id", ex_info.tour_id);
            params.append("spot_id", ex_info.spot_id);
            params.append("ex_id", ex_info.ex_id);
            params.append("posted_ex", ex_info.spot_ex);
            
           if(this.isExist(ex_info)) {
                params.append("isHidden", 1);
            } else {
                params.append("isHidden", 0);
            }
            const response = await axios.post(url, params);
        },

        async undo_post_ex(ex_info) {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/delete_posted_post.php";
            let params = new URLSearchParams();
            params.append("ex_id", ex_info.ex_id);
            const response = await axios.post(url, params);
        },

        move_draw(image) {
            //let select_name = "image" + image.id
            //let hidden_image = document.getElementById(select_name);
            //console.log(hidden_image)

            let image_hidden = document.getElementById("image_hidden");
            image_hidden.src = image.image_path;

            image_hidden.onload = ()=> {
                //画像の読み込みが終わったら
                this.canvas = this.$refs.canvas
                this.setCanvas(image_hidden.width, image_hidden.height);
                this.canvas.getContext('2d').drawImage(image_hidden, 0, 0, image_hidden.width, image_hidden.height);
                let captures = this.canvas.toDataURL('image/jpg')

                //お絵かきページに移動
                this.$router.push({
                name: 'draw',
                    params: {
                    width: image_hidden.width,
                    height: image_hidden.height,
                    captures: captures,
                    spot_image_id: image.id, //spot_imagesの主キー
                    isNotReload: true
                    }
                })
            }

        },

        setCanvas(width, height) {
            //canvasのサイズ変更
            let canvas = document.getElementById("canvas");
            canvas.width = width;
            canvas.height = height;

            console.log(canvas.width)
            console.log(canvas.height)
        },

        onClickPostImage(image) {

            console.log(image); //idはspot_imageの主キー
            if(image.isPosted == 0) {
                this.move_draw(image)
            } else {
                console.log("配信済みです")
            }

        },

        finish_tour () {
            this.flag.finish_tour = true;
        },

        returnTimeCol(isPosted) {
            if(isPosted == 0) {
                return 'rgba(0,0,0,0)';
            }
            return '#4B8E8D';
        },

        returnOpacity(isPosted) {
            if(isPosted == 0) {
                return '1';
            } else {
                return '0.4';
            }
        },

        changeSpot() {
            this.flag.change_spot = true;
        },

        show_guestList() {
            this.fetch_active_user();
            this.flag.isMounted = true;
            this.flag.guest_list = true;
        },

        closeModal() {
            this.flag.change_spot = false;
            this.flag.finish_tour = false;
            this.flag.guest_list = false;
        },
        
        returnSended(sended) {
            if(!sended) {
                return;
            }
            return sended.substr(10, 6);
        },

        return_image_name(image) {
            return "image" + image.id;
        }
    },
    components: {
        VueHeader: VueHeader,
        Footer: Footer,
        ToggleSpot: ToggleSpot,
        FinishTour: FinishTour,
        GuestList: GuestList
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#chat_g {
    height: 100%;
    width: 100%;
    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
    z-index: 1;
}

.o-background {
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 1;
}

.l-slider_images {
    margin: 100px 0 0 0;
    width: 100%;
    display: flex;
    overflow-x: scroll;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-image {
    position: relative;
    min-height: 100px;
    min-width: 100px;
    max-height: 100px;
    max-width: 100px;
    border-radius: 10px;
  }

  .img {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
  }

  .o-image:first-of-type {
      margin: 0 0 0 20px;
  }

  .o-image:not(:first-of-type) {
    margin-left: 10px;
  }

  .o-image:last-of-type {
    padding-right: 20px;
  }

  .l-border {
    margin: 20px 0;
    width: 100vw;
    display: flex;
    justify-content: flex-end;
  }

  .o-border {
    height: 1px;
    width: calc(100% - 20px);
    background-color: rgba(0,0,0, .12);
  }

  .l-comment_container {
      width: calc(100% - 40px);
  }

  .l-comment_row {
      display: flex;
      align-items: center;
      justify-content: flex-start;
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
  }

  .l-comment {
      padding: 10px 10px 10px 10px;
      background-color: #E3E5E5;
      border-radius: 10px;
  }

  .l-comment-hidden {
    padding: 8px;
    background-color: rgba(0,0,0,0);
    border: solid 2px #4B8E8D;
    border-radius: 10px;
  }

  .hidden-text {
      margin-top: 5px;
      color: #4B8E8D;
      font-size: 10px;
      text-align: center;
  }

  .o-send_time {
      margin: 0 5px;
      display: flex;
      align-items: flex-end;

      font-size: 10px;
      font-weight: bold;
      color: #A2A6A5;
  }

  .o-icon {
      display: flex;
      flex-direction: column;
      align-items: center;
  }

  .o-icon_img {
      height: 35px;
  }

  .o-footer_text {
      margin-top: 10px;
      font-size: 12px;
      color: #C2C7C6;
  }

  .u-ml5 {
      margin-left: 5px;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

  #canvas {
    visibility: hidden;
  }

  #image_hidden {
    width: calc(100% - 20px);
    visibility: hidden;
  }

</style>
