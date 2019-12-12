<template>
    <div>
        <select 
            v-model="mode"
            name="mode" 
            id="mode"
        >
            <option value="brush">ペン</option>
            <option value="eraser">消しゴム</option>
        </select>
        <button
          @click="clearCanvas"
        >リセット</button>
        <button>保存</button>
        
        <FreeDrawing
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
    }
  },
  components: {
      FreeDrawing: FreeDrawing
  }
}
</script>

<style scoped>
  .md-field {
    max-width: 110px;
  }
</style>
