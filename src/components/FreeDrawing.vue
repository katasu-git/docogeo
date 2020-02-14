<template>
  <div id="drawing">

    <transition name="fade">
      <div 
        v-show="flag.check"
        class="checkImage"
      >
        <canvas 
          id="cvs1"
          ref="cvs1"
          :width="width"
          :height="height"
          :style="{ opacity: return_opacity()}"
        />
        <div class="modal_message">この画像を配信しますか？</div>
        <div class="modal_opacity">透明度{{opacity_value}}%</div>
        <div class="button_wrapper">
          <button 
            @click="hidden_modal()"
            class="button_re"
          >
            やり直す
          </button>
          <button 
            @click="postFile()"
            class="button_post">配信</button>
        </div>
      </div>
    </transition>

    <transition name="fade">
        <Uploading
          v-if="flag_uploading"></Uploading>
    </transition>

    <!--
    <img 
      class="button_gear"
      src="../assets/gear_icon.svg" />
    -->

    <div
      class="container" 
      ref="container"
      :style="{ opacity: return_opacity()}"
    >
      
      <canvas
        :width="width"
        :height="height"
        ref="canvas"
        id="canvas"
      />

    </div>

    <input 
      type="range" 
      min="0" 
      max="100" 
      step="1" 
      v-model="opacity_value"
    />

    <div class="l-button">

      <div class="flex_colmn">
        <img
          @click="jump('camera')"
          class="button_back"
          src="../assets/back.svg"
        />
        戻る
      </div>

      <button 
        class="button_capture"
        @click="capture"
      >OK</button>

      <div 
        v-if="flag.half"
        class="flex_colmn">
        <img
          @click="set_width()"
          class="button_back"
          src="../assets/half_button.svg"
        />
        半分
      </div>
      <div 
        v-else
        class="flex_colmn">
        <img
          @click="set_width()"
          class="button_back"
          src="../assets/all_button.svg"
        />
        全体
      </div>

      <!--
      <img
        class="button_pen" 
        src="../assets/pen_icon.svg" />
        -->
    </div>

    <img 
      id="back_image_hidden" 
      :src="backgroundImage" 
    />

  </div>
</template>

<script>
import axios from 'axios'
import Konva from 'konva';
import Footer from '../components/parts/Footer'
import Uploading from '../components/Images_Modal/imgUploading'

