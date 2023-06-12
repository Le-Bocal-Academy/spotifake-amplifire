import config from "@/config";

const token = localStorage.getItem("token");

export default {
  register: async (body) => {
    // body : {
    //   "nickname": "pseudo-test",
    //   "firstname": "John",
    //   "lastname": "Doe",
    //   "email": "john@gmail.com",
    //   "password": "Azerty123!",
    //   "password_confirmation": "Azerty123!"
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/register", options);
    return data;
  },
  login: async (body) => {
    // body : {
    //   "email": "john@gmail.com",
    //   "password": "Azerty123!"
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/login", options);
    const response = await data.json();
    const token = response.token;
    localStorage.setItem("token", token);
    return response;
    //   if (data.status === 200 && token) {
    //     localStorage.setItem("token", token);
    //     const options = {
    //       method: "get",
    //       headers: {
    //         Accept: "application/json",
    //         "Content-Type": "application/json",
    //         Authorization: "Bearer " + token,
    //       },
    //     };
    //     const data = await fetch(url + "/disk", options);
    //     console.log(data);
    //     this.$router.push("/home");
    //   }
  },
  logout: async () => {
    const options = {
      method: "get",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    };
    const url = config.url;
    const data = await fetch(url + "/logout", options);
    if (data.status === 200) {
      localStorage.clear();
    }
    return data;
  },
  forgotPassword: async (body) => {
    // body : {
    //   "email": "john@gmail.com"
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/forgotPassword", options);
    const response = await data.json();
    return response;
  },
  resetPassword: async (body) => {
    // body : {
    //   "token": "token reçus par mail",
    //   "email": "john@gmail.com",
    //   "password": "Querty456!",
    //   "password_confirmation": "Querty456!"
    // }

    const options = {
      method: "post",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };
    const url = config.url;
    const data = await fetch(url + "/resetPassword", options);
    const response = await data.json();
    return response;
  },
};
