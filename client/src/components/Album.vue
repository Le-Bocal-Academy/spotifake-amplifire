<template>
  <div class="content">
    <slot></slot>
    <section class="albumContainer">
      <div class="albumIcon">
        <i class="fa-solid fa-compact-disc p-XXL"></i>
      </div>
      <div class="albumInfosContainer">
        <div class="albumInfos">
          <h1 class="yellow capitalize">{{ this.album.title }}</h1>
          <p class="p-M">{{ album.artist.name }}</p>
          <span
            v-for="style in album.styles"
            :key="style.id"
            class="p-S orange"
            >{{ style.style }}</span
          >
          <p class="p-XS">{{ album.description }}</p>
        </div>
        <RedButton link="#" @click="playRandom">
          <template v-slot:default>
            <i class="fa-solid fa-shuffle"></i> Lecture aléatoire
          </template>
        </RedButton>
      </div>
    </section>
    <table>
      <thead>
        <tr class="black p-S">
          <th>
            titre
            <hr />
          </th>
          <th>
            durée
            <hr />
          </th>
          <th>
            &nbsp
            <hr />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="track in album.tracks" :key="track.id" class="orange">
          <td>{{ track.title }}</td>
          <td>{{ track.duration }}</td>
          <td>
            <div class="actionButton">
              <button
                class="red bgWhite"
                @click="playTrack(track.id, track.title, track.artist_name)"
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
              <button class="bgRed white" @click="displayFunction(track.id)">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
            <div
              v-if="displayMenu && selectedTrackId == track.id"
              class="playlistMenu"
            >
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
  </div>
</template>
<script>
import tracks from "@/_lib/requests/tracks.js";
import RedButton from "@/components/UI/redButton.vue";
import playlists from "@/_lib/requests/playlists.js";
export default {
  components: {
    RedButton,
  },
  props: {
    album: Object,
    playlists: Array,
    audioPlaying: Object,
  },
  data() {
    return {
      displayMenu: false,
      selectedTrackId: null,
      token: null,
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
  },
  methods: {
    async playTrack(trackId, trackTitle, trackArtist) {
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
        this.sendAudioInfos(audioInfos);
      } else if (
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
      console.log(response);
    },
    displayFunction(trackId) {
      this.displayMenu = !this.displayMenu;
      this.selectedTrackId = trackId;
    },
  },
};
</script>
<style scoped>
.albumIcon {
  width: 200px;
  height: 200px;
  padding: 5%;
  border: 2px solid #26272b;
  border-radius: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
}
.albumInfos {
  padding: 5px;
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.albumInfosContainer {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 40px;
}
h1 {
  margin: 0;
}
.albumContainer {
  display: flex;
  gap: 30px;
}

table {
  width: 100%;
}
th,
td {
  width: 40%;
}
button {
  padding: 5px;
  width: 25px;
  height: 25px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.content {
  display: flex;
  flex-direction: column;
  gap: 60px;
}
.actionButton {
  display: flex;
  justify-content: center;
}
</style>
