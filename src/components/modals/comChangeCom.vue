<template>
  <div id="comchangecom">
    <form class="u-mt20">
        <textarea type="text" placeholder="ここに説明を入力" v-model="comment"></textarea>
    </form>
    <div class="l-button">
        <button class="o-button_cancel" @click="closeModal()">キャンセル</button>
        <button class="o-button_save" @click="changeCom()">保存する</button>
    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'comchangecom',
    props: {
        ex_id_avoid: '',
        ex_avoid: ''
    },
    data() {
      return {
          comment: '',
      }
    },
    watch: {
      ex_avoid: function() {
        console.log("発火");
        this.comment = this.ex_avoid;
      }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        changeCom: function() {
            if(this.comment == '') {
                return;
            }
            const url =　"https://www2.yoslab.net/~nishimura/geotour/PHP/update_comment.php";
            let params = new URLSearchParams();
            params.append("ex_id", this.ex_id_avoid);
            params.append("spot_explanation", this.comment);
            axios
                .post(url, params)
                .then(response => {
                    this.closeModal();
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
  #comchangecom {
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

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-border {
    height: 1px;
    background-color: rgba(0,0,0, .12);
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
  }

  textarea {
      outline: none;
      margin: 20px 0;
      resize: vertical;
      width: calc(100vw - 60px);
      min-height: 80px;
      font-size: 18px;
      color: rgba(0,0,0, .87);
      text-align: center;
      border: solid 1px rgba(0,0,0, .12);
      border-radius: 10px;
  }

  input::placeholder {
      font-size: 18px;
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
