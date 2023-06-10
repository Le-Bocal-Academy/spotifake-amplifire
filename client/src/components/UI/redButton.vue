<template>
  <span>
    <button class="bgRed white p-S">
      <a class="white" :href="link">{{ text }}</a>
    </button>
  </span>
</template>

<script>
export default {
  props: {
    text: String,
    link: String,
  },
};
</script>
<style scoped>
a {
  text-decoration: none;
}
</style>
