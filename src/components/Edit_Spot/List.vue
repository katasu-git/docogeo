<template>
  <div id="list">
      <div class="l-comment_container">
            <div class="l-comment_row" v-for="ex in spot_ex" :key="ex.id">
                <div class="l-flex_end">
                    <div class="flex_row">
                        <div>
                            <div
                            class="l-comment"
                            >{{ex.spot_ex}}</div>
                            <div class="o-send_time">{{return_sended(ex.updated)}}</div>
                        </div>
                        <img 
                            class="o_pen_icon u_ml10 u_pointer"
                            src="../../assets/pen_update_black.svg" 
                            @click="edit_spot_ex(ex)"
                        />
                    </div>
                    <div 
                        class="o-burger u_pointer u_ml20"
                        @click="delete_selected(ex, false)"
                    ><img src="../../assets/delete_circle.svg" /></div>
                </div>
            </div>
      </div>
  </div>
</template>

<script>
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
      delete_selected(info, isImage) {
          this.$emit('delete_selected', info, isImage);
      },
      edit_spot_ex(info) {
          this.$emit('edit_spot_ex', info);
      }
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
      align-items: center;
      justify-content: space-between;
  }

  .l-comment {
      padding: 10px;
      background-color: #E3E5E5;
      border-radius: 10px;
      white-space: pre-line; /*改行の指定に必要*/
  }

  .o-send_time {
      margin: 0 25px 0 5px;
      display: flex;
      align-items: flex-end;
      white-space: nowrap;

      font-size: 10px;
      color: #A2A6A5;
  }

  .flex_row {
      display: flex;
      justify-content: center;
  }

  .o_pen_icon {
      margin-top: -15px;
  }
</style>
