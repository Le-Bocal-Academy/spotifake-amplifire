<template>
  <section>
    <article class="bgBlack">
      <div class="article-head">
        <p class="p-M yellow">AmpliFire premium</p>
        <p class="p-XXL">2 mois offerts</p>
        <p class="p-M">Puis 9,99€ /mois*</p>
      </div>
      <div class="col forms">
        <div class="socialMedia">
          <button class="bgWhite socialButton p-S" id="fb">
            <img src="../assets/fb.png" alt="Facbook" />
            Facebook
          </button>
          <button class="bgWhite socialButton p-S" id="insta">
            <img src="../assets/insta.png" alt="Instagram" />
            Instagram
          </button>
        </div>
        <Field label="pseudo" fieldType="text" />
        <Field label="nom" fieldType="text" />
        <Field label="prénom" fieldType="text" />
        <Field label="adresse email" fieldType="text" />
        <Field label="mot de passe" fieldType="text" />
        <Field label="confirmation du mot de passe" fieldType="text" />

        <p class="p-XS">
          En cliquant sur "Inscription", tu acceptes les conditions générales
          d'utilisation et la politique de protection des données
        </p>
        <RedButton text="Inscription" @click="registration" />
        <p class="p-XS">
          Déjà inscrit(e) sur AmpliFire ? <a href="">Connexion</a>
        </p>
        <p class="p-XS">
          Ce site est protégé par reCAPTCHA. Les règles de confidentialité et
          condition d'utilisation de Google s'appliquent.
        </p>
      </div>
      <p class="p-XXS">*Jusqu'à la fin de ta vie.</p>
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
  methods: {
    async registration() {
      const body = {
        nickname: "pseudo-test",
        firstname: "John",
        lastname: "Doe",
        email: "john@gmail.com",
        password: "Azerty123!",
        password_confirmation: "Azerty123!",
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
      const data = await fetch(url + "/register", options);
      console.log(data);
    },
  },
};
</script>

<style scoped>
section {
  display: flex;
  justify-content: center;
  margin-bottom: 150px;
}
article {
  width: 40%;
  color: white;
  border-radius: 10px;
  padding: 50px 0 20px;
  display: flex;
  flex-direction: column;
  gap: 50px;
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

.socialButton {
  padding: 3px 20px 3px 5px;
  display: flex;
  align-items: center;
  gap: 30px;
  border-radius: 30px;
  width: -webkit-fill-available;
}

#fb {
  color: #4676ed;
}
#insta {
  color: #cb1f84;
}
.socialButton img {
  width: 30px;
}

.socialMedia {
  display: flex;
  width: 100%;
  align-items: center;
  gap: 20px;
  margin-bottom: 20px;
}
</style>
