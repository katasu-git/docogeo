<template>
  <div id="list">
        <div class="o-list"
            v-for="(info) in spot_info" :key="info.spot_id">
          <div class="l-image_text_burger">
            <div class="l-image_text">
              <div class="o-list_image"><img class="o-image_circle" src="../../assets/kujira.svg" /></div>
              <div class="l-list_text">
                <div class="l_text_pen_icon">
                    <div 
                        @click="move_page(info)"
                        class="o-list_text_geosite u_pointer">{{ info.spot_name }}</div>
                    <img 
                        class="u_pointer u_ml10"
                        src="../../assets/pen_update_black.svg" 
                        @click="change_name(info)"
                    />
                </div>
                <div class="o-burger">
                 </div>
                <div class="o-list_text_update">{{return_sended(info.updated)}}</div>
              </div>
            </div>
            <img 
                class="u_pointer"
                src="../../assets/delete_circle.svg" 
                @click="delete_spot(info)"
            />
          </div>
          <div class="o-border u-mt10"></div>
        </div>
      </div>
</template>

<script>
export default {
  name: 'list',
  props: {
      spot_info: Array
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
      delete_spot(spot_info) {
          this.$emit('delete_spot', spot_info);
      },
      change_name(spot_info) {
          this.$emit('change_name', spot_info);
      },
      move_page(spot_info) {
          this.$emit('move_page', 'editSpot', spot_info)
      }
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
    margin-bottom: 120px;
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
    width: 100%;
    background-color: rgba(0,0,0, .12);
}

.l_text_pen_icon {
  display: flex;
  align-items: center;
}
</style>
