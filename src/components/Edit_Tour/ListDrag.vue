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
      spot_info: ''
  },
  methods: {
    update_order_spot_name() {
        const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Edit_Tour/update_order.php';
        let params = new URLSearchParams();
        params.append('spot_info', JSON.stringify(this.spot_info));
        axios.post(url, params
        ).then(response => {
            console.log(response.data);
        }).catch(error => {
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
    width: 100%;
    background-color: rgba(0,0,0, .12);
}
</style>
