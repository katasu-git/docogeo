<template>
  <div>
    <button @click="capture">save</button>
    <div ref="container">
      <canvas
        :width="width"
        :height="height"
        ref="canvas"
        id="canvas">
      </canvas>
    </div>

    <!--消す-->

    <canvas 
      id="cvs1"
      ref="cvs1"
      :width="width"
      :height="height"
    ></canvas>

    <!---->

  </div>
</template>

<script>
import axios from 'axios'
import Konva from 'konva';

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
    get_captures: ''
  },
  data: () => ({
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
  }),
  created() {
    //ルータから受け取った画像の縦横を指定
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
        this.cvs1 = this.$refs.cvs1
        this.cvs1.getContext('2d').drawImage(this.canvas, 0, 0, this.width, this.height);
        this.file = this.cvs1.toDataURL('image/png');

       this.$nextTick(function(){
          this.postFile();
       })
      },
      postFile: function() {
        console.log(this.backgroundImage);
          const url =
            "https://www2.yoslab.net/~nishimura/geotour/PHP/upload_draw.php";
          //var formData = new FormData();
          //formData.append("selectImage", this.file);
          let params = new URLSearchParams();
          params.append('canvasData', this.file);
          params.append('backImage', this.backgroundImage);
          axios
            .post(url, params)
            .then(response => {
              console.log(response.data);
            })
            .catch(error => {
              // エラーを受け取る
              console.log(error);
            });
        },
        base64ToBlob(base64) {
            var base64Data = base64.split(',')[1], // Data URLからBase64のデータ部分のみを取得
                  data = window.atob(base64Data), // base64形式の文字列をデコード
                  buff = new ArrayBuffer(data.length),
                  arr = new Uint8Array(buff),
                  blob,
                  i,
                  dataLen;
            // blobの生成
            for (i = 0, dataLen = data.length; i < dataLen; i++) {
                arr[i] = data.charCodeAt(i);
            }
            blob = new Blob([arr], {type: 'image/png'});
            return blob;
        }
  }
}
</script>