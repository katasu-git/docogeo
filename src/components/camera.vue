<template>
  <div id="camera">
    <div class="o-background">

    　　 <div id="comuppic" v-show="flag_add_img">
            <div class="o-background_black">
                <div class="o-modal">
                    <!-- <img src="/~nishimura/docogeo/pic/FH020018.JPG" /> <!--相対パスで直接叩けば表示できる -->
                    <img class="o-preview_img" v-show="uploadedImage" :src="uploadedImage" alt="preview_img" />
                    <div class="l-button">
                        <button class="o-button_cancel" @click="closeModal()">キャンセル</button>
                        <button class="o-button_save" type="submit" @click="postFile()">追加する</button>
                    </div>
                </div>
            </div>
        </div>

      <div class="l-header_above">
        <div class="o-text_tour">Camera</div>
        <div class="o-image_image_button">
        </div>
      </div>
      <div class="l-header_under">
        <div class="o-text_tour_min">カメラ</div>
      </div>

        <div class="o-img-area">
            <img class="o-img_sample" src="../assets/sample2.jpg" width="300" height="200"/>
            <video ref="video" id="video" width="300" height="200" autoplay muted playsinline></video>
        </div>
      
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { async } from 'q';
  export default {
    name: 'camera',
    data() {
      return {
        file: '',
        uploadedImage: '',
        img_name: '',
        flag_order: false,
        flag_add_img: false,
        video: {},
        canvas: {},
        captures: []
      }
    },
    created: function () {
      this.get_tour();
    },
    mounted () {
        this.video = this.$refs.video
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            const constraints = {
                audio: false,
                video: {
                    width: 300,
                    height: 200,
                    //facingMode: "user",   // フロントカメラを利用する
                    facingMode: { exact: "environment" }  // リアカメラを利用する場合
                }
            };
            navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
                this.video.srcObject = stream
                this.video.play()
            })
        }
    },
    methods: {
      get_tour: function () {
        axios.post('https://www2.yoslab.net/~nishimura/geotour/PHP/get_tour_info.php'
        ).then(response => {
          this.tour_info = response.data;
        }).catch(error => {
          // エラーを受け取る
          console.log(error);
        });
      },
      jumpPage: function(where, tour_id, tour_name) {
        //console.log(this.avoidParam.tour_id);
        this.$router.push({
            name: where,
            params: {
              tour_id: tour_id,
              tour_name: tour_name,
            }
        })
      },
      closeModal: function() {
        this.flag_add_img = false;
        //this.get_spot_info(); //名前の更新を反映
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
            // FormData を利用して File を POST する
                let formData = new FormData();
                formData.append('upfile', this.file);
                //console.log(this.file);
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
                    })
                    .catch(function(error) {
                        // error 処理
                    })
        },
    },
  }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #camera, .o-background {
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
        width: 50px;
        position: fixed;
        bottom: 15px;
        right: 15px;
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
      height: 50px;
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
      width: calc(100% - 50px);
      border-radius: 30px;
  }

  .o-img-area {
      position: relative;
  }

  .o-img_sample {
      position: absolute;
      opacity: .3;
  }

</style>
