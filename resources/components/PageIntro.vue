<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps<{
  /** Cuando true, el velo negro se abre y muestra la página */
  reveal?: boolean
  /** URLs críticas a precargar (imágenes, videos, etc.) */
  criticalAssets?: string[]
}>()

const emit = defineEmits<{
  loaded: []
  veilGone: []
}>()

const progress = ref(0)
const loading = ref(true)

let veilTimer: ReturnType<typeof setTimeout> | null = null
let cancelled = false

const wait = (ms: number) => new Promise<void>((resolve) => setTimeout(resolve, ms))

const waitForWindowLoad = () =>
  new Promise<void>((resolve) => {
    if (document.readyState === 'complete') {
      resolve()
      return
    }
    window.addEventListener('load', () => resolve(), { once: true })
  })

const waitForFonts = () =>
  document.fonts?.ready
    ? document.fonts.ready.then(() => undefined)
    : Promise.resolve()

/** Precarga una URL (img / video / fetch genérico) */
const preloadUrl = (url: string) =>
  new Promise<void>((resolve) => {
    if (!url || url.startsWith('data:')) {
      resolve()
      return
    }

    const clean = url.split('#')[0]
    const lower = clean.toLowerCase()

    const done = () => resolve()

    if (/\.(mp4|webm|ogg)(\?|$)/i.test(lower)) {
      const video = document.createElement('video')
      video.preload = 'auto'
      video.muted = true
      video.playsInline = true
      const finish = () => {
        video.onloadeddata = null
        video.oncanplaythrough = null
        video.onerror = null
        done()
      }
      video.onloadeddata = finish
      video.oncanplaythrough = finish
      video.onerror = finish
      video.src = clean
      video.load()
      // Timeout de seguridad por videos pesados / CORS
      window.setTimeout(finish, 20000)
      return
    }

    if (/\.(png|jpe?g|gif|webp|svg|avif|bmp|ico)(\?|$)/i.test(lower) || lower.includes('/images/')) {
      const img = new Image()
      img.onload = done
      img.onerror = done
      img.src = clean
      return
    }

    // CSS / JS / otros: HEAD o GET liviano
    fetch(clean, { method: 'GET', cache: 'force-cache', mode: 'no-cors' })
      .then(() => done())
      .catch(() => done())

    window.setTimeout(done, 12000)
  })

const collectDomAssets = (): string[] => {
  const urls = new Set<string>()

  document.querySelectorAll('img').forEach((img) => {
    const src = img.currentSrc || img.src
    if (src) urls.add(src)
    const srcset = img.getAttribute('srcset')
    if (srcset) {
      srcset.split(',').forEach((part) => {
        const u = part.trim().split(/\s+/)[0]
        if (u) urls.add(u)
      })
    }
  })

  document.querySelectorAll('video').forEach((video) => {
    if (video.currentSrc) urls.add(video.currentSrc)
    if (video.src) urls.add(video.src)
    video.querySelectorAll('source').forEach((source) => {
      if (source.src) urls.add(source.src)
    })
  })

  document.querySelectorAll('link[rel="stylesheet"]').forEach((link) => {
    const href = (link as HTMLLinkElement).href
    if (href) urls.add(href)
  })

  return [...urls]
}

watch(
  () => props.reveal,
  (open) => {
    if (!open) return
    if (veilTimer) clearTimeout(veilTimer)
    veilTimer = setTimeout(() => emit('veilGone'), 1400)
  },
)

onMounted(async () => {
  document.body.style.overflow = 'hidden'
  cancelled = false

  // Esperar a que Vue monte secciones e imgs en el DOM
  await nextTick()
  await nextTick()
  await wait(80)

  const assets = new Set<string>()
  ;(props.criticalAssets || []).forEach((u) => {
    if (u) assets.add(u)
  })
  collectDomAssets().forEach((u) => assets.add(u))

  const list = [...assets]
  const extras = 2 // fonts + window load
  const total = Math.max(1, list.length + extras)
  let completed = 0

  const bump = () => {
    if (cancelled) return
    completed += 1
    progress.value = Math.min(99, Math.round((completed / total) * 100))
  }

  // Arrancar progreso con lo que ya esté cacheado/listo en DOM
  document.querySelectorAll('img').forEach((img) => {
    if (img.complete && img.naturalWidth > 0) {
      // no double-count; preloadUrl will resolve fast for cached
    }
  })

  await Promise.all([
    ...list.map((url) => preloadUrl(url).finally(bump)),
    waitForFonts().finally(bump),
    waitForWindowLoad().finally(bump),
  ])

  if (cancelled) return

  // Segunda pasada: capturar assets que Vue montó tarde
  await nextTick()
  const late = collectDomAssets().filter((u) => !assets.has(u))
  if (late.length) {
    const lateTotal = late.length
    let lateDone = 0
    await Promise.all(
      late.map((url) =>
        preloadUrl(url).finally(() => {
          lateDone += 1
          // Empuja suavemente del 99 al 99 mientras cierran
          progress.value = Math.min(
            99,
            Math.round(((completed + lateDone / lateTotal) / (total + 1)) * 100),
          )
        }),
      ),
    )
  }

  if (cancelled) return

  progress.value = 100
  await wait(220)
  loading.value = false
  emit('loaded')
})

onUnmounted(() => {
  cancelled = true
  if (veilTimer) clearTimeout(veilTimer)
  document.body.style.overflow = ''
})
</script>

<template>
  <Teleport to="body">
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
  transition: opacity 1.15s cubic-bezier(0.22, 1, 0.36, 1);
}

.page-intro-veil.open {
  opacity: 0;
  pointer-events: none;
}

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
  transition: width 0.2s ease-out;
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
