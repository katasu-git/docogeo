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
                @click="post_img(image)"
            >
                <img 
                    class="img"
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
                    @click="post_ex(ex)"
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
          user: this.$localStorage.get('user'), //guide or guest
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
          }
      }
    },
    created: function () {
        //再読み込み対策のローカル値
        this.$localStorage.set('user','guide');
        if(JSON.stringify(this.$route.params) != "{}") {
            
          //再読み込み対策のローカル値
          this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info));
          //通常の呼び出し先
          this.tour_info = this.$route.params.tour_info;

        } else {
          this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        }
        this.init()
    },
    methods: {
        init() {
            this.get_spot_info_arr();
            this.fetch_active_user();
            this.closeModal();
        },
        refresh() {
            this.get_post()
            this.get_spot_image();
            this.closeModal();
        },
        get_spot_info_arr() {
            const url =　"https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    //スポットのデータを丸ごと保存
                    this.spot_info_arr = response.data;
                    //通常の呼び出し先
                    this.spot_info = response.data[0];
                    if(!this.$localStorage.get('now_spot_info')) {
                        //再読み込み対策のローカル値
                        this.$localStorage.set('now_spot_info',JSON.stringify(response.data[0]));
                    } else {
                        this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'));
                    }

                    //後処理
                    this.get_post()
                    this.get_spot_image();
                })
                .catch(error => {
                // エラーを受け取る
                console.log(error);
                });
        },
        get_post() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPost.php";
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
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_spot_img.php';
                let params = new URLSearchParams();
                params.append('tour_id', this.tour_info.tour_id);
                params.append('spot_id', this.spot_info.spot_id);
                axios.post(url, params
                ).then(response => {
                    this.img_info = response.data;
                }).catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        change_spot_name(spot_selected) {
            this.spot_info = spot_selected;
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
        post_ex(ex) {
            if(ex.isPosted == 0) {

                const url = 'https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_G/post_ex.php';
                let params = new URLSearchParams();
                params.append("tour_id", ex.tour_id);
                params.append("spot_id", ex.spot_id);
                params.append("ex_id", ex.ex_id);
                params.append("posted_ex", ex.spot_ex);
                if(this.isExist(ex)) {
                    params.append("isHidden", 1);
                } else {
                    params.append("isHidden", 0);
                }
                axios
                    .post(url, params)
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

                const url2 = 'https://www2.yoslab.net/~nishimura/geotour/PHP/isPosted_t.php';
                let params2 = new URLSearchParams();
                params2.append("ex_id", ex.ex_id);
                axios
                    .post(url2, params2).then(response => {
                        this.get_post();
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            } else if(ex.isPosted == 1) {

                //配信済みの場合の処理
                const url2 = 'https://www2.yoslab.net/~nishimura/geotour/PHP/isPosted_f.php';
                let params2 = new URLSearchParams();
                params2.append("ex_id", ex.ex_id);
                axios
                    .post(url2, params2).then(response => {
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

                //配信済みの場合の処理
                const url3 = 'https://www2.yoslab.net/~nishimura/geotour/PHP/DELETE/delete_posted_post.php';
                let params3 = new URLSearchParams();
                params3.append("ex_id", ex.ex_id);
                axios
                    .post(url3, params3)
                    .then(response => {
                        this.refresh();
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            }
        },
        post_img(image) {
                console.log("----------------" + JSON.stringify(image));

                if(image.isPosted == 0) {
                    const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/POST/post_img.php';
                    let params = new URLSearchParams();
                    params.append("tour_id", image.tour_id);
                    params.append("spot_id", image.spot_id);
                    params.append("img_id", image.image_id);
                    params.append("img_path", image.image_path);
                    axios
                        .post(url, params).then(()=>{
                            this.refresh(); //isPostedの値更新処理
                        })
                        .catch(error => {
                            // エラーを受け取る
                            console.log(error);
                        });

                } else {
                    //配信済みの場合の処理
                    //delete処理もまとめた
                    const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/undo_posted_post.php';
                    let params = new URLSearchParams();
                    params.append("id", image.id);
                    params.append("tour_id", image.tour_id);
                    params.append("spot_id", image.spot_id);
                    params.append("image_id", image.image_id);
                    axios
                        .post(url, params).then(response => {
                            this.refresh(); //isPostedの値更新処理
                        })
                        .catch(error => {
                            // エラーを受け取る
                            console.log(error);
                        });
                }

        },
        isActive(isPosted) {
            if(isPosted == 1) {
                return false;
            } else {
                return true;
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
            setTimeout(() => {
                this.flag.change_spot = false;
                this.flag.finish_tour = false;
                this.flag.guest_list = false;
            }, 200)
        },
        returnSended(sended) {
            return sended.substr(10, 6);
        },
        async fetch_active_user() {
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_G/fetch_active_user.php";
            const res = await axios.post(url);
            this.userList = res.data;
        },
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
    background-color: rgba(0,0,0, .05);
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

</style>
