<template>
  <div id="footer_comp">
    <div 
        class="o-icon"
        @click="jump('camera')"
    >
        <img class="o-icon_img" src="../../assets/camera_button.svg" />
        <div class="o-footer_text">カメラ</div>
    </div>
    <div 
        class="o-icon"
        @click="jump('')"
    >
        <img class="o-icon_img" src="../../assets/comment_active.svg" />
        <div class="o-footer_text u-color-green">説明</div>
    </div>
    <div 
        class="o-icon"
        @click="jump('')"
    >
        <img class="o-icon_img" src="../../assets/map_button.svg" />
        <div class="o-footer_text">地図</div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'footer_comp',
    data() {
      return {
          tour_id: '',
      }
    },
    methods: {
        jump(where) {
            this.$router.push({
                name: where,
                params: {
                    tour_id: this.tour_id
                }
            })
        },
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

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
