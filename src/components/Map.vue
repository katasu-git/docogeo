<template>
    <div id="maps">

    <div class="background">
        <div class="l_altitude">
            <div class="o_altitude">
                <div class="o_alt_text">
                    現在地の海抜：
                    <div 
                        class="o_alt_green"
                        :style="{ 'font-size': return_font_size()}" 
                    >{{return_altitude()}}</div>
                m</div>
            </div>
        </div>
        <div class="body">

            <GmapMap
                class="gmap"
                :center="{lat:this.lat, lng:this.lng}"
                :zoom="18"
                :options="{streetViewControl: false, gestureHandling: 'cooperative'}"
                map-type-id="terrain"
                :style="{ width: width, height: height }"
            >
                <GmapMarker
                    :key="index"
                    v-for="(m, index) in markers"
                    :position="m.position"
                    :clickable="true"
                    :draggable="true"
                    @click="center=m.position"
                />
            </GmapMap>

        </div>
    </div>
    <Footer
        :place="place"
        :user="user"
    ></Footer>
  </div>
</template>

<script>
import axios from 'axios'
import VueHeader from '../components/parts/Header'
import Footer from '../components/parts/Footer'

  export default {
    name: 'maps',
    data() {
      return {
        place: "map",
        user: "guide",
        tour_info: '',
        spot_info: '',
        user_info: '',
        src: String,
        lat: Number,
        lng: Number,
        altitude: Number,
        width: '200px',
        height: '450px',
        markers: [{ position: { lat: 10, lng: 10 } }],
        flag: {
            isLoad: false
        }
      }
    },
    created() {
        navigator.geolocation.getCurrentPosition(this.create_src);
        this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'));
        this.user_info = JSON.parse(this.$localStorage.get('user_info'));
    },
    mounted() {
        this.init();
        this.countup_pageview()
    },
    methods: {
        init() {
            this.width = (document.getElementById("maps").clientWidth - 40 ) + "px";
        },
        create_src(position) {
            this.lat =  Number(position.coords.latitude);
            this.lng = Number(position.coords.longitude);
            this.markers[0].position.lat = this.lat;
            this.markers[0].position.lng = this.lng;
            this.getElevation();
        },
        getElevation() {
            let url ="https://map.yahooapis.jp/alt/V1/getAltitude?appid=dj00aiZpPW5HanZkalZwY1poTyZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-&coordinates=" 
                + this.lng + "," + this.lat + "&output=json";
            this.$jsonp(url).then(json => {
                // Success.
                console.log(json.Feature[0].Property.Altitude);
                this.altitude = json.Feature[0].Property.Altitude;
                this.flag.isLoad = true;
            }).catch(err => {
                // Failed.
            })
        },
        return_altitude() {
            if(this.flag.isLoad) {
                return this.altitude;
            } else {
                return '読み込み中';
            }
        },
        return_font_size() {
            if(this.flag.isLoad) {
                return '36px' 
            } else {
                return '20px'
            }
        },
        async countup_pageview() {
            if(!this.user_info) {
                return;
            }
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/countup_pageview.php";
            let params = new URLSearchParams();
            params.append("id", this.user_info.id);
            params.append("where", this.place);
            const res = await axios.post(url, params);
        },
    },
    components: {
        Footer: Footer,
        VueHeader: VueHeader
    }
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#maps {
    position: fixed;
    height: 100%;
    width: 100%;
    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
    overflow-y: scroll;
	-webkit-overflow-scrolling: touch;
}

.background {
    width: 100%;
}

.l_altitude {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.o_altitude {
    margin-top: 100px;
    margin-bottom: 20px;
    width: calc(100% - 40px);
    height: 150px;
    background-color: #FFF;
    border-radius: 10px;
    filter: drop-shadow(0 1px 3px rgba(0,0,0,.26));
}

.o_alt_text {
    margin-top: 10px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    line-height: 35px;
    font-weight: bold;
    font-size: 18px;
}

.o_alt_green {
    line-height: 50px;
    color: #4B8E8D;
}

.gmap {
    margin-left: 20px;
    margin-bottom: 170px;
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
align-items: flex-start;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}

.o-text_tour {
    padding: 10px 20px 0 20px;
    font-size: 24px;
    line-height: calc(24px * 1.5);
    font-weight: bold;
}

.o-image_image_button {
    padding: 10px 20px 0 0;
}

.o-button_sort {
    fill: #4B8E8D;
}

.l-header_under {
width: 100%;

display: flex;
justify-content: space-between;
align-items: center;
}

.o-text_tour_min {
    padding: 0 0 0 20px;

    font-size: 14px;
    font-weight: bold;
}

.o-text_add_image {
    padding: 0 10px 0 0;

    font-size: 12px;
    font-weight: bold;
    color: rgba(0,0,0, .26);
}

</style>
