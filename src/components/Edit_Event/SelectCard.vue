<template>
  <div id="toplongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
            <div class="o-text">{{ tour_info.tour_name }}</div>
          <div class="o-text_sub"></div>
          <div class="l_button">
            <div
              @click="close_modal"
              class="o_button_cancel u_pointer">キャンセル</div>
            <div
            @click="move_page('editTour')"
            class="o_button_edit u_pointer">編集</div>
            <div 
              @click="start_tour()"
              class="o_button_delete u_pointer">ツアー開始</div>
          </div>
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
        tour_info: ''
    },
    methods: {
        close_modal: function() {
            this.$emit('close_modal');
        },
        move_page(where) {
            this.$emit('move_page', where, this.tour_info);
        },
        start_tour() {

          if(this.tour_info.isActive == 0) {
            //該当ツアー開始処理
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/start_tour.php';
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_info.tour_id);
            axios
                .post(url, params).then(response=>{
                  //配信ページに移動
                  this.move_page('chat_g');
                })
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
          } else {
            this.move_page('chat_g');
          }

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
    height: 100%;
    width: 100%;
    position: fixed;
    z-index: 1;
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
    justify-content: center;
    z-index: 1;
  }

  .o-modal {
    width: calc(100% - 40px);
    max-width: 400px;
    border-radius: 10px;
    filter: drop-shadow(0 2px 5px rgba(0,0,0,.26));
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    margin: 20px 10px;
    padding: 40px 20px 0 20px;
    font-size: 20px;
    font-weight: bold;
    transition: 100ms;
    border-radius: 5px;
  }

  .o-text_sub {
    margin: 20px 10px;
    padding: 0 0 20px 0;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
    border-radius: 5px;
  }

  .l_button {
    position: relative;
    height: 40px;
    width: 100%;
  }

  .o_button_cancel {
    position: absolute;
    left: 20px;
    font-size: 12px;
    font-weight: bold;
    width: 70px;
    color: #4B8E8D;
    text-align: center;
  }

  .o_button_edit {
    position: absolute;
    left: 90px;
    font-size: 12px;
    font-weight: bold;
    width: 30px;
    text-align: center;
  }

  .o_button_delete {
    position: absolute;
    right: 20px;
    font-size: 12px;
    padding: 5px;
    margin-top: -5px;
    font-weight: bold;
    width: 80px;
    text-align: center;
    background-color: #4B8E8D;
    color: white;
    border-radius: 100px;
  }

</style>
