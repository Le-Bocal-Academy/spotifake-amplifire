<template>
  <div class="iconsContainer">
    <div
      class="playlistIcons"
      v-for="playlist in this.playlists"
      :key="playlist.id"
    >
      <div
        class="playlistIcon"
        @click="
          console.log('playlist ' + playlist.id);
          this.sendPlaylistId(playlist.id);
        "
      >
        <i class="fa-solid fa-play p-M"></i>
      </div>
      <p>{{ playlist.name }}</p>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    playlists: Array,
  },
  methods: {
    sendPlaylistId(id) {
      this.$emit("clickedPlaylist", id);
    },
  },
};
</script>
<style scoped>
.playlistIcon {
  width: 150px;
  height: 150px;
  padding: 5%;
  border: 2px solid #26272b;
  border-radius: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.playlistIcons {
  display: flex;
  flex-direction: column;
  width: fit-content;
  gap: 20px;
  align-items: flex-start;
  padding: 20px;
}
.iconsContainer {
  display: flex;
  gap: 20px;
}
</style>
