<template>
  <span>
    <button class="bgYellow black p-S button">
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
a:hover {
  color: #2b2b2b;
}
</style>
