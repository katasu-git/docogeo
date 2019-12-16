<template>
  <div id="uploading">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
            <div class="o-text">
                <p>アップロードしています...</p>
            </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'uploading',
    props: {
        tour_id: '',
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
            this.$emit('jumpPage', 'editSpot');
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #uploading {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    height: 100%;
    width: 100%;
    position: fixed;
    z-index: 1;
    pointer-events: none;
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
    background-color: rgba(0,0,0, .70);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
  }

  .o-modal {
    width: calc(100% - 40px);
    border-radius: 30px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
  }

  .o-text {
    padding: 30px;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  textarea {
      outline: none;
      margin: 20px 0;
      resize: vertical;
      width: calc(100vw - 60px);
      min-height: 80px;
      font-size: 18px;
      color: rgba(0,0,0, .87);
      text-align: center;
      border: solid 1px rgba(0,0,0, .12);
      border-radius: 10px;
  }

  input::placeholder {
      font-size: 18px;
      color: rgba(0,0,0, .12);
      text-align: center;
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
    border-top: solid 1px rgba(0,0,0, .12);
  }

  .o-button_finish {
    background: #CC544D;
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
