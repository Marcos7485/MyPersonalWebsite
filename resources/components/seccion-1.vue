<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

export interface AppCard {
    id: number
    project: string
    card: number
    image: string
    hover_text: string | null
    descripcion: string | null
    component: string | null
    active: boolean
    imageUrl: string
    projectIconUrl: string
}

const props = withDefaults(defineProps<{
    cards?: AppCard[]
}>(), {
    cards: () => [],
})

const imageStore = useImageStore();
const languageStore = useLanguageStore();

const aboutOpen = ref(false);

const arcBrand = computed(() => {
    const first = props.cards[0]
    if (first) {
        return {
            src: first.projectIconUrl,
            alt: first.project,
        }
    }
    return {
        src: `${imageStore.imagePath || '/images'}/iqathletic/icon.png`,
        alt: 'iqathletic',
    }
})

const uiLabels = computed(() => ({
    heroTagline: languageStore.t('hero.tagline'),
    aboutButton: languageStore.t('about.button'),
    aboutTitle: languageStore.t('about.title'),
    aboutBody: languageStore.t('about.body'),
}));

const toggleAbout = () => {
    aboutOpen.value = !aboutOpen.value;
};

onMounted(() => {
    imageStore.fetchImagePath();
});
</script>

<template>
    <section id="Home" class="hero">
        <div class="hero-bg" aria-hidden="true">
            <img
                :src="`${imageStore.imagePath}/seccion-1/fondo.png`"
                alt=""
                class="hero-bg-img"
            >
        </div>

        <div class="atmosphere" aria-hidden="true">
            <div class="glow glow-a"></div>
            <div class="glow glow-b"></div>
            <div class="vignette"></div>
        </div>

        <div class="hero-deco hero-deco--left" aria-hidden="true">
            <video
                class="hero-deco-video"
                :src="`${imageStore.imagePath}/seccion-1/video1.mp4`"
                autoplay
                muted
                loop
                playsinline
            ></video>
        </div>

        <div class="hero-deco hero-deco--right" aria-hidden="true">
            <img
                :src="`${imageStore.imagePath}/seccion-1/1.png`"
                alt=""
                class="hero-deco-img"
            >
        </div>

        <div class="hero-inner">
            <div class="stage">
                <!-- Hueco de las cards: ancla del único dragón (Header lo teleporta aquí) -->
                <div class="cards-row">
                    <div class="dragon-block">
                        <div id="dragon-dock" class="dragon-dock"></div>
                        <p class="dragon-tagline">{{ uiLabels.heroTagline }}</p>
                    </div>
                </div>

                <div class="arc-brand">
                    <div class="arc-brand-mark">
                        <img
                            :src="arcBrand.src"
                            :alt="arcBrand.alt"
                            class="arc-brand-logo"
                        >
                        <span class="arc-brand-shine-clip" aria-hidden="true">
                            <span class="arc-brand-shine"></span>
                        </span>
                    </div>
                    <a
                        href="https://www.iqathleticsoftware.com"
                        class="arc-brand-link"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        www.iqathleticsoftware.com
                    </a>
                </div>
            </div>

            <div class="about">
                <button
                    type="button"
                    class="about-trigger"
                    :class="{ open: aboutOpen }"
                    :aria-expanded="aboutOpen"
                    @click="toggleAbout"
                >
                    <span class="about-icon">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <span>{{ uiLabels.aboutButton }}</span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>

                <div class="about-panel" :class="{ open: aboutOpen }">
                    <div class="about-panel-inner">
                        <div class="about-photo">
                            <img
                                :src="`${imageStore.imagePath}/seccion-1/picture.webp`"
                                alt="Marcos Gonzalez"
                            >
                        </div>
                        <div class="about-text">
                            <h2>{{ uiLabels.aboutTitle }}</h2>
                            <p>{{ uiLabels.aboutBody }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero {
    --hero-red: var(--color-first);

    position: relative;
    width: 100%;
    min-height: 64rem;
    margin-top: -5rem;
    overflow: hidden;
    background: #02060c;
    color: white;
    animation: appear 1.2s ease forwards;
}

.hero-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}

.hero-bg-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    display: block;
    opacity: 0.9;
}

