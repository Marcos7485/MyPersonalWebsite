<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

export interface AppCard {
    id: number
    project: string
    card: number
    image: string
    component: string
    active: boolean
    imageUrl: string
    projectIconUrl: string
}

type CardSlot =
    | { slot: number; type: 'empty'; id: string }
    | (AppCard & { slot: number; type: 'app' })

const props = defineProps<{
    cards: AppCard[]
}>()

const imageStore = useImageStore();
const languageStore = useLanguageStore();

/** 3 posiciones; `card` de la DB = slot 1..3 */
const apps = computed<CardSlot[]>(() =>
    [1, 2, 3].map((slot) => {
        const row = props.cards.find((c) => Number(c.card) === slot)
        if (!row) {
            return { slot, type: 'empty' as const, id: `empty-${slot}` }
        }
        return { ...row, slot, type: 'app' as const }
    }),
)

const arcBrand = computed(() => {
    const first = props.cards[0]
    if (!first) return null
    return {
        src: first.projectIconUrl,
        alt: first.project,
    }
})

const emit = defineEmits<{
    'enter-app': [card: AppCard];
}>();

const aboutOpen = ref(false);
const hoveredIndex = ref<number | null>(null);
const selectedIndex = ref<number | null>(null);

const selectedApp = computed(() => {
    if (selectedIndex.value === null) return null
    return apps.value[selectedIndex.value]
});

const onCardEnter = (index: number) => {
    if (selectedIndex.value !== null) return;
    hoveredIndex.value = index;
};

const onCardLeave = (index: number) => {
    if (hoveredIndex.value === index) {
        hoveredIndex.value = null;
    }
};

const selectCard = (index: number) => {
    if (selectedIndex.value === index) return;
    selectedIndex.value = index;
    hoveredIndex.value = null;
};

const deselectCard = () => {
    selectedIndex.value = null;
};

const openAppDetail = () => {
    const app = selectedApp.value
    if (!app || app.type !== 'app') return
    emit('enter-app', app);
};

const toggleAbout = () => {
    aboutOpen.value = !aboutOpen.value;
};

const uiLabels = computed(() => ({
    moreInfo: languageStore.t('cards.moreInfo'),
    back: languageStore.t('cards.back'),
    aboutButton: languageStore.t('about.button'),
    aboutTitle: languageStore.t('about.title'),
    aboutBody: languageStore.t('about.body'),
}));

onMounted(() => {
    imageStore.fetchImagePath();
});
</script>

