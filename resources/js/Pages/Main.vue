<script setup lang="ts">
import { ref, computed, defineAsyncComponent, onMounted, onUnmounted, type Component } from 'vue'
import Header from '../../components/HeaderView.vue'
import Footer from '../../components/FooterView.vue'
import Seccion1 from '../../components/seccion-1.vue'
import Seccion5 from '../../components/seccion-5.vue'
import Seccion6 from '../../components/seccion-6.vue'
import PageIntro from '../../components/PageIntro.vue'

interface Review {
  id: number;
  name: string;
  stars: number;
  opinion: string;
}

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

const props = defineProps<{
  reviews: Review[]
  cards: AppCard[]
}>()

const activeCard = ref<AppCard | null>(null)
const isEntering = ref(false)

/** Un solo dragón en Header: loading (centro) → docking → done */
const introPhase = ref<'loading' | 'docking' | 'done'>('loading')
const introActive = ref(true)
const revealPage = ref(false)

let enterTimer: ReturnType<typeof setTimeout> | null = null

/** Carga directa: cards/{project}/{component}.vue según la BD */
const cardModules = import.meta.glob('../../components/cards/**/*.vue') as Record<
  string,
  () => Promise<{ default: Component }>
>

const activeCardView = computed(() => {
  const card = activeCard.value
  if (!card) return null

  const key = `../../components/cards/${card.project}/${card.component}.vue`
  const loader = cardModules[key]
  if (!loader) {
    console.warn(`[Main] No se encontró ${key}`)
    return null
  }

  return defineAsyncComponent(loader)
})

const goToFirstSection = () => {
  if (window.location.hash) {
    history.replaceState(null, '', window.location.pathname + window.location.search)
  }
  window.scrollTo({ top: 0, left: 0, behavior: 'auto' })
  document.documentElement.scrollTop = 0
  document.body.scrollTop = 0
}

const onEnterApp = (card: AppCard) => {
  if (isEntering.value || activeCard.value !== null || introPhase.value !== 'done') return

  isEntering.value = true
  document.body.style.overflow = 'hidden'

  enterTimer = setTimeout(() => {
    activeCard.value = card
    isEntering.value = false
  }, 900)
}

const closeApp = () => {
  activeCard.value = null
  isEntering.value = false
  document.body.style.overflow = ''
}

const onIntroLoaded = () => {
  introPhase.value = 'docking'
}

const onDockNearEnd = () => {
  revealPage.value = true
  goToFirstSection()
}

const onDockDone = () => {
  introPhase.value = 'done'
  document.body.style.overflow = ''
  goToFirstSection()
}

const onVeilGone = () => {
  introActive.value = false
  goToFirstSection()
}

onMounted(() => {
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual'
  }
  goToFirstSection()
})

onUnmounted(() => {
  if (enterTimer) clearTimeout(enterTimer)
  document.body.style.overflow = ''
})
</script>

<template>
    <PageIntro
        v-if="introActive"
        :reveal="revealPage"
        @loaded="onIntroLoaded"
        @veil-gone="onVeilGone"
    />

    <div
        class="site-world"
        :class="{ 'is-shrinking': isEntering }"
        v-show="activeCard === null"
    >
        <Header
            :intro-phase="introPhase"
            @dock-near-end="onDockNearEnd"
            @dock-done="onDockDone"
        />
        <Seccion1 :cards="props.cards" @enter-app="onEnterApp"></Seccion1>
        <Seccion5 :reviews="props.reviews"></Seccion5>
        <Seccion6></Seccion6>
        <Footer></Footer>
    </div>

    <Teleport to="body">
        <div v-if="isEntering" class="page-enter" aria-hidden="true">
            <div class="page-enter-card">
                <div class="page-enter-face">
                    <span class="page-enter-ghost"></span>
                    <span class="page-enter-ghost short"></span>
                    <span class="page-enter-ghost shorter"></span>
                </div>
            </div>
        </div>
    </Teleport>

    <component
        :is="activeCardView"
        v-if="activeCard && activeCardView"
        :card="activeCard"
        @close="closeApp"
    />
</template>

<style scoped>
.site-world {
    transform-origin: 50% 32%;
    transition:
        transform 0.9s cubic-bezier(0.22, 0.7, 0.25, 1),
        opacity 0.9s ease,
        filter 0.9s ease;
}

.site-world.is-shrinking {
    transform: scale(0.38);
    opacity: 0.2;
    filter: blur(4px);
    pointer-events: none;
    will-change: transform, opacity, filter;
}

.page-enter {
    position: fixed;
    inset: 0;
    z-index: 50000;
    display: grid;
    place-items: center;
    pointer-events: none;
    animation: pageEnterBg 0.9s ease forwards;
}

.page-enter-card {
    width: min(22rem, 55vw);
    height: min(31rem, 70vh);
    border-radius: 1.6rem;
    transform-origin: center center;
    animation: pageEnterCard 0.9s cubic-bezier(0.22, 0.7, 0.25, 1) forwards;
    box-shadow: 0 2rem 5rem rgba(0, 0, 0, 0.75);
}

.page-enter-face {
    width: 100%;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(165deg, #545d6c 0%, #323944 55%, #222831 100%);
    border: 1px solid rgba(140, 185, 255, 0.45);
    padding: 2rem;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 1.2rem;
}

.page-enter-ghost {
    display: block;
    height: 1.2rem;
    width: 78%;
    border-radius: 0.4rem;
    background: #505868;
    opacity: 0.5;
}

.page-enter-ghost.short {
    width: 56%;
}

.page-enter-ghost.shorter {
    width: 40%;
}

@keyframes pageEnterBg {
    0% {
        background: rgba(2, 6, 12, 0);
    }
    55% {
        background: rgba(2, 6, 12, 0.25);
    }
    100% {
        background: #02060c;
    }
}

@keyframes pageEnterCard {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    70% {
        opacity: 1;
    }
    100% {
        transform: scale(9);
        opacity: 0;
    }
}
</style>
