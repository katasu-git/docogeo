<template>
  <div id="camera">

    <div class="video_wrapper">
      <video
        ref="video" 
        id="video"
        autoplay muted playsinline
      ></video>
      <button 
        class="button_capture"
        @click="capture"
      ></button>
    </div>

    <div class="wrapper">
      <canvas
        v-show="photo_flag"
        ref="canvas"
        id="canvas"
      ></canvas>
    </div>

    <Footer></Footer>

  </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
import Footer from '../components/parts/Footer'
  export default {
    name: 'camera',
    data() {
      return {
        tour_id: '',
        video: {},
        canvas: {},
        captures: [],
        photo_flag: true,
        bottom_flag: false,
        video_w: '200',
        video_h: '200'
      }
    },
    created() {
      this.tour_id = this.$route.params.tour_id;
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

        this.setCanvas();

    },
    methods: {
      turnCam() {
        if(this.photo_flag) {
          this.photo_flag = false;
        } else {
          this.photo_flag = true;
        }
      },
      capture() {

        //canvs再描画
        this.setCanvas();

        this.canvas = this.$refs.canvas
        let w = this.video_h;
        this.canvas.getContext('2d').drawImage(this.video, 0, 0, this.video_w, this.video_h);
        this.captures.push(this.canvas.toDataURL('image/png'))

        //お絵かきページに移動
        this.jump();
      },
      setCanvas() {
        //canvasのサイズ変更
        let canvas = document.getElementById("canvas");
        this.video_w = document.getElementById("video").clientWidth;
        this.video_h = document.getElementById("video").clientHeight;

        canvas.width = this.video_w;
        canvas.height = this.video_h;
      },
      jump() {
        if(this.captures == '' || this.captures == undefined || this.captures == null) {
          return;
        }
         this.$router.push({
            name: 'draw',
            params: {
              width: this.video_w,
              height: this.video_h,
              captures: this.captures,
              tour_id: this.tour_id
            }
        })
      }
    },
    components: {
      Footer: Footer
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #camera, .o-img-area{
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
  }

  .video_wrapper {
    position: absolute;
    top: 10px;
    left: 10px;
  }

  #video {
    width: calc(100% - 20px);
  }

  #canvas {
    position: absolute;
    top: 0;
    opacity: .3;
  }

  .button_capture {
    z-index: 2;
    position: absolute;
    bottom: 10px;
    right: 0;
    left: 0;
    margin: auto;
    width: 60px;
    height: 60px;
    border-radius: 100px;
    background-color: rgba(0,0,0,.26);
  }

</style>
