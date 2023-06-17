import config from "@/config";

export default {
  get: async (id, token) => {
    const options = {
      method: "get",
      headers: {
        Accept: "audio/mpeg",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    };
    const url = config.url;
    const data = await fetch(url + "/track/" + id, options);
    return data;
  },
};
