<script setup>
defineProps({
  src: { type: String, default: "" },
  alt: { type: String, default: "" },
  placeholderText: { type: String, default: "NOVARO" },
  loading: { type: String, default: "lazy" },
  dataSplideLazy: { type: String, default: null, required: false },
});
</script>

<template>
  <div class="img-plate">
    <div class="img-plate__inner">
      <img
        v-if="src"
        class="img-plate__photo"
        :src="src"
        :alt="alt"
        :loading="loading"
        :data-splide-lazy="dataSplideLazy"
      />
      <span v-else class="img-plate__mark latin" aria-hidden="true">
        {{ placeholderText }}
      </span>
    </div>
  </div>
</template>
