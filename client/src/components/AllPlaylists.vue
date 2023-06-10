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
      <div class="playlistNameAndMenu">
        <p>{{ playlist.name }}</p>
        <span
          class="p-L red"
          style="cursor: pointer"
          @click="
            displayMenu = !displayMenu;
            selectedPlaylistId = playlist.id;
          "
        >
          ...
        </span>
      </div>

      <div
        v-if="displayMenu && selectedPlaylistId == playlist.id"
        class="playlistMenu"
      >
        <p id="menu1" class="red" @click="console.log('rename')">Renommer</p>
        <p id="menu2" class="red" @click="console.log('delete')">Supprimer</p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    playlists: Array,
  },
  data() {
    return {
      displayMenu: false,
      selectedPlaylistId: null,
    };
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
  align-items: flex-start;
  padding: 20px;
}
.iconsContainer {
  display: flex;
  gap: 20px;
}
.playlistNameAndMenu {
  width: 100%;
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  padding: 0 5px;
}
.playlistMenu {
  width: 100%;
  text-align: right;
  margin: 5px 0;
}
.playlistMenu p {
  padding: 5px 15px;
  border: 1px solid white;
  cursor: pointer;
}
.playlistMenu p:hover {
  background: #f8f8f8;
}
#menu1 {
  border-radius: 5px 0 0 0;
}
#menu2 {
  border-radius: 0 0 5px 5px;
}
</style>
