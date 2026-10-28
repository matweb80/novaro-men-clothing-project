<template>
  <transition :name="animationClass">
    <div v-if="isVisible" ref="el">
      <slot></slot>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";

const props = defineProps({
  type: {
    type: String,
    default: "fade-up", // انیمیشن پیش‌فرض
    validator: (val) =>
      ["fade-up", "fade-left", "fade-right", "zoom-in", "zoom-out"].includes(
        val
      ),
  },
  threshold: { type: Number, default: 0.2 }, // چند درصد المان دیده شود
  once: { type: Boolean, default: true }, // آیا فقط یک‌بار انیمیشن اجرا شود؟
});

const isVisible = ref(false);
const el = ref(null);
let observer;

const animationClass = computed(() => props.type);

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
          if (props.once) observer.unobserve(entry.target);
        } else if (!props.once) {
          isVisible.value = false;
        }
      });
    },
    { threshold: props.threshold }
  );

  if (el.value) observer.observe(el.value);
});

onBeforeUnmount(() => {
  if (observer && el.value) observer.unobserve(el.value);
});
</script>

<style scoped>
/* fade-up */
.fade-up-enter-active {
  transition: all 0.8s ease;
}
.fade-up-enter-from {
  opacity: 0;
  transform: translateY(30px);
}
.fade-up-enter-to {
  opacity: 1;
  transform: translateY(0);
}

/* fade-left */
.fade-left-enter-active {
  transition: all 0.8s ease;
}
.fade-left-enter-from {
  opacity: 0;
  transform: translateX(-40px);
}
.fade-left-enter-to {
  opacity: 1;
  transform: translateX(0);
}

/* fade-right */
.fade-right-enter-active {
  transition: all 0.8s ease;
}
.fade-right-enter-from {
  opacity: 0;
  transform: translateX(40px);
}
.fade-right-enter-to {
  opacity: 1;
  transform: translateX(0);
}

/* zoom-in */
.zoom-in-enter-active {
  transition: all 0.8s ease;
}
.zoom-in-enter-from {
  opacity: 0;
  transform: scale(0.9);
}
.zoom-in-enter-to {
  opacity: 1;
  transform: scale(1);
}

/* zoom-out */
.zoom-out-enter-active {
  transition: all 0.8s ease;
}
.zoom-out-enter-from {
  opacity: 0;
  transform: scale(1.1);
}
.zoom-out-enter-to {
  opacity: 1;
  transform: scale(1);
}
</style>