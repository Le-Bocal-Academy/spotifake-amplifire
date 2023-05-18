<template>
  <div>
    <Header />
    <div class="slogan">
      <h2 class="p-XXXL">Enflammez le rythme de votre vie !</h2>
      <p class="p-L">Souscrivez à notre offre unique.</p>
    </div>
    <Offer />
  </div>
</template>

<script>
// @ is an alias to /src
import Header from "@/components/Header.vue";
import Offer from "@/components/Offer.vue";

export default {
  name: "HomeView",
  components: {
    Header,
    Offer,
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
