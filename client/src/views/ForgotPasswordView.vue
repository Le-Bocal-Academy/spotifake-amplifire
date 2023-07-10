<template>
  <div>
    <Header>
      <template v-slot:default>
        <YellowButton text="Inscription" link="/" />
        <BlueButton text="Connexion" @click="this.$router.push('/login')" />
      </template>
    </Header>
    <ForgotPassword />
    <Footer />
  </div>
</template>

<script>
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import ForgotPassword from "@/components/ForgotPassword.vue";
import ResetPassword from "../components/ResetPassword.vue";

export default {
  name: "ForgotPasswordView",
  components: {
    Header,
    ForgotPassword,
    Footer,
    ResetPassword,
    YellowButton,
    BlueButton,
  },
};
</script>
<style scoped>
div {
  color: white;
  text-align: center;
}
.slogan {
  margin: 100px;
}
</style>
