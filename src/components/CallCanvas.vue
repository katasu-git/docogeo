<template>
    <div id="callcanvas">
        <FreeDrawing
            class="freeDrawing"
            ref="freeDrawing"
            :tour_id="tour_id"
            :tour_name="tour_name"
            :spot_id="spot_id"
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
    tour_id: '',
    tour_name: '',
    spot_id: '',
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
      this.tour_id = this.$route.params.tour_id;
      this.tour_name = this.$route.params.tour_name;
      this.spot_id = this.$route.params.spot_id;
      this.width = this.$route.params.width;
      this.height = this.$route.params.height;
      this.captures = this.$route.params.captures;
    } else {
      this.$router.push({
            name: 'camera'
      })
    }
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
