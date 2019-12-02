<template>
  <div id="camera">

        <div class="o-img-area">
            <img
              v-show="photo_flag"
              id="o-img_sample"
              src="../assets/sample2.jpg" 
            />
            <video
              ref="video" 
              id="video"
              autoplay muted playsinline
            ></video>
            <canvas
              ref="canvas"
              id="canvas"
            ></canvas>
            <div class="l-img_right_bottom">
              <img
                v-show="bottom_flag" 
                class="o-img_right_bottom"
                src="../assets/sample2.jpg" 
              />
            </div>
        </div>

        <div class="l-button">
          <button 
            class="o-button"
            @click="turnCam()"
          >重ね合わせON/OFF</button>

          <button 
            class="o-button"
            @click="turnBottom()"
          >右下ON/OFF</button>

          <button 
            class="o-button"
            @click="capture()"
          >撮影</button>
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
        photo_flag: false,
        bottom_flag: false,
        video_width: '',
        video_height: ''
      }
    },
    mounted() {
        this.video = this.$refs.video
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            const constraints = {
                audio: false,
                video: {
                  width: this.returnVideoWidth(),
                  height: this.returnVideoHeight(),
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
      turnCam() {
        if(this.photo_flag) {
          this.photo_flag = false;
        } else {
          this.photo_flag = true;
        }
      },
      turnBottom() {
        if(this.bottom_flag) {
          this.bottom_flag = false;
        } else {
          this.bottom_flag = true;
        }
      },
      capture() {
        this.canvas = this.$refs.canvas
        this.canvas.getContext('2d').drawImage(this.video, 0, 0, this.returnVideoWidth(), 300);
        this.captures.push(this.canvas.toDataURL('image/png'))
        console.log(this.captures)
      },
      returnVideoWidth() {
        return '300';
      },
      returnVideoHeight() {
        //let height = document.getElementById('video').clientHeight;
        return '300';
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
    object-fit: contain;
  }

  #canvas {
    /*object-fit: contain;*/
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
