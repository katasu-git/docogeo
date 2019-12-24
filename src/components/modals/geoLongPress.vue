<template>
  <div id="geolongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text-first">{{spot_name_avoid}}</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text" @click="jumpPage()">編集</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text" @click="wakeChangeNameModal()">名前の変更</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-red" @click="delete_spot()">削除</div>
          <div class="o-border u-mt10"></div>
          <div class="o-text u-color-green" @click.stop='closeModal()'>キャンセル</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'geolongpress',
    props: {
      spot_id_avoid: '',
      spot_name_avoid: '',
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        wakeChangeNameModal: function() {
            this.$emit('wakeChangeNameModal');
        },
        delete_spot: function() {
          this.$emit('closeModal');
          this.$emit("confDelete");
        },
        jumpPage() {
          this.$emit('jumpPage', "editSpot", this.spot_id_avoid, this.spot_name_avoid);
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #geolongpress {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-border {
    height: 1px;
    width: 100%;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .35);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
  }

  .o-modal {
    width: calc(100% - 80px);
    max-width: 400px;
    border-radius: 10px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text, .o-text-first {
    padding: 20px;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
  }

  /*
  .o-text:nth-child(3) {
    width: calc(100% - 40px);
    max-width: 400px;
    text-align: center;
    background-color: #4B8E8D;
    color: #fff;
  }
  */

  .o-text-first {
    font-size: 30px;
  }

  .o-text:active {
    opacity: .7;
    transform: scale(1.2);
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
