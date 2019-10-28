<template>
  <div id="chat_u">
    <div class="l-body">
        <div class="l-header">
            <div class="o-header">{{ spot_name }}</div>
        </div>
        <div class="l-chat-body">
            <div class="l-chat">
                <div class="l-comment">
                    <div class="o-comment" v-for="ex in spot_ex">{{ ex.spot_ex }}</div>
                </div>
            </div>
        </div>
        <div class="l-duck-img">
            <img class="o-duck-img" src="../assets/duck.svg" />
        </div>
        <div class="l-input">

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
      }
    },
    created: function () {
        if (JSON.stringify(this.$route.params) == "{}") {
            // 更新されたときはトップに戻る
            this.jumpPage("top_u");
        } else {
            this.tour_id = this.$route.params.tour_id;
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
  #chat_u,
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

    .l-comment {
        padding: 50px 0 0 20px;
    }

    .o-comment {
        min-height: 20px;
        width: calc(100vw - 110px);
        font-size: 12px;

        padding: 10px;

        border-radius: 5px;
        background-color: #5c9982;
        color: white;
    }

    .o-comment:not(:first-of-type) {
        margin-top: 20px;
    }

    .o-comment:last-of-type {
        margin-bottom: 20px;
    }

    .l-input {
      height: 100px;
      width: 100%;

      background-color: white;
      border-top: solid 1px rgba(0,0,0,.12);
    }

    .l-duck-img {
        height: 50px;
        width: 50px;

        position: fixed;
        right: 0;
        bottom: 100px;
    }

    .o-duck-img {
        height: 50px;
        width: 50px;
    }

</style>
