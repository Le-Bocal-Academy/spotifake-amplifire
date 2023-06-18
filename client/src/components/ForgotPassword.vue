<template>
  <section>
    <article class="bgBlack">
      <div class="col forms">
        <Field label="adresse email" fieldType="text" @getValue="getEmail" />
        <RedButton text="Envoyer un email" @click="sendNewPassword" />
      </div>
    </article>
  </section>
</template>

<script>
import Field from "./UI/fields.vue";
import RedButton from "./UI/redButton.vue";
import account from "../_lib/requests/account";
import errors from "../_lib/requests/errors";

export default {
  components: {
    Field,
    RedButton,
  },
  data() {
    return {
      email: "",
    };
  },
  methods: {
    async sendNewPassword() {
      const body = {
        email: this.email,
      };
      const response = await account.forgotPassword(body);
      // gestion des erreurs
      const responseJson = await response.json();
      const errorMessage = errors.constructor(responseJson);
      if (response.status == 200) {
        alert("Un email à été envoyé");
      } else {
        alert("Une erreur s'est produite. " + errorMessage);
      }
    },
    getEmail(value) {
      // récupération de la valeur du formulaire
      this.email = value;
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
  width: 50%;
  color: white;
  border-radius: 10px;
  padding: 50px 0 20px;
  display: flex;
  flex-direction: column;
  gap: 70px;
  align-items: center;
  margin: 10%;
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
