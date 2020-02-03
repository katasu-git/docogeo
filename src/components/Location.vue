<template>
    <div id="location">
        <p>GET LOCATION HERE!!</p>
        <p>left time <span class="fs30">{{increment}}</span> sec</p>
        <p>success <span class="fs30">{{increment_success}}</span></p>
        <p>dont move <span class="fs30">{{increment_dontMove}}</span></p>
        <div v-for="(alt, i) in altitudes" :key="i">
            {{ alt }} m
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q'

export default {
    name: 'location',
    data() {
      return {
          tour_info: '',
          lat: Number,
          lng: Number,
          altitude: Number,
          altitudes: [],
          increment: 0,
          increment_success: 0,
          increment_dontMove: 0
      }
    },
    created() {
        this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        setInterval(function() {
            this.init();
            this.increment += 10;
        }.bind(this), 10000);
    },
    methods: {
        init() {
            navigator.geolocation.getCurrentPosition(this.get_location);
        },
        get_location(position) {
            if(this.lat == Number(position.coords.latitude) && this.lng == Number(position.coords.longitude)) {
                //動いていない場合は何もしない
                console.log("you dont move");
                this.increment_dontMove++;
                return;
            }
            this.lat =  Number(position.coords.latitude);
            this.lng = Number(position.coords.longitude);
            this.get_elevation();
        },
        get_elevation() {
            let url ="https://map.yahooapis.jp/alt/V1/getAltitude?appid=dj00aiZpPW5HanZkalZwY1poTyZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-&coordinates=" 
                + this.lng + "," + this.lat + "&output=json";
            this.$jsonp(url).then(json => {
                // Success.
                this.altitude = json.Feature[0].Property.Altitude;
                this.altitudes.push(this.altitude);
                this.post_location(this.altitude)
            }).catch(err => {
                // Failed.
            })
        },
        async post_location(altitude) {
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/post_location.php";
            let params = new URLSearchParams();
            params.append("tour_id", this.tour_info.tour_id);
            params.append("altitude", altitude);
            const res = await axios.post(url, params);
            this.increment_success++;
        },
    },
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#location {
    position: fixed;
    height: 100%;
    width: 100%;
    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);
}

.fs30 {
    font-size: 30px;
    font-weight: bold;
}

</style>
