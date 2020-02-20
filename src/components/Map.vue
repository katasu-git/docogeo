<template>
    <div id="maps">

    <div class="background">
        <div class="l_altitude">
            <div class="o_altitude">
                <p class="example"><span class="u-color-red">赤いピン</span>を動かして海抜を見られます</p>
                <div class="o_alt_text">
                    海抜：
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
                    v-for="(m) in markers"
                    :position="m.position"
                    :clickable="false"
                    :draggable="false"
                    :icon="icon_center"
                />
                <GmapMarker
                    :key="index"
                    v-for="(m, index) in markers"
                    :position="m.position"
                    :clickable="false"
                    :draggable="true"
                    @dragend="updateCoordinates"
                    :icon="icon_pin"
                />
            </GmapMap>

            <div class="dif_altitude">1分ごとの海抜の変化</div>

            <chart
                :eval_log="eval_log" 
                class="chart u-mt20 u-mb150" 
            />

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
import Chart from '../components/parts/Chart';

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
        },
        icon_center: {
            url: require('../assets/center_icon.png'),
            size: {width: 30, height: 30, f: 'px', b: 'px'},
            scaledSize: {width: 30, height: 30, f: 'px', b: 'px'}
        },
        icon_pin: {
            url: require('../assets/pin_icon.png'),
            size: {width: 30, height: 60, f: 'px', b: 'px'},
            scaledSize: {width: 30, height: 50, f: 'px', b: 'px'}
        },
        eval_log: []
      }
    },
    created() {
        navigator.geolocation.getCurrentPosition(this.create_src);
        this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'));
        this.user_info = JSON.parse(this.$localStorage.get('user_info'));
        this.fetch_eval_log();
    },
    mounted() {
        this.init();
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
            this.getElevation(this.lat, this.lng);
        },
        getElevation(lat, lng) {
            let url ="https://map.yahooapis.jp/alt/V1/getAltitude?appid=dj00aiZpPW5HanZkalZwY1poTyZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-&coordinates=" 
                + lng + "," + lat + "&output=json";
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

        updateCoordinates(location) {
            let lat = location.latLng.lat()
            let lng = location.latLng.lng()
            this.getElevation(lat, lng);
        },

        async fetch_eval_log() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/fetch_eval_log.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            const res = await axios.post(url, params);
            let eval_log = res.data;
            for(let i=eval_log.length - 1; i>=0; i--) {
                this.eval_log.push(eval_log[i].altitude);
            }
            console.log(this.eval_log)
        }
    },
    components: {
        Footer: Footer,
        VueHeader: VueHeader,
        Chart: Chart
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
    overflow-x: hidden;
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
    margin: 20px;
    width: calc(100% - 40px);
    background-color: #FFF;
    border-radius: 10px;
    filter: drop-shadow(0 1px 3px rgba(0,0,0,.26));
}

.o_alt_text {
    padding: 20px;
    width: calc(100% - 40px);
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

.example {
    font-weight: bold;
    font-size: 14px;
    width: 100%;
    text-align: center;
    padding: 20px;
}

.u-mt20 {
    margin-top: 20px;
}

.u-mb150 {
    margin-bottom: 150px;
}

.dif_altitude {
    margin: 40px 0 0 20px;
    font-weight: bold;
    font-size: 18px;
}

</style>
