<template>
  <div id="images">
    <div class="o-background">

    　　 <div id="comuppic" v-show="flag_add_img">
            <div class="o-background_black">
                <div class="o-modal">
                    <input 
                      type="file"
                      @change="onFileChange"
                      name="upfile" 
                      id="upfile"
                      class="u-mt40"
                    />
                    <img class="o-preview_img" v-show="uploadedImage" :src="uploadedImage" alt="preview_img" />
                    <div class="l-button u-mt40">
                        <button class="o-button_cancel" @click="closeModal()">キャンセル</button>
                        <button class="o-button_save" type="submit" @click="postFile()">追加する</button>
                    </div>
                </div>
            </div>
        </div>

        <Success
          v-show="flag_success"
          @closeModal="closeModal"
          @jumpPage=jumpPage></Success>

      <div class="l-header_above">
        <div class="o-text_tour">Images</div>
        <div 
          class="o-image_image_button"
          v-show="!flag_add"
        >
            <img @click="startSort()" v-show="!flag_order" src="../assets/sort_button.svg" />
            <img @click="startSort()" v-show="flag_order" src="../assets/sort_button_active.svg" />
        </div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">
          <p v-show="!flag_add">画像</p>
          <p v-show="flag_add">追加する画像を選択</p>
        </div>
        <div 
          class="o-text_add_image" 
          v-bind:style="{ color: returnSortColor()}"
          v-show="!flag_add"
        >並べ替え</div>
      </div>

      <label 
        class="o-upload_button"
        v-show="!flag_add"
      >
        <img src="../assets/upload_button.svg" @click="wakeAddImg()"/>
      </label>

      <div class="o-img_container">
        <div class="o-img_fit" v-for="image in srcArray" :key="image.id">
            <img
            class="box"
            :src="image.imgPath"
            :style="{opacity: returnOpacity(image.isAdded)}"
            @touchstart="addImgToSpot(image.id, image.isAdded)" />
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
import Success from '../components/modals/imgSuccess'
  export default {
    name: 'images',
    data() {
      return {
        file: '',
        uploadedImage: '',
        img_name: '',
        flag_order: false,
        flag_add_img: false,
        flag_success: false,
        flag_add: false,
        tour_id: '', //commentから渡ってきた場合
        spot_id: '', //commentから渡ってきた場合
        srcArray: [],
      }
    },
    created: function () {
      if(this.$route.params.tour_id != undefined && this.$route.params.spot_id != undefined) {
        this.tour_id = this.$route.params.tour_id;
        this.spot_id = this.$route.params.spot_id;
        this.flag_add = true;
      }
      this.getAllImage();
    },
    methods: {
        jumpPage: function(where) {
            this.$router.push({
                name: where,
                params: {
                tour_id: this.tour_id,
                spot_id: this.spot_id,
                }
            })
        },
        closeModal: function() {
            console.log("発火");
            this.flag_add_img = false;
            this.flag_success = false;
            this.file = '';
            this.uploadedImage = '';
            this.img_name = '';
            this.getAllImage();
        },
        wakeAddImg() {
            this.flag_add_img = true;
        },
        startSort: function() {
            if(this.flag_order) {
            this.flag_order = false;
            } else {
            this.flag_order = true;
            }
        },
        returnSortColor: function() {
            if(this.flag_order) {
            return '#4B8E8D';
            } else {
            return 'rgba(0,0,0,.26)';
            }
        },
        onFileChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            //console.log(files[0]);
            this.file = files[0];
            this.createImage(files[0]);
            this.img_name = files[0].name;
        },
        createImage(file) {
            const reader = new FileReader();
            reader.onload = e => {
                this.uploadedImage = e.target.result;
            };

            reader.readAsDataURL(file);
        },
        postFile: function() {
          console.log(this.file.type);
          if(this.file.type == 'image/jpeg' || this.file.type == 'image/png') {
                let formData = new FormData();
                formData.append('upfile', this.file);
                axios
                    .post('https://www2.yoslab.net/~nishimura/geotour/PHP/upload.php', 
                        formData, {
                            headers: {
                                 'Content-Type': 'multipart/form-data',
                            }
                        })
                    .then(function(response) {
                        // response 処理
                        console.log(response.data);
                        this.closeModal();
                    })
                    .catch(function(error) {
                        // error 処理
                    })
          } else {
            console.log("だめ");
          }
        },
        addImgToSpot(index, isAdded) {
          if(this.tour_id == '' || this.spot_id == '' || isAdded == 1) {
            console.log("reject");
            return; //editページ以外からの遷移時は登録しない
          }
          console.log(this.tour_id);
          console.log(this.spot_id);
          console.log(index);
          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/add_img_spot.php';
              let params = new URLSearchParams();
              params.append('tour_id', this.tour_id);
              params.append('spot_id', this.spot_id);
              params.append('image_id', index);
              axios.post(url, params
              ).then(response => {
                //ここでeditに戻る処理
                this.flag_success = true;
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
        },
        getAllImage() {
          const url = 'https://www2.yoslab.net/~nishimura/geotour/PHP/GET/get_all_image.php';
              axios.post(url
              ).then(response => {
                this.srcArray = response.data;
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
        },
        returnOpacity(isAdded) {
          if(isAdded == 1 && this.flag_add) {
            return '0.2';
          } else {
            return '1';
          }
        }
    },
    components: {
      Success: Success,
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
  }

  .l-header_above {
    width: 100%;

    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }

    .o-text_tour {
      padding: 20px 0 0 20px;

      font-size: 36px;
      font-weight: bold;
    }

    .o-image_image_button {
      padding: 0 20px 0 20px;
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

    .o-text_add_image {
      padding: 0 15px 0 0;

      font-size: 12px;
      font-weight: bold;
      color: rgba(0,0,0, .26);
    }

    .o-upload_button {
        width: 40px;
        position: fixed;
        bottom: 20px;
        right: 20px;
    }

    .u-disp_none {
        display: none;
    }

    #comuppic {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .o-border {
    height: 1px;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;
    background-color: rgba(0,0,0, .54);

    display: flex;
    align-items: flex-end;
    z-index: 1;
  }

  .o-modal {
    width: 100%;
    border-radius: 30px 30px 0 0;
    background-color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .l-button {
      width: 100%;
      display: flex;
  }

  button {
      height: 60px;
      width: 50%;
      font-size: 18px;
      font-weight: bold;
  }

  .o-button_save {
      background-color: #4B8E8D;
      border: none;
      outline: none;
      color: #fff;
  }

  .o-preview_img {
      padding: 20px;
      max-width: calc(100% - 50px);
      max-height: 200px;
      border-radius: 30px;
  }

  .o-img_container {
    margin: 20px 0 100px 20px;
    width: calc(100vw - 100px);
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

  .u-mt40 {
    margin-top: 40px;
  }

</style>
