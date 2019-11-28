<template>
  <div id="toplongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text" @click="startTour()">ツアー開始</div>
          <div class="o-border u-mt10"></div>
        　<div class="o-text" @click="jumpPage('editTour')">編集</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text" @click="wakeChangeName">名前の変更</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-red" @click="deleteTour()">削除</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-green" @click='closeModal()'>キャンセル</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
  export default {
    name: 'toplongpress',
    props: {
        tour_id: '',
        tour_name: '',
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        wakeChangeName() {
          this.$emit('wakeChangeName');
        },
        jumpPage: function(where, tour_id, tour_name) {
            //console.log(this.avoidParam.tour_id);
            this.$router.push({
                name: where,
                params: {
                tour_id: this.tour_id,
                tour_name: this.tour_name,
                }
            })
        },
        deleteTour() {
            const url =　"https://www2.yoslab.net/~nishimura/geotour/PHP/delete_tour.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_id);
            axios
            .post(url, params)
            .then(response => {
              this.closeModal();
            })
            .catch(error => {
              // エラーを受け取る
              console.log(error);
            });
        },
        startTour() {

          ////////////////////////

          //ここに開催中のツアーがある場合はreturnする処理
          //ツアー終了後でないと次のツアーは開催できない

          ////////////////////////

          console.log("hello");

          //該当ツアー開始処理
          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/start_tour.php';
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_id);
            axios
                .post(url, params).then(response=>{
                  //配信ページに移動
                  this.jumpPage("chat_g");
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #toplongpress {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-border {
    height: 1px;
    width: 100%;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .35);

    display: flex;
    align-items: center;
    z-index: 1;
  }

  .o-modal {
    width: 100px;
    border-radius: 0 30px 30px 0;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    padding: 20px;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
  }

  .o-text:first-of-type {
    border-top-right-radius: 20px;
    background-color: #4B8E8D;
    color: #fff;
  }

  .o-text:active {
    opacity: .7;
    transform: scale(1.2);
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
