<template>
  <div id="images">
    <div class="o-background">

      <transition name="fade">
        <PopupImage
          @closeModal="closeModal"
          @confDelete="confDelete"
          :image="image_avoid"
          v-show="flag_popup_image"></PopupImage>
      </transition>

      <transition name="fade">
        <DeleteImage
          @closeModal="closeModal"
          :image="image_avoid"
          v-show="flag_delete_image"></DeleteImage>
      </transition>

      <transition name="fade">
        <Uploading
          v-if="flag_uploading"></Uploading>
      </transition>

        <Success
          v-show="flag_success"
          @closeModal="closeModal"
          @jumpPage=jumpPage></Success>

      <div class="l-header_above">
        <div class="o-text_tour">Images</div>

      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">
          <p v-show="!flag_add">画像</p>
          <p v-show="flag_add">追加する画像を選択</p>
        </div>

      </div>

      <label 
        class="o-upload_button"
        v-show="!flag_add"
      >
        <img src="../assets/upload_button.svg" @click="wakeAddImg()"/>
      </label>

      <div class="o-img_container">
        <div 
          class="o-img_fit" 
          v-for="image in srcArray"
          :key="image.id"
          @click="popup_image(image, image.id, image.isAdded, image.image_path)"
        >
          <v-lazy-image
            class="box"
            :src="image.image_path"
          />
        </div>
      </div>
      
    </div>
    <div class="o-modal"
        v-show="flag_add_img"
        v-bind:class="{ slideIn: flag_add_img, slideOut: !flag_add_img }">
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
              <button class="o-button_save" type="submit" @click="postFile(file)">追加する</button>
          </div>
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
        file: '',
        uploadedImage: '',
        img_name: '',
        flag_order: false,
        flag_add_img: false,
        flag_success: false,
        flag_add: false,
        flag_popup_image: false,
        flag_uploading: false,
        flag_delete_image: false,
        srcArray: [],
        image_avoid: ''
      }
    },
    created: function () {
      if(JSON.stringify(this.$route.params) != "{}") {
        this.tour_info = this.$route.params.tour_info;
        this.spot_info = this.$route.params.spot_info;
      }
      if(this.$route.params.add_image_flag) {
        this.flag_add = true;
      }
      this.getAllImage();
    },
    methods: {
        jumpPage: function(where) {
            this.$router.push({
                name: where,
                params: {
                tour_info: this.tour_info,
                spot_info: this.spot_info,
                }
            })
        },
        closeModal: function() {
            this.flag_add_img = false;
            this.flag_success = false;
            this.flag_popup_image = false;
            this.flag_uploading = false;
            this.flag_delete_image = false;
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
            this.file = files[0];
            this.createImage(files[0]);
            this.img_name = files[0].name;
            
            //圧縮の処理
            this.comp_file(this.file)
              .then((value) => {
                this.comp_file2(value)
                  .then((value) => {
                    this.file = value;
                    console.log("圧縮完了")
                  })
              })
        },
        comp_file(file) {
          return new Promise(function(resolve) {
            new Compressor(file, {
              quality: .2,
              mimeType: 'image/jpeg',
              maxWidth: 600,
              success(result) {
                resolve(result);
              },
              error(err) {
                //エラー処理
                console.log(err);
              }
            });
          })
        },
        comp_file2(file) {
          return new Promise(function(resolve) {
            
            const reader = new FileReader();
            reader.onload = function(){
              let file_post = reader.result;
              resolve(file_post)
            };
            reader.readAsDataURL(file);

          })
        },
        createImage(file) {
            const reader = new FileReader();
            reader.onload = e => {
                this.uploadedImage = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        postFile(file) {
            this.closeModal();
            this.flag_uploading = true;
            const url = "https://www3.yoslab.net/~nishimura/docogeo/PHP/Images/upload.php";
            let params = new URLSearchParams();
            params.append('image_data', file);
            axios
              .post(url, params)
              .then(response => {
                  console.log(response.data)
                  this.closeModal();
              })
              .catch(error => {
                // エラーを受け取る
                console.log(error);
              });
        },
        addImgToSpot(index, isAdded, image_path) {
          if(this.tour_info.tour_id == '' || this.spot_info.spot_id == '') {
            console.log("reject");
            return; //editページ以外からの遷移時は登録しない
          }
          const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Images/add_img_spot.php';
              let params = new URLSearchParams();
              params.append('tour_id', this.tour_info.tour_id);
              params.append('spot_id', this.spot_info.spot_id);
              params.append('image_id', index);
              params.append('image_path', image_path);
              console.log(image_path);
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
          const url = 'https://www3.yoslab.net/~nishimura/docogeo/PHP/Images/get_all_image.php';
              axios.post(url
              ).then(response => {
                this.srcArray = response.data;
              }).catch(error => {
                  // エラーを受け取る
                  console.log(error);
              });
        },
        popup_image(image, index, isAdded, image_path) {
          if(this.flag_add) {
            this.addImgToSpot(index, isAdded, image_path)
          } else {
            this.image_avoid = image;
            this.flag_popup_image = true;
          }
        },
        confDelete() {
          this.flag_delete_image = true;
        }
    },
    components: {
      Success: Success,
      PopupImage: PopupImage,
      Uploading: Uploading,
      DeleteImage: DeleteImage
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

  .o-border {
    height: 1px;
    background-color: rgba(0,0,0, .12);
  }

  .o-background_black {
    height: 100%;
    width: 100%;
    position: fixed;

    display: flex;
    align-items: flex-end;
    z-index: 2;
  }

  .o-modal {
    position: absolute;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
    width: 100%;
    max-width: 400px;
    border-radius: 30px 30px 0 0;
    background-color: #fff;
    z-index: 1;

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

  .timeup {
    position: fixed;
    height: 100%;
    width: 100%;
    z-index: 2;
  }

</style>
