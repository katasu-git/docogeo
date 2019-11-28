<template>
  <div id="chat_u">
      <div class="o-background">
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">{{ tour_name }}</div>
            </div>
            <div class="l-header_under u-mb20">
                <div class="o-text_tour_min"><span class="u-color-green">{{ spot_name }}</span></div>
            </div>
        </div>

        <div class="l-comment_container">
            <div class="l-comment_row" v-for="ex in spot_ex">
                <div class="l-flex_end">
                    <div class="l-comment" 
                        v-if="returnFlag(ex.spot_ex)"
                    >{{ return_spot_ex(ex) }}</div>
                    <img 
                        class="o-spot_img"
                        :src="return_spot_img(ex)"
                        v-if="returnFlag(!ex.spot_ex)" 
                    />
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
          tour_name: '',
          spot_id: 1,
          spot_name: '',
          spot_ex: JSON,
          hello: 5,
      }
    },
    created: function () {
        if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("top_u");
        } else {
            this.tour_id = this.$route.params.tour_id;
            this.tour_name = this.$route.params.tour_name;
            this.getPost();
        }
        setInterval(function() {
            this.getPost();
        }.bind(this), 1000);
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
                    this.spot_ex = response.data;
                    this.get_spot_name();
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        get_spot_name: function() {
            return 'かりの名前';
        },
        jumpPage: function(where) {
            this.$router.push({
                name: where,
                params: {
                    tour_id: this.tour_id
                }
            });
        },
        return_spot_ex(ex) {
            return ex.spot_ex;
        },
        return_spot_img(ex) {
            return ex.imgPath;
        },
        returnFlag(contents) {
            if(contents == '' || contents == undefined || contents == null) {
                return false;
            } else {
                return true;
            }
        }
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

  .o-spot_img {
      width: calc(100% - 35px);
      max-width: 400px;
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
