<template>
  <div class="bgBlack header">
    <div class="logo">
      <img alt="Logo" src="../assets/logo.png" />
    </div>
    <div class="nav">
      <BlueButton text="Connexion" link="/login" />
      <SettingButton />
    </div>
  </div>
</template>

<script>
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";
import SettingButton from "./UI/settingButton.vue";

export default {
  components: {
    YellowButton,
    BlueButton,
    SettingButton,
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