.atmosphere {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    background:
        radial-gradient(ellipse 70% 50% at 50% 40%, rgba(8, 24, 48, 0.35), transparent 65%),
        linear-gradient(180deg, rgba(2, 6, 12, 0.35) 0%, rgba(2, 6, 12, 0.55) 55%, rgba(1, 3, 8, 0.78) 100%);
}

.glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(70px);
}

.glow-a {
    width: 48rem;
    height: 48rem;
    top: 18%;
    left: 50%;
    transform: translateX(-50%);
    background: radial-gradient(circle, rgba(28, 80, 150, 0.4), transparent 70%);
    animation: pulseGlow 7s ease-in-out infinite;
}

.glow-b {
    width: 28rem;
    height: 28rem;
    bottom: 10%;
    left: 50%;
    transform: translateX(-50%);
    background: radial-gradient(circle, rgba(170, 24, 24, 0.12), transparent 70%);
}

.vignette {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at center, transparent 42%, rgba(0, 0, 0, 0.75) 100%);
    box-shadow: inset 0 0 10rem rgba(0, 8, 20, 0.85);
}

.hero-deco {
    position: absolute;
    top: 0;
    bottom: 0;
    width: min(40vw, 46rem);
    z-index: 1;
    pointer-events: none;
    overflow: hidden;
}

.hero-deco--left {
    left: 0;
    -webkit-mask-image: linear-gradient(
        to right,
        #000 0%,
        #000 42%,
        transparent 92%
    );
    mask-image: linear-gradient(
        to right,
        #000 0%,
        #000 42%,
        transparent 92%
    );
}

.hero-deco--right {
    right: 0;
    -webkit-mask-image: linear-gradient(
        to left,
        #000 0%,
        #000 42%,
        transparent 92%
    );
    mask-image: linear-gradient(
        to left,
        #000 0%,
        #000 42%,
        transparent 92%
    );
}

.hero-deco-img,
.hero-deco-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    opacity: 0.72;
    filter: saturate(0.92) contrast(1.05);
    display: block;
}

.hero-deco-img {
    object-position: 55% 20%;
}

.hero-inner {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 2rem;
    padding: 13.5rem 2rem 3rem;
    min-height: 64rem;
}

.stage {
    position: relative;
    width: min(100%, 118rem);
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2.8rem;
    overflow: visible;
}

.cards-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 4rem;
    width: 100%;
    z-index: 2;
    padding-top: 1.5rem;
    /* Misma altura aproximada que tenían las 3 cards (26rem + arco) */
    min-height: 30rem;
    box-sizing: border-box;
}

.dragon-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.2rem;
    width: min(34rem, 58vw);
}

.dragon-dock {
    width: 100%;
    min-height: min(20rem, 34vw);
    display: grid;
    place-items: center;
}

.dragon-tagline {
    margin: 0;
    text-align: center;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.8rem, 2.4vw, 2.6rem);
    letter-spacing: 0.04em;
    color: rgba(230, 236, 248, 0.9);
    text-shadow: 0 0.2rem 0.8rem rgba(0, 0, 0, 0.55);
}

.arc-brand {
    position: relative;
    left: auto;
    top: auto;
    transform: none;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.7rem;
    pointer-events: none;
}

.arc-brand-mark {
    position: relative;
    display: inline-block;
}

.arc-brand-logo {
    width: 9rem;
    height: auto;
    display: block;
    filter: drop-shadow(0 0.35rem 0.8rem rgba(0, 0, 0, 0.55));
    opacity: 0.92;
}

/* Ventana central: el brillo solo se ve en el medio del logo */
.arc-brand-shine-clip {
    position: absolute;
    left: 10%;
    right: 1%;
    top: 35%;
    bottom: 10%;
    border-radius: .5rem 1rem 0 0;
    overflow: hidden;
    pointer-events: none;
}

.arc-brand-shine {
    position: absolute;
    inset: -15% auto -15% 0;
    width: 45%;
    background: linear-gradient(
        100deg,
        transparent 0%,
        rgba(255, 255, 255, 0.1) 35%,
        rgba(255, 220, 220, 0.5) 50%,
        rgba(255, 255, 255, 0.1) 65%,
        transparent 100%
    );
    pointer-events: none;
    opacity: 0;
    mix-blend-mode: screen;
    animation: iqShineSweep 4.2s 0.8s ease-in-out infinite;
}

