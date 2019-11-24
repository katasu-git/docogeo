<template>
  <div id="chat_u">
      <div class="o-background">
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">TOUR_NAME</div>
            </div>
            <div class="l-header_under u-mb20">
                <div class="o-text_tour_min"><span class="u-color-green">Spot_name_here</span></div>
            </div>
        </div>

        <div class="l-comment_container">
            <div class="l-comment_row" v-for="f in hello">
                <div class="l-flex_end">
                    <div class="l-comment">世界無形文化遺産の指定を受けた那智の田楽が奉納される場所です。</div>
                    <div class="o-send_time">11:22</div>
                </div>
                <div class="o-button_hoe">
                    <img src="../assets/hoe_button_gray.svg" />
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
    name: 'chat_u',
    data() {
      return {
          tour_id: 1,
          spot_id: 1,
          spot_name: '',
          spot_ex: JSON,
          hello: 5,
      }
    },
    created: function () {
        /*if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("top_u");
        } else {
            this.tour_id = this.$route.params.tour_id;
            this.getPost();
        }
        setInterval(function() {
            this.getPost();
        }.bind(this), 1000);*/
    },
    methods: {
        getPost: function() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPostedPost.php";
            let params = new URLSearchParams();
            //console.log("発火");
            params.append("tour_id", this.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    //console.log(response.data);
                    this.spot_ex = response.data;
                    this.get_spot_name();
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
                    let spot_id_arr = [];
                    for(let i=0; i<this.spot_ex.length; i++) {
                        spot_id_arr.push(this.spot_ex[i].spot_id);
                    }
                    this.spot_id = Math.max.apply(null, spot_id_arr);
                    for(let i=0; i<response.data.length; i++) {
                        if(response.data[i].spot_id == this.spot_id) {
                            this.spot_name = response.data[i].spot_name;
                            break;
                        }
                    }
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
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #chat_u {
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

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
