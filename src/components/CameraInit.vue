<template>
<div id="cameraInit">

    <video
        ref="video" 
        id="video"
        autoplay muted playsinline
    ></video>

    <img
        class="image_line" 
        :src="lineImage.image_path" />
    <img 
        class="image_photo" 
        :src="photoImage.image_path" />

    <button
        class="button_recapture"
        @click="jump()"
    >もう一度撮影する</button>

</div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
  export default {
    name: 'cameraInit',
    data() {
      return {
          images:[],
          lineImage: '',
          photoImage: '',
          tour_id: '',
      }
    },
    created() {
        this.tour_id = this.$route.params.tour_id;
        this.getImage();
    },
    mounted() {
        this.video = this.$refs.video
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            const constraints = {
                audio: false,
                video: {
                  facingMode: { ideal: "environment" }  // リアカメラを利用する場合
                }
            };
            navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
                this.video.srcObject = stream
                this.video.play()
            })
        }
    },
    methods: {
      jump() {
         this.$router.push({
            name: 'camera',
            params: {
                tour_id: this.tour_id
            }
        })
      },
      getImage() {
          const url =
            "https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_draw_image.php";
            axios
                .post(url)
                .then(response => {
                //画像を受け取ったときの処理
                this.photoImage = response.data[0];
                this.lineImage = response.data[1];
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
  #camera, .o-background {
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
  }

  #video, img {
    width: calc(100% - 20px);
    z-index: 1;
  }

  .image_photo {
      opacity: .3;
      z-index: 2;
  }

  .image_line {
      opacity: .8;
      z-index: 3;
  }

  .button_recapture {
      position: absolute;
      bottom: 20px;
      right: 0;
      left: 0;
      margin: auto;

      width: 200px;
      height: 60px;
      color: #fff;
      font-size: 20px;
      background-color: #4B8E8D;
      border-radius: 10px;
  }

</style>
