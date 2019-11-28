<template>
  <div id="top_u">
    <div class="o-background">

      <div class="l-header_above">
        <div class="o-text_tour">Tour</div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">開催中のツアー</div>
      </div>
      <div class="o-slider">
        <div class="o-card"
          v-for="info in tour_info"
          :key="info.tour_id"
          @click="jumpPage('chat_u', info.tour_id, info.tour_name)"
        >
          <img src="../assets/sample.jpg" class="o-image_tour" />
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
      jumpPage: function(where, tour_id, tour_name) {
        this.$router.push({
            name: where,
            params: {
              tour_id: tour_id,
              tour_name: tour_name
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
    padding: 40px 0 0 0;
    display: flex;
    overflow-x: scroll;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /*ios*/
  }

  .o-card {
    padding: 0 0 0 20px;
    position: relative;
    height: 330px;
    width: 240px;
  }

  .o-card:last-of-type {
    padding: 0 20px 0 20px;
  }

    .o-image_tour {
      height: 330px;
      width: 240px;
      border-radius: 30px;
      object-fit: cover;
    }

    .o-transparent {
      height: 80px;
      width: 240px;
      position: absolute;
      bottom: 0;
      border-radius: 0 0 30px 30px;
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
    width: calc(100% - 80px);
  }

  .o-text_kujira {
    font-size: 18px;
    font-weight: bold;
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
