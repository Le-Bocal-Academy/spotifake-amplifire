<template>
  <section>
    <article class="bgBlack">
      <div class="col forms">
        <Field label="token" fieldType="text" @getValue="getToken" />
        <Field label="email" fieldType="text" @getValue="getEmail" />
        <Field
          label="nouveau mot de passe"
          fieldType="text"
          @getValue="getNewPassword"
        />
        <Field
          label="confirmation du mot de passe"
          fieldType="text"
          @getValue="getConfirmationNewPasword"
        />
        <RedButton text="Changer mon mot de passe" @click="sendNewPassword" />
      </div>
    </article>
  </section>
</template>

<script>
import Field from "./UI/fields.vue";
import RedButton from "./UI/redButton.vue";
import config from "../config.js";

export default {
  components: {
    Field,
    RedButton,
  },
  data() {
    return {
      email: "",
      token: "",
      newPassword: "",
      confirmationNewPasword: "",
    };
  },
  methods: {
    async resetPassword() {
      const body = {
        token: this.token,
        email: this.email,
        password: this.newPassword,
        password_confirmation: this.confirmationNewPasword,
      };
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
      console.log(response);
    },
    getEmail(value) {
      this.email = value;
    },
    getToken(value) {
      this.token = value;
    },
    getNewPassword(value) {
      this.newPassword = value;
    },
    getConfirmationNewPasword(value) {
      this.confirmationNewPasword = value;
    },
  },
};
</script>

<style scoped>
section {
  display: flex;
  justify-content: center;
  margin-bottom: 30%;
  margin-top: 5%;
}
article {
  width: 30%;
  color: white;
  border-radius: 10px;
  padding: 50px 0 20px;
  display: flex;
  flex-direction: column;
  gap: 70px;
  align-items: center;
}

.article-head {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.forms {
  width: 70%;
}

.fields input {
  margin: 5px 0;
  padding: 8px;
  width: -webkit-fill-available;
  border-radius: 5px;
  outline: none;
  border: none;
}
</style>
