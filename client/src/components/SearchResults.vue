<template>
  <div class="searchData">
    <button @click="sendCloseSearch">Revenir sur mon profil</button>
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
              <button @click="playTrack(track.id)">
                <i class="fa-solid fa-play"></i>
              </button>
              <button @click="displayFunction(track.id)">
                <i class="fa-solid fa-plus"></i>
              </button>
              <div
                v-if="displayMenu && selectedTrackId == track.id"
                class="playlistMenu"
              >
                <p
                  v-for="playlist in this.playlists"
                  :key="playlist.id"
                  class="red"
                  style="cursor: pointer"
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

    <section class="albumsSection">
      <h2 class="p-L yellow">Albums</h2>
      <div class="album-list">
        <div v-for="album in data.albums" :key="album.id" class="album">
          <div class="albumIcon">
            <i class="fa-solid fa-compact-disc p-XXL"></i>
          </div>
          <div class="album-title">{{ album.title }}</div>
          <div class="album-artist">
            par {{ getArtistName(album.artist_id) }}
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import tracks from "@/_lib/requests/tracks.js";
import playlists from "@/_lib/requests/playlists.js";
import YellowButton from "../components/UI/yellowButton.vue";
export default {
  props: {
    data: Object,
    playlists: Array,
  },
  components: {
    YellowButton,
  },
  data() {
    return {
      trackId: null,
      displayMenu: false,
      clickCount: 0,
    };
  },
  methods: {
    getArtistName(artistId) {
      const artist = this.data.artists.find((artist) => artist.id === artistId);
      console.log(artist);
      return artist ? artist.name : "";
    },
    getAlbumTitle(albumId) {
      const album = this.data.albums.find((album) => album.id === albumId);
      console.log(album);
      return album ? album.title : "";
    },
    async playTrack(trackId) {
      console.log("play");
      const response = await tracks.get(trackId);
      console.log(response);
    },
    async addToPlaylist(trackId = null, playlistId = null) {
      const body = {
        playlist_id: playlistId,
        track_id: trackId,
      };
      const response = await playlists.addTrack(body);
      console.log(response);
    },
    displayFunction(trackId) {
      this.displayMenu = !this.displayMenu;
      this.selectedTrackId = trackId;
    },
    sendCloseSearch() {
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
</style>
