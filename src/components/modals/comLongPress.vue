<template>
  <div id="comlongpress">
      
      <div class="o-background_black">
        <div class="o-modal">
          <img 
            class="o-image"
            :src="info.image_path"
            v-show="info.image_path"
          />
          <div 
            class="o-text"
            v-show="info.spot_ex"
          >{{info.spot_ex}}</div>
          <div class="l_button">
            <div
              @click="closeModal" 
              class="o_button_cancel u_pointer">キャンセル</div>
            <div 
              @click="changeCom"
              v-show="!flag_press_img && info.spot_ex"
              class="o_button_edit u_pointer"
            >編集</div>
            <div 
              @click="deleteEx()"
              v-show="!flag_press_img"
              class="o_button_delete u_pointer">削除</div>
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
      info: '',
      flag_press_img: Boolean
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        deleteEx: function() {

          this.$emit("closeModal")
          this.$emit("confDelete")

          //先に何かしらの確認が欲しいか
        },
        changeCom() {
          this.$emit("changeCom")
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-image {
    max-width: calc(100% - 20px);
    max-height: 250px;
    margin: 10px 0 40px 0;
    border-radius: 10px;
  }

  .o-text {
    margin: 20px 10px;
    padding: 60px 20px;
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
