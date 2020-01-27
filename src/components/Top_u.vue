<template>
  <div id="top_u">
    <div class="o-background">

      <div class="l-header_above">
        <div class="o-text_tour">Event</div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">開催中のイベント</div>
      </div>
      <div 
        class="o-slider"
        v-if="!checkActiveTour()"
      >
        <div class="o-card"
          v-for="info in tour_info"
          :key="info.tour_id"
          @click="move_page('chat_u', info)"
        >
          <img src="../assets/kujira.svg" class="o-image_tour" />
          <div class="o-transparent">
            <div class="o-text_tour_title">{{ info.tour_name }}</div>
            <div class="o-text_update">最終更新 2019.11.7</div>
          </div>
        </div>
      </div>

      <div 
        class="o-kujira"
        v-if="checkActiveTour() && isMounted"
      >
        <img class="o-img_kujira" src="../assets/kujira.svg" />
        <p class="o-text_kujira">開催中のツアーはありません</p>
        <p class="o-text_kujira_min">しばらくお待ちください</p>
      </div>

      <div class="l-footer">
        <div class="o-text_footer">DoCoGeo for <span class="u-color-green">GUEST</span></div>
      </div>

    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'top_u',
    data() {
      return {
        tour_info: [],
        isMounted: false,
      }
    },
    created: function () {
      //ローカルストレージの初期化
      this.$localStorage.remove('now_tour_info');
      this.$localStorage.remove('now_spot_info');
      this.$localStorage.remove('user');
      //this.$localStorage.remove('user_info');
      this.get_tour_info();
    },
    methods: {
      get_tour_info: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
        ).then(response => {
            for(let i=0; i<response.data.length; i++) {
                if(response.data[i].isActive == 1) {
                  this.tour_info.push(response.data[i]);
                }
            }
            this.isMounted = true;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      move_page: function(where, tour_info) {
        this.$router.push({
            name: where,
            params: {
              tour_info: tour_info,
            }
        })
      },
      checkActiveTour() {
        if(this.tour_info.length < 1 || this.tour_info == []) {
          return true;
        } else {
          return false;
        }
      }
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#top_u, .o-background {
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
  padding: 0 10px 0 0;

  font-size: 12px;
  font-weight: bold;
  color: rgba(0,0,0, .26);
}

.o-slider {
  height: calc(100% - 100px - 90px - 60px);
  padding: 20px 0 0 0;
  display: flex;
  overflow-x: scroll;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch; /*ios*/
}

.o-card {
  padding: 0 0 0 20px;
  position: relative;
  height: 100%;
  width: calc(100vw - 80px);
  max-width: 400px;
}

.o-card:last-of-type {
  padding: 0 20px 0 20px;
}

.o-image_tour {
  height: 100%;
  width: calc(100vw - 80px);
  max-width: 400px;
  border-radius: 10px;
  object-fit: cover;
  border: solid 1px rgba(0,0,0, .12);
}

.o-transparent {
  height: 80px;
  width: calc(100vw - 80px);
  max-width: 400px;
  position: absolute;
  bottom: 0;
  border-radius: 0 0 10px 10px;
  background-color: rgba(0,0,0,.5);
}

.o-text_tour_title {
  padding: 20px 0 0 20px;

  font-size: 18px;
  font-weight: bold;
  color: #fff;
}

.o-text_update {
  padding: 0 0 0 20px;

  font-size: 14px;
  color: rgba(255,255,255,.70);
}

.o-kujira {
  margin-top: 50px;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.o-img_kujira {
  width: calc(100% - 80px);
}

.o-text_kujira {
  font-size: 18px;
  font-weight: bold;
}

.o-text_kujira_min {
  margin-top: 5px;
  font-size: 14px;
  color: rgba(0,0,0, .54);
}

.l-footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  height: 30px;
  background-color: #fff;

  display: flex;
  align-items: center;
}

.o-text_footer {
  margin-left: 20px; 
  font-size: 12px;
  font-weight: bold;
}

.u-color-green {
  color: #4B8E8D;
}
</style>
