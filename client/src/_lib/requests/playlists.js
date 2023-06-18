import config from "@/config";

const authorizerHeaders = (token) => {
  return {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: "Bearer " + token,
  };
};
export default {
  create: async (body, token) => {
    // body : {
    //     "name": "ma playlist"
    // }
    const options = {
      method: "post",
      headers: authorizerHeaders(token),
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/create", options);
    const response = await data.json();
    return response;
  },
  getAll: async (token) => {
    const options = {
      method: "get",
      headers: authorizerHeaders(token),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist", options);
    const response = await data.json();
    return response;
  },
  addTrack: async (body, token) => {
    // body : {
    //     "playlist_id": 3,
    //     "track_id": 11
    // }

    const options = {
      method: "post",
      headers: authorizerHeaders(token),
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/addTrack", options);
    const response = await data.json();
    return response;
  },
  delTrack: async (body, token) => {
    // body : {
    //     "playlist_id": 3,
    //     "track_id": 11
    // }

    const options = {
      method: "post",
      headers: authorizerHeaders(token),
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/deleteTrack", options);
    const response = await data.json();
    return response;
  },
  delPlaylist: async (body, token) => {
    // body : {
    //     "playlist_id": 3
    // }

    const options = {
      method: "post",
      headers: authorizerHeaders(token),
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/deletePlaylist", options);
    const response = await data.json();
    return response;
  },
  renamePlaylist: async (body, token) => {
    // body : {
    //     "playlist_id": 4,
    //     "name": "t"
    // }

    const options = {
      method: "put",
      headers: authorizerHeaders(token),
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/playlist/renamePlaylist", options);
    const response = await data.json();
    return response;
  },
};
