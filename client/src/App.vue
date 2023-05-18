<template>
  <router-view />
</template>

<style>
body {
  margin: 0;
  background: linear-gradient(
    to bottom,
    rgba(255, 42, 42, 0.8),
    rgba(255, 120, 0, 0.8),
    rgba(255, 197, 21, 0.8),
    rgba(191, 255, 241, 0.8)
  );
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.bgBlack {
  background: #2b2b2b;
}
nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>
