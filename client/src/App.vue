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
  font-family: "Sen", sans-serif;
}
.bgBlack {
  background: #26272b;
}
.bgBlue {
  background: #bffff1;
}
.bgYellow {
  background: #ffc515;
}
.bgOrange {
  background: #ff7800;
}
.bgRed {
  background: #ff2a2a;
}
.bgWhite {
  background: white;
}
.white {
  color: white;
}
.black {
  color: #2b2b2b;
}
.blue {
  color: #bffff1;
}
.yellow {
  color: #ffc515;
}
.orange {
  color: #ff7800;
}
.red {
  color: #ff2a2a;
}
p {
  margin: 0;
}
.p-XXS {
  font-size: 10px;
}
.p-XS {
  font-size: 12px;
}
.p-S {
  font-size: 15px;
}
.p-M {
  font-size: 20px;
}
.p-L {
  font-size: 25px;
}
.p-XL {
  font-size: 35px;
}
.p-XXL {
  font-size: 42px;
}
.p-XXXL {
  font-size: 50px;
}
.col {
  display: flex;
  flex-direction: column;
}
.fields {
  align-items: flex-start;
  width: 100%;
}
.forms {
  gap: 20px;
  align-items: center;
}
button {
  padding: 10px 20px;
  margin: 5px;
  border: none;
  border-radius: 5px;
}

.capitalize {
  text-transform: capitalize;
}
</style>
