<template>
  <div id="popupimage">
      
      <div class="o-background_black">
        <div class="o-modal">
            <img 
              @click="delete_image()"
              class="o_button_delete"
              src="../../assets/delete_icon.svg"
            />
            <div class="o-text">
                <img
                    class="o-image" 
                    :src="image.image_path" />
            </div>
            <div class="l-button">
                <button 
                    class="o-button_cancel"
                    @click="closeModal()"
                >閉じる</button>
            </div>
        </div>
      </div>


  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'popupimage',
    props: {
        image: '',
    },
    methods: {
        closeModal: function() {
            console.log(this.image);
            this.$emit('closeModal');
        },
        delete_image() {
            this.$emit('closeModal');
            this.$emit('confDelete');
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #popupimage {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    height: 100%;
    width: 100%;
    position: fixed;
    z-index: 1;
  }

  .o-border {
    width: calc(100vw - 40px);
    height: 1px;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .75);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
  }

  .o-modal {
    position: relative;
    width: calc(100% - 40px);
    max-width: 400px;
    border-radius: 10px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-image {
      max-width: calc(100% - 20px);
      max-height: 250px;
      border-radius: 10px;
  }


  .l-button {
      width: 100%;
      display: flex;
  }

  .o-button_cancel, .o-button_finish {
    padding: 20px;
    width: 100%;
    font-size: 16px;
    font-weight: bold;
    color: #4B8E8D;
  }

  .o-button_finish {
    background: #CC544D;
    color: #fff;
  }

  .o_button_delete {
    position: absolute;
    right: 0;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

  .u-mt10 {
      margin-top: 10px;
  }

  .u-mt20 {
      margin-top: 20px;
  }

  .u-mt40 {
      margin-top: 40px;
  }

  .tour_name {
      font-size: 30px;
  }

</style>
