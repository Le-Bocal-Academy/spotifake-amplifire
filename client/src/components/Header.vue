<template>
  <div class="bgBlack header">
    <div class="logo">
      <img class="entireLogo" alt="Logo" src="../assets/logo.png" />
      <img class="logoSimple" alt="Logo" src="../assets/logoSimple.png" />
    </div>
    <slot name="search" />
    <div class="nav">
      <slot />
    </div>
  </div>
</template>

<script>
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";
import config from "../config.js";

export default {
  components: {
    YellowButton,
    BlueButton,
  },
  methods: {
    async logout() {
      const token = localStorage.getItem("token");
      const options = {
        method: "get",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: "Bearer " + token,
        },
      };
      const url = config.url;
      const data = await fetch(url + "/logout", options);
      if (data.status === 200) {
        localStorage.clear();
      }
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 50px;
}

.logo {
  width: 15%;
  display: flex;
}

.logo img {
  width: 100%;
}

.nav {
  display: flex;
  align-items: center;
  gap: 20px;
}

.logoSimple {
  display: none;
}

/* responsive */

@media screen and (max-width: 1200px) {
  .logo {
    width: 17%;
  }
}

@media screen and (max-width: 1000px) {
  .logo {
    width: 20%;
  }
}

@media screen and (max-width: 800px) {
  .entireLogo {
    display: none;
  }
  .logoSimple {
    display: block;
  }
  .logo {
    width: 12%;
  }
  .header {
    padding: 10px 20px;
  }
}

@media screen and (max-width: 600px) {
  .logo {
    display: none;
  }
  .nav {
    gap: 15px;
  }
  .header {
    padding: 10px 5px;
  }
}
</style>
