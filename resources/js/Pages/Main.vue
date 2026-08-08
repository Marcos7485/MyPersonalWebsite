<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '../../components/HeaderView.vue'
import Footer from '../../components/FooterView.vue'
import Seccion1 from '../../components/seccion-1.vue'
import Seccion5 from '../../components/seccion-5.vue'
import Seccion6 from '../../components/seccion-6.vue'
import AppDetalle from '../../components/AppDetalle.vue'
import PageIntro from '../../components/PageIntro.vue'

interface Review {
  id: number;
  name: string;
  stars: number;
  opinion: string;
}

const props = defineProps<{ reviews: Review[] }>()

const activeAppId = ref<number | null>(null)
const isEntering = ref(false)

/** Un solo dragón en Header: loading (centro) → docking → done */
const introPhase = ref<'loading' | 'docking' | 'done'>('loading')
const introActive = ref(true)
const revealPage = ref(false)

let enterTimer: ReturnType<typeof setTimeout> | null = null

const goToFirstSection = () => {
  if (window.location.hash) {
    history.replaceState(null, '', window.location.pathname + window.location.search)
  }
  window.scrollTo({ top: 0, left: 0, behavior: 'auto' })
  document.documentElement.scrollTop = 0
  document.body.scrollTop = 0
}

const onEnterApp = (appId: number) => {
  if (isEntering.value || activeAppId.value !== null || introPhase.value !== 'done') return

  isEntering.value = true
  document.body.style.overflow = 'hidden'

  enterTimer = setTimeout(() => {
    activeAppId.value = appId
    isEntering.value = false
  }, 900)
}

const closeApp = () => {
  activeAppId.value = null
  isEntering.value = false
  document.body.style.overflow = ''
}

const onIntroLoaded = () => {
  introPhase.value = 'docking'
}

const onDockNearEnd = () => {
  // Solo revelar el velo; el overflow se restaura al terminar el dock
  // para no mover el layout (scrollbar) a mitad de la animación
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
        v-show="activeAppId === null"
    >
        <Header
            :intro-phase="introPhase"
            @dock-near-end="onDockNearEnd"
            @dock-done="onDockDone"
        />
        <Seccion1 @enter-app="onEnterApp"></Seccion1>
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

    <AppDetalle v-if="activeAppId !== null" @close="closeApp" />
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
