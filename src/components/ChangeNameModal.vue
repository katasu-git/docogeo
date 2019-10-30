<template>
  <div id="chageNameModal">
    <div class="l-body">
        <form class="l-input_ex">
            <input class="o-input_ex" type="text" placeholder="スポットの名前を入力" v-model="spot_name_updated" />
        </form>
        <div class="l-err_message">
          <div class="o-err_message" v-show="errFlag()">名前を入力してください！</div>
        </div>
        <div class="l-button">
            <button class="o-btn-prime" v-on:click='changeName()'>保存する</button>
            <button class="o-btn-second u-mt16px" v-on:click='closeModal()'>キャンセル</button>
        </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'changeNameModal',
    props: {
        spot_id: Number,
        spot_name: String
    },
    data() {
      return {
          spot_name_updated: '',
          errFlag_first: false,
      }
    },
    created: function () {
        this.spot_name_updated = this.spot_name;
    },
    methods: {
        changeName: function() {
          if (this.spot_name_updated == '') {
                this.errFlag_first = true;
                return;
          }
        const url =
        "https://www2.yoslab.net/~nishimura/geotour/PHP/update_spot_name.php";
        let params = new URLSearchParams();
        //console.log("発火");
        params.append("spot_id", this.spot_id);
        params.append("spot_name_updated", this.spot_name_updated);
        axios
        .post(url, params)
        .then(response => {
          console.log("更新成功");
          this.$emit('get_spot_name'); //名前の更新処理
          this.closeModal();
        })
        .catch(error => {
          // エラーを受け取る
          console.log(error);
        });
        },
        closeModal: function() {
            this.$emit('closeModal');
        },
        errFlag() {
          if (this.errFlag_first && this.spot_name_updated == '') {
                return true;
          }
        }
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#chageNameModal, .l-body {
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
