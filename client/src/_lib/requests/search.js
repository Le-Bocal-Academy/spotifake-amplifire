import config from "@/config";

export default {
  get: async (query, token) => {
    const options = {
      method: "get",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    };
    const url = config.url;
    const data = await fetch(url + "/search?query=" + query, options);
    const response = await data.json();
    return response;
  },
};
