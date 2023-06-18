<template>
  <div class="iconsContainer">
    <div
      class="playlistIcons"
      v-for="playlist in this.playlists"
      :key="playlist.id"
    >
      <!-- icone de la playlist -->
      <div
        class="playlistIcon"
        @click="
          this.displayPlaylist = !this.displayPlaylist;
          this.sendPlaylistId(playlist.id);
        "
      >
        <i class="fa-solid fa-play p-M"></i>
      </div>
      <!-- infos de la playlist -->
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

      <!-- Menu -->
      <div
        v-if="displayMenu && selectedPlaylistId == playlist.id"
        class="playlistMenu"
      >
        <p
          id="menu1"
          class="red"
          @click="showPopupRenamePlaylist = !showPopupRenamePlaylist"
        >
          Renommer
        </p>
        <p id="menu2" class="red" @click="delPlaylist(playlist.id)">
          Supprimer
        </p>
      </div>
    </div>

    <!-- modal du formulaire pour entrer le nom de la nouvelle playlist -->
    <Modal v-if="showPopupRenamePlaylist" title="Renommer la playlist">
      <template v-slot:content>
        <Fields
          label="nom de la playlist"
          fieldType="text"
          @getValue="getPlaylistName"
        />
        <span>
          <BlueButton
            text="Annuler"
            @click="
              showPopupRenamePlaylist = false;
              this.playlistName = null;
            "
          />
          <RedButton text="Enregistrer" @click="this.renamePlaylist()" />
        </span>
      </template>
    </Modal>
  </div>
</template>
<script>
import playlists from "@/_lib/requests/playlists.js";
import errors from "../_lib/requests/errors";
import Modal from "@/components/UI/modal.vue";
import Fields from "@/components/UI/fields.vue";
import RedButton from "@/components/UI/redButton.vue";
import BlueButton from "@/components/UI/blueButton.vue";

export default {
  props: {
    playlists: Array,
  },
  components: {
    Modal,
    Fields,
    RedButton,
    BlueButton,
  },
  data() {
    return {
      displayMenu: false,
      selectedPlaylistId: null,
      playlistName: null,
      showPopupRenamePlaylist: false,
      displayPlaylist: false,
      token: null,
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
  },
  methods: {
    sendPlaylistId(id) {
      // fonction pour afficher le detail de la playlist selectionnée
      this.$emit("clickedPlaylist", id);
      this.$emit("display", this.displayPlaylist);
    },
    async delPlaylist(id) {
      const body = {
        playlist_id: id,
      };
      const response = await playlists.delPlaylist(body, this.token);
      // effacer la playlist de la variable playlist en attendant un reload
      const playlistIndex = this.playlists.findIndex(
        (playlist) => playlist.id === id
      );
      if (playlistIndex !== -1) {
        this.playlists.splice(playlistIndex, 1);
      }
      this.displayMenu = false;
    },
    async renamePlaylist() {
      const body = {
        playlist_id: this.selectedPlaylistId,
        name: this.playlistName,
      };
      const response = await playlists.renamePlaylist(body, this.token);
      // reload de la page pour afficher les changements apportés
      window.location.reload();
    },
    getPlaylistName(value) {
      // récuperer la valeur envoyée du formulaire
      this.playlistName = value;
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
  margin-bottom: 60px;
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

/* responsive */
@media screen and (max-width: 600px) {
  .playlistIcon {
    display: none;
  }
  .iconsContainer {
    display: flex;
    margin-bottom: 60px;
    flex-direction: column;
  }
  .playlistIcons {
    width: 100%;
    padding: 5px;
  }
}
</style>
