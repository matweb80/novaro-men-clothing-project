<template>
  <teleport to="body">
    <div v-if="show" @click="tryClose" class="backdrop"></div>
    <transition name="mini-dialog">
      <dialog open v-if="show" class="alert" :class="alertColor">
        <section>
          <slot>
            <h6>
              {{ message }}
            </h6>
          </slot>
          <slot name="actions">
            <font-awesome-icon
              icon="fa-solid fa-close"
              class="close-icon"
              @click="tryClose"
            />
          </slot>
        </section>
      </dialog>
    </transition>
  </teleport>
</template>

<script>
import { computed } from "vue";
export default {
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    message: {
      type: String,
      required: false,
    },
    type: {
      type: String,
      required: false,
      default: "success",
    },
  },
  emits: ["close"],
  setup(props, context) {
    const tryClose = () => {
      context.emit("close");
    };

    const alertColor = computed(() => {
      if (props.type === "success") {
        return "alert-success";
      } else if (props.type === "danger") {
        return "alert-danger";
      }
    });

    return { tryClose, alertColor };
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
  background: none;
  z-index: 0;
}
dialog {
  position: fixed;
  top: 2%;
  width: 250px;
  right: 30%;
  z-index: var(--nv-z-toast);
  border-radius: 12px;
  border: none;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  margin: 0;
  max-height: 200px;
}

section {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

h6 {
  margin: 0 !important;
}

.close-icon {
  font-size: 16px;
  cursor: pointer;
}

.mini-dialog-enter-from,
.mini-dialog-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

.mini-dialog-enter-active {
  transition: all 0.3s ease-out;
}

.mini-dialog-leave-active {
  transition: all 0.3s ease-in;
}

.mini-dialog-enter-to,
.mini-dialog-leave-from {
  opacity: 1;
  transform: translateY(0);
}

@media (min-width: 768px) {
  dialog {
    right: 40%;
  }
}
</style>
