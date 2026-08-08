<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps<{
  /** Cuando true, el velo negro se abre y muestra la página */
  reveal?: boolean
}>()

const emit = defineEmits<{
  loaded: []
  veilGone: []
}>()

const progress = ref(0)
const loading = ref(true)

let rafId = 0
let veilTimer: ReturnType<typeof setTimeout> | null = null

const waitForWindowLoad = () =>
  new Promise<void>((resolve) => {
    if (document.readyState === 'complete') {
      resolve()
      return
    }
    window.addEventListener('load', () => resolve(), { once: true })
  })

const wait = (ms: number) => new Promise<void>((resolve) => setTimeout(resolve, ms))

watch(
  () => props.reveal,
  (open) => {
    if (!open) return
    if (veilTimer) clearTimeout(veilTimer)
    veilTimer = setTimeout(() => emit('veilGone'), 900)
  },
)

onMounted(async () => {
  document.body.style.overflow = 'hidden'

  const minDuration = 2400
  const start = performance.now()

  const tick = () => {
    const elapsed = performance.now() - start
    const t = Math.min(elapsed / minDuration, 1)
    const eased = 1 - Math.pow(1 - t, 2.4)
    progress.value = Math.min(92, eased * 92)
    if (t < 1 && loading.value) {
      rafId = requestAnimationFrame(tick)
    }
  }
  rafId = requestAnimationFrame(tick)

  await Promise.all([waitForWindowLoad(), wait(minDuration)])

  cancelAnimationFrame(rafId)
  progress.value = 100
  await wait(280)
  loading.value = false
  emit('loaded')
})

onUnmounted(() => {
  cancelAnimationFrame(rafId)
  if (veilTimer) clearTimeout(veilTimer)
  document.body.style.overflow = ''
})
</script>

<template>
  <Teleport to="body">
    <!-- Fondo negro: tapa la página. El dragón (Header) queda por encima con z-index. -->
    <div
      class="page-intro-veil"
      :class="{ open: reveal }"
      aria-hidden="true"
    ></div>

    <div
      v-if="loading"
      class="page-intro-bar-wrap"
      aria-busy="true"
      aria-live="polite"
    >
      <div class="page-intro-bar">
        <div class="page-intro-bar-track">
          <div class="page-intro-bar-fill" :style="{ width: `${progress}%` }"></div>
        </div>
        <span class="page-intro-pct">{{ Math.round(progress) }}%</span>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.page-intro-veil {
  position: fixed;
  inset: 0;
  z-index: 100000;
  background: #000;
  opacity: 1;
  pointer-events: all;
  transition: opacity 0.85s ease;
}

.page-intro-veil.open {
  opacity: 0;
  pointer-events: none;
}

/* --splash-bottom lo publica el header: borde inferior real del dragón */
.page-intro-bar-wrap {
  position: fixed;
  left: 50%;
  top: calc(var(--splash-bottom, 50vh) + 3rem);
  transform: translateX(-50%);
  z-index: 100003;
  width: min(28rem, 55vw);
}

.page-intro-bar {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
}

.page-intro-bar-track {
  width: 100%;
  height: 0.35rem;
  border-radius: 2rem;
  background: rgba(255, 255, 255, 0.12);
  overflow: hidden;
  box-shadow: inset 0 0 0.2rem rgba(0, 0, 0, 0.5);
}

.page-intro-bar-fill {
  height: 100%;
  width: 0%;
  border-radius: inherit;
  background: linear-gradient(90deg, #6a1010, var(--color-first), #ff5a5a);
  box-shadow: 0 0 1rem rgba(170, 24, 24, 0.65);
  transition: width 0.18s linear;
}

.page-intro-pct {
  font-family: var(--familyTitles), Georgia, serif;
  font-size: 1.2rem;
  letter-spacing: 0.12em;
  color: rgba(255, 255, 255, 0.55);
}

@media (max-width: 600px) {
  .page-intro-bar-wrap {
    top: calc(var(--splash-bottom, 50vh) + 2.4rem);
    width: min(24rem, 70vw);
  }
}
</style>
