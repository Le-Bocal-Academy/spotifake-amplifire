<template>
  <section class="conainer">
    <slot />
    <h2 class="p-L red capitalize" v-if="playlist">{{ playlist.name }}</h2>
    <div v-if="playlist">
      <table>
        <thead>
          <tr class="black">
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
          <tr v-for="track in playlist.tracks" :key="track.id" class="orange">
            <td>{{ track.title }}</td>
            <td>{{ track.duration }}</td>
            <td class="actionTD">
              <button
                class="bgRed white deleteTrackButton"
                @click="this.delTrack(track.id, playlist.id)"
              >
                <i class="fa-solid fa-xmark p-S"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
import redButton from "./UI/redButton.vue";
import playlists from "../_lib/requests/playlists.js";

export default {
  components: { redButton },
  props: {
    playlist: Object,
  },
  data() {
    return {
      token: null,
    };
  },
  mounted() {
    const token = localStorage.getItem("token");
    this.token = token;
  },
  methods: {
    async delTrack(trackId, playlistId) {
      const body = {
        playlist_id: playlistId,
        track_id: trackId,
      };
      const response = await playlists.delTrack(body, this.token);
      console.log(response.status);
      const trackIndex = this.playlist.tracks.findIndex(
        (track) => track.id === trackId
      );
      if (trackIndex !== -1) {
        this.playlist.tracks.splice(trackIndex, 1);
      }
      console.log(response);
    },
  },
};
</script>
<style scoped>
h2 {
  margin-bottom: 70px;
  text-align: center;
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
.conainer {
  padding: 2% 5%;
}

.actionTD {
  display: flex;
  width: auto;
  justify-content: center;
}
</style>
