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
      const responseJson = await response.json();
      const errors = responseJson["errors"];
      let errorMessage = "";
      Object.keys(errors).forEach((key) => {
        const errorMessages = errors[key];
        errorMessage += `${key}: `;
        errorMessages.forEach((message) => {
          errorMessage += `${message}\n`;
        });
      });
      if (response.status == 200) {
        alert("la réinitialisation de votre mot de passe à été effectué");
        this.$router.push("/login");
      } else {
        alert("Une erreur s'est produite. " + errorMessage);
      }
    },
    getEmail(value) {
      this.email = value;
    },
    getNewPassword(value) {
      this.newPassword = value;
    },
    getConfirmationNewPasword(value) {
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
