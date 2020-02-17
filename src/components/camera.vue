<template>
  <div id="camera">

    <transition name="fade">
      <Uploading v-if="flag_uploading"/>
    </transition>

    <div class="video_wrapper">

      <video
        ref="video" 
        id="video"
        autoplay muted playsinline
      ></video>

      <div class="image_wrapper">
        <img
          :src="pile_image"
          class="pile_image"
          v-show="pile_flag"
          :style="{ opacity: return_opacity()}"
        />
      </div>

      <img 
        class="button_upImage"
        src="../assets/upImage.svg"
        @click="choice_image()"
      />

      <button 
        class="button_capture"
        @click="capture"
      ></button>

      <input 
        type="range" 
        min="0" 
        max="100" 
        step="1" 
        v-model="opacity_value"
      />

    </div>

      <canvas
        ref="canvas"
        id="canvas"
      ></canvas>

    <Footer
      :user="user"
      :place="place"
    ></Footer>

  </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
import Footer from '../components/parts/Footer'
import Uploading from '../components/Images_Modal/imgUploading'

  export default {
    name: 'camera',
    data() {
      return {
        place: "camera",
        user: 'guide',
        user_info: '',
        isNotReload: true,
        video: {},
        canvas: {},
        captures: '',
        flag_uploading: false,
        pile_flag: true,
        video_w: '200',
        video_h: '200',
        pile_image: '',
        opacity_value: 0,
      }
    },
    created() {
      this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
      this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'))
      this.user_info = JSON.parse(this.$localStorage.get('user_info'));
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
      capture() {

        //canvs再描画
        this.setCanvas();

        this.canvas = this.$refs.canvas
        this.canvas.getContext('2d').drawImage(this.video, 0, 0, this.video_w, this.video_h);
        this.captures = this.canvas.toDataURL('image/png')

        //お絵かきページに移動
        //this.jump();
        this.postFile();
      },

      async postFile() {
        this.flag_uploading = true;
        const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Images/upload.php";
        let params = new URLSearchParams();
        params.append('image_data', this.captures);
        params.append('tour_id', this.tour_info.tour_id);
        params.append('spot_id', this.spot_info.spot_id);
        params.append('camera_flag', true);
        axios
          .post(url, params)
          .then(response => {
              console.log("処理完了")
              this.flag_uploading = false;
          })
          .catch(error => {
            // エラーを受け取る
            console.log(error);
          });
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
              isNotReload: this.isNotReload
            }
        })
      },
      getImage() {
        const url ="https://www3.yoslab.net/~nishimura/docogeo/PHP/Images/get_trans_image.php";
        let params = new URLSearchParams();
        params.append('tour_id', this.tour_info.tour_id);
        axios
            .post(url, params)
            .then(response => {
              console.log(response.data);
              this.pile_image = response.data.image_path;
              //this.opacity_value = response.data[0].opacity;
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
      },
      return_opacity() {
         return this.opacity_value + "%";
      },
      choice_image() {
        this.$router.push({
            name: 'imagesTrans',
            params: {
              isNotReload: true
            }
        })
      }
    },
    components: {
      Footer: Footer,
      Uploading: Uploading
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
  position: relative;
  margin-top: 10px;

  display: flex;
  justify-content: center;
  align-items: center;
}

#video {
  width: calc(100% - 20px);
  max-height: calc(100% - 80px);
}

.image_wrapper {
  position: absolute;
  left: 10px;
  width: calc(100% - 20px);
  height: 100%;
  z-index: 2;
  overflow: hidden;

  display: flex;
  justify-content: center;
  align-items: center;
}

.pile_image {
  width: 100%;
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

.button_upImage {
  z-index: 3;
  position: absolute;
  bottom: 15px;
  left: 50px;
}

input[type=range] {
  position: absolute;
  z-index: 3;
  top: 30px;
  right: 0;
  left: 0;
  margin: auto;
  width: calc(100% - 40px);

  -webkit-appearance:none;
  background:#000;
  height: 5px;
  border-radius: 100px;
}

input[type=range]::-webkit-slider-thumb{
  z-index: 3;
  -webkit-appearance:none;
  background:#fff;
  border: solid 3px #000;
  height: 30px;
  width: 30px;
  border-radius:50%;
}

</style>
