<template>
  <div>
    <Header />
    <div class="bgWhite homeContainer">
      <div class="userInfos">
        <div class="user">
          <h1 class="yellow">Ambre</h1>
          <p class="p-S">12 folowers . 8 following</p>
        </div>
        <RedButton link="#" @click="play">
          <template v-slot:default>
            <i class="fa-solid fa-shuffle"></i> Lecture aléatoire
          </template>
        </RedButton>
      </div>

      <div class="playlistContainer">
        <div>
          <div class="headerPlaylist">
            <div>
              <p class="p-L">Playlists</p>
              <div class="underlign bgYellow"></div>
            </div>
            <div class="searchPlaylistComponent">
              <input
                type="text"
                class="searchBar"
                :placeholder="''"
                v-html="this.placeholderContent"
              />
              <button
                class="bgRed white p-M addButton"
                @click="showPopupAddPlaylist = true"
              >
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          </div>
          <hr />
        </div>

        <!-- Modal add playlist -->
        <Modal v-if="showPopupAddPlaylist" title="Ajouter une playlist">
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
                  showPopupAddPlaylist = false;
                  this.newPlaylistName = null;
                "
              />
              <RedButton text="Ajouter" @click="this.createPlaylist" />
            </span>
          </template>
        </Modal>

        <div class="iconsContainer">
          <div class="playlistIcons">
            <div class="playlistIcon">
              <i class="fa-solid fa-play p-M"></i>
            </div>
            <p>Playlist 1</p>
          </div>
          <div class="playlistIcons">
            <div class="playlistIcon">
              <i class="fa-solid fa-play p-M"></i>
            </div>
            <p>Playlist 2</p>
          </div>
          <div class="playlistIcons">
            <div class="playlistIcon">
              <i class="fa-solid fa-play p-M"></i>
            </div>
            <p>Playlist 3</p>
          </div>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
// @ is an alias to /src
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import RedButton from "@/components/UI/redButton.vue";
import playlists from "@/_lib/requests/playlists.js";
import Modal from "../components/UI/modal.vue";
import Fields from "../components/UI/fields.vue";
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";

export default {
  name: "HomeView",
  components: {
    Header,
    Footer,
    RedButton,
    Modal,
    Fields,
    YellowButton,
    BlueButton,
  },
  data() {
    return {
      audioUrl: "chemin_vers_le_fichier_audio.mp3",
      audioElement: null,
      placeholderContent:
        '<i class="fa-solid fa-magnifying-glass"></i> Rechercher',
      playlist: null,
      newPlaylistName: null,
      playlistRename: null,
      playlistId: null,
      trackId: null,
      showPopupAddPlaylist: false,
    };
  },
  mounted() {
    this.getPlaylist();
  },
  methods: {
    async getPlaylist() {
      const response = await playlists.getAll();
      console.log(response);
      this.playlist = response;
    },
    async createPlaylist() {
      console.log(this.newPlaylistName);
      const body = {
        name: this.newPlaylistName,
      };
      const response = await playlists.create(body);
      console.log(response);
    },
    async addTrack() {
      const body = {
        playlist_id: this.playlistId,
        track_id: this.trackId,
      };
      const response = await playlists.addTrack(body);
      console.log(response);
    },
    async delTrack() {
      const body = {
        playlist_id: this.playlistId,
        track_id: this.trackId,
      };
      const response = await playlists.delTrack(body);
      console.log(response);
    },
    async delPlaylist() {
      const body = {
        playlist_id: this.playlistId,
      };
      const response = await playlists.delPlaylist(body);
      console.log(response);
    },
    async renamePlaylist() {
      const body = {
        playlist_id: this.playlistId,
        name: this.playlistRename,
      };
      const response = await playlists.renamePlaylist(body);
      console.log(response);
    },
    getPlaylistName(value) {
      this.newPlaylistName = value;
    },
  },
};
</script>
<style scoped>
.homeContainer {
  padding: 5%;
}
.user {
  padding: 5px;
}
.userInfos {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.playlistContainer {
  padding: 5% 0;
}
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
.addButton {
  padding: 1px;
  width: 35px;
  height: 35px;
}
.searchBar {
  padding: 6px;
  border-radius: 8px;
  outline: none;
  border: 1.5px solid lightgrey;
}
.searchBar:focus {
  /* outline-color: #ff2a2a; */
  border: 1.5px solid #ff2a2a;
}
.searchPlaylistComponent {
  display: flex;
  align-items: center;
  gap: 10px;
}
.headerPlaylist {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
}
.underlign {
  width: 105px;
  height: 3px;
}
</style>
