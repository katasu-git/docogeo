<template>
  <div id="maps">
      <div class="o-background">
      
        <div class="o-header">
            <div class="l-header_above">
                <div class="o-text_tour">ツアーの名前</div>
            </div>
            <div class="l-header_under u-mb20">
                <div class="o-text_tour_min"><span class="u-color-green">スポットの名前</span></div>
            </div>
        </div>

        <!--
        <iframe 
            :src=src 
            width="300" 
            height="450" 
            frameborder="0" 
            style="border:0;" 
            allowfullscreen=""
        >
        </iframe>
        -->

        <Footer></Footer>

      </div>
  </div>
</template>

<script>
  import Footer from '../components/parts/Footer'
  //AIzaSyAoNzWElg3mCVGsrO8oOFTlWxMwAHjnY30

  export default {
    name: 'maps',
    data() {
      return {
        src: String,
      }
    },
    created() {
        //navigator.geolocation.getCurrentPosition(this.create_src);
    },
    mounted() {
        /*
        const map = L.map( 'map', { center: L.latLng( this.lat, this.lon ), zoom: 17 } ).addLayer(
            L.tileLayer( 'http://{s}.tile.osm.org/{z}/{x}/{y}.png' )
        )

        var lc = L.control.locate(option).addTo(map);
        lc.start();
        */
    },
    methods: {
        create_src(position) {
            this.lat =  position.coords.latitude;
            this.lon = position.coords.longitude;
        },
    },
    components: {
        Footer: Footer,
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#maps {
    position: relative;
    height: 100%;
    width: 100%;
    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
}

#map {
    position: absolute;
    top: 200px;
    width: 300px;
    height: 300px;
}

.o-header {
    position: fixed;
    height: 80px;
    width: 100%;
    background-color: #fff;
    filter: drop-shadow(0 0 5px rgba(0,0,0,.26));
    z-index: 1;
}

.l-header_above {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.o-text_tour {
    padding: 10px 0 0 20px;
    font-size: 24px;
    font-weight: bold;
}

.l-header_under {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

  .l-footer {
      position: fixed;
      right: 20px;
      bottom: 20px;
      height: 80px;
      width: calc(100% - 40px);
      background-color: #fff;
      border-radius: 100px;
      filter: drop-shadow(0 3px 10px rgba(0,0,0,.26));

      display: flex;
      justify-content: space-evenly;
      align-items: center;
  }

  .o-icon {
      display: flex;
      flex-direction: column;
      align-items: center;
  }

  .o-icon_img {
      height: 35px;
  }

  .o-footer_text {
      margin-top: 10px;
      font-size: 12px;
      color: #C2C7C6;
  }

  .u-color-green {
    color: #4B8E8D;
  }

  .u-color-red {
    color: #CC544D;
  }

</style>
