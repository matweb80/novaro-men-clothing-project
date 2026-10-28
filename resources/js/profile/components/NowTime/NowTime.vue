<template>
  <div class="now-time">
    <font-awesome-icon icon="fa-regular fa-clock" class="clock-icon" />
    <span>{{ nowTime }}</span>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, reactive } from "vue";

const nowTime = ref(0);
let timer = null;

const currentDate = new Date();
const optionsTime = reactive({
  hour: "2-digit",
  minute: "2-digit",
  second: "2-digit",
  timeZone: "Asia/Tehran",
});

const update = () => {
  nowTime.value = currentDate.toLocaleTimeString("fa-IR", optionsTime);
};

onMounted(() => {
  update();
  timer = setInterval(update, 1000);
});

onUnmounted(() => clearInterval(timer));
</script>