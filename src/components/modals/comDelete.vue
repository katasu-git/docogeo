<template>
  <div id="comdelete">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text">{{ ex_avoid }}</div>
          <div class="o-text_sub">この説明文を削除します。よろしいですか？</div>
          <div class="l_button">
            <div
              @click="closeModal" 
              class="o_button_cancel u_pointer">キャンセル</div>
            <div 
              @click="delete_spot()"
              class="o_button_delete u_pointer">削除</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'comdelete',
    props: {
        ex_avoid: '',
        ex_id_avoid: ''
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        delete_spot() {
            const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/delete_spot_ex.php';
              let params = new URLSearchParams();
              params.append('ex_id', this.ex_id_avoid);
              axios.post(url, params
              ).then(response => {
                this.$emit('get_spot_ex');
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
    width: calc(100% - 40px);
    max-width: 400px;
    border-radius: 10px;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .o-text {
    margin: 20px 10px;
    padding: 40px 0 20px 0;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
    border-radius: 5px;
  }

  .o-text_sub {
    margin: 20px 10px;
    padding: 0 0 20px 0;
    font-size: 12px;
    font-weight: bold;
    transition: 100ms;
    border-radius: 5px;
  }

  .l_button {
    position: relative;
    height: 40px;
    width: 100%;
  }

  .o_button_cancel {
    position: absolute;
    left: 20px;
    font-size: 12px;
    font-weight: bold;
    width: 70px;
    color: #4B8E8D;
  }

  .o_button_edit {
    position: absolute;
    right: 60px;
    font-size: 12px;
    font-weight: bold;
    width: 30px;
  }

  .o_button_delete {
    position: absolute;
    right: 20px;
    font-size: 12px;
    font-weight: bold;
    width: 30px;
    color: #CC544D;
  }
</style>
