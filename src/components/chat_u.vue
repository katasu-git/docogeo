<template>
  <div id="chat_u">
      <div class="o-background">

        <transition name="fade">
            <TourEnd
                v-show="flag.end"
                :tour_name="tour_info.tour_name"
                :user_info="user_info"
                @reset_user_info="reset_user_info"
            ></TourEnd>
        </transition>

        <HeaderGuest
            :tour_info="tour_info"
            :user_info="user_info"
            @change_name="change_name"
        />

        <div class="l-comment_container">
            <div 
                class="l-comment_row" 
                v-for="ex in spot_ex" 
                :key="ex.ex_id"
            >
                <div class="l-flex_end">
                    <div 
                        v-if="isHidden(ex) && !isExistInShowList(ex.ex_id)"
                        class="l-comment-hidden"
                        @click="show_message(ex.ex_id)"
                    >
                        <p>ここをタッチすると</p>
                        <p>メッセージが表示されます</p>
                    </div>
                    <div v-else>
                        <div
                            class="l-comment"
                            v-if="ex.spot_ex"
                        >
                            <div class="kaigyo">{{ ex.spot_ex }}</div>
                        </div>
                        <img 
                            v-if="ex.imgPath"
                            class="o-image"
                            :src="(ex.imgPath)"
                        />
                    </div>
                    <div class="o-send_time">{{returnSended(ex.created)}}</div>
                </div>
                <div
                    class="o-button_hoe"
                >
                    <img src="../assets/hoe_button_gray.svg" />
                </div>
            </div>
        </div>

        <Footer
            :place="place"
            :user="user"
            @move_page="move_page">
        </Footer>

        <ChageName
            v-show="flag.isMounted"
            v-bind:class="{ slideIn: flag.change_name, slideOut: !flag.change_name }"
            :user_info="user_info"
            @close_modal="close_modal"
            @set_local_user_name="set_local_user_name"
        />

      </div>
  </div>
</template>

<script>
import axios from 'axios'
import HeaderGuest from './parts/HeaderGuest'
import Footer from '../components/parts/Footer'
import TourEnd from '../components/Chat_Guest/TourEnd'
import ChageName from '../components/Chat_Guest/ChangeName'
import { async } from 'q'