@keyframes iqShineSweep {
    0% {
        transform: translateX(-120%) skewX(-18deg);
        opacity: 0;
    }
    12% { opacity: 0.8; }
    35% {
        transform: translateX(220%) skewX(-18deg);
        opacity: 0;
    }
    100% {
        transform: translateX(220%) skewX(-18deg);
        opacity: 0;
    }
}

@media (prefers-reduced-motion: reduce) {
    .arc-brand-shine {
        animation: none !important;
        opacity: 0;
    }
}

.arc-brand-link {
    pointer-events: auto;
    color: rgba(220, 230, 245, 0.78);
    font-family: var(--familyTitles);
    font-size: 2rem;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: color 0.2s ease, opacity 0.2s ease;
}

.arc-brand-link:hover {
    color: #fff;
    text-decoration: underline;
}

.card {
    position: relative;
    /* Misma proporción que card1.png (1080x1920) para que la img no se corte */
    height: 26rem;
    width: calc(26rem * 1080 / 1920);
    aspect-ratio: 1080 / 1920;
    flex: 0 0 auto;
    box-sizing: border-box;
    cursor: pointer;
    border-radius: 1rem;
    overflow: hidden;
    transform-origin: 50% 100%;
    transition:
        transform 0.35s cubic-bezier(0.22, 0.85, 0.28, 1),
        filter 0.35s ease,
        opacity 0.35s ease;
}

/* Arco suave: laterales más bajas e inclinadas */
.cards-row .card:nth-child(1) {
    transform: rotate(-10deg) translateY(2.2rem);
}

.cards-row .card:nth-child(2) {
    transform: rotate(0deg) translateY(0);
    z-index: 2;
}

.cards-row .card:nth-child(3) {
    transform: rotate(10deg) translateY(2.2rem);
}

.cards-row .card:nth-child(1).hovered {
    transform: rotate(-10deg) translateY(1.2rem) scale(1.04);
    z-index: 3;
}

.cards-row .card:nth-child(2).hovered {
    transform: rotate(0deg) translateY(-0.7rem) scale(1.04);
    z-index: 3;
}

.cards-row .card:nth-child(3).hovered {
    transform: rotate(10deg) translateY(1.2rem) scale(1.04);
    z-index: 3;
}

.card.dimmed {
    filter: brightness(0.45);
    opacity: 0.55;
    pointer-events: none;
}

.card-modal-root {
    position: fixed;
    inset: 0;
    z-index: 999998;
    pointer-events: none;
}

.card-modal-backdrop {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: auto;
    background: rgba(1, 3, 8, 0.72);
    backdrop-filter: blur(4px);
}

.card.selected {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    transform-origin: center center;
    cursor: default;
    width: min(92vw, 96rem);
    height: min(80vh, 56rem);
    max-height: 80vh;
    aspect-ratio: unset;
    border-radius: 1.4rem;
    z-index: 2;
    pointer-events: auto;
    overflow: hidden;
}

.card-modal-enter-active,
.card-modal-leave-active {
    transition: opacity 0.38s ease;
}

.card-modal-enter-active .card.selected,
.card-modal-leave-active .card.selected {
    transition:
        transform 0.42s cubic-bezier(0.22, 0.85, 0.28, 1),
        opacity 0.38s ease;
}

.card-modal-enter-from,
.card-modal-leave-to {
    opacity: 0;
}

.card-modal-enter-from .card.selected {
    transform: translate(-50%, -46%) scale(0.88);
    opacity: 0;
}

.card-modal-leave-to .card.selected {
    transform: translate(-50%, -48%) scale(0.92);
    opacity: 0;
}

