<template>
  <div id="geolongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text">名前の変更</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-red">削除</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-green">キャンセル</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'geolongpress',
    data() {
      return {
      }
    },
    created: function () {
    },
    methods: {
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .o-border {
    height: 1px;
    width: 100%;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .54);

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .o-modal {
    width: 240px;
    border-radius: 30px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
