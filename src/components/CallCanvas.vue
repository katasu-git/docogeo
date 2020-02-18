<template>
    <div id="callcanvas">
        <FreeDrawing
            class="freeDrawing"
            ref="freeDrawing"
            :backgroundImage="captures"
            :mode="mode"
            :get_width="width"
            :get_height="height"
            :spot_image_id="spot_image_id"
            :image_path="image_path"
            :isTrans="isTrans"
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
    captures: '',
    spot_image_id: '',
    image_path: '',
    isTrans: false
  }),
  created() {
    if(this.$route.params.isNotReload) {
      this.width = this.$route.params.width;
      this.height = this.$route.params.height;
      this.captures = this.$route.params.captures;
      this.spot_image_id = this.$route.params.spot_image_id;
      this.image_path = this.$route.params.image_path;
      this.isTrans = this.$route.params.isTrans;
    } else {
      this.$router.push({
            name: 'chat_g'
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
