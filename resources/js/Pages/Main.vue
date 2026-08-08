<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
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

/** Dragón: loading (centro) → docking (#dragon-dock) → done */
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

    <div class="site-world">
        <Header
            :intro-phase="introPhase"
            @dock-near-end="onDockNearEnd"
            @dock-done="onDockDone"
        />
        <Seccion1 :cards="props.cards" />
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
