<template>
  <div>
    <Header />
    <div class="slogan">
      <h2 class="p-XXXL">Enflammez le rythme de votre vie !</h2>
      <p class="p-L">Souscrivez à notre offre unique.</p>
    </div>
    <Register />
    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import Register from "@/components/Register.vue";

export default {
  name: "HomeView",
  components: {
    Header,
    Register,
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