/* El resaltado va en la cara: el hitbox del .card NO se mueve */
.card-face {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    border-radius: 1rem;
    background: linear-gradient(165deg, #424956 0%, #262b34 55%, #1a1e26 100%);
    border: 1px solid rgba(180, 190, 205, 0.16);
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    gap: 0;
    overflow: hidden;
    filter: brightness(0.82);
    box-shadow:
        0 1.4rem 2.6rem rgba(0, 0, 0, 0.55),
        0 0.5rem 1.2rem rgba(0, 0, 0, 0.35);
    transition:
        filter 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease,
        background 0.25s ease;
    pointer-events: none;
}

.card.selected .card-face {
    pointer-events: auto;
    filter: none;
    border-color: rgba(140, 185, 255, 0.55);
    background: #0a0a0a;
    box-shadow:
        0 2.8rem 5rem rgba(0, 0, 0, 0.8),
        0 0 3.5rem rgba(40, 110, 200, 0.45),
        0 0 1.5rem rgba(170, 24, 24, 0.2);
    display: flex;
    flex-direction: column;
    padding: 0;
    border-radius: 1.4rem;
    overflow: hidden;
}

.card.selected .card-back {
    position: absolute;
    top: 1.4rem;
    left: 1.4rem;
    z-index: 5;
    width: auto;
}

.modal-layout {
    display: grid;
    grid-template-columns: minmax(0, 42%) minmax(0, 58%);
    width: 100%;
    height: 100%;
    min-height: 0;
    flex: 1 1 auto;
}

.modal-media {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    min-height: 0;
    padding: 2.4rem 1.6rem 2.4rem 2.4rem;
    box-sizing: border-box;
    overflow: hidden;
    background: #070b12;
}

.modal-image {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: center;
    border-radius: 1rem;
}

.modal-copy {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 0;
    height: 100%;
    padding: 3.5rem 3rem;
    box-sizing: border-box;
    overflow: hidden;
    background: linear-gradient(165deg, #1a2030 0%, #0c1018 100%);
}

.modal-copy-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    width: 100%;
    max-width: 48rem;
    max-height: 100%;
    overflow-y: auto;
    text-align: center;
    padding-right: 0.4rem;
}

.modal-description {
    margin: 0;
    color: rgba(232, 238, 248, 0.92);
    font-family: var(--familyTitles);
    font-size: clamp(1.5rem, 1.6vw, 2rem);
    line-height: 1.55;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.card.selected .card-banner {
    position: relative;
    top: auto;
    right: auto;
    z-index: 1;
    width: auto;
    min-width: 16rem;
    margin: 0;
    flex-shrink: 0;
}

.card.selected .card-empty {
    width: 100%;
    height: 100%;
}

.card-face::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        linear-gradient(145deg, rgba(255, 255, 255, 0.12), transparent 40%),
        linear-gradient(to left, rgba(0, 0, 0, 0.22), transparent 28%),
        linear-gradient(to right, rgba(0, 0, 0, 0.22), transparent 28%);
    pointer-events: none;
}

.card-face::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: inherit;
    box-shadow: inset 0 0 2.5rem rgba(0, 0, 0, 0.35);
    pointer-events: none;
}

.card.hovered .card-face {
    filter: none;
    border-color: rgba(140, 185, 255, 0.5);
    box-shadow:
        0 2.4rem 4.2rem rgba(0, 0, 0, 0.72),
        0 0 2.8rem rgba(40, 110, 200, 0.35),
        0 0 1.2rem rgba(170, 24, 24, 0.15);
}

.card-back {
    position: relative;
    z-index: 2;
    flex: 0 0 auto;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: 1px solid rgba(180, 200, 230, 0.35);
    background: rgba(0, 0, 0, 0.4);
    color: #e8eef8;
    font-size: 1.25rem;
    line-height: 1.2;
    font-family: var(--familyTitles);
    padding: 0.7rem 0.9rem;
    border-radius: 0.6rem;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: background 0.2s ease, border-color 0.2s ease;
}

.card-back:hover {
    background: rgba(170, 24, 24, 0.35);
    border-color: rgba(170, 24, 24, 0.6);
}

.card-back i {
    font-size: 1.1rem;
    flex-shrink: 0;
}

