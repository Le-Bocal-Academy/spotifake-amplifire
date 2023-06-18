<template>
  <div v-if="this.authenticated">
    <Header>
      <template v-slot:search>
        <form @submit.prevent="search" class="form">
          <input
            class="searchBarHome"
            type="text"
            placeholder="Rechercher"
            v-model="searchValue"
          />
        </form>
      </template>
      <template v-slot:default>
        <BlueButton
          class="deconexionButton"
          text="Déconnexion"
          @click="logout"
        />
        <i
          class="fa-solid fa-arrow-right-from-bracket blue p-M"
          @click="logout"
        ></i>
      </template>
    </Header>

    <SearchResults
      v-if="searchData && displaySearchResults"
      :data="this.searchData"
      :playlists="this.playlists"
      :audioPlaying="audioPlaying"
      @getEvent="getSearchDisplay"
      @getAudioInfos="playAudio"
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
    <div class="bgWhite divAudioBar">
      <div class="audioController">
        <p style="padding: 10px 20px" class="red">
          {{ trackTitle }} - {{ trackArtist }}
        </p>
        <audio
          style="width: 100%"
          ref="audioPlayer"
          src=""
          controls
          @play="handleAudioPlay"
          @pause="handleAudioPause"
        ></audio>
      </div>
    </div>
    <Footer ref="footer" />
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
import errors from "../_lib/requests/errors";

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
      audioUrl: "",
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
      isFixed: false,
      footerPosition: 0,
      audioPlaying: {
        bool: false,
        trackId: null,
      },
      trackControlId: null,
      trackTitle: "",
      trackArtist: "",
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
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
      const response = await playlists.getAll(this.token);
      this.playlists = response.data;
    },
    async createPlaylist() {
      const body = {
        name: this.newPlaylistName,
      };
      console.log(this.newPlaylistName);
      const response = await playlists.create(body, this.token);
      window.location.reload();
    },
    async addTrack() {
      const body = {
        playlist_id: this.playlistId,
        track_id: this.trackId,
      };
      const response = await playlists.addTrack(body, this.token);
      alert("Une erreur s'est produite. ");
    },
    getUserInfos() {
      this.nickname = localStorage.getItem("nickname");
    },
    getPlaylistId(id) {
      const playlist = this.playlists.find((playlist) => playlist.id == id);
      this.clickedPlaylist = playlist;
    },
    getPlaylistName(value) {
      this.newPlaylistName = value;
    },
    async search() {
      if (this.displaySearchResults == true && this.searchValue == "") {
        this.displaySearchResults = false;
        window.location.reload();
      }
      this.displaySearchResults = true;
      const response = await search.get(this.searchValue, this.token);
      this.searchData = this.parseResult(response.data);
    },
    parseResult(data) {
      let results = {
        tracks: [],
        albums: [],
      };

      // TRACKS
      if (data.tracks.length > 0) {
        data.tracks.forEach((track) => {
          results["tracks"].push(track);
        });
        console.log("tracks");
      }

      // ALBUMS
      if (data.albums.length > 0) {
        data.albums.forEach((album) => {
          results["albums"].push(album);
        });
      }

      // ARTIST TRACKS
      if (data.artists.length > 0) {
        data.artists.forEach((artist) => {
          if (artist.artist_tracks.length > 0) {
            artist.artist_tracks.forEach((track) => {
              results["tracks"].push(track);
            });
          }
        });
      }

      // ARTIST ALBUMS
      if (data.artists.length > 0) {
        data.artists.forEach((artist) => {
          if (artist.artist_albums.length > 0) {
            artist.artist_albums.forEach((album) => {
              results["albums"].push(album);
            });
          }
        });
      }

      // STYLES ALBUMS
      if (data.styles.length > 0 && data.styles[0].albums) {
        data.styles[0].albums.forEach((album) => {
          results["albums"].push(album);
        });
      }

      // DUPLICATE CHECKING
      const uniqueTracks = Array.from(
        new Set(results["tracks"].map((track) => track.id))
      ).map((id) => results["tracks"].find((track) => track.id === id));

      const uniqueAlbums = Array.from(
        new Set(results["albums"].map((album) => album.id))
      ).map((id) => results["albums"].find((album) => album.id === id));

      // RESULT
      results["tracks"] = uniqueTracks;
      results["albums"] = uniqueAlbums;

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
      localStorage.clear();
      this.$router.push("/login");
    },
    playAudio(audioInfos) {
      this.trackControlId = audioInfos.trackId;
      this.trackTitle = audioInfos.trackTitle;
      this.trackArtist = audioInfos.trackArtist;
      if (audioInfos.stop == false) {
        this.$refs.audioPlayer.src = audioInfos.url;
        this.$refs.audioPlayer.addEventListener("loadedmetadata", () => {
          this.$refs.audioPlayer.play();
          this.audioPlaying = {
            bool: true,
            trackId: audioInfos.trackId,
          };
        });
      } else {
        this.$refs.audioPlayer.pause();
        this.audioPlaying = {
          bool: false,
          trackId: audioInfos.trackId,
        };
      }
    },
    handleAudioPlay() {
      this.audioPlaying = {
        bool: true,
        trackId: this.trackControlId,
      };
    },
    handleAudioPause() {
      this.audioPlaying = {
        bool: false,
        trackId: this.trackControlId,
      };
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
.divAudioBar {
  display: flex;
  justify-content: center;
}
.audioController {
  width: 90%;
  position: relative;
  top: 30px;
}

.fa-arrow-right-from-bracket {
  display: none;
}

.form {
  width: 40%;
}

/* responsive */

@media screen and (max-width: 600px) {
  .deconexionButton {
    display: none;
  }
  .fa-arrow-right-from-bracket {
    display: block;
  }
  .form {
    width: 100%;
  }
}
</style>
