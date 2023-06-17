import config from "@/config";

const url = config.url;

const defaultHeaders = {
  Accept: "application/json",
  "Content-Type": "application/json",
};

const authorizerHeaders = (token) => {
  return {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: "Bearer " + token,
  };
};

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
      headers: defaultHeaders,
      body: JSON.stringify(body),
    };
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
      headers: defaultHeaders,
      body: JSON.stringify(body),
    };
    const data = await fetch(url + "/login", options);
    return data;
  },
  logout: async (token) => {
    const options = {
      method: "get",
      headers: authorizerHeaders(token),
    };
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
      headers: defaultHeaders,
      body: JSON.stringify(body),
    };
    const data = await fetch(url + "/forgotPassword", options);
    return data;
  },
  resetPassword: async (body) => {
    // body : {
    //   "token": "token reçus par mail",
    //   "email": "john@gmail.com",
    //   "password": "Querty456!",
    //   "password_confirmation": "Querty456!"
    // }
    console.log(body);
    const options = {
      method: "post",
      headers: defaultHeaders,
      body: JSON.stringify(body),
    };
    const data = await fetch(url + "/resetPassword", options);
    return data;
  },
};
