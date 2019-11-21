<template>
  <div id="comlongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text" @click="changeCom">編集</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-red" @click="deleteEx()">削除</div>
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
    name: 'comlongpress',
    props: {
      ex_id_avoid: '',
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        deleteEx: function() {

          //先に何かしらの確認が欲しいかも？

          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/delete_spot_ex.php';
              let params = new URLSearchParams();
              params.append('ex_id', this.ex_id_avoid);
              axios.post(url, params
              ).then(response => {
                this.$emit('get_spot_ex');
                this.closeModal();
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
        },
        changeCom() {
          this.$emit("changeCom")
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #comlongpress {
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
