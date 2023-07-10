<template>
  <div>
    <Header>
      <template v-slot:default>
        <YellowButton text="Inscription" link="/" />
      </template>
    </Header>
    <Login />
    <Footer />
  </div>
</template>

<script>
import YellowButton from "../components/UI/yellowButton.vue";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import Login from "@/components/Login.vue";

export default {
  name: "LoginView",
  components: {
    Header,
    Login,
    Footer,
    YellowButton,
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
