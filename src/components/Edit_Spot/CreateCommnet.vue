<template>
  <div id="create">
    <form class="u-mt40">
        <textarea placeholder="説明を入力" v-model="comment" cols="50" rows="5"></textarea>
    </form>
    <div class="o-border u-mt40"></div>
    <div class="l-button">
        <button class="o-button_cancel" v-on:click="close_modal()">キャンセル</button>
        <button class="o-button_save" v-on:click="create_selected()">保存する</button>
    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'create',
    props: {
        tour_info: '',
        spot_info: '',
    },
    data() {
        return {
            comment: ''
        }
    },
    methods: {
        close_modal: function() {
            this.$emit('close_modal');
        },
        create_selected: function() {
            this.test();
            const url =
            "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Edit_Spot/create_comment.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            params.append("spot_id", this.spot_info.spot_id);
            params.append("comment", this.comment);
            axios
            .post(url, params)
            .then(response => {
                this.comment = ''; //リセット
                this.close_modal();
            })
            .catch(error => {
                // エラーを受け取る
                console.log(error);
            });
        },
        test() {
          console.log(this.comment)
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  #create {
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
