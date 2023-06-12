<template>
  <section>
    <h2 v-if="playlist">{{ playlist.name }}</h2>
    <div v-if="playlist">
      <p v-for="track in playlist.tracks" :key="track.id">
        {{ track.title }} {{ track.duration }}
      </p>
    </div>
  </section>
</template>
<script>
import redButton from "./UI/redButton.vue";
export default {
  components: { redButton },
  props: {
    playlist: Object,
  },
  mounted() {
    console.log(this.playlist);
  },
};
</script>
