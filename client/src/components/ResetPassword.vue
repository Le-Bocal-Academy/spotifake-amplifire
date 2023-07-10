<template>
  <section>
    <article class="bgBlack">
      <div class="col forms">
        <Field label="email" fieldType="text" @getValue="getEmail" />
        <Field
          label="nouveau mot de passe"
          fieldType="password"
          @getValue="getNewPassword"
        />
        <Field
          label="confirmation du mot de passe"
          fieldType="password"
          @getValue="getConfirmationNewPasword"
        />
        <RedButton text="Changer mon mot de passe" @click="resetPassword" />
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
      newPassword: "",
      confirmPassword: "",
    };
  },
  methods: {
    async resetPassword() {
      // récupérer la valeur du token inscrit dans l'url
      const currentUrl = new URL(window.location.href);
      const params = new URLSearchParams(currentUrl.search);
      let token = "";
      for (const [param] of params.entries()) {
        token = param;
      }
      const body = {
        token: token,
        email: this.email,
        password: this.newPassword,
        password_confirmation: this.confirmPassword,
      };
      const response = await account.resetPassword(body);
      // gestion des erreurs
      const responseJson = await response.json();
      const errorMessage = errors.constructor(responseJson);
      if (response.status == 200) {
        alert("la réinitialisation de votre mot de passe à été effectué");
        this.$router.push("/login");
      } else {
        alert("Une erreur s'est produite. " + errorMessage);
      }
    },
    getEmail(value) {
      // récupérer la valeur email du formulaire
      this.email = value;
    },
    getNewPassword(value) {
      // récupérer la valeur password du formulaire
      this.newPassword = value;
    },
    getConfirmationNewPasword(value) {
      // récupérer la valeur confirmation du password du formulaire
      this.confirmPassword = value;
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
