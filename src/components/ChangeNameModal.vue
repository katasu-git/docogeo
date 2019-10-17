<template>
  <div id="chageNameModal">
    <div class="l-body">
        <form class="l-input_ex">
            <input class="o-input_ex" type="text" placeholder="ジオサイトの名前を入力" v-model="spot_name_updated" />
        </form>
        <button v-on:click="changeName()">保存する</button>
        <button v-on:click="closeModal()">キャンセル</button>
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
      }
    },
    created: function () {
        this.spot_name_updated = this.spot_name;
    },
    methods: {
        changeName: function() {
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
    padding: 50px 0 0 20px;
}

.o-input_ex {
    min-height: 30px;
    width: calc(100vw - 80px);
    border-radius: 5px;
}
button {
    color: white;
}

</style>
