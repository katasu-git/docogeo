<template>
  <div id="imageList">
      <div class="l-slider_images">
        <button
          class="o-button_add_img u_pointer"
          @click="addImg()">画像を追加する</button>
        <div class="o-image u_pointer" 
          v-for="image in image_info"
          :key="image.id"
          @click="delete_selected(image, true)"
        >
          <img class="img" :src="image.image_path" :alt="image.imgName" />
        </div>
      </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'imageList',
    props: {
        tour_info: '',
        spot_info: '',
        image_info: ''
    },
    methods: {
        delete_selected(info, isImage) {
          this.$emit('delete_selected', info, isImage);
        },
        addImg() {
            this.$router.push({
                name: 'images',
                params: {
                    tour_info: this.tour_info,
                    spot_info: this.spot_info,
                    add_image_flag: true, 
                }
            })
        },
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.o-image_circle {
  height: 50px;
  width: 50px;
  border-radius: 100px;
  object-fit: cover;
}

.l-slider_images {
  padding: 40px 0 0 20px;
  display: flex;
  overflow-x: scroll;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch; /*ios*/
}

.o-image, .o-button_add_img {
  height: 100px;
  width: 100px;
  min-height: 100px;
  min-width: 100px;
  border-radius: 10px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.img {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  object-fit: cover;
}

.o-image:not(:first-of-type) {
  margin-left: 10px;
}

.o-image:last-of-type {
  padding-right: 20px;
}

.o-button_add_img {
  margin-right: 10px;
  background-color: #4B8E8D;
  color: white;
  font-weight: bold;
}

</style>
