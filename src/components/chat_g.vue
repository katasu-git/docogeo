<template>
  <div id="chat_g">
      <div class="o-background">

        <ChangeSpot
            :tour_id="tour_id"
            :spot_id="spot_id"
            :spot_names="spot_names"
            @closeModal="closeModal"
            @change_spot_name="change_spot_name"
            v-show="flag_change_spot"
        ></ChangeSpot>

        <FinishTour
            :tour_id="tour_id"
            @closeModal="closeModal"
            @jumpPage="jumpPage"
            v-show="flag_finish_tour"
        ></FinishTour>
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">{{ tour_name }}</div>
                <div 
                    class="o-image_image_button"
                    v-on:click='finish_tour()'
                ><img src="../assets/close_button.svg" /></div>
            </div>
            <div class="l-header_under u-mb20">
                <button 
                    class="o-text_tour_min"
                    @click="changeSpot()"
                >
                    <span class="u-color-green">{{ spot_name }}</span>
                    <img class="u-ml5" src="../assets/Polygon 1.svg" />
                </button>
                <div class="o-text_add_image">ツアー終了</div>
            </div>
        </div>

        <div class="l-slider_images" :animation="150">
            <div class="o-image" 
                v-for="image in srcArray"
                :key="image.id"
                @click="postImg(image)"
            >
                <img 
                    class="img"
                    :src="image.imgPath"
                    :alt="image.imgName"
                    :style="{ opacity:returnOpacity(image.isPosted) }"
                />
            </div>
        </div>

        <div class="l-border">
            <div class="o-border u-mt20"></div>
        </div>

        <div class="l-comment_container">
            <div class="l-comment_row" v-for="ex in spot_ex" :key="ex.ex_id">
                <div class="l-flex_end">
                    <div class="l-comment" :style="{ opacity:returnOpacity(ex.isPosted) }">{{ ex.spot_ex }}</div>
                    <div 
                        class="o-send_time"
                        :style="{ color:returnTimeCol(ex.isPosted) }"
                    >{{returnSended(ex.sended)}}</div>
                </div>
                <div class="o-button_hoe"
                    :style="{ opacity:returnOpacity(ex.isPosted) }"
                    @click="postEx(ex)"
                >
                    <img src="../assets/send_button.svg" />
                </div>
            </div>
        </div>

        <div class="l-footer">
            <div class="o-icon">
                <img class="o-icon_img" src="../assets/camera_button.svg" />
                <div class="o-footer_text">カメラ</div>
            </div>
            <div class="o-icon">
                <img class="o-icon_img" src="../assets/comment_active.svg" />
                <div class="o-footer_text u-color-green">説明</div>
            </div>
            <div class="o-icon">
                <img class="o-icon_img" src="../assets/map_button.svg" />
                <div class="o-footer_text">地図</div>
            </div>
            
        </div>

      </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import draggable from 'vuedraggable';
  import ChangeSpot from '../components/modals/chatChangeSpot'
  import FinishTour from '../components/modals/chatFinish'
  export default {
    name: 'chat_g',
    data() {
      return {
          tour_id: '',
          tour_name: '',
          spot_id: '',
          spot_name: '',
          spot_names: [],
          srcArray: [],
          spot_ex: JSON,
          flag_change_spot: false,
          flag_finish_tour: false,
      }
    },
    created: function () {
        if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("HelloWorld");
        } else {
            this.tour_id = this.$route.params.tour_id;
            this.tour_name = this.$route.params.tour_name;
            this.get_spot_name_arr();
        }
    },
    methods: {
        getPost() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPost.php";
            let params = new URLSearchParams();
            //スポットの表示順はarrの長さから逆順で引いていけばok
            params.append("spot_id", this.spot_id); //ここを直す
            axios 
                .post(url, params)
                .then(response => {
                    this.spot_ex = response.data;
                    this.getSpotImage();
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        jumpPage(where) {
            this.$router.push({
                name: where,
                params: {
                    tour_id: this.tour_id
                }
            });
        },
        postEx(ex) {
            if(ex.isPosted == 0) {

                const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/post_ex.php';
                let params = new URLSearchParams();
                params.append("tour_id", ex.tour_id);
                params.append("spot_id", ex.spot_id);
                params.append("ex_id", ex.ex_id);
                params.append("posted_ex", ex.spot_ex);
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
                        this.getPost();
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
                console.log(ex.ex_id);
                axios
                    .post(url3, params3)
                    .then(response => {
                        this.getPost();
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            }
        },
        postImg(image) {

                if(image.isPosted == 0) {
                    const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/POST/post_img.php';
                    let params = new URLSearchParams();
                    params.append("tour_id", image.tour_id);
                    params.append("spot_id", image.spot_id);
                    params.append("img_id", image.id);
                    params.append("img_path", image.imgPath);
                    axios
                        .post(url, params).then(()=>{
                            this.getPost(); //isPostedの値更新処理
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
                    params.append("img_id", image.id);
                    axios
                        .post(url, params).then(response => {
                            this.getPost(); //isPostedの値更新処理
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
            this.flag_finish_tour = true;
        },
        returnTimeCol(isPosted) {
            if(isPosted == 0) {
                return 'rgba(0,0,0,0)';
            }
            return '#4B8E8D';
        },
        returnOpacity(isPosted) {
            console.log(isPosted);
            if(isPosted == 0) {
                return '1';
            } else {
                return '0.4';
            }
        },
        changeSpot() {
            this.flag_change_spot = true;
        },
        closeModal() {
            setTimeout(() => {
                this.flag_change_spot = false;
                this.flag_finish_tour = false;
            }, 200)
        },
        get_spot_name_arr() {
            const url =　"https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    this.spot_names = response.data;
                    if(this.spot_id == '') {
                        //初回の処理
                        this.spot_id = this.spot_names[0].spot_id;
                        this.spot_name = this.spot_names[0].spot_name;
                    }
                    this.getPost();
                    this.closeModal();
                })
                .catch(error => {
                // エラーを受け取る
                console.log(error);
                });
        },
        change_spot_name(spot_id_selected) {
            for(let i=0; i<this.spot_names.length; i++) {
                this.spot_id = spot_id_selected;
                if(this.spot_names[i].spot_id == spot_id_selected) {
                    this.spot_name = this.spot_names[i].spot_name;
                    break;
                }
            }
            this.getPost();
            this.closeModal();
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
            return sended.substr(10, 6);
        }
    },
    components: {
        ChangeSpot: ChangeSpot,
        FinishTour: FinishTour,
        draggable: draggable,
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
  }

  .o-background {
      display: flex;
      flex-direction: column;
      align-items: center;
  }

  .o-header {
      position: fixed;
      height: 80px;
      width: 100%;
      background-color: #fff;
      filter: drop-shadow(0 0 5px rgba(0,0,0,.26));
      z-index: 1;
  }

  .l-header_above {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-start;

    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

    .o-text_tour {
        padding: 10px 20px 0 20px;
        font-size: 24px;
        line-height: calc(24px * 1.5);
        font-weight: bold;
    }

    .o-image_image_button {
        padding: 10px 20px 0 0;
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
      padding: 0 10px 0 0;

      font-size: 12px;
      font-weight: bold;
      color: rgba(0,0,0, .26);
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
      padding: 10px;
      background-color: #E3E5E5;
      border-radius: 10px;
  }

  .o-send_time {
      margin: 0 5px;
      display: flex;
      align-items: flex-end;

      font-size: 10px;
      font-weight: bold;
      color: #A2A6A5;
  }

  .l-footer {
      position: fixed;
      right: 20px;
      bottom: 20px;
      height: 80px;
      width: calc(100% - 40px);
      background-color: #fff;
      border-radius: 100px;
      filter: drop-shadow(0 3px 10px rgba(0,0,0,.26));

      display: flex;
      justify-content: space-evenly;
      align-items: center;
      z-index: 1;
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
