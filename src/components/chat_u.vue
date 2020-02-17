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
        
        <!--
        <ChageName
            v-show="flag.isMounted"
            v-bind:class="{ slideIn: flag.change_name, slideOut: !flag.change_name }"
            :user_info="user_info"
            @close_modal="close_modal"
            @set_local_user_name="set_local_user_name"
        />
        -->

        <HeaderGuest
            :tour_info="tour_info"
            :user_info="user_info"
            @change_name="change_name"
        />

        <transition name="fade">
        <LookImage
            v-show="flag.lookImage"
            :src="src_selected"
            @close_modal="close_modal"
        />
        </transition>
        
        <div 
            v-if="!isPostExist()"
            class="nothing_here"
        >
            <img 
                src="../assets/kujira.png"
                class="kujira"
            />
            <p>ようこそ<span class="fs20"> {{user_info.name}} </span>さん！</p>
            <p class="u-mt20 sub-text">{{user_info.comment}}</p>
            <!--
            <p class="u-mt20 sub-text">くじらの名前をもじったニックネームです</p>
            -->
        </div>

        <div id="l-comment_container">
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
                    <div 
                        @click="lookImage(ex.image_path)"
                        v-else>
                        <div
                            class="l-comment"
                            v-if="ex.spot_ex"
                        >
                            <div class="kaigyo">{{ ex.spot_ex }}</div>
                        </div>
                        <div class="rel">
                            <v-lazy-image 
                                v-if="ex.image_path"
                                class="o-image"
                                :src="(ex.image_path)"
                            />
                            <div class="likes">
                                <img class="u-mr5" src="../assets/like_whole.svg" />
                                {{ex.likes}}
                            </div>
                        </div>
                    </div>
                    <div class="o-send_time">{{returnSended(ex.created)}}</div>
                </div>
                <div
                    class="o-button_hoe"
                >
                    <img 
                        @click="done_like(ex)"
                        src="../assets/hoe_button_gray.svg"
                        v-if="!isLiked(ex.ex_id)"
                    />
                    <img 
                        @click="done_like(ex.ex_id)"
                        src="../assets/hoe_button.svg"
                        v-else
                    />
                </div>
            </div>
        </div>
        <div id="chat-log" />

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
import HeaderGuest from './parts/HeaderGuest'
import Footer from '../components/parts/Footer'
import TourEnd from '../components/Chat_Guest/TourEnd'
import ChageName from '../components/Chat_Guest/ChangeName'
import LookImage from '../components/Chat_Guest/LookImage'
import { async } from 'q'

