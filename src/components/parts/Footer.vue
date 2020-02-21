<template>
  <div id="footer_comp">
    <div 
        class="o-icon"
        @click="move_page('camera')"
    >
        <img class="o-icon_img" :src="return_src('camera')" />
        <div 
          class="o-footer_text"
          :style="{ color: return_color('camera')}"
        >カメラ</div>
    </div>
    <div 
        class="o-icon"
        @click="move_page('chat')"
    >
        <div>
          <img class="o-icon_img" :src="return_src('chat')" />
        </div>
        <div 
          class="o-footer_text"
          :style="{ color: return_color('chat')}"
        >説明</div>
    </div>
    <div 
        class="o-icon"
        @click="move_page('maps')"
    >
        <img class="o-icon_img" :src="return_src('map')" />
        <div 
          class="o-footer_text"
          :style="{ color: return_color('map')}"
        >地図</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'footer_comp',
    props: {
      place: '',
      user: '',
    },
    data() {
      return {
      }
    },
    methods: {
        move_page(where) {
          this.$emit("stop_interval");
          let page;

          if(this.user === "guest") {
            if(where === "camera") {
              page = "userCamera"
            } else if(where === "chat") {
              page = "chat_u"
              this.$emit('stop_video');
            } else if(where === "maps") {
              page = "userMap"
              this.$emit('stop_video');
            }

          } else if(this.user === "guide") {
            if(where === "camera") {
              page =  "camera"
            } else if(where === "chat") {
              page = "chat_g"
              this.$emit('stop_video');
            } else if(where === "maps") {
              page = "maps"
              this.$emit('stop_video');
            }

          }

          this.$router.push({
                name: page
          });
        },
        return_color(button) {
          if(button == 'camera' && (this.place == 'camera' || this.place == 'userCamera')) {
            return '#4B8E8D';
          } else if(button == 'chat' && (this.place == 'chat' || this.place == 'userChat')) {
            return '#4B8E8D';
          } else if(button == 'map' && (this.place == 'map' || this.place == 'userMap')) {
            return '#4B8E8D';
          }
        },
        return_src(button) {
          if(button == 'camera') {
            if(this.place == 'camera' || this.place == 'userCamera') {
              return require('../../assets/camera_active.svg')
            } else {
              return require('../../assets/camera_button.svg')
            }
          } else if(button == 'chat') {
            if(this.place == 'chat' || this.place == 'userChat') {
              return require('../../assets/comment_active.svg')
            } else {
              return require('../../assets/comment_button.svg')
            }
          } else if(button == 'map') {
            if(this.place == 'map' || this.place == 'userMap') {
              return require('../../assets/map_active.svg')
            } else {
              return require('../../assets/map_button.svg')
            }
          }
        }
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  #footer_comp {
      position: fixed;
      right: 20px;
      bottom: 20px;
      height: 80px;
      width: calc(100% - 40px);
      background-color: #fff;
      border-radius: 100px;
      filter: drop-shadow(0 3px 10px rgba(0,0,0,.26));

      display: flex;
      justify-content: space-evenly;
      align-items: center;
  }

  .o-icon {
      display: flex;
      flex-direction: column;
      align-items: center;
  }

  .o-icon_img {
      height: 35px;
  }

  .o-footer_text {
      margin-top: 10px;
      font-size: 12px;
      color: #C2C7C6;
  }

</style>
