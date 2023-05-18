<template>
  <div class="bgBlack header">
    <div class="logo">
      <img alt="Vue logo" src="../assets/logo.png" />
    </div>
    <div>
      <button class="bgYellow black">Inscription</button>
      <button class="bgBlue black">Connexion</button>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 50px;
}

.logo {
  width: 15%;
  display: flex;
}

.logo img {
  width: 100%;
}
</style>
