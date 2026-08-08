<script setup lang="ts">
defineEmits<{
    close: [];
}>();
</script>

<template>
    <section class="app-detalle">
        <p>componente nuevo</p>
    </section>
</template>

<style scoped>
.app-detalle {
    position: fixed;
    inset: 0;
    z-index: 50001;
    width: 100%;
    height: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    display: grid;
    place-items: center;
    background: linear-gradient(180deg, #050b14 0%, #02060c 100%);
    color: white;
    font-family: var(--familyTitles);
    font-size: 3.5rem;
    animation: enterView 0.4s ease forwards;
}

@keyframes enterView {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
