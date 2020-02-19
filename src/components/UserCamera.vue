<template>
  <div id="userCamera">

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
          :style="{ opacity: return_opacity()}"
        />
      </div>

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
      @stop_video="stop_video"
    ></Footer>

  </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
import Footer from '../components/parts/UserFooter'
  export default {
    name: 'userCamera',
    data() {
      return {
        place: "userCamera",
        user: 'guest',
        tour_info: '',
        user_info: '',
        video: {},
        canvas: {},
        video_w: '200',
        video_h: '200',
        pile_image: '',
        opacity_value: 50,
      }
    },
    created() {
      this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
      this.user_info = JSON.parse(this.$localStorage.get('user_info'));
      this.countup_pageview();
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
      stop_video() {
        //this.video.pause();
        let stream = this.video.srcObject;
        let tracks = stream.getTracks();

        tracks.forEach(function(track) {
          track.stop();
        });
      },
      async countup_pageview() {
        const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/countup_pageview.php";
        let params = new URLSearchParams();
        params.append("id", this.user_info.id);
        params.append("where", this.place);
        const res = await axios.post(url, params);
      },

      setCanvas() {
        //canvasのサイズ変更
        let canvas = document.getElementById("canvas");
        this.video_w = document.getElementById("video").clientWidth;
        this.video_h = document.getElementById("video").clientHeight;

        canvas.width = this.video_w;
        canvas.height = this.video_h;
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
              this.opacity_value = response.data.opacity;
            })
            .catch(error => {
            // エラーを受け取る
            console.log(error);
            });
      },

      return_opacity() {
        return this.opacity_value / 100;
      },
    },
    components: {
      Footer: Footer
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#userCamera, .o-img-area{
  position: fixed;
  overflow: hidden;
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
  max-height: calc(100% - 150px);
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

.button_turnoff {
  z-index: 3;
  position: absolute;
  bottom: 25px;
  right: 40px;
  width: 40px;
  height: 30px;
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
  -webkit-appearance:none;
  background:#fff;
  border: solid 3px #000;
  height: 30px;
  width: 30px;
  border-radius:50%;
}

</style>
