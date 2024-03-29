<template>
  <div>
    <!-- composant ablum pour afficher la page album et son détail -->
    <section v-if="showAlbum" class="albumContainer">
      <Album
        @getAudioInfos="sendAudioInfos"
        :album="this.clickedAlbum"
        :playlists="this.playlists"
        :audioPlaying="this.audioPlaying"
      >
        <template v-slot:default>
          <button @click="showAlbum = false">Revenir à la recherche</button>
        </template>
      </Album>
    </section>
    <!-- résultats de la recherche -->
    <div v-else class="searchData">
      <!-- bouton retour -->
      <button @click="sendCloseSearch">Revenir sur mon profil</button>
      <!-- section des résultats des titres unique -->
      <section>
        <h2 class="p-L yellow">Titres</h2>
        <table>
          <thead>
            <tr class="orange">
              <th>
                Titre
                <hr />
              </th>
              <th>
                Artiste
                <hr />
              </th>
              <th>
                Album
                <hr />
              </th>
              <th>
                Durée
                <hr />
              </th>
              <th>
                &nbsp
                <hr />
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="track in data.tracks" :key="track.title">
              <td>{{ track.title }}</td>
              <td>{{ track.artist_name }}</td>
              <td>{{ track.album_title }}</td>
              <td>{{ track.duration }}</td>
              <td>
                <div class="actionButton">
                  <button
                    @click="playTrack(track.id, track.title, track.artist_name)"
                    class="red bgWhite p-S buttonTrack"
                  >
                    <i
                      v-if="
                        audioPlaying.bool == true &&
                        audioPlaying.trackId == track.id
                      "
                      class="fa-solid fa-pause"
                    ></i>
                    <i v-else class="fa-solid fa-play"></i>
                  </button>
                  <button
                    @click="displayFunction(track.id)"
                    class="bgRed white p-S buttonTrack"
                  >
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
                <div v-if="displayMenu && selectedTrackId == track.id">
                  <p
                    v-for="playlist in this.playlists"
                    :key="playlist.id"
                    class="red"
                    style="cursor: pointer; text-align: center; padding: 2px"
                    @click="addToPlaylist(track.id, playlist.id)"
                  >
                    {{ playlist.name }}
                  </p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </section>
      <!-- section des résultats d'albums -->
      <section class="albumsSection">
        <h2 class="p-L yellow">Albums</h2>
        <div class="album-list">
          <div v-for="album in data.albums" :key="album.id" class="album">
            <div class="albumIcon" @click="getAlbum(album.id)">
              <i class="fa-solid fa-compact-disc p-XXL"></i>
            </div>
            <div class="album-title">{{ album.title }}</div>
            <div class="album-style">{{ album.style }}</div>
            <div class="album-artist">par {{ album.artist_name }}</div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import tracks from "@/_lib/requests/tracks.js";
import playlists from "@/_lib/requests/playlists.js";
import albums from "@/_lib/requests/albums.js";
import YellowButton from "../components/UI/yellowButton.vue";
import Album from "../components/Album.vue";
import errors from "../_lib/requests/errors";

export default {
  props: {
    data: Object,
    playlists: Array,
    audioPlaying: Object,
  },
  components: {
    YellowButton,
    Album,
  },
  data() {
    return {
      trackId: null,
      displayMenu: false,
      clickCount: 0,
      clickedAlbum: null,
      showAlbum: false,
      selectedTrackId: null,
      token: null,
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
  },
  methods: {
    async getAlbum(id) {
      const response = await albums.get(id, this.token);
      this.clickedAlbum = response.data;
      this.showAlbum = true;
    },
    async playTrack(trackId, trackTitle, trackArtist) {
      /**
       * fonction pour recuprérer le fihcier audio
       * et envoyer les infos au composant parent pour la lecture
       */

      // si la piste séléctionée n'a pas encore été chargée
      if (
        this.audioPlaying.bool == false &&
        this.audioPlaying.trackId != trackId
      ) {
        const response = await tracks.get(trackId, this.token);
        const data = await response.blob();
        const audioUrl = URL.createObjectURL(data);
        const audioInfos = {
          url: audioUrl,
          trackId: trackId,
          trackTitle: trackTitle,
          trackArtist: trackArtist,
          stop: false,
        };
        // envoie des donées utiles au composant parent
        this.sendAudioInfos(audioInfos);
      }
      // si la piste séléctionée à déjà été chargée et qu'elle est sur pause
      else if (
        this.audioPlaying.bool == true &&
        this.audioPlaying.trackId == trackId
      ) {
        const audioInfos = {
          url: null,
          trackId: trackId,
          trackTitle: trackTitle,
          trackArtist: trackArtist,
          stop: true,
        };
        // envoie des donées utiles au composant parent
        this.sendAudioInfos(audioInfos);
      }
    },
    sendAudioInfos(audioInfos) {
      this.$emit("getAudioInfos", audioInfos);
    },
    async addToPlaylist(trackId = null, playlistId = null) {
      this.displayMenu = false;
      this.selectedTrackId = null;
      const body = {
        playlist_id: playlistId,
        track_id: trackId,
      };
      const response = await playlists.addTrack(body, this.token);
    },
    displayFunction(trackId) {
      /**
       * pour afficher la liste des playlist quand on clique sur le bouton ajouter
       * et mettre à jour la variable selectedTrackId pour envoyer dans la requete
       */
      this.displayMenu = !this.displayMenu;
      this.selectedTrackId = trackId;
    },
    sendCloseSearch() {
      // envoyer l'information au comosant parent de sortir du composant présent
      this.$emit("getEvent", false);
    },
  },
};
</script>

<style scoped>
.album-list {
  display: flex;
  flex-wrap: wrap;
}
.album {
  width: 150px;
  margin: 10px;
}
.album-cover {
  background-color: #ccc;
}
.album-title {
  margin-top: 5px;
}
.album-artist {
  margin-top: 2px;
}
.searchData {
  background: white;
  padding: 3% 5%;
  display: flex;
  flex-direction: column;
  gap: 30px;
}
.albumContainer {
  background: white;
  padding: 6% 5%;
  padding-bottom: 20%;
}

table {
  width: 100%;
}

th,
td {
  width: 22%;
}
h2 {
  margin-bottom: 30px;
}
.albumsSection {
  margin-bottom: 150px;
}
.albumIcon {
  width: 150px;
  height: 150px;
  padding: 5%;
  border: 2px solid #26272b;
  border-radius: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
}

.buttonTrack {
  padding: 5px;
  width: 25px;
  height: 25px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.actionButton {
  display: flex;
  justify-content: center;
}
</style>