.card-back span {
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-empty {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    min-height: 0;
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    flex: 1 1 auto;
    justify-content: center;
    padding: 1.8rem;
    box-sizing: border-box;
}

.card-image {
    position: absolute;
    inset: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: inherit;
    display: block;
    transform: scale(1);
    filter: brightness(1);
    transition:
        transform 0.5s cubic-bezier(0.22, 0.85, 0.28, 1),
        filter 0.45s ease;
}

.card-hover-layer {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.8rem;
    box-sizing: border-box;
    background: rgba(2, 6, 12, 0.45);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.card-hover-text {
    margin: 0;
    color: #f4f7fc;
    text-align: center;
    font-family: var(--familyTitles);
    font-size: 1.7rem;
    line-height: 1.35;
    letter-spacing: 0.02em;
    text-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.65);
    transform: translateY(1rem) scale(0.96);
    opacity: 0;
    transition:
        transform 0.45s cubic-bezier(0.22, 0.85, 0.28, 1),
        opacity 0.35s ease;
}

.card.is-app.hovered .card-image {
    transform: scale(1.14);
    filter: brightness(0.42);
}

.card.is-app.hovered .card-hover-layer {
    opacity: 1;
}

.card.is-app.hovered .card-hover-text {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.card.is-app .card-face {
    padding: 0;
    filter: none;
    background: #0a0a0a;
    overflow: hidden;
}

.card.is-app .card-face::before,
.card.is-app .card-face::after {
    display: none;
}

.card.is-app.hovered .card-face {
    filter: none;
    background: #0a0a0a;
}

.card-banner {
    position: relative;
    z-index: 2;
    flex: 0 0 auto;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    margin: 0;
    padding: 0.9rem 0.8rem;
    text-align: center;
    font-family: var(--familyTitles);
    font-size: 1.3rem;
    line-height: 1.2;
    letter-spacing: 0.03em;
    color: #111;
    background: linear-gradient(180deg, #f5d76e 0%, #e4c04a 45%, #d4a017 100%);
    border-radius: 0.6rem;
    border: 1px solid rgba(80, 60, 0, 0.25);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    transition: filter 0.2s ease, transform 0.2s ease;
}

.card-banner:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
}

.card-ghost {
    display: block;
    height: 1.2rem;
    width: 78%;
    border-radius: 0.4rem;
    background: linear-gradient(90deg, #505868, #3a414d 50%, #505868);
    background-size: 200% 100%;
    animation: shimmer 2.4s linear infinite;
    opacity: 0.5;
}

.card-ghost.short {
    width: 56%;
}

.card-ghost.shorter {
    width: 40%;
}

.about {
    position: relative;
    z-index: 5;
    width: min(70rem, 92%);
    flex-shrink: 0;
    margin-top: 0.5rem;
}

.about-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.2rem;
    padding: 1.3rem 2rem;
    border-radius: 1rem;
    border: 1px solid rgba(100, 140, 200, 0.22);
    background: linear-gradient(180deg, rgba(14, 28, 50, 0.8), rgba(6, 12, 22, 0.92));
    color: #e8eef8;
    font-size: 1.6rem;
    font-family: var(--familyTitles);
    cursor: pointer;
    box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.4);
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}

.about-trigger:hover,
.about-trigger.open {
    border-color: rgba(170, 24, 24, 0.5);
    box-shadow:
        0 1.2rem 3rem rgba(0, 0, 0, 0.5),
        0 0 2rem rgba(170, 24, 24, 0.18);
}

.about-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: rgba(170, 24, 24, 0.2);
    color: #ff6b6b;
    font-size: 1.3rem;
}

.chevron {
    transition: transform 0.35s ease;
    font-size: 1.2rem;
    opacity: 0.7;
}

.about-trigger.open .chevron {
    transform: rotate(180deg);
}

.about-panel {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.55s cubic-bezier(0.22, 0.9, 0.25, 1);
}

.about-panel.open {
    grid-template-rows: 1fr;
}

.about-panel-inner {
    overflow: hidden;
    min-height: 0;
    display: flex;
    gap: 3rem;
    align-items: center;
    padding: 0 2rem;
    opacity: 0;
    transform: translateY(-1rem);
    transition:
        opacity 0.4s ease 0.05s,
        transform 0.45s ease,
        padding 0.45s ease;
    background: linear-gradient(180deg, rgba(10, 18, 32, 0.95), rgba(4, 8, 14, 0.98));
    border: 1px solid rgba(100, 140, 200, 0.12);
    border-top: none;
    border-radius: 0 0 1.2rem 1.2rem;
    box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.55);
}

.about-panel.open .about-panel-inner {
    opacity: 1;
    transform: translateY(0);
    padding: 2.5rem 2rem;
}

.about-photo img {
    width: 16rem;
    max-width: 100%;
    border-radius: 1.4rem;
    box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.65);
}

.about-text {
    flex: 1;
    text-align: left;
}

.about-text h2 {
    font-family: var(--familyTitles);
    font-size: 3rem;
    color: var(--hero-red);
    margin-bottom: 1rem;
}

