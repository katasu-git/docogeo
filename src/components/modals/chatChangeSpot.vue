<template>
  <div id="changespot">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
            <button 
                v-for="name in spot_names"
                @click="change_spot_name(name.spot_id)"
            >
                <div class="o-text" :style="{color:returnCol(name.spot_id)}">{{ name.spot_name }}</div>
                <div class="o-border u-mt10"></div>
            </button>
            <button
                class="o-button_cancel"
                @click="closeModal()"
            >キャンセル</button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'changespot',
    props: {
        tour_id: '',
        spot_id: '',
        spot_names: '',
    },
    data() {
      return {
          comment: '',
      }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        returnCol(id) {
            console.log(this.spot_id);
            console.log(id);
            if(this.spot_id == id) {
                return '#4B8E8D';
            }
        },
        change_spot_name(spot_id_selected) {
            this.$emit('change_spot_name', spot_id_selected);
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #changespot {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    height: 100%;
    width: 100%;
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
    width: calc(100% - 40px);
    border-radius: 30px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
  }

  .o-text {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
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

  .o-button_cancel {
    padding: 20px;
    font-size: 16px;
    font-weight: bold;
    background: #CC544D;
    color: #fff;
    border-radius: 0 0 30px 30px;
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
