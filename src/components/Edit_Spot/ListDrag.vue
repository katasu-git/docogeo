<template>
  <div id="list">
      <draggable 
        class="l-comment_container"
        @update="update_order_spot_ex()"
        :list="spot_ex" 
        :animation="150"
      >
            <div class="l-comment_row" v-for="ex in spot_ex" :key="ex.id">
                <div class="l-flex_end">
                    <div>
                        <div
                        class="l-comment"
                        >{{ ex.spot_ex }}</div>
                        <div class="o-send_time">{{return_sended(ex.created)}}</div>
                    </div>
                </div>
                <div class="o-burger u_pointer u_ml20"><img src="../../assets/burger_button.svg" /></div>
            </div>
      </draggable>
  </div>
</template>

<script>
import axios from "axios";
import draggable from 'vuedraggable';
export default {
  name: "list",
  props: {
    spot_ex: ''
  },
  methods: {
      return_sended(sended) {
        if(!sended) {
            return;
        }
        let month = sended.substr(5, 2) + '月';
        let day = sended.substr(8, 2) + '日';
        let time = ' ' + sended.substr(10, 6);
        return month + day + time;
      },
      update_order_spot_ex() {
            const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Spot/update_order_spot_ex.php';
            let params = new URLSearchParams();
            params.append("spot_ex", JSON.stringify(this.spot_ex));
            axios.post(url, params)
                .then(response => {})
                .catch(error => {
                    // エラーを受け取る
                    console.log(error);
                });
      },
  },
  components: {
    draggable: draggable,
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .l-comment_container {
      width: calc(100% - 40px);
      margin-left: 20px;
      word-wrap: break-word;
  }

  .l-comment_row {
      display: flex;
      align-items: center;
      justify-content: flex-start;
  }

  .l-comment_row:first-of-type {
      margin-top: 20px;
  }

  .l-comment_row:not(:first-of-type) {
      margin-top: 20px;
  }

  .l-comment_row:last-of-type {
      margin-bottom: 120px;
  }

  .l-flex_end {
      width: 100%;
      display: flex;
      align-items: flex-end;
  }

  .l-comment {
      padding: 10px;
      background-color: #E3E5E5;
      border-radius: 10px;
  }

  .o-send_time {
      margin: 0 25px 0 5px;
      display: flex;
      align-items: flex-end;
      white-space: nowrap;

      font-size: 10px;
      color: #A2A6A5;
  }
</style>
