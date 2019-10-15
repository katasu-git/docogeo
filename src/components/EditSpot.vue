<template>
  <div id="editSpot">
    <div class="l-body">
      <button class="o-backBtn" v-on:click='jumpPage("editTour")'>ジオサイトの選択に戻る</button>
      <div class="l-justify-center">
        <div class="l-tour_info">
          <div class="o-tour_name-text">{{ spot_name }}</div>
        </div>
      </div>
      <div class="l-images">
        <div class="o-img_box-add"></div>
        <div class="o-img_box" v-for="img in images"></div>
      </div>
      <form class="l-input_ex">
        <input class="o-input_ex" type="text" placeholder="追加する説明を入力" v-model="text" />
        <button v-on:click="innsert_spot_ex()">追加</button>
      </form>
      <div class="l-comment">
        <div class="o-comment" v-for="ex in spot_ex">{{ ex.spot_ex }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "editSpot",
  data() {
    return {
      images: 5,
      comments: 3,
      tour_id: Number,
      spot_id: Number,
      spot_name: "ジオサイトの名前を入力",
      spot_ex: JSON,
      text: 'asmdl;amd',
    };
  },
  created: function() {
    if (JSON.stringify(this.$route.params) == "{}") {
      // 更新されたときはトップに戻る
      this.jumpPage("editTour");
    } else {
      this.spot_id = this.$route.params.spot_id;
      if (this.$route.params.spot_name != undefined) {
        this.spot_name = this.$route.params.spot_name;
      }
      this.accessDb();
    }
  },
  methods: {
    accessDb: function() {
      const url =
        "https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_ex.php";
      let params = new URLSearchParams();
      //console.log("発火");
      params.append("spot_id", this.spot_id);
      axios
        .post(url, params)
        .then(response => {
          this.spot_ex = response.data;
          this.tour_id = this.spot_ex[0].tour_id;
        })
        .catch(error => {
          // エラーを受け取る
          console.log(error);
        });
    },
    innsert_spot_ex: function() {
      const url =
        "https://www2.yoslab.net/~nishimura/geotour/PHP/insert_spot_ex.php";
      let params = new URLSearchParams();
      params.append("tour_id", this.tour_id);
      params.append("spot_id", this.spot_id);
      params.append("spot_ex", this.text);
      axios.post(url, params
      ).then(response => {
        console.log("挿入に成功しました");
      }).catch(error => {
        // エラーを受け取る
        console.log(error);
      });
    },
    jumpPage: function(where) {
      this.$router.push({
        name: where,
        params: {
          tour_id: this.tour_id
        }
      });
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#editSpot,
.l-body {
  height: 100%;
  width: 100%;
  overflow-x: hidden;
}

.l-body {
  background: #5c9982;
}

.l-justify-center {
  display: flex;
  justify-content: center;
  /*中央よせ*/
}

.l-tour_info {
  padding: 40px;

  display: flex;
  flex-direction: column;
  justify-content: center;
  /*中央よせ*/
  align-items: center;
}

.o-tour_name-text {
  font-size: 20px;
  font-weight: bold;
  color: white;
}

.o-backBtn {
  padding: 20px;
  font-size: 12px;
  color: white;
}

.l-images {
  width: 100vw;
  padding-left: 20px;

  display: flex;
  overflow: scroll;
}

.o-img_box {
  min-height: 60px;
  min-width: 60px;
  background: white;
}

.o-img_box:not(:first-of-type) {
  margin-left: 10px;
}

.o-img_box-add {
  min-height: 60px;
  min-width: 60px;
  border: dashed 1px white;
}

.l-input_ex {
  padding: 50px 0 0 20px;
}

.o-input_ex {
  min-height: 30px;
  width: calc(100vw - 80px);
  border-radius: 5px;
}

.l-comment {
  padding: 50px 0 0 20px;
}

.o-comment {
  min-height: 20px;
  width: calc(100vw - 80px);
  font-size: 12px;

  padding: 10px;

  border-radius: 5px;
  background-color: white;
}

.o-comment:not(:first-of-type) {
  margin-top: 20px;
}
</style>
