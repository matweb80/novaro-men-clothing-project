<template>
  <component
    :is="link ? 'router-link' : 'button'"
    :class="['nv-button', `nv-button--${resolvedVariant}`, mode]"
    :to="link ? to : undefined"
    :type="link ? undefined : type"
    :aria-busy="loading ? 'true' : undefined"
    :aria-disabled="isDisabled ? 'true' : undefined"
    :disabled="link ? undefined : isDisabled"
    @click="handleClick"
  >
    <span class="nv-button__label"><slot /></span>
    <span v-if="loading" class="visually-hidden">در حال انجام</span>
  </component>
</template>

<script>
export default {
  inject: {
    isLoading: { default: false },
  },
  props: {
    mode: { type: String, default: null },
    variant: {
      type: String,
      default: "primary",
      validator: (value) => ["primary", "secondary", "outline", "ghost"].includes(value),
    },
    link: { type: Boolean, default: false },
    to: { type: String, default: "/" },
    // Preserve the native behavior of the previous button implementation.
    type: { type: String, default: "submit" },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
  },
  emits: ["click"],
  computed: {
    isDisabled() {
      return this.disabled || this.loading || this.isLoading;
    },
    resolvedVariant() {
      if (this.mode === "danger") return "outline";
      if (this.mode === "success" || this.mode === "active") return "primary";
      return this.variant;
    },
  },
  methods: {
    handleClick(event) {
      if (this.isDisabled) {
        event.preventDefault();
        return;
      }
      this.$emit("click", event);
    },
  },
};
</script>

<style scoped>
.nv-button {
  align-items: center;
  justify-content: center;
  min-block-size: 2.75rem;
  padding-block: 0.625rem;
  padding-inline: 1.25rem;
  border: 1px solid transparent;
  border-radius: var(--nv-radius-sm);
  cursor: pointer;
  font: inherit;
  font-size: var(--nv-text-button);
  font-weight: 500;
  line-height: 1.4;
  text-decoration: none;
  transition: background-color var(--nv-duration-normal) var(--nv-ease-out), color var(--nv-duration-normal) var(--nv-ease-out), border-color var(--nv-duration-normal) var(--nv-ease-out), transform var(--nv-duration-fast) var(--nv-ease-out);
}

.nv-button--primary { background: var(--nv-primary); color: var(--nv-secondary); }
.nv-button--primary:hover { background: #1f2937; color: var(--nv-secondary); }
.nv-button--secondary { background: var(--nv-gold); color: var(--nv-primary); }
.nv-button--secondary:hover { background: var(--nv-gold-hover); color: var(--nv-primary); }
.nv-button--outline { background: transparent; border-color: var(--nv-primary); color: var(--nv-primary); }
.nv-button--outline:hover { background: var(--nv-primary); color: var(--nv-secondary); }
.nv-button--ghost { background: transparent; color: var(--nv-primary); }
.nv-button--ghost:hover { background: var(--nv-surface-subtle); color: var(--nv-primary); }

/* Keep established class-based calls working while components migrate to variants. */
.nv-button.active,
.nv-button.success { background: var(--nv-success); color: var(--nv-secondary); }
.nv-button.danger { background: var(--nv-error); color: var(--nv-secondary); }
.nv-button.google-map { background: var(--nv-error); color: var(--nv-secondary); }
.nv-button.neshan-map,
.nv-button.balad-map { background: var(--nv-info); color: var(--nv-secondary); }

.nv-button:active:not(:disabled):not([aria-disabled="true"]) { transform: translateY(1px); }
.nv-button:disabled,
.nv-button[aria-disabled="true"] { background: var(--nv-disabled-bg); border-color: var(--nv-disabled-bg); color: var(--nv-disabled-text); cursor: not-allowed; pointer-events: none; }
.nv-button__label { display: inline-flex; align-items: center; gap: var(--nv-space-2); }
</style>
