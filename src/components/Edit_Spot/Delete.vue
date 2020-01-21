<template>
  <div id="delete">
      <div class="o-background_black">
        <div class="o-modal">
          <img 
            class="o-image"
            :src="selected.image_path"
            v-show="selected.image_path"
          />
          <div 
            class="o-text"
            v-show="!selected.image_path"
          >「{{ selected.spot_ex }}」</div>
          <div class="o-text_sub">この{{return_text()}}を削除します。よろしいですか？</div>
          <div class="l_button">
            <div
              @click="close_modal"
              class="o_button_cancel u_pointer">キャンセル</div>
            <div 
              @click="delete_spot_ex()"
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
        selected: '',
        isImage: ''
    },
    methods: {
        close_modal: function() {
            this.$emit('close_modal');
        },
        delete_spot_ex() {
          let url;
          if(this.isImage) {
            url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Edit_Spot/delete_spot_image.php";
          } else {
            url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Edit_Spot/delete_spot_ex.php";
          }
            let params = new URLSearchParams();
            params.append("id", this.selected.id);
            axios
            .post(url, params)
            .then(response => {
              this.close_modal();
            })
            .catch(error => {
              // エラーを受け取る
              console.log(error);
            });
        },
        return_text() {
          if(this.isImage) {
            return '画像';
          } else {
            return '説明'
          }
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

  .o-text {
    margin: 20px 10px;
    padding: 40px 0 20px 0;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
    border-radius: 5px;
  }

  .o-image {
    max-width: calc(100% - 20px);
    max-height: 250px;
    margin: 10px 0 40px 0;
    border-radius: 10px;
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
