<template>
  <div id="edit">
    <div class="o-image u-mt20"></div>
    <form class="u-mt20">
        <textarea placeholder="説明を入力" v-model="selected_changed" cols="50" rows="5"></textarea>
    </form>
    <div class="o-border u-mt40"></div>
    <div class="l-button">
        <button class="o-button_cancel" v-on:click="close_modal()">キャンセル</button>
        <button class="o-button_save" v-on:click="update_spot_ex()">変更を保存する</button>
    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'edit',
    props: {
        selected: ''
    },
    data() {
        return {
            selected_changed: ''
        }
    },
    watch: {
      selected: function() {
        this.selected_changed = this.selected.spot_ex;
        console.log(this.selected);
      }
    },
    methods: {
        close_modal: function() {
            this.$emit('close_modal');
        },
        update_spot_ex: function() {
            const url =
            "https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Spot/update_spot_ex.php";
            let params = new URLSearchParams();
            params.append("id", this.selected.id);
            params.append("selected_changed", this.selected_changed);
            axios
            .post(url, params)
            .then(response => {
                this.selected_changed = ''; //リセット
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

  #edit {
    position: absolute;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
    width: 100%;
    max-width: 400px;
    border-radius: 30px 30px 0 0;
    background-color: #fff;
    z-index: 1;
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

  form {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  textarea {
    padding: 20px;
    font-size: 16px;
    width: calc(100% - 80px);
    border-radius: 10px;
    border: 1px solid rgba(0,0,0, .12);
    outline: none;
  }

</style>
