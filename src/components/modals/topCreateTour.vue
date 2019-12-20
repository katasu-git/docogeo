<template>
  <div id="createtour">
    <div class="o-image u-mt20"></div>
    <form class="u-mt20">
        <input type="text" placeholder="ここにツアーの名前を入力" v-model="tour_name_updated" />
    </form>
    <div class="o-border u-mt40"></div>
    <div class="l-button">
        <button class="o-button_cancel" v-on:click="closeModal()">キャンセル</button>
        <button class="o-button_save" v-on:click="addNewTour()">保存する</button>
    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'createtour',
    props: {
        tour_id: '',
    },
    data() {
      return {
          tour_name_updated: '',
      }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        addNewTour: function() {
            const url =
            "https://www2.yoslab.net/~nishimura/geotour/PHP/add_new_tour.php";
            let params = new URLSearchParams();
            params.append("tour_name", this.tour_name_updated);
            axios
            .post(url, params)
            .then(response => {
                console.log("更新成功");
                this.tour_name_updated = ''; //リセット
                this.closeModal();
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
  #createtour {
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
