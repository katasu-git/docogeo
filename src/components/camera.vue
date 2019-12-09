<template>
  <div id="camera">

        <div class="o-img-area">
            <video
              ref="video" 
              id="video"
              autoplay muted playsinline
            ></video>
            <div class="wrapper">
              <canvas
                v-show="photo_flag"
                ref="canvas"
                id="canvas"
              ></canvas>
            </div>
        </div>

        <div class="l-button">
          <button 
            class="o-button"
            @click="turnCam()"
          >重ね合わせON/OFF</button>

          <button 
            class="o-button"
            @click="capture()"
          >撮影</button>

          <button 
            class="o-button"
            @click="jump()"
          >ページ移動</button>
        </div>
      
    </div>

</template>

<script>
import axios from 'axios'
import { async } from 'q';
  export default {
    name: 'camera',
    data() {
      return {
        video: {},
        canvas: {},
        captures: [],
        photo_flag: true,
        bottom_flag: false,
        video_w: '200',
        video_h: '200'
      }
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

        console.log(this.captures);

        // 画像の各ピクセルをグレースケールに変換する //

        /*
        var pixels = this.canvas.getContext('2d').getImageData(0, 0, canvas.width, canvas.height);
        for (var y = 0; y < pixels.height; y++) {
            for (var x = 0; x < pixels.width; x++) {
                var i = (y * 4) * pixels.width + x * 4;
                var rgb = parseInt((pixels.data[i] + pixels.data[i + 1] + pixels.data[i + 2]) / 3, 10);
                pixels.data[i] = rgb;
                pixels.data[i + 1] = rgb;
                pixels.data[i + 2] = rgb;
            }
        }
        this.canvas.getContext('2d').putImageData(pixels, 0, 0, 0, 0, pixels.width, pixels.height);

        */

        /////////////////////////////////////
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
              captures: this.captures
            }
        })
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

  #o-img-area {
    position: relative;
    width: calc(100% - 20px);
    height: calc(100% - 80px - 20px);
    padding: 10px;
  }

  .o-img_sample {
    position: absolute;
    opacity: .3;
    width: 100%;
    height: calc(100% - 20px);
    object-fit: cover;
  }

  #video {
    object-fit: cover;
    width: 100%;
  }

  #canvas {
    position: absolute;
    top: 0;
    opacity: .3;
  }

  .wrapper{
    width: 100%;
    height: 100%;
  }

  .l-button {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-button {
    margin: 20px 0;
    width: 50%;
  }

  .l-img_right_bottom {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 30%;
    height: 30%;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-img_right_bottom {
    height: 100%;
    width: 100%;
    object-fit: cover;
  }

</style>
