<template>
  <span>
    <button class="bgBlue black p-S">
      <a class="black" :href="link">{{ text }}</a>
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
