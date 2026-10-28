<template>
  <router-view v-slot="slotProps">
    <transition name="route">
      <component :is="slotProps.Component"> <slot></slot> </component>
    </transition>
  </router-view>
</template>

<style scoped>
.route-enter-from {
  opacity: 0;
  transform: scale(0.9);
}

.route-leave-to {
  opacity: 0;
  transform: scale(1.1);
}

.route-enter-active {
  transition: all 0.3s ease-out;
}

.route-leave-active {
  transition: all 0.3s ease-in;
}

.route-enter-to,
.route-leave-from {
  opacity: 1;
  transform: scale(1);
}
</style>