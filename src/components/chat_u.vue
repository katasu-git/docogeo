<template>
  <div id="chat_u">
      <div class="o-background">

        <transition name="fade">
            <TourEnd
                v-show="flag.end"
                :tour_name="tour_info.tour_name"
            ></TourEnd>
        </transition>
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">{{ tour_info.tour_name }}</div>
            </div>
            <div class="l-header_under u-mb20">
                <div class="o-text_tour_min"><span class="u-color-green"></span></div>
            </div>
        </div>

        <div class="l-comment">
            <div class="l-commnet_row" v-for="ex in spot_ex" :key="ex.id">
                <div class="l-flex-end">
                    <img 
                        class="o-image"
                        :src="return_spot_img(ex)"
                        v-if="returnFlag(!ex.spot_ex)"
                    />
                    <div 
                        class="o-commnet"
                        v-if="returnFlag(ex.spot_ex)"
                    >{{ return_spot_ex(ex) }}</div>
                    <div class="o-send_time">{{returnSended(ex.created)}}</div>
                </div>
                <img src="../assets/hoe_button_gray.svg" />
            </div>
        </div>

        <Footer
            :place="place"
            :user="user"
            @move_page="move_page">
        </Footer>

      </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import Footer from '../components/parts/Footer'
  import TourEnd from '../components/Chat_Guest/TourEnd'
  export default {
    name: 'chat_u',
    data() {
      return {
          place: 'chat',
          user: 'guest',
          tour_info: '',
          spot_ex: JSON,
          flag: {
              end: false
          },
      }
    },
    created: function () {
        this.$localStorage.set('user','guest');
        if(JSON.stringify(this.$route.params) != "{}") {
            
          //再読み込み対策のローカル値
          this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info[0]));
          //通常の呼び出し先
          this.tour_info = this.$route.params.tour_info[0];

        } else {
          this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        }

        setInterval(function() {
        this.getPost();

        //ツアー終了の処理
        this.judge_tour_isActive();
        this.break_tour_timer();
        //////////////

        }.bind(this), 1000);
    },
    methods: {
        break_tour_timer() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_tour_start_time.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    let s_date = response.data.start_time;
                    let timestamp = Date.parse(s_date)
                    //http://yut.hatenablog.com/entry/20111015/1318633937
                    let now = Date.now()
                    let dif = now - timestamp;
                    let pass_min = dif/60/1000;
                    if(pass_min > 5400) {
                        //開始から9時間でアクセス不可にする
                        this.flag.end = true;
                        this.finish_tour();
                    }
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        judge_tour_isActive() {
            const url ="https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/judge_tour_isActive.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    if(response.data[0].isActive == 0) {
                        //開始から9時間でアクセス不可にする
                        this.flag.end = true;
                        this.finish_tour();
                    }
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        finish_tour() {
            //isAcive == 0にする
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/finish_tour.php';
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_info.tour_id);
            axios
                .post(url, params)
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        getPost: function() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPostedPost.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
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
        move_page: function(where) {
            this.$router.push({
                name: where
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
        },
        returnSended(sended) {
            return sended.substr(10, 6);
        }
    },
    components: {
        Footer: Footer,
        TourEnd: TourEnd
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

.l-header_under {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.l-comment {
    position: absolute;
    top: 100px;
    width: 100%;
    overflow-y: scroll;

    display: flex;
    flex-direction: column;
    align-items: center;
}

.l-commnet_row {
    width: calc(100% - 40px);
    display: flex;
    align-items: center;
}

.l-commnet_row:not(:first-of-type) {
    margin-top: 10px;
}

.l-commnet_row:last-of-type {
    margin-bottom: 100px;
}

.o-commnet {
    width: calc(100% - 55px);
    margin-right: 10px;
    padding: 10px;
    background-color: #E3E5E5;
    border-radius: 10px;
}

.o-image {
    width: calc(100% - 45px);
    margin-right: 10px;
    border-radius: 10px;
}

.l-flex-end {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
}

.o-send_time {
    width: 25px;
    margin-right: 10px;
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
