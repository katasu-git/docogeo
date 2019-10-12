<template>
  <div id="editSpot">
    <div class="l-body">
        
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'editTour',
    data() {
      return {
          spot_info: JSON,
          tour_name: 'ツアー名を入力',
          group_name: 'グループ名を入力'
      }
    },
    created: function () {
      this.accessDb();
      if(JSON.stringify(this.$route.params) == "{}") {
        // 更新されたときはトップに戻る
        this.jumpPage("EditTour");
      }
    },
    methods: {
      accessDb: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_spot_info.php').then(response => {
          this.spot_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where) {
        this.$router.push({
            name: where,
            params: {}
        })
      },
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #editSpot,
  .l-body {
    height: 100%;
    width: 100%;
  }

  .l-body {
    background: #5c9982;
  }

  .l-justify-center {
    display: flex;
    justify-content: center;
    /*中央よせ*/
  }

</style>
