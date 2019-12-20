<template>
  <div id="comaddcomment">
            <form class="u-mt20">
                <textarea type="text" placeholder="ここに説明を入力" v-model="comment"></textarea>
            </form>
            <div class="l-button">
                <button class="o-button_cancel" @click="closeModal()">キャンセル</button>
                <button class="o-button_save" @click="addComment()">追加する</button>
            </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'comaddcomment',
    props: {
        tour_id: '',
        spot_id: '',
    },
    data() {
      return {
          comment: '',
      }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        addComment: function() {
            if(this.comment == '') {
                return;
            }
            const url =　"https://www2.yoslab.net/~nishimura/geotour/PHP/insert_spot_ex.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_id);
            params.append("spot_id", this.spot_id);
            params.append("spot_ex", this.comment);
            console.log(this.tour_id);
            console.log(this.spot_id);
            console.log(this.comment);
            axios
                .post(url, params)
                .then(response => {
                    this.comment = '';//コメントの初期化
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
  #comaddcomment {
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

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .54);

    display: flex;
    align-items: flex-end;
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
      width: 100%;
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