export default {
    name: 'chat_u',
    data() {
      return {
          place: 'chat',
          user: 'guest',
          tour_info: '',
          user_info: '',
          spot_ex: [],
          flag: {
              end: false,
              chage_name: false,
              isMounted: false
          },
          showList: []
      }
    },
    created: function () {
        //URLを直接叩かれた場合
        if(JSON.stringify(this.$route.params) != "{}" 
            && JSON.parse(this.$localStorage.get('now_tour_info'))) {
                this.move_page('top_u');
        }

        this.set_tour_info();
        this.set_user_info();
        this.init();
        this.countup_pageview();
    },
    methods: {
        init() {
            this.get_all_post();//全件取得
            setInterval(function() {
                this.get_added_post();
                //ツアー終了の処理
                this.judge_tour_isActive();
                this.break_tour_timer();

            }.bind(this), 1000);
        },
        close_modal: function() {
            this.flag.change_name = false;
        },
        set_tour_info() {
            this.$localStorage.set('user','guest');

            if(JSON.stringify(this.$route.params) != "{}") {
            //トップページから遷移してきた場合
            //再読み込み対策のローカル値
            this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info));
            //通常の呼び出し先
            this.tour_info = this.$route.params.tour_info;

            } else {
            this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
            }
        },
        async set_user_info() {
            if(this.is_exist_local_user_info()) {
                this.user_info = JSON.parse(this.$localStorage.get('user_info'));
                if( this.is_created_over_time(this.user_info.created) 
                    ||  (this.tour_info.tour_id != this.user_info.tour_id)) {
                    //最初にログインした時から9時間以上けいかしている場合 or ツアーが違う場合
                    await this.reset_user_info()
                    this.fetch_user_name_arr()
                }

            } else {
                //ローカルにデータが残っていない場合は新たに名前を設定する
                this.fetch_user_name_arr()
            }
        },
        async reset_user_info() {
            //isActivceを解除する処理
            console.log("発火")
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/reset_user_info.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("user_name_id", this.user_info.user_name_id);
            const res = await axios.post(url, params);
            this.$localStorage.remove('user_info');
        },
        is_exist_local_user_info() {
            if(this.$localStorage.get('user_info')) {
                return true;
            } else {
                return false;
            }
        },
        is_created_over_time(s_date) {
            let timestamp = Date.parse(s_date)
            //http://yut.hatenablog.com/entry/20111015/1318633937
            let now = Date.now()
            let dif = now - timestamp;
            let pass_min = dif/60/1000;
            //540分 = 9時間経過に設定
            if(pass_min > 540) {
                return true;
            } else {
                return false;
            }
        },
        async fetch_user_name_arr() {
            //名前を取ってくる
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/fetch_user_name.php";
            const res1 = await axios.post(url);

            let random = Math.floor( Math.random() * res1.data.length )
            console.log( res1.data[random] )
            const id = res1.data[random].user_name_id
            const init_name = res1.data[random].init_name
            const changeble_name = res1.data[random].init_name

            //user_infoをセット
            this.user_info = {
                "id": "",
                "user_name_id": id,
                "tour_id": this.tour_info.tour_id,
                "init_name": init_name,
                "name": changeble_name,
                "created": ""
            }
            //actice_userに登録する
            this.set_active_user(this.user_info)
        },
        async set_active_user(user_info_json) {
            const url2 = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/set_active_user.php";
            let params = new URLSearchParams();
            console.log(this.user_info)
            params.append("user_name_id", user_info_json.user_name_id);
            params.append("tour_id", user_info_json.tour_id);
            params.append("init_name", user_info_json.init_name);

            const res2 = await axios.post(url2, params);
            
            this.user_info.id = res2.data[0].id;
            this.user_info.created = res2.data[0].created;
            
            //ローカルを更新
            this.$localStorage.set('user_info',JSON.stringify(this.user_info));
            console.log("ユーザ情報が更新されました" + this.user_info);
        },
        break_tour_timer() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_tour_start_time.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    let s_date = response.data.start_time;
                    if(this.is_created_over_time(response.data.start_time)) {
                        //開始から9時間でアクセス不可にする
                        this.flag.end = true;
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
                    }
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        get_all_post() {
            const url ="https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/getPostedPost.php";
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
        get_added_post() {
            const url ="https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/getPostedPost.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    let dif = this.count_dif_spot_ex(response.data)

                    if( this.isAddedPost(dif) ) {
                        //新しく説明が追加された場合
                        this.insert_new_post(response.data);
                    } else {
                        //変化がない場合
                        this.get_all_post();
                    }
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        },
        count_dif_spot_ex(added_data) {
            let now_len = this.spot_ex.length;
            let new_len = added_data.length;
            let dif = new_len - now_len;
            return dif;
        },
        isAddedPost(dif) {
            if(dif > 0) {
                console.log("新しい説明が配信されました");
                return true;
            } else if(dif < 0) {
                console.log("説明が取り消されました");
                return false;
            }
        },
        insert_new_post(added_data) {
            let new_len = added_data.length;
            let dif = this.count_dif_spot_ex(added_data);
            added_data.splice(0, new_len - dif);
            for(let i=0; i<dif; i++) {
                this.spot_ex.push(added_data[i]);
            }
        },
        move_page: function(where) {
            this.$router.push({
                name: where
            });
        },
        returnSended(sended) {
            return sended.substr(10, 6);
        },
        isHidden(ex) {
            if(ex.isHidden == 0) {
                return false;
            } else {
                return true;
            }
        },
        show_message(id) {
            this.showList.push(id);
        },
        isExistInShowList(id) {
            if(this.showList.length === 0) {
                return false;
            }
            let flag = false;
            for(let i=0; i<this.showList.length; i++) {
                if(this.showList[i] === id) {
                    flag = true;
                    break;
                }
            }
            return flag;
        },
        change_name() {
            this.flag.isMounted = true;
            this.flag.change_name = true;
        },
        set_local_user_name(user_name_changed) {
          this.user_info.name = user_name_changed;
          this.$localStorage.remove('user_info');
          this.$localStorage.set('user_info',JSON.stringify(this.user_info));
        },
        async countup_pageview() {
            if(!this.user_info) {
                return;
            }
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/countup_pageview.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("where", this.place);
            const res = await axios.post(url, params);
        },
    },
    components: {
        HeaderGuest: HeaderGuest,
        Footer: Footer,
        TourEnd: TourEnd,
        ChageName: ChageName
    }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#chat_u {
    position: fixed;
    height: 100%;
    width: 100%;
    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
}

.l-comment_container {
    width: calc(100% - 40px);
    padding: 100px 0 0 20px;
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

.l-comment-hidden {
    padding: 20px 10px;
    background-color: #4B8E8D;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
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

.o-image {
    width: calc(100% - 45px);
    margin-right: 10px;
    border-radius: 10px;
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
