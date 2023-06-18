<template>
  <div>
    <Header>
      <template v-slot:default>
        <BlueButton text="Connexion" link="/login" />
      </template>
    </Header>
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
import BlueButton from "../components/UI/blueButton.vue";

export default {
  name: "RegisterView",
  components: {
    Header,
    Register,
    Footer,
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

/* responsive */
@media screen and (max-width: 1200px) {
  .slogan {
    margin: 50px;
  }
}

@media screen and (max-width: 1200px) {
  .slogan h2 {
    font-size: 40px;
  }
}

@media screen and (max-width: 800px) {
  .slogan h2 {
    font-size: 35px;
  }
  .slogan p {
    font-size: 20px;
  }
}

@media screen and (max-width: 530px) {
  .slogan h2 {
    font-size: 27px;
  }
  .slogan p {
    font-size: 20px;
  }
}
</style>
