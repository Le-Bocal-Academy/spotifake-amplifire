<template>
  <section>
    <article class="bgBlack">
      <div class="col forms">
        <Field label="adresse email" fieldType="text" @getValue="getEmail" />
        <Field
          label="mot de passe"
          fieldType="password"
          @getValue="getPassword"
        />
        <p class="p-XS">
          <a href="/forgotpassword">mot de passe oublié ?</a>
        </p>
        <RedButton text="Connexion" @click="login" />
        <p class="p-XS">
          Tu n'as pas de compte ?
          <a href="/register">S'inscrire</a>
        </p>
        <p class="p-XS">
          Ce site est protégé par reCAPTCHA. Les règles de confidentialité et
          condition d'utilisation de Google s'appliquent.
        </p>
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
      password: "",
    };
  },
  methods: {
    async login() {
      const body = {
        email: this.email,
        password: this.password,
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
      const data = await fetch(url + "/login", options);
      const response = await data.json();
      const token = response.token;
      if (data.status === 200 && token) {
        localStorage.setItem("token", token);
        this.$router.push("/home");
      }
    },
    getEmail(value) {
      this.email = value;
    },
    getPassword(value) {
      this.password = value;
    },
  },
};
</script>

<style scoped>
section {
  display: flex;
  justify-content: center;
  margin-bottom: 10%;
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
