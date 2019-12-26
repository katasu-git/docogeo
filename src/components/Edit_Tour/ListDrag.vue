<template>
  <div id="list_drag">
    <draggable 
        :list="spot_info" 
        :animation="150"
        @update="update_order_spot_name()"
    >
        <div 
            class="o-list"
            v-for="(info) in spot_info" 
            :key="info.spot_id"
        >
          <div class="l-image_text_burger">
            <div class="l-image_text">
              <div class="o-list_image">
                <img 
                    class="o-image_circle"
                    src="../../assets/kujira.svg" 
                />
              </div>
              <div class="l-list_text">
                <div class="o-list_text_geosite">{{ info.spot_name }}</div>
                <div class="o-list_text_update">最終更新 {{return_sended(info.updated)}}</div>
              </div>
            </div>
            <div class="o-burger">
                <img 
                    class="u_pointer"
                    src="../../assets/burger_button.svg" 
                />
            </div>
          </div>
          <div class="o-border u-mt10"></div>
        </div>
    </draggable>
  </div>
</template>

<script>
import axios from 'axios'
import draggable from 'vuedraggable'
export default {
  name: 'list_drag',
  props: {
      spot_info: Array
  },
  methods: {
      return_sended(sended) {
            let month = sended.substr(5, 2) + '月';
            let day = sended.substr(8, 2) + '日';
            let time = ' ' + sended.substr(10, 6);
            return month + day + time;
      },
      update_order_spot_name() {
        console.log("hello");
        const url = 'https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Edit_Tour/update_order_spot_name.php';
        axios.post(url, this.spot_info
        ).then(response => {
            //返ってきたデータの処理
            console.log(response.data);
        }).catch(error => {
            // エラーを受け取る
            console.log(error);
        });
      },
  },
  components: {
      draggable: draggable,
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.o-image_circle {
    height: 50px;
    width: 50px;
    border-radius: 100px;
    object-fit: cover;
    border: solid 1px rgba(0,0,0, .12);
}

.o-list {
    padding: 20px 0 0 20px;
    width: calc(100% - 80px);
}

.o-list:last-of-type {
    margin-bottom: 100px;
}

.l-image_text_burger {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.o-burger {
    margin-right: 20px;
}

.l-image_text {
    display: flex;
}

.l-list_text {
    margin: 0 0 0 10px;
}

.o-list_text_geosite {
    font-size: 18px;
    font-weight: bold;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }

.o-list_text_update {
    font-size: 12px;
    color: rgba(0,0,0, .26);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.o-border {
    height: 1px;
    width: calc(100vw - 100px);
    background-color: rgba(0,0,0, .12);
}
</style>
