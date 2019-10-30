<template>
  <div id="chageTourNameModal">
    <div class="l-body">
        <form class="l-input_ex">
            <input class="o-input_ex" type="text" placeholder="ツアーの名前を入力" v-model="tour_name_updated" />
        </form>
        <div class="l-err_message">
          <div class="o-err_message" v-show="errFlag()">名前を入力してください！</div>
        </div>
        <div class="l-button">
            <button class="o-btn-prime" v-on:click='changeTourName()'>保存する</button>
            <button class="o-btn-second u-mt16px" v-on:click='closeModal()'>キャンセル</button>
        </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'changeTourNameModal',
    props: {
        tour_id: Number,
        tour_name: String,
    },
    data() {
      return {
          tour_name_updated: '',
          errFlag_first: false,
      }
    },
    created: function () {
        this.tour_name_updated = this.tour_name;
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        changeTourName: function() {
          if (this.tour_name_updated == '') {
                this.errFlag_first = true;
                return;
          }
          const url =
          "https://www2.yoslab.net/~nishimura/geotour/PHP/update_tour_name.php";
          let params = new URLSearchParams();
          //console.log("発火");
          params.append("tour_id", this.tour_id);
          params.append("tour_name_updated", this.tour_name_updated);
          axios
          .post(url, params)
          .then(response => {
            console.log("更新成功");
            this.$emit('get_tour_name'); //名前の更新処理
            this.closeModal();
          })
          .catch(error => {
            // エラーを受け取る
            console.log(error);
          });
        },
        errFlag() {
          if (this.errFlag_first && this.tour_name_updated == '') {
                return true;
          }
        }
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#chageTourNameModal, .l-body {
    height: 100%;
    width: 100%;
    position: fixed;
}

.l-body {
    background: rgba(0,0,0,.85);
}

.l-input_ex {
  width: 100vw;
  padding-top: 50px;

  display: flex;
  flex-direction: column;
  align-items: center;
}

.o-input_ex {
    min-height: 60px;
    width: calc(100vw - 40px);
    border-radius: 5px;
    font-size: 16px;
    background: rgba(255, 255, 255, .7);
}

button {
    color: white;
}

.u-mt16px {
    margin-top: 16px;
}

.l-button {
    width: 100%;

    padding-top: 40px;

    display: flex;
    flex-direction: column;
    align-items: center;
}

.o-btn-prime {
    height: 60px;
    width: calc(100vw - 40px);
    border: solid 0 white;
    border-radius: 100px;
    background-color: #F7941E;

    font-size: 20px;
    font-weight: bold;
}

.o-btn-second {
    height: 60px;
    width: calc(100vw - 40px);
    border: solid 2px white;
    border-radius: 100px;
    background-color: rgba(0,0,0,0);

    font-size: 20px;
    font-weight: bold;
}

.l-text-title {
    padding: 40px 0 0 20px;
}

.o-text-title {
  font-size: 20px;
  font-weight: bold;
  color: white;
}

.l-err_message {
  padding: 20px 0 0 20px;
}

.o-err_message {
  color: red;
}

</style>
