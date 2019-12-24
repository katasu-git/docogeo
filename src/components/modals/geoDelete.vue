<template>
  <div id="topdelete">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
            <div class="o-text">
                <p class="tour_name">{{ spot_name }}</p>
                <p class="u-mt20">このスポットを削除します</p>
                <p>よろしいですか？</p>
            </div>
            <div class="l-button">
                <button 
                    class="o-button_cancel"
                    @click="closeModal()"
                >キャンセル</button>
                <button 
                    class="o-button_finish"
                    @click="delete_spot()"
                >削除する</button>
            </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'topdelete',
    props: {
        spot_id: '',
        spot_name: ''
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        delete_spot() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/DELETE/delete_spot.php';
            let params = new URLSearchParams();
            params.append('spot_id', this.spot_id);
            axios.post(url, params
            ).then(response => {
                this.closeModal();
            }).catch(error => {
                // エラーを受け取る
                console.log(error);
            });
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #topdelete {
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
    width: calc(100vw - 40px);
    height: 1px;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .75);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
  }

  .o-modal {
    width: calc(100% - 40px);
    max-width: 400px;
    border-radius: 10px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .l-button {
      width: 100%;
      display: flex;
  }

  .o-button_cancel, .o-button_finish {
    padding: 20px;
    width: 50%;
    font-size: 16px;
    font-weight: bold;
    background-color: #4B8E8D;
    color: #fff;
    border-radius: 0 0 0 10px;
  }

  .o-button_finish {
    border-top: solid 1px rgba(0,0,0, .12);
    border-radius: 0 0 10px 0;
    background-color: #fff;
    color: #CC544D;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

  .u-mt10 {
      margin-top: 10px;
  }

  .u-mt20 {
      margin-top: 20px;
  }

  .u-mt40 {
      margin-top: 40px;
  }

  .tour_name {
      font-size: 30px;
  }

</style>
