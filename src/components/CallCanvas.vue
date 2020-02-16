<template>
    <div id="callcanvas">
        <FreeDrawing
            class="freeDrawing"
            ref="freeDrawing"
            :backgroundImage="captures[0]"
            :mode="mode"
            :get_width="width"
            :get_height="height"
            :get_captures="captures"
        />
    </div>
</template>

<script>
import FreeDrawing from '../components/FreeDrawing'
export default {
  name: 'CallCanvas',
  data: () => ({
    mode: '',
    brushColor: '',
    defaultMode: 'brush',
    defaultBrushColor: '#FFFFFF',
    width: '',
    height: '',
    captures: ''
  }),
  created() {
    if(this.$route.params.isNotReload) {
      console.log(this.$route.params.width)
      this.width = this.$route.params.width;
      this.height = this.$route.params.height;
      this.captures = this.$route.params.captures;
    } else {
      this.$router.push({
            name: 'camera'
      })
    }
    console.log(this.captures)
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
</style>