export default {
  name: 'FreeDrawing',
  // propsは親の「CallCanvas.vue」から値を受け取るためのプロパティ
  props: {
    mode: {
      type: String,
      default: ''
    },
    brushColor: {
      type: String,
      default: 'rgba(255, 20, 147, 1)' //ペンの色
    },
    backgroundImage: {
      type: String,
      default: '' //CallCanvasないのcapture[0]を指定して受け渡してる
    },
    get_width: '',
    get_height: '',
    get_captures: '',
  },
  data: () => ({
    tour_info: '',
    spot_info: '',
    width: window.innerWidth,
    height: window.innerHeight,
    stage: null,
    canvas: null,
    cvs1: null,
    context: null,
    drawingLayer: null,
    drawingScope: null,
    lastPointerPosition: {},
    localPos: {
      x: 0,
      y: 0
    },
    pos: null,
    isPaint: false,
    imageObj: null,
    backgroundLayer: null,
    backgroundImageScope: null,
    file: '',
    flag_uploading: false,
    opacity_value: 50,
    flag: {
      half: false,
      check: false,
    }
  }),
  created() {
    this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'))
    this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'))
    this.width = this.get_width;
    this.height = this.get_height;
  },
  mounted: function () {
    var container = this.$refs.container;
    this.stage = new Konva.Stage({
      container,
      width: this.width,
      height: this.height
    })
    this.drawingLayer = new Konva.Layer()
    this.stage.add(this.drawingLayer)

    this.canvas = this.$refs.canvas
    this.drawingScope = new Konva.Image({
      image: this.canvas,
      x: 0,
      y: 0,
      //stroke: 'pink'
    })
    this.drawingLayer.add(this.drawingScope)
    this.stage.draw()

    this.context = this.canvas.getContext('2d')
    this.context.strokeStyle = this.brushColor
    this.context.lineJoin = 'round'
    this.context.lineWidth = 5

    // イベント追加
    this.drawingScope.on('mousedown', this.mousedown)
    this.stage.addEventListener('mouseup', this.mouseup)
    this.stage.addEventListener('mousemove', this.mousemove)
    this.drawingScope.on('touchstart', this.mousedown)
    this.stage.addEventListener('touchend', this.mouseup)
    this.stage.addEventListener('touchmove', this.mousemove)

    //追加
    this.imageObj = new Image()
    this.imageObj.addEventListener('load', this.imageOnload)
    this.imageObj.src = this.backgroundImage
  },
  methods: {
    mousedown: function () {
      this.isPaint = true

      // マウスダウン時の座標を取得しておく
      this.lastPointerPosition = this.stage.getPointerPosition()
    },
    mouseup: function () {
      this.isPaint = false
    },
    mousemove: function () {
      if (!this.isPaint) {
        return;
      }
      // ペンモード時
      if (this.isTargetMode('brush') || this.isTargetMode('line')) {
        this.context.globalCompositeOperation = 'source-over';
      }
      // 消しゴムモード時
      if (this.isTargetMode('eraser')) {
        this.context.globalCompositeOperation = 'destination-out';
      }

      this.context.beginPath()

      this.localPos.x = this.lastPointerPosition.x - this.drawingScope.x()
      this.localPos.y = this.lastPointerPosition.y - this.drawingScope.y()

      // 描画開始座標を指定する
      this.context.moveTo(this.localPos.x, this.localPos.y)

      this.pos = this.stage.getPointerPosition()
      this.localPos.x = this.pos.x - this.drawingScope.x()
      this.localPos.y = this.pos.y - this.drawingScope.y()

      // 描画開始座標から、lineToに指定された座標まで描画する
      this.context.lineTo(this.localPos.x, this.localPos.y)
      this.context.closePath()
      this.context.stroke()
      this.drawingLayer.draw()

      this.lastPointerPosition = this.pos
    },
    onClearCanvas: function () {
      this.context.globalCompositeOperation = 'destination-out'
      this.context.fillRect(0, 0, this.width, this.height)
      this.drawingLayer.draw()

      this.$emit('on-reset')
    },
    // 現在のモードが指定されたモードと一致するかどうか
    isTargetMode: function (targetMode) {
      return this.mode === targetMode
    },
    /** 追加 */
    imageOnload: function () {
      // 背景レイヤ
      this.backgroundLayer = new Konva.Layer()

      // 背景イメージ（xとy座標はthis.drawingScopeと同じにする）
      this.backgroundImageScope = new Konva.Image({
        image: this.imageObj,
        x: 0,
        y: 0,
        width: this.canvas.width, //キャンバスと同じサイズに設定
        height: this.canvas.height
      })

      // 背景レイヤに背景イメージを追加
      this.backgroundLayer.add(this.backgroundImageScope)
      this.stage.add(this.backgroundLayer)

      // 背景イメージを最背面に移動。これをしないとペンの描画が画像の下に潜ってしまう。
      this.backgroundLayer.moveToBottom()
    },
    capture() {

      let image = document.getElementById("back_image_hidden")
      this.cvs1 = this.$refs.cvs1;
      this.cvs1.getContext('2d').clearRect(0, 0, this.width, this.height); //キャンバスのリセット
      if(this.flag.half) {
        this.cvs1.getContext('2d').drawImage(image, 0, 0, this.width / 2, this.height, 0, 0, this.width / 2, this.height); //背景
        // http://www.htmq.com/canvas/drawImage_s.shtml
      } else {
        this.cvs1.getContext('2d').drawImage(image, 0, 0, this.width, this.height, 0, 0, this.width, this.height); //背景
      }
      this.cvs1.getContext('2d').drawImage(this.canvas, 0, 0, this.width, this.height); //線
      this.file = this.cvs1.toDataURL('image/png');
      this.flag.check = true;
      },
      postFile: function() {
          this.flag.check = false;
          this.flag_uploading = true;
          const url = "https://www2.yoslab.net/~nishimura/geotour/PHP/upload_draw.php";
          let params = new URLSearchParams();
          params.append('canvasData', this.file);
          params.append('opacity', this.opacity_value);
          axios
            .post(url, params)
            .then(response => {
              this.add_image_to_spot(response.data[0]);
            })
            .catch(error => {
              // エラーを受け取る
              console.log(error);
            });
        },
        add_image_to_spot(image) {
          const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/add_image_to_spot.php";
          let params = new URLSearchParams();
          params.append('tour_id', this.tour_info.tour_id);
          params.append('spot_id', this.spot_info.spot_id);
          params.append('image_id', image.id);
          params.append('image_path', image.image_path);
          axios.post(url, params).then(()=>{
            this.flag_uploading = false;
            this.jump("camera");
          })
        },
        jump(where) {
          this.$router.push({
            name: where,
          })
       },
       return_opacity() {
         return this.opacity_value + "%";
       },
       set_width() {
         if(this.flag.half) {
           this.flag.half = false;
         } else {
           this.flag.half = true;
         }
       },
       hidden_modal() {
         this.flag.check = false;
         this.onClearCanvas();
       }
  },
  components: {
    Footer: Footer,
    Uploading: Uploading
  }
}
</script>
<style scoped>

#drawing {
  position: fixed;
  width: 100%;
  height: 100%;
}

#back_image_hidden {
  visibility: hidden;
}

.container {
    position: absolute;
    top: 10px;
    left: 10px;
}

.l-button {
  width: 100%;
  position: absolute;
  bottom: 30px;
  right: 0;
  left: 0;
  margin: auto;

  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.button_capture {
  width: 60px;
  height: 60px;
  border-radius: 100px;
  background-color: #fff;
  border: solid 4px #4B8E8D;

  font-size: 18px;
  font-weight: bold;
  color: #4B8E8D;
  margin: -5px 30px 0 30px;
}

.button_gear {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 1;
}

.re_shot {
  width: 100px;
  height: 60px;
  border-radius: 10px;
  border: solid 3px #fff;
  background-color: rgba(0,0,0,.12);
  font-size: 16px;
}

.button_back {
  margin-bottom: 5px;
}

.flex_colmn {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 12px;
  font-weight: bold;
}

input[type=range] {
  position: absolute;
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

.checkImage {
  position: fixed;
  z-index: 3;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,.90);

  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.modal_message {
  margin-top: 30px;
  font-weight: bold;
  color: #fff;
}

.modal_opacity {
  margin-top: 5px;
  color: #fff;
  font-size: 12px;
}

#cvs1 {
  max-height: calc(100% - 300px);
}

.button_wrapper {
  position: absolute;
  bottom: 20px;
  width: 100%;
  display: flex;
  justify-content: center;
}

.button_re {
  margin-right: 100px;
  font-size: 16px;
  font-weight: bold;
  color: #fff;
}

.button_post {
  width: 80px;
  height: 40px;
  background-color: #4B8E8D;
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  border-radius: 3px;
}

</style>