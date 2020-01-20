<template>
  <div id="camera">

    <div class="video_wrapper">

      <video
        ref="video" 
        id="video"
        autoplay muted playsinline
      ></video>

      <img
        :src="pile_image"
        class="pile_image"
        v-show="pile_flag"
      />

      <button 
        v-show="!user_flag"
        class="button_capture"
        @click="capture"
      ></button>

      <img
        class="button_turnoff"
        @click="turnOffPile()"
        src="../assets/turnoff.svg"
      />

    </div>

      <canvas
        ref="canvas"
        id="canvas"
      ></canvas>

    <Footer
      :place="place"
    ></Footer>

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
        place: "camera",
        tour_id: '',
        tour_name: '',
        spot_id: '',
        video: {},
        canvas: {},
        captures: [],
        photo_flag: true,
        bottom_flag: false,
        pile_flag: true,
        user_flag: '',
        video_w: '200',
        video_h: '200',
        pile_image: '',
      }
    },
    created() {
      this.tour_id = this.$route.params.tour_id;
      this.tour_name = this.$route.params.tour_name;
      this.spot_id = this.$route.params.spot_id;
      this.user_flag = this.$route.params.user_flag;
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
        this.getImage();
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
        //this.video_h = document.getElementById("video").clientHeight;

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
              tour_id: this.tour_id,
              tour_name: this.tour_name,
              spot_id: this.spot_id
            }
        })
      },
      backToExPage() {
        let where = "chat_g"
        if(this.user_flag) {
          where = "chat_u";
        }
         this.$router.push({
            name: where,
            params: {
              tour_id: this.tour_id,
              tour_name: this.tour_name
            }
        })
      },
      getImage() {
          const url ="https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_draw_image.php";
          axios
              .post(url)
              .then(response => {
              //画像を受け取ったときの処理
                this.pile_image = response.data[0].image_path;
                console.log(response.data[0].image_path);
              })
              .catch(error => {
              // エラーを受け取る
              console.log(error);
              });
      },
      turnOffPile() {
        if(this.pile_flag) {
          this.pile_flag = false;
        } else {
          this.pile_flag = true;
        }
        console.log(this.pile_flag)
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
    width: calc(100% - 10px);
  }

  .pile_image {
    width: calc(100% - 10px);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
    opacity: .3;
  }

  #canvas {
    position: absolute;
    top: 0;
    visibility: hidden;
  }

  .button_capture {
    z-index: 3;
    position: absolute;
    bottom: 10px;
    right: 0;
    left: 0;
    margin: auto;
    width: 60px;
    height: 60px;
    border-radius: 100px;
    background-color: #C4C4C4;
    border: solid 3px #fff;
  }

  .button_turnoff {
    z-index: 3;
    position: absolute;
    bottom: 25px;
    right: 40px;
    width: 40px;
    height: 30px;
  }

</style>
