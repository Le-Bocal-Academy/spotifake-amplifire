import config from "@/config";

export default {
  get: async (id, token) => {
    const options = {
      method: "get",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    };
    const url = config.url;
    const data = await fetch(url + "/album/" + id, options);
    const response = await data.json();
    return response;
  },
};
