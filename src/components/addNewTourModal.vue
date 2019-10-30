<template>
  <div id="addNewTourModal">
    <div class="l-body">
      <div class="l-text-title">
        <div class="o-text-title">
          新しくツアーを作る
        </div>
      </div>
        <form class="l-input_ex">
            <input class="o-input_ex" type="text" placeholder="ツアーの名前を入力" v-model="tour_name" />
            <input class="o-input_ex u-mt16px" type="text" placeholder="グループの名前を入力" v-model="group_name" />
        </form>
        <div class="l-button">
            <button class="o-btn-prime" v-on:click='addNewSpot()'>新規作成</button>
            <button class="o-btn-second u-mt16px" v-on:click='closeModal()'>キャンセル</button>
        </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'addNewTourModal',
    props: {
        //tour_id: Number, //親から受け取り
    },
    data() {
      return {
          tour_name: '',
          group_name: '',
      }
    },
    created: function () {
        //this.tour_name_updated = this.tour_name;
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        addNewSpot: function() {
            if (this.spot_name_new == '') {
                console.log("からです");
                return;
            }
          const url =
          "https://www2.yoslab.net/~nishimura/geotour/PHP/add_new_tour.php";
          let params = new URLSearchParams();
          //console.log("発火");
          params.append("tour_name", this.tour_name);
          params.append("group_name", this.group_name);
          axios
          .post(url, params)
          .then(response => {
            console.log("更新成功");
            //this.$emit('get_tour_name'); //名前の更新処理
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
#addNewTourModal, .l-body {
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

    padding-top: 60px;

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

</style>
