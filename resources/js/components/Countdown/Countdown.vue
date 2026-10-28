<template>
  <div v-if="remaining > 0" class="countdown">
    <font-awesome-icon
      icon="fa-regular fa-clock"
      class="filter-search__icon"
    /><span v-if="days > 0">{{ days }}روز </span
    ><span v-if="hours > 0 || days > 0">{{ hours }}ساعت </span
    ><span v-if="minutes > 0 || hours > 0 || days > 0">{{ minutes }}دقیقه </span
    >{{ seconds }}ثانیه
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";

const props = defineProps({
  endAt: String,
});

const remaining = ref(0);
let timer = null;

const update = () => {
  remaining.value = Math.floor((new Date(props.endAt) - new Date()) / 1000);
};

onMounted(() => {
  update();
  timer = setInterval(update, 1000);
});

onUnmounted(() => clearInterval(timer));

const days = computed(() => Math.floor(remaining.value / 86400));
const hours = computed(() => Math.floor((remaining.value % 86400) / 3600));
const minutes = computed(() => Math.floor((remaining.value % 3600) / 60));
const seconds = computed(() => remaining.value % 60);
</script>

<style scoped>
.countdown {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 2px;
  padding: 6px 10px;
  background-color: rgba(255, 36, 36, 0.5);
  border-radius: 10px;
  color: #fff;
  position: absolute;
  top: 8px;
  left: 2px;
  z-index: 10;
}
</style>