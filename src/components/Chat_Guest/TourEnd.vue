<template>
  <div id="tourEnd">
    <div class="o-background">
      
      <div class="o-background_black">
        <div class="o-modal">
          <div class="o-text">{{ tour_name }}</div>
          <div class="o-text_sub">このイベントは終了しました</div>
          <div class="l_button">
            <div
                @click="end_tour()"
              class="o_button_cancel u_pointer">トップに戻る</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'tourEnd',
    props: {
        tour_name: '',
        user_info: '',
    },
    methods: {
        async end_tour() {
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_U/end_tour.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("tour_id", this.user_info.tour_id);
            axios.post(url, params).then(response => {
              //ユーザ情報のリセット
              this.$localStorage.remove('user_info');
              this.$router.push({
                  name: "top_u"
              });
            })
            .catch(error => {
              // エラーを受け取る
              console.log(error);
            });
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #tourEnd {
    position: fixed;
    z-index: 2;
  }
  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .35);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
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
    padding: 40px 20px 0 20px;
    font-size: 20px;
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
    left: 0;
    right: 0;
    margin: auto;
    font-size: 12px;
    font-weight: bold;
    width: 100px;
    text-align: center;
    color: #4B8E8D;
  }

</style>
