<template>
  <div id="images">
    <div class="o-background">

        <div class="l-header_above">
            <div class="o-text_tour"></div>
        </div>
        <div class="l-header_under">
            <div class="o-text_tour_min">
            <p>透過させる画像を選択</p>
            </div>

        </div>
        <div class="o-img_container">
            <div 
            class="o-img_fit" 
            v-for="image in srcArray"
            :key="image.id"
            @click="onClickPostImage(image)"
            >
            <v-lazy-image
                class="box"
                :src="image.image_path"
            />
            </div>
        </div>
        <canvas
            ref="canvas"
            id="canvas"
        />
        <img id="image_hidden" src="" />
      
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { async, resolve, reject } from 'q';
import Compressor from 'compressorjs'
import Success from '../components/Images_Modal/imgSuccess'
import PopupImage from "../components/Images_Modal/imgPopup"
import Uploading from "../components/Images_Modal/imgUploading"
import DeleteImage from "../components/Images_Modal/imgDelete"
  export default {
    name: 'images',
    data() {
      return {
        tour_info: '',
        spot_info: '',
        srcArray: [],
        canvas: ''
      }
    },
    created: function () {
        
        if(!this.$route.params.isNotReload) {
            this.jumpPage("camera")
        }
        this.tour_info = JSON.parse(this.$localStorage.get('now_tour_info'));
        this.spot_info = JSON.parse(this.$localStorage.get('now_spot_info'))
        this.get_spot_image();
    },
    methods: {
        jumpPage: function(where) {
            this.$router.push({
                name: where,
            })
        },
        async get_spot_image() {
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Chat_G/get_spot_img.php";
            let params = new URLSearchParams();
            params.append('tour_id', this.tour_info.tour_id);
            params.append('spot_id', this.spot_info.spot_id);
            const response = await axios.post(url, params);

            this.srcArray = response.data;
        },

        onClickPostImage(image) {
            this.move_draw(image)
        },

        move_draw(image) {
            let image_hidden = document.getElementById("image_hidden");
            image_hidden.src = image.image_path;

            image_hidden.onload = ()=> {
                //画像の読み込みが終わったら
                this.canvas = this.$refs.canvas
                this.setCanvas(image_hidden.width, image_hidden.height);
                this.canvas.getContext('2d').drawImage(image_hidden, 0, 0, image_hidden.width, image_hidden.height);
                let captures = this.canvas.toDataURL('image/jpg')

                console.log(captures)
                //お絵かきページに移動
                this.$router.push({
                name: 'draw',
                    params: {
                        width: image_hidden.width,
                        height: image_hidden.height,
                        captures: captures,
                        spot_image_id: image.id, //spot_imagesの主キー,
                        image_path: image.image_path,
                        isNotReload: true,
                        isTrans: true
                    }
                })
            }
        },

        setCanvas(width, height) {
            //canvasのサイズ変更
            let canvas = document.getElementById("canvas");
            canvas.width = width;
            canvas.height = height;
        },
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#images, .o-background {
    height: 100%;
    width: 100%;

    background-color: #F5F5F5;
    color: rgba(0,0,0,.87);

    position: fixed;
    overflow: auto;
}

.o-text_tour {
    padding: 20px 0 0 20px;
    font-size: 36px;
    font-weight: bold;
}

.l-header_under {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.o-text_tour_min {
    padding: 0 0 0 20px;
    font-size: 18px;
    font-weight: bold;
}



.o-img_container {
    padding: 20px;
    width: calc(100% - 40px);
    display: flex;
    flex-wrap: wrap;
}

  /*ここを30%にすればデスクトップでいい感じ*/

.o-img_fit {
    position: relative;
    width: 50%;
    height: auto;
}

@media screen and (min-width:1024px) { 
    /*　画面サイズが480pxからはここを読み込む　*/
    .o-img_fit {
    position: relative;
    width: 20%;
    height: auto;
    }
}

.o-img_fit:before {
    content: "";
    display: flex;
    padding-top: 100%;
}

.box {
    position: absolute;
    top: 0; 
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

#canvas {
    visibility: hidden;
  }

#image_hidden {
    width: calc(100% - 20px);
    visibility: hidden;
}

</style>
