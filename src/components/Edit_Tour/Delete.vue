<template>
  <div id="delete">
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text">{{ spot_info.spot_name }}</div>
          <div class="o-text_sub">このスポットを削除します。よろしいですか？</div>
          <div class="l_button">
            <div
              @click="close_modal"
              class="o_button_cancel u_pointer">キャンセル</div>
            <div 
              @click="delete_spot()"
              class="o_button_delete u_pointer">削除</div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'delete',
    props: {
        tour_info: '',
        spot_info: ''
    },
    methods: {
        close_modal: function() {
            this.$emit('close_modal');
        },
        delete_spot() {
            const url =　"https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Tour/delete_spot.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            params.append("spot_id", this.spot_info.spot_id);
            axios
            .post(url, params)
            .then(response => {
              this.close_modal();
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
  #delete {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    height: 100%;
    width: 100%;
    position: fixed;
    z-index: 1;
  }
  .o-background_black {
    height: 100%;
    width: 100%;
    background-color: rgba(0,0,0, .54);
    position: fixed;
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
  }

  .o_button_edit {
    position: absolute;
    right: 60px;
    font-size: 12px;
    font-weight: bold;
    width: 30px;
  }

  .o_button_delete {
    position: absolute;
    right: 20px;
    font-size: 12px;
    font-weight: bold;
    width: 30px;
    color: #CC544D;
  }

</style>
