<template>
  <div id="geolongpress">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
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
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        wakeChangeNameModal: function() {
            this.$emit('wakeChangeNameModal');
        },
        delete_spot: function() {
          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/DELETE/delete_spot.php';
            let params = new URLSearchParams();
            params.append('spot_id', this.spot_id_avoid);
            axios.post(url, params
            ).then(response => {
                this.closeModal();
            }).catch(error => {
                // エラーを受け取る
                console.log(error);
            });
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
  }

  .o-modal {
    width: 100px;
    border-radius: 0 30px 30px 0;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    padding: 20px;
    font-size: 12px;
    font-weight: bold;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
