<template>
  <section>
    <h2 v-if="playlist">{{ playlist.name }}</h2>
  </section>
</template>
<script>
import redButton from "./UI/redButton.vue";
export default {
  components: { redButton },
  props: {
    playlist: Object,
  },
};
</script>
