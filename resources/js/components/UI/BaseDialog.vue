<template>
  <teleport to="body">
    <div v-if="show" @click="tryClose" class="backdrop"></div>
    <transition name="dialog">
      <dialog open v-if="show">
        <header :style="{'background-color': headerColor }">
          <slot name="header">
            <h3>{{ title }}</h3>
          </slot>
        </header>
        <section>
          <slot></slot>
        </section>
        <menu v-if="!fixed">
          <slot name="actions">
            <button class="btn btn--danger" @click="tryClose">لغو</button>
            <button class="btn btn--success" @click="submitDialog"
              >ارسال</button
            >
          </slot>
        </menu>
      </dialog>
    </transition>
  </teleport>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      required: false,
    },
    fixed: {
      type: Boolean,
      required: false,
      default: false,
    },
    headerColor: {
      type: String,
      required: false,
      default: 'var(--primary-color)',
    },
  },
  emits: ["close"],
  methods: {
    tryClose() {
      if (this.fixed) {
        return;
      }
      this.$emit("close");
    },
    submitDialog() {
      if (this.fixed) {
        return;
      }
      this.$emit("submit-dialog");
    },
  },
};
</script>

<style scoped>
.backdrop {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.75);
  z-index: calc(var(--z-modal) - 1);
}

dialog {
  position: fixed;
  top: 20vh;
  right: 20%;
  width: 60%;
  z-index: var(--z-modal);
  border-radius: 12px;
  border: none;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  padding: 0;
  margin: 0;
  overflow: hidden;
  background-color: white;
  max-height: 500px;
}

header {
  width: 100%;
  padding: 1rem;
}


header h3 {
  margin: 0;
  color: var(--c-white);
}

section {
  padding: 1rem;
  text-align: center;
}

menu {
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  margin: 0;
}

.dialog-enter-from,
.dialog-leave-to {
  opacity: 0;
  transform: scale(0.8);
}

.dialog-enter-active {
  transition: all 0.3s ease-out;
}

.dialog-leave-active {
  transition: all 0.3s ease-in;
}

.dialog-enter-to,
.dialog-leave-from {
  opacity: 1;
  transform: scale(1);
}

@media (min-width: 768px) {
  dialog {
    right: 30%;
    width: 45%;
  }
}
</style>
