<script setup>
defineProps({
  ariaLabel: { type: String, required: true },
  ariaPressed: { type: [Boolean, String], default: undefined },
  ariaExpanded: { type: [Boolean, String], default: undefined },
  ariaHaspopup: { type: String, default: undefined },
  ariaControls: { type: String, default: undefined },
  badge: { type: [String, Number], default: undefined },
})

defineEmits(['click'])
</script>

<template>
  <button
    type="button"
    class="icon-btn"
    :aria-label="ariaLabel"
    :aria-pressed="ariaPressed"
    :aria-expanded="ariaExpanded"
    :aria-haspopup="ariaHaspopup"
    :aria-controls="ariaControls"
    @click="$emit('click', $event)"
  >
    <slot />
    <span v-if="badge !== undefined" class="badge">{{ badge }}</span>
  </button>
</template>