<template>
    <section id="Home" class="hero">
        <div class="atmosphere" aria-hidden="true">
            <div class="glow glow-a"></div>
            <div class="glow glow-b"></div>
            <div class="vignette"></div>
        </div>

        <div class="hero-inner">
            <div class="stage" :class="{ 'has-selection': selectedIndex !== null }">
                <div class="cards-row">
                    <article
                        v-for="(app, index) in apps"
                        :key="app.id"
                        class="card"
                        :class="{
                            'is-app': app.type === 'app',
                            'is-empty': app.type === 'empty',
                            hovered: hoveredIndex === index && selectedIndex === null,
                            dimmed: selectedIndex !== null,
                        }"
                        @pointerenter="onCardEnter(index)"
                        @pointerleave="onCardLeave(index)"
                        @click="selectCard(index)"
                    >
                        <div class="card-face">
                            <img
                                v-if="app.type === 'app'"
                                :src="app.imageUrl"
                                :alt="app.project"
                                class="card-image"
                            >

                            <div v-else class="card-empty">
                                <span class="card-ghost"></span>
                                <span class="card-ghost short"></span>
                                <span class="card-ghost shorter"></span>
                            </div>
                        </div>
                    </article>
                </div>

                <div v-if="arcBrand" class="arc-brand">
                    <img
                        :src="arcBrand.src"
                        :alt="arcBrand.alt"
                        class="arc-brand-logo"
                    >
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

    <Teleport to="body">
        <Transition name="card-modal">
            <div v-if="selectedApp" class="card-modal-root">
                <div
                    class="card-modal-backdrop"
                    @click="deselectCard"
                ></div>
                <article
                    class="card selected is-app"
                    :class="{ 'is-empty': selectedApp.type === 'empty' }"
                >
                    <div class="card-face">
                        <button
                            type="button"
                            class="card-back"
                            @click.stop="deselectCard"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>{{ uiLabels.back }}</span>
                        </button>

                        <img
                            v-if="selectedApp.type === 'app'"
                            :src="selectedApp.imageUrl"
                            :alt="selectedApp.project"
                            class="card-image"
                        >

                        <div v-else class="card-empty">
                            <span class="card-ghost"></span>
                            <span class="card-ghost short"></span>
                            <span class="card-ghost shorter"></span>
                        </div>

                        <button
                            v-if="selectedApp.type === 'app'"
                            type="button"
                            class="card-banner"
                            @click.stop="openAppDetail"
                        >
                            {{ uiLabels.moreInfo }}
                        </button>
                    </div>
                </article>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.hero {
    --hero-red: var(--color-first);

    position: relative;
    width: 100%;
    min-height: 64rem;
    margin-top: -5rem;
    overflow: hidden;
    background:
        radial-gradient(ellipse 70% 50% at 50% 45%, rgba(14, 40, 78, 0.5), transparent 65%),
        linear-gradient(180deg, #050b14 0%, #02060c 55%, #010308 100%);
    color: white;
    animation: appear 1.2s ease forwards;
}

.atmosphere {
    position: absolute;
    inset: 0;
    pointer-events: none;
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
    align-items: flex-start;
    gap: 4rem;
    width: 100%;
    z-index: 2;
    padding-top: 1.5rem;
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

.arc-brand-logo {
    width: 9rem;
    height: auto;
    display: block;
    filter: drop-shadow(0 0.35rem 0.8rem rgba(0, 0, 0, 0.55));
    opacity: 0.92;
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
    width: 18rem;
    height: 26rem;
    flex: 0 0 auto;
    box-sizing: border-box;
    cursor: pointer;
    border-radius: 1.6rem;
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
    width: min(92vw, 110rem);
    height: min(78vh, 64rem);
    border-radius: 1.4rem;
    z-index: 2;
    pointer-events: auto;
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
    position: relative;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    border-radius: 1.6rem;
    background: linear-gradient(165deg, #424956 0%, #262b34 55%, #1a1e26 100%);
    border: 1px solid rgba(180, 190, 205, 0.16);
    padding: 1.8rem;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    gap: 1rem;
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
    filter: brightness(1.12);
    border-color: rgba(140, 185, 255, 0.55);
    background: linear-gradient(165deg, #545d6c 0%, #323944 55%, #222831 100%);
    box-shadow:
        0 2.8rem 5rem rgba(0, 0, 0, 0.8),
        0 0 3.5rem rgba(40, 110, 200, 0.45),
        0 0 1.5rem rgba(170, 24, 24, 0.2);
    display: grid;
    grid-template-columns: auto 1fr auto;
    grid-template-rows: auto 1fr;
    gap: 1.2rem 1.4rem;
    padding: 1.6rem;
    border-radius: 1.4rem;
    align-items: center;
}

.card.selected .card-back {
    grid-column: 1;
    grid-row: 1;
    width: auto;
    justify-self: start;
}

.card.selected .card-banner {
    grid-column: 3;
    grid-row: 1;
    width: auto;
    min-width: 16rem;
    justify-self: end;
    margin: 0;
}

.card.selected .card-image,
.card.selected .card-empty {
    grid-column: 1 / -1;
    grid-row: 2;
    width: 100%;
    height: 100%;
    min-height: 0;
    align-self: stretch;
}

.card.selected .card-image {
    flex: unset;
    max-height: none;
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
    filter: brightness(1.1);
    border-color: rgba(140, 185, 255, 0.5);
    background: linear-gradient(165deg, #505868 0%, #2f3642 55%, #20252e 100%);
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
    min-height: 0;
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    flex: 1 1 auto;
    justify-content: center;
}

.card-image {
    position: relative;
    z-index: 1;
    width: 100%;
    min-height: 0;
    flex: 1 1 auto;
    object-fit: contain;
    object-position: center;
    border-radius: 0.9rem;
    display: block;
}

.card.is-app .card-face {
    padding: 1rem;
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

    .cards-row {
        gap: 2.8rem;
    }

    .cards-row .card:nth-child(1),
    .cards-row .card:nth-child(3) {
        transform: rotate(-8deg) translateY(1.6rem);
    }

    .cards-row .card:nth-child(3) {
        transform: rotate(8deg) translateY(1.6rem);
    }

    .card {
        width: 15rem;
        height: 22rem;
    }

    .card.selected {
        width: min(94vw, 72rem);
        height: min(72vh, 48rem);
    }

    .arc-brand-logo {
        width: 7.5rem;
    }

    .arc-brand-link {
        font-size: 1.6rem;
    }

    .card-back,
    .card-banner {
        font-size: 1.15rem;
        padding: 0.65rem 0.75rem;
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
    .hero {
        margin-top: -3rem;
        min-height: 78rem;
    }

    .hero-inner {
        min-height: 78rem;
        padding-top: 14.5rem;
    }

    .cards-row {
        gap: 2rem;
    }

    .cards-row .card:nth-child(1) {
        transform: rotate(-6deg) translateY(1.2rem);
    }

    .cards-row .card:nth-child(3) {
        transform: rotate(6deg) translateY(1.2rem);
    }

    .card {
        width: 13rem;
        height: 19rem;
    }

    .card.selected {
        width: min(96vw, 42rem);
        height: min(78vh, 58rem);
    }

    .card.selected .card-face {
        grid-template-columns: 1fr;
        grid-template-rows: auto auto 1fr;
        gap: 1rem;
    }

    .card.selected .card-back {
        grid-column: 1;
        grid-row: 1;
        width: 100%;
    }

    .card.selected .card-banner {
        grid-column: 1;
        grid-row: 2;
        width: 100%;
        min-width: 0;
        justify-self: stretch;
    }

    .card.selected .card-image,
    .card.selected .card-empty {
        grid-column: 1;
        grid-row: 3;
    }

    .arc-brand-logo {
        width: 6.5rem;
    }

    .arc-brand-link {
        font-size: 1.35rem;
    }

    .card-back,
    .card-banner {
        font-size: 1.05rem;
        padding: 0.55rem 0.65rem;
    }
}
</style>
