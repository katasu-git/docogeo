<template>
  <div id="top_u">
    <div class="o-background">

      <div class="l-header_above">
        <div class="o-text_tour">Event</div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">参加するイベントを選択</div>
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
          <div class="img_wrapper">
            <img 
              :src='require("../assets/kujira.png")' 
              class="o-image_tour" 
            />
          </div>
          <div class="o-transparent">
            <div class="o-text_tour_title">{{ info.tour_name }}</div>
          </div>
        </div>
      </div>

      <div 
        class="o-kujira"
        v-if="checkActiveTour() && isMounted"
      >
        <img 
          class="o-img_kujira" 
          :src='require("../assets/kujira.png")'  
        />
        <p class="o-text_kujira">開催中のツアーはありません</p>
        <p class="o-text_kujira_min">しばらくお待ちください</p>
      </div>

      <div class="l-footer">
        <div class="o-text_footer">DoCoGeo(www3) for <span class="u-color-green">GUEST</span></div>
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
      this.$localStorage.remove('like_array');
      //this.$localStorage.remove('user_info');
      this.get_tour_info();
    },
    methods: {
      get_tour_info: function () {
        axios.post('https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Event/fetch_tour_info.php'
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

  display: flex;
  justify-content: center;
}

.o-card:last-of-type {
  padding: 0 20px 0 20px;
}

.img_wrapper {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  border: solid 1px rgba(0,0,0, .12);

  display: flex;
  align-items: center;
}

.o-image_tour {
  width: 100%;
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
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.o-img_kujira {
  margin-top: 20vw;
  width: calc(100% - 80px);
  max-width: 300px;
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
