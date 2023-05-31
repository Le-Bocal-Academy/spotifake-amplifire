<template>
  <div class="col fields">
    <label for="" class="p-S">{{ label }}</label>
    <input :type="fieldType" />
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    fieldType: String,
  },
};
</script>

<style scoped>
.fields input {
  margin: 5px 0;
  padding: 8px;
  width: -webkit-fill-available;
  border-radius: 5px;
  outline: none;
  border: none;
}
</style>
