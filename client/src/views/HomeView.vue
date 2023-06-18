<template>
  <div v-if="this.authenticated">
    <Header>
      <template v-slot:search>
        <form @submit.prevent="search" style="width: 40%">
          <input
            class="searchBarHome"
            type="text"
            placeholder="Rechercher"
            v-model="searchValue"
          />
        </form>
      </template>
      <template v-slot:default>
        <BlueButton text="Déconnexion" @click="logout" />
      </template>
    </Header>

    <SearchResults
      v-if="searchData && displaySearchResults"
      :data="this.searchData"
      :playlists="this.playlists"
      @getEvent="getSearchDisplay"
    />

    <div
      v-if="!searchData || !displaySearchResults"
      class="bgWhite homeContainer"
    >
      <div class="userInfos">
        <div class="user">
          <h1 class="yellow capitalize">{{ this.nickname }}</h1>
          <p class="p-S">12 folowers . 8 following</p>
        </div>
        <RedButton link="#" @click="playRandom">
          <template v-slot:default>
            <i class="fa-solid fa-shuffle"></i> Lecture aléatoire
          </template>
        </RedButton>
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
            <RedButton
              text="Ajouter"
              @click="
                this.createPlaylist();
                showPopupAddPlaylist = false;
              "
            />
          </span>
        </template>
      </Modal>

      <div class="playlistContainer">
        <div>
          <div class="headerPlaylist">
            <div>
              <p class="p-L">Playlists</p>
              <div class="underlign bgYellow"></div>
            </div>
            <div class="searchPlaylistComponent">
              <!-- <input
                type="text"
                class="searchBar"
                :placeholder="''"
                v-html="this.placeholderContent"
              /> -->
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

        <Playlists
          :playlists="this.playlists"
          @clickedPlaylist="getPlaylistId"
          @display="displayPlaylistFunction"
          v-if="!displayPlaylist"
        />

        <Playlist v-if="displayPlaylist" :playlist="this.clickedPlaylist">
          <template v-slot:default>
            <button @click="displayPlaylist = false">Retour</button>
          </template>
        </Playlist>
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
import account from "@/_lib/requests/account.js";
import search from "@/_lib/requests/search.js";
import Modal from "../components/UI/modal.vue";
import Fields from "../components/UI/fields.vue";
import YellowButton from "../components/UI/yellowButton.vue";
import BlueButton from "../components/UI/blueButton.vue";
import Playlists from "../components/AllPlaylists.vue";
import Playlist from "../components/Playlist.vue";
import SearchResults from "../components/SearchResults.vue";

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
    Playlists,
    Playlist,
    SearchResults,
  },
  data() {
    return {
      token: null,
      authenticated: false,
      audioUrl: "chemin_vers_le_fichier_audio.mp3",
      audioElement: null,
      placeholderContent:
        '<i class="fa-solid fa-magnifying-glass"></i> Rechercher',
      playlists: null,
      newPlaylistName: null,
      playlistRename: null,
      playlistId: null,
      trackId: null,
      showPopupAddPlaylist: false,
      firstname: "",
      lastname: "",
      userId: "",
      nickname: "",
      email: "",
      clickedPlaylist: null,
      searchValue: "",
      searchData: null,
      displaySearchResults: false,
      displayPlaylist: false,
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
    console.log(token);
    this.checkAuth();
    this.getPlaylist();
    this.getUserInfos();
  },
  methods: {
    checkAuth() {
      if (!this.token) {
        this.$router.push("/login");
      } else {
        this.authenticated = true;
      }
    },
    async getPlaylist() {
      console.log(this.token);
      const response = await playlists.getAll(this.token);
      console.log(response.data);
      this.playlists = response.data;
      if (this.playlists) {
      }
    },
    async createPlaylist() {
      console.log(this.newPlaylistName);
      const body = {
        name: this.newPlaylistName,
      };
      const response = await playlists.create(body, this.token);
      window.location.reload();
      console.log(response);
    },
    async addTrack() {
      const body = {
        playlist_id: this.playlistId,
        track_id: this.trackId,
      };
      const response = await playlists.addTrack(body, this.token);
      console.log(response);
    },
    getUserInfos() {
      this.nickname = localStorage.getItem("nickname");
    },
    getPlaylistId(id) {
      const playlist = this.playlists.find((playlist) => playlist.id == id);
      this.clickedPlaylist = playlist;
    },
    async search() {
      if (this.displaySearchResults == true && this.searchValue == "") {
        this.displaySearchResults = false;
        window.location.reload();
      }
      this.displaySearchResults = true;
      console.log(this.searchValue);
      const response = await search.get(this.searchValue, this.token);

      console.log(response.data);
      this.searchData = this.parseResult(response.data);
    },
    parseResult(data) {
      let results = {
        tracks: [],
        albums: [],
      };
      if (data.tracks) {
        data.tracks.forEach((track) => {
          results["tracks"].push(track);
        });
      }
      if (data.albums.length > 0) {
        data.albums.forEach((album) => {
          results["albums"].push(album);
        });
      }
      if (data.artists.length > 0 && data.artists.artist_tracks) {
        data.artists.artist_tracks.forEach((track) => {
          results["tracks"].push(track);
        });
      }
      if (data.artists.length > 0 && data.artists.artist_albums) {
        this.data.artists.artist_albums.forEach((album) => {
          results["albums"].push(album);
        });
      }
      if (data.styles.length > 0 && data.styles[0].albums) {
        data.styles[0].albums.forEach((album) => {
          results["albums"].push(album);
        });
      }

      console.log(results);

      results["tracks"] = [...new Set(results["tracks"])];
      results["albums"] = [...new Set(results["albums"])];

      return results;
    },
    getSearchDisplay(bool) {
      this.displaySearchResults = bool;
      window.location.reload();
    },
    displayPlaylistFunction(bool) {
      this.displayPlaylist = bool;
    },
    async logout() {
      const response = await account.logout(this.token);
      if (response.status === 200) {
        localStorage.clear();
        this.$router.push("/login");
      }
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

.searchBarHome {
  width: 100%;
  padding: 5px 10px;
  background: #ffffff24;
  border: none;
  border-radius: 5px;
  color: white;
}

.searchBarHome:focus {
  outline: none;
}
</style>