.about-text p {
    font-size: var(--fontsize);
    color: rgba(220, 230, 245, 0.88);
    line-height: 1.55;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

@keyframes pulseGlow {
    0%, 100% { opacity: 0.4; transform: translateX(-50%) scale(1); }
    50% { opacity: 0.65; transform: translateX(-50%) scale(1.06); }
}

@keyframes appear {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 900px) {
    .hero,
    .hero-inner {
        min-height: 70rem;
    }

    .hero-inner {
        padding: 14rem 1.5rem 3rem;
    }

    .hero-deco {
        width: min(48vw, 34rem);
        opacity: 0.85;
    }

    .dragon-block {
        width: min(32rem, 66vw);
    }

    .arc-brand-logo {
        width: 7.5rem;
    }

    .arc-brand-link {
        font-size: 1.6rem;
    }

    .about-panel-inner {
        flex-direction: column;
        text-align: center;
    }

    .about-text {
        text-align: center;
    }

    .about-text h2 {
        font-size: var(--fontsizeTitlesMobile);
    }
}

@media (max-width: 600px) {
    /* El header ya no reserva alto: el hero arranca pegado arriba */
    .hero {
        margin-top: 0;
        min-height: 64rem;
    }

    .hero-inner {
        min-height: 64rem;
        gap: 1.6rem;
        padding: 15rem 1.5rem 4rem;
    }

    .stage {
        gap: 2rem;
    }

    /* El fondo llena todo el hero, sin que el viñeteo lo coma en los bordes */
    .hero-bg,
    .hero-bg-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .hero-bg-img {
        object-fit: cover;
        object-position: center center;
        opacity: 1;
    }

    .atmosphere {
        background:
            radial-gradient(ellipse 90% 45% at 50% 35%, rgba(8, 24, 48, 0.28), transparent 70%),
            linear-gradient(180deg, rgba(2, 6, 12, 0.28) 0%, rgba(2, 6, 12, 0.42) 55%, rgba(1, 3, 8, 0.62) 100%);
    }

    .vignette {
        background: radial-gradient(ellipse at center, transparent 62%, rgba(0, 0, 0, 0.6) 100%);
        box-shadow: inset 0 0 5rem rgba(0, 8, 20, 0.6);
    }

    /* Decoración: bandas laterales angostas y acotadas en alto, para que no
       tapen el dragón ni la zona del botón de abajo */
    .hero-deco {
        width: 50vw;
    }

    /* Video: cubre todo el lado izquierdo */
    .hero-deco--left {
        top: 0;
        bottom: 0;
        height: auto;
        -webkit-mask-image: linear-gradient(to right, #000 0%, #000 22%, transparent 82%);
        mask-image: linear-gradient(to right, #000 0%, #000 22%, transparent 82%);
    }

    /* Imagen: arriba a la derecha */
    .hero-deco--right {
        top: 0;
        bottom: auto;
        height: 58%;
        -webkit-mask-image: linear-gradient(to left, #000 0%, #000 22%, transparent 82%);
        mask-image: linear-gradient(to left, #000 0%, #000 22%, transparent 82%);
    }

    .hero-deco-img,
    .hero-deco-video {
        opacity: 0.34;
    }

    .hero-deco-video {
        object-position: 35% center;
    }

    .hero-deco-img {
        object-position: 70% 12%;
    }

    .cards-row {
        gap: 2rem;
        padding-top: 0;
        min-height: 0;
    }

    .dragon-block {
        width: min(30rem, 78vw);
        gap: 0.8rem;
    }

    .dragon-dock {
        min-height: min(16rem, 44vw);
    }

    .dragon-tagline {
        font-size: clamp(1.6rem, 5vw, 2.1rem);
    }

    .arc-brand {
        gap: 0.4rem;
    }

    .arc-brand-logo {
        width: 6.5rem;
    }

    .arc-brand-link {
        font-size: 1.35rem;
    }

    .about {
        width: 100%;
    }

    .about-trigger {
        font-size: 1.4rem;
        padding: 1.1rem 1.4rem;
        gap: 0.8rem;
    }

    .about-panel-inner {
        gap: 1.6rem;
    }

    .about-panel.open .about-panel-inner {
        padding: 1.8rem 1.4rem;
    }

    .about-photo img {
        width: 12rem;
    }
}
</style>
