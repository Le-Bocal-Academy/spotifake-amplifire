<template>
  <div class="popup">
    <div class="popup-content bgBlack white">
      <h2 class="yellow">{{ title }}</h2>
      <slot name="content"></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
  },
};
</script>

<style scoped>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-content {
  display: flex;
  flex-direction: column;
  gap: 40px;
  padding: 40px 50px;
  border-radius: 10px;
  align-items: center;
}

h2 {
  margin: 0;
}

@media screen and (max-width: 600px) {
  h2 {
    font-size: 20px;
  }
}

@media screen and (max-width: 600px) {
  .popup-content {
    padding: 20px;
    width: 70%;
  }
}
@media screen and (max-width: 400px) {
}
</style>
