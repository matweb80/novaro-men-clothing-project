<template>
    <div>
        <slot></slot>
    </div>
</template>

<style scoped>
div{
    border-radius: 50%;
    margin: 16px;
}
div:hover{
    transform: scale(1.1);
}
</style>