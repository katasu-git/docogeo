<template>
  <div id="chageTourNameModal">
    <div class="l-body">
        <form class="l-input_ex">
            <input class="o-input_ex" type="text" placeholder="ツアーの名前を入力" v-model="tour_name_updated" />
        </form>
        <button v-on:click="changeTourName()">保存する</button>
        <button v-on:click="closeModal()">キャンセル</button>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'changeTourNameModal',
    props: {
        tour_id: Number,
        tour_name: String
    },
    data() {
      return {
          tour_name_updated: '',
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
