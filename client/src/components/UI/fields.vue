<template>
  <div class="col fields">
    <label for="" class="p-S">{{ label }}</label>
    <input :type="fieldType" :value="value" @input="updateValue($event)" />
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    fieldType: String,
    value: [String, Number],
  },
  methods: {
    updateValue(event) {
      const value = event.target.value;
      this.$emit("getValue", value);
    },
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
  color: black;
}
</style>
