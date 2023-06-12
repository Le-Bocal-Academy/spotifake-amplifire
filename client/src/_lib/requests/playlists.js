import config from "@/config";

const token = localStorage.getItem("token");

export default {
  create: async (body) => {
    // body : {
    //     "name": "ma playlist"
    // }
    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/create", options);
    const response = await data.json();
    return response;
  },
  getAll: async () => {
    const options = {
      method: "get",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    };
    const url = config.url;
    const data = await fetch(url + "/playlist", options);
    const response = await data.json();
    return response;
  },
  addTrack: async (body) => {
    // body : {
    //     "playlist_id": 3,
    //     "track_id": 11
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/addTrack", options);
    const response = await data.json();
    return response;
  },
  delTrack: async (body) => {
    // body : {
    //     "playlist_id": 3,
    //     "track_id": 11
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/deleteTrack", options);
    const response = await data.json();
    return response;
  },
  delPlaylist: async (body) => {
    // body : {
    //     "playlist_id": 3
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/deletePlaylist", options);
    const response = await data.json();
    return response;
  },
  renamePlaylist: async (body) => {
    // body : {
    //     "playlist_id": 4,
    //     "name": "t"
    // }

    const options = {
      method: "put",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/renamePlaylist", options);
    const response = await data.json();
    return response;
  },
};
