<template>
  <div id="editTour">
    <div class="o-background">

      <GeoLongPress v-show="flag"
        @closeModal="closeModal" @wakeChangeNameModal="wakeChangeNameModal"></GeoLongPress>

      <GeoChangeName v-show="flag_name"
        @closeModal="closeModal" @update_spot_name="update_spot_name"></GeoChangeName>
      
      <div class="l-header_above">
        <div class="o-text_tour">Geosite</div>
        <div class="o-image_image_button"><img src="../assets/sort_button.svg" /></div>
      </div>
      <div class="l-header_under u-mb20">
        <div class="o-text_tour_min">ジオサイト</div>
        <div class="o-text_add_image">並べ替え</div>
      </div>
      <div class="o-list" v-long-press="300" @long-press-start="onPlusStart(info.spot_id, info.spot_name)"
          v-for="info in spot_info" v-on:click='jumpPage("editSpot", info.spot_id, info.spot_name)'>
        <div class="l-image_text">
          <div class="o-list_image"><img class="o-image_circle" src="../assets/sample.jpg" /></div>
          <div class="l-list_text">
            <div id="o-list_text_geosite">{{ info.spot_name }}</div>
            <div class="o-list_text_update">最終更新 2019.11.7</div>
          </div>
        </div>
        <div class="o-border u-mt10"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import GeoLongPress from '../components/modals/geoLongPress'
  import GeoChangeName from '../components/modals/geoChageName'
  export default {
    name: 'editTour',
    data() {
      return {
          spot_info: JSON,
          tour_id: Number,
          flag: false,
          flag_name: false,
          spot_id_avoid: '', //名前を変更する時に呼び出し
      }
    },
    created: function () {
      if(JSON.stringify(this.$route.params) == "{}") {
        // 更新されたときはトップに戻る
        this.jumpPage("HelloWorld");
      } else {
        this.tour_id = this.$route.params.tour_id;
        this.get_spot_info();
      }
    },
    methods: {
      get_spot_info: function () {
        const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php';
        let params = new URLSearchParams();
        params.append('tour_id', this.tour_id);
        axios.post(url, params
        ).then(response => {
          this.spot_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      update_spot_name: function (spot_name_updated) {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/update_spot_name.php';
            let params = new URLSearchParams();
            params.append('spot_id', this.spot_id_avoid);
            params.append('spot_name_updated', spot_name_updated);
            axios.post(url, params
            ).then(response => {
                this.closeModal();
            }).catch(error => {
                // エラーを受け取る
                console.log(error);
            });
      },
      jumpPage: function(where, spot_id, spot_name) {
        this.$router.push({
            name: where,
            params: {
              tour_id: this.tour_id,
              spot_id: spot_id,
              spot_name: spot_name
            }
        })
      },
      onPlusStart: function(spot_id, spot_name)  {
        this.spot_id_avoid = spot_id
        this.flag = true;
      },
      closeModal: function() {
        this.flag = false;
        this.flag_name = false;
        this.get_spot_info(); //名前の更新を反映
      },
      wakeChangeNameModal: function() {
        this.flag = false; //前のモーダルを閉じる
        this.flag_name = true;
      }
    },
    components: {
      GeoLongPress: GeoLongPress,
      GeoChangeName: GeoChangeName,
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #editTour, .o-background {
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
  }

  .l-header_above {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }

    .o-text_tour {
      padding: 20px 0 0 20px;

      font-size: 36px;
      font-weight: bold;
    }

    .o-image_image_button {
      padding: 0 20px 0 20px;
    }

  .l-header_under {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: center;
  }

    .o-text_tour_min {
      padding: 0 0 0 20px;

      font-size: 18px;
      font-weight: bold;
    }

    .o-text_add_image {
      padding: 0 15px 0 0;

      font-size: 12px;
      font-weight: bold;
      color: rgba(0,0,0, .26);
    }

  .o-image_circle {
    height: 50px;
    width: 50px;
    border-radius: 100px;
    object-fit: cover;
  }

  .o-list {
    padding: 20px 0 0 20px;
  }

  .l-image_text {
    display: flex;
  }

  .l-list_text {
    margin: 0 0 0 10px;
  }

  #o-list_text_geosite {
    font-size: 18px;
    font-weight: bold;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-list_text_update {
    font-size: 12px;
    color: rgba(0,0,0, .26);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-border {
    height: 1px;
    width: 280px;
    background-color: rgba(0,0,0, .12);
  }

  .u-mt10 {
    margin-top: 10px;
  }

  .u-mb20 {
    margin-bottom: 20px;
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .54);

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-modal {
    width: 240px;
    border-radius: 30px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
