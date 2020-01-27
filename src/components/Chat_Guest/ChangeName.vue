<template>
  <div id="changename">
    <div class="o-image u-mt20"></div>
    <form class="u-mt20">
        <input type="text" placeholder="新しい名前を入力" v-model="user_name_changed" />
    </form>
    <div class="o-border u-mt40"></div>
    <div class="l-button">
        <button class="o-button_cancel" v-on:click="close_modal()">キャンセル</button>
        <button class="o-button_save" v-on:click="update_spot_name()">変更を保存する</button>
    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'changename',
    props: {
      user_info: ''
    },
    data() {
        return {
            user_name_changed: ''
        }
    },
    watch: {
      user_info() {
        this.user_name_changed = this.user_info.name;
      }
    },
    methods: {
        close_modal() {
          this.$emit('close_modal');
        },
        update_spot_name() {
            const url =
            "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/change_user_name.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("user_name_changed", this.user_name_changed);
            axios
            .post(url, params)
            .then(response => {
                this.$emit('set_local_user_name', this.user_name_changed);
                this.user_name_changed = ''; //リセット
                this.close_modal();
            })
            .catch(error => {
                // エラーを受け取る
                console.log(error);
            });
        },
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  #changename {
    position: absolute;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
    width: 100%;
    max-width: 400px;
    border-radius: 30px 30px 0 0;
    background-color: #fff;
    z-index: 3;
    filter: drop-shadow(0 0 5px rgba(0,0,0,.26));

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-image {
      height: 80px;
      width: 80px;
      border-radius: 100px;
      object-fit: cover;
      background-color: rgba(0,0,0,.12);
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
  }

  input {
      width: calc(100vw - 20px);
      font-size: 18px;
      font-weight: bold;
      color: rgba(0,0,0, .87);
      text-align: center;
  }

  input::placeholder {
      font-size: 18px;
      font-weight: bold;
      color: rgba(0,0,0, .12);
      text-align: center;
  }

  .l-button {
      width: 100%;
      display: flex;
  }

  button {
      height: 50px;
      width: 50%;
      font-size: 18px;
      font-weight: bold;
  }

  .o-button_save {
      background-color: #4B8E8D;
      border: none;
      outline: none;
      color: #fff;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

  .u-mt20 {
      margin-top: 20px;
  }

  .u-mt40 {
      margin-top: 40px;
  }

</style>
