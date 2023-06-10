<template>
  <div>
    <Header />
    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

export default {
  name: "HomeView",
  components: {
    Header,
    Footer,
  },
};
</script>
<style scoped></style>
