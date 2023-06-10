<template>
  <div class="bgBlack header">
    <div class="logo">
      <img alt="Logo" src="../assets/logo.png" />
    </div>
    <slot></slot>
    <div class="nav">
      <YellowButton text="Inscription" link="/" />
      <BlueButton text="Connexion" link="/login" />
      <BlueButton text="Déconnexion" @click="logout" />
      <SettingButton />
    </div>
  </div>
</template>

<script>
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";
import SettingButton from "./UI/settingButton.vue";
import config from "../config.js";

export default {
  components: {
    YellowButton,
    BlueButton,
    SettingButton,
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
</style>
