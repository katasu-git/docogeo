<template>
  <div id="chat_g">
      <div class="o-background">
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">{{ tour_name }}</div>
            </div>
            <div class="l-header_under u-mb20">
                <div class="o-text_tour_min"><span class="u-color-green">{{ spot_name }}</span><img class="u-ml5" src="../assets/Polygon 1.svg" /></div>
            </div>
        </div>

        <div class="l-comment_container">
            <div class="l-comment_row" v-for="ex in spot_ex">
                <div class="l-flex_end">
                    <div class="l-comment" :style="{ opacity:returnOpacity(ex.isPosted) }">{{ ex.spot_ex }}</div>
                    <div class="o-send_time" :style="{ color:returnTimeCol(ex.isPosted) }">11:22</div>
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
  export default {
    name: 'chat_g',
    data() {
      return {
          tour_id: 1,
          tour_name: '',
          spot_id: 1,
          spot_id_arr: [],
          spot_count: 0,
          spot_ex: JSON,
          spot_name: '',
      }
    },
    created: function () {
        if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("HelloWorld");
        } else {
            this.tour_id = this.$route.params.tour_id;
            this.tour_name = this.$route.params.tour_name;
            this.get_spot_id_array();
        }
    },
    methods: {
        getPost: function() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPost.php";
            let params = new URLSearchParams();
            //スポットの表示順はarrの長さから逆順で引いていけばok
            params.append("spot_id", this.spot_id_arr[this.spot_count]); //ここを直す
            axios 
                .post(url, params)
                .then(response => {
                    this.spot_ex = response.data;
                    this.get_spot_name(); //ちゃんとdb叩いてデータ持ってくる
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        get_spot_name: function() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php';
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    this.spot_name = response.data[this.spot_count].spot_name;
                }).catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        jumpPage: function(where) {
            this.$router.push({
                name: where,
                params: {
                    tour_id: this.tour_id
                }
            });
        },
        postEx: function(ex) {
            if(ex.isPosted == 0) {

                const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/post_ex.php';
                let params = new URLSearchParams();
                params.append("tour_id", ex.tour_id);
                params.append("spot_id", ex.spot_id);
                params.append("ex_id", ex.ex_id);
                params.append("posted_ex", ex.spot_ex);
                axios
                    .post(url, params)
                    .then(response => {
                        this.get_spot_name(); //ちゃんとdb叩いてデータ持ってくる
                        this.getPost(); //ちゃんとdb叩いてデータ持ってくる
                        //console.log("成功");
                    })
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
                        this.getPost();
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            }
        },
        isActive: function(isPosted) {
            if(isPosted == 1) {
                return false;
            } else {
                return true;
            }
        },
        get_spot_id_array: function() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_id_array.php';
                let params = new URLSearchParams();
                params.append("tour_id", this.tour_id);
                axios
                    .post(url, params)
                    .then(response => {
                        const arr = [];
                        for(let i=0; i<response.data.length; i++) {
                            arr.push(response.data[i].spot_id);
                        }
                        this.spot_id_arr = arr;
                        this.getPost();
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });
        },
        showNextSpot: function() {
            if(this.spot_count < this.spot_id_arr.length - 1) {
                this.spot_count++;
                this.getPost();
            } else {
                console.log("ここが最後のスポットです");
                this.finishTour();
            }
        },
        showBeforeSpot: function() {
            if(this.spot_count > 0) {
                this.spot_count--;
                this.getPost();
            } else {
                //戻るボタンをグレーアウト
            }
        },
        finishTour: function() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/finish_tour.php';
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_id);
            axios
                .post(url, params).then(response => {
                    this.jumpPage("HelloWorld");
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
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
            }
            return '0.4';
        }
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
    align-items: flex-end;

    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

    .o-text_tour {
      padding: 10px 0 0 20px;

      font-size: 24px;
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

  .l-comment_container {
      width: calc(100% - 40px);
  }

  .l-comment_row {
      display: flex;
      align-items: center;
      justify-content: flex-start;
  }

  .l-comment_row:first-of-type {
      margin-top: 100px;
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
