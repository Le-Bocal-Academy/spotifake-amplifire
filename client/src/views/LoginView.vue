<template>
  <div>
    <Header />
    <Login />
    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import Login from "@/components/Login.vue";

export default {
  name: "LoginView",
  components: {
    Header,
    Login,
    Footer,
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
