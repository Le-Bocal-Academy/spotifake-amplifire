<template>
  <div>
    <Header />
    <ForgotPassword />
    <ResetPassword />
    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
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
