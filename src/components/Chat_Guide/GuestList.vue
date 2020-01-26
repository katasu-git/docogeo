<template>
  <div id="guestList">
      <div class="modal">
        <div class="header">
            <div class="header-text">
            <span class="header-text-num">{{return_user_num()}}</span>
            頭が参加中
            <img 
                class="header-img" 
                src="../../assets/kujira-single.svg" 
            />
            </div>
        </div>

        <div 
            v-show="flag.isUserBlank"
            class="message-blank">
            まだ参加者はいません
        </div>

        <div class="userList">
        <div
            v-for="(userName, i) in userList" :key=i
            class="userName"
        >
            {{i}} . {{userName.name}}
        </div>
        </div>

        <button 
            @click="closeModal"
            class="button-close"
        >
        閉じる
        </button>
      </div>
  </div>
</template>

<script>
　import axios from 'axios'
  export default {
    name: 'guestList',
    props: {
    },
    data() {
      return {
          userList: Array,
          flag: {
              isUserBlank: false
          }
      }
    },
    created() {
        this.fetch_active_user();
    },
    methods: {
        closeModal: function() {
            this.$emit('closeModal');
        },
        async fetch_active_user() {
            //isActivceを解除する処理
            const url = "https://www2.yoslab.net/~nishimura/docogeo/PHP_C/Chat_G/fetch_active_user.php";
            const res = await axios.post(url);
            this.userList = res.data;
            this.show_isUser_blank(this.userList);
        },
        show_isUser_blank(userList) {
            if(userList.length === 0) {
                this.flag.isUserBlank = true;
            } else {
                this.flag.isUserBlank = false;
            }
        },
        return_user_num() {
            return this.userList.length;
        }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#guestList {
    height: 100%;
    width: 100%;
    background-color: rgba(0,0,0, .30);
    position: fixed;
    z-index: 2;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.modal {
    position: relative;
    width: 60%;
    height: 100%;
    background-color: #fff;
}

.header {
    width: 100%;
    height: 80px;
    border-bottom: solid 1px rgba(0,0,0, .12);

    display: flex;
    align-items: center;
    justify-content: center;
}

.header-text-num {
    font-size: 24px;
    font-weight: bold;
    color: #4B8E8D;
}

.message-blank {
    position: absolute;
    top: 50%;
    right: 0;
    left: 0;
    margin: auto;
    text-align: center;
    font-size: 12px;
}

.userList {
    width: calc(100% - 20px);
    height: calc(100% - 140px);
    margin: 20px 0 0 20px;
    overflow-y: scroll;
}

.userName:not(:first-of-type) {
    margin-top: 5px;
}

.userName:last-of-type {
    margin-bottom: 20px;
}

.button-close {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 40px;
    background-color: #4B8E8D;
    color: #fff;
    font-size: 18px;
}

</style>
