<template>
  <div id="vueHeader">
    <div class="title">
        <div class="title-tourName">
            {{tour_info.tour_name}}
        </div>
        <div 
            class="title-spotName"
        >
            {{user_info.name}}
            <img class="u-ml5" src="../../assets/Polygon 1.svg" />
        </div>
    </div>
  </div>
</template>

<script>
export default {
name: 'vueHeader',
    props: {
        tour_info: '',
        user_info: '',
    },
    data() {
        return {
        }
    },
    methods: {

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
