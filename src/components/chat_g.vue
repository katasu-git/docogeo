<template>
  <div id="chat_g">
    <div class="l-body">
        <div class="l-header">
            <div class="o-header">{{ spot_name }}</div>
        </div>
        <div class="l-chat-body">
            <div class="l-chat">
                <!-- <div class="" v-for="spot in spot_ex">
                    {{ spot.spot_ex }}
                </div> -->
                <div class="l-comment">
                    <div class="o-comment" v-for="ex in spot_ex">{{ ex.spot_ex }} : {{ ex.isPosted }}
                        <button v-on:click="postEx(ex.ex_id, ex.isPosted)">配信</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="l-input">

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
          spot_id: 1,
          spot_ex: JSON,
          spot_name: 'かりの名前',
      }
    },
    created: function () {
        if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("HelloWorld");
        } else {
            this.tour_id = this.$route.params.tour_id;
            this.spot_id = this.$route.params.spot_id;
            //this.spot_name = this.$route.params.spot_name;
            this.getPost();
        }
    },
    methods: {
        getPost: function() {
            const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/getPost.php";
            let params = new URLSearchParams();
            //console.log("発火");
            params.append("spot_id", this.spot_id);
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
                    if(response.data[this.spot_id - 1].spot_name != undefined) {
                        this.spot_name = response.data[this.spot_id - 1].spot_name;
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
        postEx: function(id, isPosted) {
            if(isPosted == 0) {

                const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/isPosted_t.php';
                let params = new URLSearchParams();
                params.append("ex_id", id);
                //params.append("isPosted", is);
                axios
                    .post(url, params)
                    .then(response => {
                        this.get_spot_name(); //ちゃんとdb叩いてデータ持ってくる
                        this.getPost(); //ちゃんとdb叩いてデータ持ってくる
                        console.log("成功");
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            } else if(isPosted == 1) {

                const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/isPosted_f.php';
                let params = new URLSearchParams();
                params.append("ex_id", id);
                //params.append("isPosted", is);
                axios
                    .post(url, params)
                    .then(response => {
                        this.get_spot_name(); //ちゃんとdb叩いてデータ持ってくる
                        this.getPost(); //ちゃんとdb叩いてデータ持ってくる
                        console.log("成功");
                    })
                    .catch(error => {
                        // エラーを受け取る
                        console.log(error);
                    });

            }
        }
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #chat_g,
  .l-body {
    height: 100%;
    width: 100%;
  }

  .l-body {
    background: #5c9982;
  }

  .l-header {
    height: 100px;
    width: 100%;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-header {
      color: white;
      font-size: 18px;
      font-weight: bold;
  }

  .l-chat-body {
      height: calc(100% - 200px);
      width: 100%;
      background: white;

      overflow: scroll;
      -webkit-overflow-scrolling: touch; /* 惰性でスクロールさせる */
  }

  .l-input {
      height: 100px;
      width: 100%;

      background-color: white;
      border-top: solid 1px rgba(0,0,0,.12);
  }

    .l-comment {
        padding: 50px 0 0 20px;
    }

    .o-comment {
        min-height: 20px;
        width: calc(100vw - 80px);
        font-size: 12px;

        padding: 10px;

        border-radius: 5px;
        background-color: #5c9982;
    }

    .o-comment:not(:first-of-type) {
        margin-top: 20px;
    }

    .o-comment:last-of-type {
        margin-bottom: 20px;
    }

</style>
