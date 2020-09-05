<template>
  <div>
    <div class="particles-js" style="min-height:730px;">
      <div id="particles-js"></div>
    </div>  
  </div>
</template>

<script>
  import particlesJs from "particles.js";
  import particlesConfig from "./particles.json";
  export default {
    data() {
      return {
        clientHeight: document.body.scrollHeight || document.documentElement.scrollHeight,
      };
    },
    mounted() {
      this.init();
    },
    methods: {
      init() {
        particlesJS("particles-js", particlesConfig);
        // console.log(this.clientHeight)
      }
    },
    beforeDestroy () {
      // 销毁 particlesJS
      if (pJSDom && pJSDom.length > 0) {
        pJSDom.forEach(pJSDomItem => {
          pJSDomItem.pJS.fn.vendors.destroypJS();
        })
      }
    }
  };
</script>

<style scoped>
  #particles-js{
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: -1;
    width: 100%;
    height: 100%;    
    background: linear-gradient(90deg,#2196F3,#3F51B5);
    background-size: cover;
    background-position: 50% 50%;
    background-repeat: no-repeat;
  }
</style>