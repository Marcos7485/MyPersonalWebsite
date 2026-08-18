<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import Header from '../../components/HeaderView.vue'
import Footer from '../../components/FooterView.vue'
import Seccion1 from '../../components/seccion-1.vue'
import Seccion4 from '../../components/seccion-4.vue'
import Seccion41 from '../../components/seccion-4-1.vue'
import Seccion5 from '../../components/seccion-5.vue'
import Seccion6 from '../../components/seccion-6.vue'
import PageIntro from '../../components/PageIntro.vue'
import { useImageStore } from '../../store/imageStore'

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
  hover_text: string | null
  descripcion: string | null
  component: string | null
  active: boolean
  imageUrl: string
  projectIconUrl: string
}

const props = defineProps<{
  reviews: Review[]
  cards: AppCard[]
}>()

const imageStore = useImageStore()
imageStore.fetchImagePath()

/** Assets que deben estar listos antes de salir del intro */
const criticalAssets = computed(() => {
  const base = imageStore.imagePath || '/images'
  const list = [
    `${base}/drs.webp`,
    `${base}/letras.webp`,
    `${base}/seccion-1/fondo.png`,
    `${base}/seccion-1/video1.mp4`,
    `${base}/seccion-1/1.png`,
    `${base}/seccion-1/picture.webp`,
    `${base}/iqathletic/icon.png`,
    `${base}/seccion-4/1.jpg`,
    `${base}/seccion-4/2.jpg`,
    `${base}/seccion-4/3.jpg`,
    `${base}/seccion-4/4.jpg`,
    `${base}/cliente/video-cel.mp4`,
    `${base}/cliente/video.mp4`,
    `${base}/seccion-5/figura1.svg`,
    `${base}/seccion-5/figura2.svg`,
    `${base}/seccion-5/figura3.svg`,
    `${base}/seccion-5/figura4.svg`,
    `${base}/seccion-6/fondo.webp`,
    `${base}/seccion-6/instagram.svg`,
    `${base}/seccion-6/email.svg`,
    `${base}/seccion-6/1.svg`,
    `${base}/seccion-6/2.svg`,
    `${base}/seccion-6/3.svg`,
    `${base}/seccion-6/4.svg`,
    `${base}/seccion-6/5.svg`,
    `${base}/seccion-6/6.svg`,
    `${base}/seccion-6/7.svg`,
  ]

  props.cards.forEach((card) => {
    if (card.imageUrl) list.push(card.imageUrl)
    if (card.projectIconUrl) list.push(card.projectIconUrl)
  })

  return [...new Set(list)]
})

/** Dragón: loading (centro) → docking (vuelo al dock) → done */
const introPhase = ref<'loading' | 'docking' | 'done'>('loading')
const introActive = ref(true)
const revealPage = ref(false)

const goToFirstSection = () => {
  if (window.location.hash) {
    history.replaceState(null, '', window.location.pathname + window.location.search)
  }
  window.scrollTo({ top: 0, left: 0, behavior: 'auto' })
  document.documentElement.scrollTop = 0
  document.body.scrollTop = 0
}

const onIntroLoaded = () => {
  introPhase.value = 'docking'
}

/** Mitad del vuelo: abrir velo para ver el dragón acomodarse sobre la página */
const onDockNearEnd = () => {
  revealPage.value = true
  goToFirstSection()
}

/** Fin del vuelo: teleporta al dock (Header hace FLIP) */
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
  document.body.style.overflow = ''
})
</script>

<template>
    <PageIntro
        v-if="introActive"
        :reveal="revealPage"
        :critical-assets="criticalAssets"
        @loaded="onIntroLoaded"
        @veil-gone="onVeilGone"
    />

    <div class="site-world">
        <Header
            :intro-phase="introPhase"
            @dock-near-end="onDockNearEnd"
            @dock-done="onDockDone"
        />
        <Seccion1 :cards="props.cards" />
        <Seccion4 />
        <Seccion41 />
        <Seccion5 :reviews="props.reviews"></Seccion5>
        <Seccion6></Seccion6>
        <Footer></Footer>
    </div>
</template>

<style scoped>
.site-world {
    transform-origin: 50% 32%;
}
</style>
