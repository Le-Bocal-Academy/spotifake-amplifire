<template>
  <section>
    <article class="bgBlack">
      <div class="article-head">
        <p class="p-M yellow">AmpliFire premium</p>
        <p class="p-XXL">2 mois offerts</p>
        <p class="p-M">Puis 9,99€ /mois*</p>
      </div>
      <div class="col forms">
        <div class="col fields">
          <label for="" class="p-S">adresse email</label>
          <input type="text" />
        </div>
        <div class="col fields">
          <label for="" class="p-S">mot de passe</label>
          <input type="text" />
        </div>
        <button class="bgRed white">s'inscrire</button>
      </div>
      <p class="p-XXS">*Jusqu'à la fin de ta vie.</p>
    </article>
  </section>
</template>

<style scoped>
section {
  display: flex;
  justify-content: center;
  margin-bottom: 150px;
}
article {
  width: 30%;
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
</style>
