<template>
  <div class="bg-primary">
    <h2>
        <slot name="title"></slot>
    </h2>
    <slot></slot>
  </div>
</template>

<style scoped>
div {
    padding: 12px;
    border-radius: 20px;
}
h2{
    font-weight: 600;
    font-size: 32px;
    padding-right: 12px;
    padding-top: 12px;
}
</style>