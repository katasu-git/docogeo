<template>
    <div id="callcanvas">
        <!-- <select 
            v-model="mode"
            name="mode" 
            id="mode"
        >
            <option value="brush">ペン</option>
            <option value="eraser">消しゴム</option>
        </select> -->
        <FreeDrawing
            class="freeDrawing"
            ref="freeDrawing"
            :tour_id="tour_id"
            :backgroundImage="captures[0]"
            :mode="mode"
            :get_width="width"
            :get_height="height"
            :get_captures="captures"
        />
        <div class="l_button">
          <img src="../assets/pen.svg" />
          <img class="u-ml10" src="../assets/el.svg" />
          <button class="button_capture" @click="capture">配信</button>
        </div>
    </div>
</template>

<script>
import FreeDrawing from '../components/FreeDrawing'
export default {
  name: 'CallCanvas',
  data: () => ({
    tour_id: '',
    mode: '',
    brushColor: '',
    defaultMode: 'brush',
    defaultBrushColor: '#FFFFFF',
    width: '',
    height: '',
    captures: ''
  }),
  created() {
    this.tour_id = this.$route.params.tour_id;
    this.width = this.$route.params.width;
    this.height = this.$route.params.height;
    this.captures = this.$route.params.captures;
  },
  mounted: function() {
    this.init();
  },
  methods: {
    // モードとペンの色を初期状態にする
    init: function() {
      this.mode = this.defaultMode;
      this.brushColor = this.defaultBrushColor;
    },
    // キャンバスをクリアする
    clearCanvas: function () {
      this.$refs.freeDrawing.onClearCanvas()
      this.init()
    },
    capture() {
      //子コンポーネントの関数呼び出し
      this.$refs.freeDrawing.capture();
    }
  },
  components: {
      FreeDrawing: FreeDrawing
  }
}
</script>

<style scoped>

  #callcanvas {
    position: relative;
    top: 0;
  }

  .freeDrawing {
    height: 100%;
    top: 10px;
    right: 10px;
  }

  .l_button {
    position: absolute;
    top: 20px;
    left: 10px;
    width: calc(100% - 20px);
  }

  .button_capture {
    position: absolute;
    width: 120px;
    height: 60px;
    right: 0;
    background-color: 
  }

  .u-ml10 {
    margin-left: 10px;
  }
</style>
