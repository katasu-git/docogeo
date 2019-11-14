<template>
  <div id="geochangename">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
            <div class="l-image u-mt20"><img class="o-image" src="../../assets/sample.jpg" /></div>
            <form class="u-mt20">
                <input type="text" placeholder="ここにジオサイトの名前を入力！" v-model="spot_name_updated" />
            </form>
            <div class="o-border u-mt40"></div>
            <div class="l-button">
                <button class="o-button_cancel" v-on:click="closeModal()">キャンセル</button>
                <button class="o-button_save" v-on:click="update_spot_name()">保存する</button>
            </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'geochangename',
    props: {
        spot_id: Number,
        spot_name: String,
    },
    data() {
      return {
          spot_name_updated: '',
      }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        update_spot_name: function() {
            this.$emit('update_spot_name', this.spot_name_updated);
        }
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
    align-items: flex-end;
  }

  .o-modal {
    width: 100%;
    border-radius: 30px 30px 0 0;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-image {
      height: 80px;
      width: 80px;
      border-radius: 100px;
      object-fit: cover;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
  }

  input {
      width: calc(100vw - 20px);
      font-size: 18px;
      font-weight: bold;
      color: rgba(0,0,0, .87);
      text-align: center;
  }

  input::placeholder {
      font-size: 18px;
      font-weight: bold;
      color: rgba(0,0,0, .12);
      text-align: center;
  }

  .l-button {
      width: 100%;
      display: flex;
  }

  button {
      height: 50px;
      width: 50%;
      font-size: 18px;
      font-weight: bold;
  }

  .o-button_save {
      background-color: #4B8E8D;
      border: none;
      outline: none;
      color: #fff;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

  .u-mt20 {
      margin-top: 20px;
  }

  .u-mt40 {
      margin-top: 40px;
  }

</style>