export default {
    name: 'chat_u',
    data() {
      return {
          place: 'userChat',
          user: 'guest',
          tour_info: '',
          user_info: '',
          spot_ex: [],
          flag: {
            end: false,
            chage_name: false,
            isMounted: false,
            lookImage: false
          },
          showList: [],
          showLike: [],
          src_selected: '',
          comment: ''
      }
    },
    created: function () {
        this.init();
        this.countup_pageview();
    },
    methods: {
        init() {
            this.set_tour_info();
            
            //ツアー終了の判定
            this.judge_tour_isActive();
            this.break_tour_timer();
            ///////////////

            this.set_user_info();

            this.get_all_post();//全件取得

            this.set_likes();//いいねを反映

            setInterval(function() {
                this.get_added_post();
                //this.get_all_post();//全件取得
            }.bind(this), 1000);
        },

        set_tour_info() {
            if(JSON.stringify(this.$route.params) != "{}") {
                //ローカルストレージの初期化
                this.$localStorage.remove('now_tour_info');
                //再読み込み対策のローカル値
                this.$localStorage.set('now_tour_info',JSON.stringify(this.$route.params.tour_info));
                //通常の呼び出し先
                this.tour_info = this.$route.params.tour_info;

            } else {
                this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
            }
        },

        async set_user_info() {
            if(!this.is_exist_local_user_info()) {

                this.set_new_user_name()
            
            } else {
                this.user_info = JSON.parse(this.$localStorage.get('user_info'));
                
                if( this.is_created_over_time(this.user_info.created) || (this.tour_info.tour_id != this.user_info.tour_id)) {

                    //最初にログインした時から9時間以上経過している場合 or ツアーが違う場合
                    await this.reset_user_info()
                    this.set_new_user_name()

                }
            }
        },

        async set_new_user_name() {
            let whale_info = await this.fetch_ramdom_whale_info()
            //user_infoをセット
            this.user_info = {
                "id": "",
                "user_name_id": whale_info.user_name_id,
                "tour_id": this.tour_info.tour_id,
                "init_name": whale_info.init_name,
                "name": whale_info.init_name,
                "created": "",
                "comment": whale_info.comment
            }
            //actice_userに登録する
            this.set_active_user(this.user_info)
        },

        async fetch_ramdom_whale_info() {
            //鯨の名前を取ってくる
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/fetch_user_name.php";
            const res1 = await axios.post(url);

            this.comment = res1.data.comment;
            let random = Math.floor( Math.random() * res1.data.length )
            return res1.data[random];

        },

        async set_active_user(user_info_json) {
            const url2 = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/set_active_user.php";
            let params = new URLSearchParams();
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

        async reset_user_info() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/reset_user_info.php";
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

        set_likes() {
            if(JSON.parse(this.$localStorage.get('like_array'))) {
                this.showLike = JSON.parse(this.$localStorage.get('like_array'))
            }
        },

        is_created_over_time(s_date) {
            console.log(s_date)
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
        
        break_tour_timer() {

            let s_date = this.tour_info.start_time;
            if(this.is_created_over_time(this.tour_info.start_time)) {
                //開始から9時間でアクセス不可にする
                this.flag.end = true;
            }

        },

        judge_tour_isActive() {
            const url ="https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/judge_tour_isActive.php";
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

        },

        get_all_post() {
            const url ="https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/getPostedPost.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    this.spot_ex = response.data;
                })
        },

        get_added_post() {
            const url ="https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/getPostedPost.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            axios
                .post(url, params)
                .then(response => {
                    let dif = this.count_dif_spot_ex(response.data)

                    if( this.isAddedPost(dif) ) {
                        console.log(response.data);
                        this.insert_new_post(response.data);
                    } else {
                        //変化がない場合
                        this.get_all_post();
                    }
                })

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
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/countup_pageview.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("where", this.place);
            const res = await axios.post(url, params);
        },

        isLiked(id) {
            let len = this.showLike.length;
            let flag = false;
            for(let i=0; i<len; i++) {
                if(this.showLike[i] === id) {
                    flag = true;
                    break;
                }
            }
            return flag;
        },

        done_like(ex) {
            if(!this.isLiked(ex.ex_id)) {
                this.showLike.push(ex.ex_id);
                this.$localStorage.remove('like_array');
                //再読み込み対策のローカル値
                this.$localStorage.set('like_array',JSON.stringify(this.showLike));
                this.countup_likes(ex);
            } else {

                return; //いいねは取り消さない
                /*
                this.showLike = JSON.parse(this.$localStorage.get('like_array'));
                let len = this.showLike.length;
                for(let i=0; i<len; i++) {
                    if(this.showLike[i] === id) {
                        this.showLike.splice(i,1);
                        this.$localStorage.remove('like_array');
                        //再読み込み対策のローカル値
                        this.$localStorage.set('like_array',JSON.stringify(this.showLike));
                        break;
                    }
                }
                */
            }
        },

        async countup_likes(ex) {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_U/countup_likes.php";
            let params = new URLSearchParams();
            params.append("dist_id", ex.ex_id); //distの主キー
            params.append("img_flag", this.isImage(ex.image_path));
            params.append("ex_id", ex.exp_id); //spot_explanationの主キー
            params.append("image_id", ex.img_id);
            params.append("tour_id", ex.tour_id);
            params.append("spot_id", ex.spot_id);
            params.append("spot_image_id", ex.spot_image_id);
            const res = await axios.post(url, params);
            console.log("処理完了")
        },

        isImage(img_path) {
            if(img_path) {
                return true;
            } else {
                return false;
            }
        },

        isPostExist() {
            if(this.spot_ex.length > 0) {
                return true;
            } else {
                return false;
            }
        },

        lookImage(src) {
            if(!src) {
                return;
            }
            this.src_selected = src;
            this.flag.lookImage  = true;
            console.log("click")
        },
        
        close_modal: function() {
            this.flag.change_name = false;
            this.flag.lookImage = false;
        },
    },
    components: {
        HeaderGuest: HeaderGuest,
        Footer: Footer,
        TourEnd: TourEnd,
        ChageName: ChageName,
        LookImage: LookImage
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

.o-background {
    height: 100%;
    width: 100%;
    -webkit-overflow-scrolling: touch; /*ios対応*/
    overflow: scroll;
}

#l-comment_container {
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
    position: relative;
    padding: 10px;
    background-color: #E3E5E5;
    border-radius: 10px;
}

.rel {
    position: relative;
}

.likes {
    margin-top: -10px;
    position: absolute;
    right: 0;
    background-color: #fff;
    width: 40px;
    height: 20px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,.25));
    border-radius: 40px;

    display: flex;
    justify-content: center;
    align-items: center;

    font-size: 10px;
    color: #4B8E8D;
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
    position: relative;
    width: 100%;
    max-width: 300px;
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

.nothing_here {
    position: absolute;
    top: -10%;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.kujira {
    width: calc(100% - 120px);
    max-width: 300px;
}

.fs20 {
    font-size: 30px;
    font-weight: bold;
    color: #4B8E8D;
}

.u-mr5 {
    margin-right: 5px;
}

</style>
