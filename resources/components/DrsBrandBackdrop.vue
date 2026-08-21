<script setup lang="ts">
import { onMounted } from 'vue'
import { useImageStore } from '../store/imageStore'

const imageStore = useImageStore()
onMounted(() => {
    imageStore.fetchImagePath()
})

const figures = [1, 2, 3, 4, 5, 6, 7] as const
</script>

<template>
    <div class="drs-backdrop" aria-hidden="true">
        <div
            class="drs-backdrop-panel"
            :style="{ backgroundImage: `url(${imageStore.imagePath}/seccion-6/fondo.webp)` }"
        ></div>
        <div class="drs-backdrop-shade"></div>

        <div
            v-for="n in figures"
            :key="n"
            class="drs-fig"
            :class="`drs-fig--${n}`"
        >
            <img :src="`${imageStore.imagePath}/seccion-6/${n}.svg`" alt="" />
        </div>
    </div>
</template>

<style scoped>
.drs-backdrop {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
    background: #000;
}

.drs-backdrop-panel {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: min(130rem, 92vw);
    height: min(70rem, 78vh);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.55;
    animation: panelIn 1.1s ease both;
}

.drs-backdrop-shade {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 55% at 50% 45%, rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.72) 75%),
        linear-gradient(180deg, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.55));
}

.drs-fig {
    position: absolute;
    z-index: 1;
}

.drs-fig img {
    display: block;
    width: 100%;
    height: auto;
    opacity: 0.92;
}

.drs-fig--1 {
    top: 0;
    right: 0;
    width: min(28rem, 32vw);
    animation: slideFromRight 1s 0.15s ease both;
}

.drs-fig--2 {
    bottom: 0;
    right: 0;
    width: min(28rem, 30vw);
    animation: slideFromRight 1s 0.25s ease both;
}

.drs-fig--3 {
    bottom: 0;
    left: 0;
    width: min(28rem, 30vw);
    animation: slideFromLeft 1s 0.1s ease both;
}

.drs-fig--4 {
    bottom: 0;
    left: 18%;
    width: min(48rem, 42vw);
    animation: slideFromLeft 1s 0.2s ease both;
}

.drs-fig--5 {
    bottom: 0;
    right: 22%;
    width: min(26rem, 24vw);
    animation: slideFromRight 1s 0.3s ease both;
}

.drs-fig--6 {
    top: 22%;
    right: 0;
    width: min(14rem, 12vw);
    animation: slideFromRight 1s 0.35s ease both;
}

.drs-fig--7 {
    top: 8%;
    left: 0;
    width: min(26rem, 24vw);
    animation: slideFromLeft 1s 0.18s ease both;
}

@keyframes panelIn {
    from {
        opacity: 0;
        transform: translate(-48%, -50%);
    }
    to {
        opacity: 0.55;
        transform: translate(-50%, -50%);
    }
}

@keyframes slideFromLeft {
    from {
        opacity: 0;
        transform: translateX(-4rem);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideFromRight {
    from {
        opacity: 0;
        transform: translateX(4rem);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@media (max-width: 900px) {
    .drs-backdrop-panel {
        width: 94vw;
        height: 72vh;
        opacity: 0.45;
    }

    .drs-fig--2,
    .drs-fig--5 {
        display: none;
    }

    .drs-fig--1 {
        width: min(22rem, 42vw);
    }

    .drs-fig--3 {
        width: min(22rem, 40vw);
    }

    .drs-fig--4 {
        width: min(32rem, 55vw);
        left: 10%;
    }

    .drs-fig--6 {
        width: min(10rem, 18vw);
        top: 55%;
    }

    .drs-fig--7 {
        width: min(20rem, 38vw);
        top: 10%;
    }
}

@media (max-width: 600px) {
    .drs-backdrop-panel {
        height: 68vh;
    }

    .drs-fig--4,
    .drs-fig--6 {
        display: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    .drs-backdrop-panel,
    .drs-fig {
        animation: none;
        opacity: 1;
    }

    .drs-backdrop-panel {
        opacity: 0.5;
    }
}
</style>
