<template>
  <div>
    <div ref="container">
      <canvas
        :width="width"
        :height="height"
        ref="canvas">
      </canvas>
    </div>
    <canvas id="cvs1" width="200" height="200"></canvas>
  </div>
</template>

<script>
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
      default: ''
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
    backgroundImageScope: null
  }),
  created() {
    //ルータから受け取った画像の縦横を指定
    this.width = this.get_width;
    this.height = this.get_height;
    console.log(this.get_width);
    console.log(this.get_height);
    console.log(this.get_captures);
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
      /*
      描画する位置を指定
      x: this.width / 4,
      y: 5,
      */
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
        /*
        x: this.width / 4,
        y: 5,
        */
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
    test() {
        let test = document.getElementById("hiddenImg").clientHeight;
        console.log(test);
    },
  },
  watch: {
    // ペンの色変更
    brushColor: function () {
      this.context.strokeStyle = this.brushColor
    }
  }
}
</script>
<style scoped>

div {
    margin: 0;
    padding: 0;
}
#container_img {
    width: 100vw;
}

#hiddenImg {
    width: 100%;
}
</style>