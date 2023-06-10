import config from "@/config";

const token = localStorage.getItem("token");

export default {
  get: async (id) => {
    const options = {
      method: "get",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/album/" + id, options);
    const response = await data.json();
    return response;
  },
};
