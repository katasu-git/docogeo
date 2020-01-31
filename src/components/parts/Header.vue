<template>
  <div id="vueHeader">
    <div class="title">
        <div class="title-tourName">
            {{tour_info.tour_name}}
        </div>
        <div 
            @click="changeSpot()"
            class="title-spotName"
        >
            {{spot_info.spot_name}}
            <img 
                v-if="place !== 'map'"
                class="u-ml5" 
                src="../../assets/Polygon 1.svg" 
            />
        </div>
    </div>

    <div 
        v-if="place !== 'map'"
        class="button-wrapper">
        <div 
            @click="show_guestList()"
            class="button-userList">
            <img src="../../assets/user_list_button.svg" />
            参加者
        </div>
        <div 
            @click="finish_tour()"
            class="button-end">
            <img src="../../assets/close_button.svg" />
            ツアー終了
        </div>
    </div>
      
  </div>
</template>

<script>
export default {
name: 'vueHeader',
    props: {
        tour_info: '',
        spot_info: '',
        place: ''
    },
    data() {
        return {
        }
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        finish_tour() {
            if(this.place === "map") {
                return;
            }
            this.$emit('finish_tour');
        },
        changeSpot: function() {
            if(this.place === "map") {
                return;
            }
            this.$emit('changeSpot');
        },
        show_guestList() {
            if(this.place === "map") {
                return;
            }
            this.$emit('show_guestList');
        }
    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#vueHeader {
    width: 100%;
    height: 80px;
    position: fixed;
    background-color: #fff;
    border-bottom: solid 1px rgba(0,0,0, .12);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    filter: drop-shadow(0 3px 10px rgba(0,0,0,.26));

    display: flex;
    justify-content: space-between;
    z-index: 1;
}

.title {
    padding: 10px 0 0 20px;
}

.title-tourName {
    font-size: 24px;
    font-weight: bold;
}

.title-spotName {
    margin-top: 2px;
    font-size: 14px;
    font-weight: bold;
    color: #4B8E8D;
}

.button-wrapper {
    padding: 10px 10px 0 0;
    font-size: 12px;
    font-weight: bold;
    color: #A6A2A2;
    display: flex;
}

.button-userList, .button-end {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.button-userList {
    margin-right: 5px;
}

</style>
