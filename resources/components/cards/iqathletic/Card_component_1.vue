<script setup lang="ts">
const emit = defineEmits<{
    close: [];
}>();

defineProps<{
    card?: {
        id: number
        project: string
        card: number
        image: string
        component: string
        imageUrl: string
    }
}>()
</script>

<template>
    <section class="app-detalle">
        <button type="button" class="app-detalle-close" @click="emit('close')" aria-label="Cerrar">
            <i class="fa-solid fa-xmark"></i>
        </button>
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

.app-detalle-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 3.2rem;
    height: 3.2rem;
    border: 1px solid rgba(180, 200, 230, 0.35);
    background: rgba(0, 0, 0, 0.45);
    color: #e8eef8;
    border-radius: 0.6rem;
    cursor: pointer;
    font-size: 1.6rem;
}

.app-detalle-close:hover {
    background: rgba(170, 24, 24, 0.4);
    border-color: rgba(170, 24, 24, 0.6);
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
