<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { useImageStore } from '../store/imageStore';
import LanguagesSelector from './LanguagesSelector.vue';

const props = withDefaults(defineProps<{
  /** loading: splash | docking: vuelo al dock | done: quieto en #dragon-dock */
  introPhase?: 'loading' | 'docking' | 'done'
}>(), {
  introPhase: 'done',
})

const emit = defineEmits<{
  'dock-near-end': []
  'dock-done': []
}>()

const imageStore = useImageStore();
imageStore.fetchImagePath();

const isMobile = ref(window.innerWidth <= 600);
const isReady = ref(false);
/** Chrome (letras + idioma) ya en su sitio, sin animación de entrada que mueva el layout */
const chromeSettled = ref(false);
const logoRef = ref<HTMLElement | null>(null);
const logoAnchorRef = ref<HTMLElement | null>(null);
const logoSettling = ref(false);

/** Un solo dragón: body en intro, #dragon-dock al terminar */
const logoTeleportTarget = computed(() =>
  props.introPhase === 'done' ? '#dragon-dock' : 'body',
)

/** Duraciones: vuelo visible + settle FLIP */
const DOCK_MS = 1100
const SETTLE_MS = 480
/** Revelar página cuando el dragón ya va a mitad de camino */
const REVEAL_AT = 0.28

let nearTimer: ReturnType<typeof setTimeout> | null = null
let doneTimer: ReturnType<typeof setTimeout> | null = null

const wait = (ms: number) => new Promise<void>((resolve) => setTimeout(resolve, ms))

const updateLayout = () => {
  isMobile.value = window.innerWidth <= 600;
  if (props.introPhase === 'loading') placeSplash()
};

const publishSplashBottom = (bottom: number) => {
  document.documentElement.style.setProperty('--splash-bottom', `${bottom}px`)
};

const clearLogoInline = () => {
  const el = logoRef.value
  if (!el) return
  el.style.position = ''
  el.style.left = ''
  el.style.top = ''
  el.style.width = ''
  el.style.height = ''
  el.style.right = ''
  el.style.margin = ''
  el.style.zIndex = ''
  el.style.transform = ''
  el.style.transition = ''
  el.style.transformOrigin = ''
  el.style.opacity = ''
}

const getDockAnchor = () => document.getElementById('dragon-dock') || logoAnchorRef.value

const getDockTarget = (anchor: HTMLElement) => {
  const rect = anchor.getBoundingClientRect()
  return {
    left: rect.left,
    top: rect.top,
    width: rect.width,
    height: rect.height,
    centerX: rect.left + rect.width / 2,
    centerY: rect.top + rect.height / 2,
  }
}

/**
 * Splash: siempre grande y centrado en pantalla (viaje claro hacia el dock).
 */
const placeSplash = async () => {
  await nextTick()
  await nextTick()

  const el = logoRef.value
  if (!el) return

  const img = el.querySelector('img') as HTMLImageElement | null
  if (img && !img.complete) {
    await new Promise<void>((resolve) => {
      img.onload = () => resolve()
      img.onerror = () => resolve()
    })
  }

  const splashW = Math.min(
    window.innerWidth * (isMobile.value ? 0.7 : 0.42),
    isMobile.value ? 320 : 440,
  )

  logoSettling.value = false

  el.style.transition = 'none'
  el.style.position = 'fixed'
  el.style.left = `${window.innerWidth / 2}px`
  el.style.top = `${window.innerHeight * 0.42}px`
  el.style.width = `${splashW}px`
  el.style.height = 'auto'
  el.style.right = 'auto'
  el.style.margin = '0'
  el.style.zIndex = '100002'
  el.style.opacity = '1'
  el.style.transform = 'translate(-50%, -50%)'
  el.style.transformOrigin = 'center center'

  await nextTick()
  publishSplashBottom(el.getBoundingClientRect().bottom)
}

const waitForImage = (img: HTMLImageElement | null) =>
  new Promise<void>((resolve) => {
    if (!img) {
      resolve()
      return
    }
    if (img.complete && img.naturalWidth > 0) {
      resolve()
      return
    }
    const done = () => resolve()
    img.addEventListener('load', done, { once: true })
    img.addEventListener('error', done, { once: true })
  })

/**
 * Deja letras + idioma ya en su lugar (bajo el velo) antes de medir el dock
 * y volar el dragón. Así no “aparecen después” al abrir la página.
 */
const prepareChrome = async () => {
  isReady.value = true
  chromeSettled.value = true
  await nextTick()
  await nextTick()

  const letterImgs = document.querySelectorAll<HTMLImageElement>(
    '.letters .brand-letters img, .lettersMobile .brand-letters img',
  )
  await Promise.all([...letterImgs].map((img) => waitForImage(img)))
  await nextTick()
}

/**
 * Vuelo suave del centro → #dragon-dock, mientras se revela la página.
 * El dragón permanece fixed y visible todo el tiempo.
 */
const playDock = async () => {
  await nextTick()
  const el = logoRef.value

  // Chrome listo ANTES de revelar / medir (evita reacomodo al aparecer letras)
  goScrollTop()
  await prepareChrome()

  const anchor = getDockAnchor()
  if (!el || !anchor) {
    emit('dock-near-end')
    emit('dock-done')
    return
  }

  await nextTick()
  const to = getDockTarget(anchor)
  const from = el.getBoundingClientRect()

  // Anclar por centro para no “saltar” al quitar translate
  el.style.transition = 'none'
  el.style.position = 'fixed'
  el.style.left = `${from.left + from.width / 2}px`
  el.style.top = `${from.top + from.height / 2}px`
  el.style.width = `${from.width}px`
  el.style.right = 'auto'
  el.style.margin = '0'
  el.style.zIndex = '100002'
  el.style.opacity = '1'
  el.style.transform = 'translate(-50%, -50%)'
  el.style.transformOrigin = 'center center'

  void el.offsetWidth

  const ease = 'cubic-bezier(0.22, 1, 0.36, 1)'
  el.style.transition = [
    `left ${DOCK_MS}ms ${ease}`,
    `top ${DOCK_MS}ms ${ease}`,
    `width ${DOCK_MS}ms ${ease}`,
  ].join(', ')

  el.style.left = `${to.centerX}px`
  el.style.top = `${to.centerY}px`
  el.style.width = `${to.width}px`

  if (nearTimer) clearTimeout(nearTimer)
  if (doneTimer) clearTimeout(doneTimer)

  // Abrir velo mientras el dragón vuela (chrome ya está en su sitio)
  nearTimer = setTimeout(() => {
    emit('dock-near-end')
  }, DOCK_MS * REVEAL_AT)

  doneTimer = setTimeout(() => finishDock(), DOCK_MS + 40)
}

const goScrollTop = () => {
  window.scrollTo({ top: 0, left: 0, behavior: 'auto' })
  document.documentElement.scrollTop = 0
  document.body.scrollTop = 0
}

/**
 * Entrega al DOM del dock con FLIP (sin blink) para que quede estacionado.
 */
const finishDock = async () => {
  const el = logoRef.value
  if (!el) {
    isReady.value = true
    chromeSettled.value = true
    emit('dock-done')
    return
  }

  // Recalcular destino por si el velo/scrollbar movió el layout
  const anchor = getDockAnchor()
  if (anchor) {
    const to = getDockTarget(anchor)
    el.style.transition = `left 180ms ease, top 180ms ease, width 180ms ease`
    el.style.left = `${to.centerX}px`
    el.style.top = `${to.centerY}px`
    el.style.width = `${to.width}px`
    await wait(190)
  }

  const before = el.getBoundingClientRect()

  isReady.value = true
  emit('dock-done') // Teleport → #dragon-dock

  await nextTick()
  await nextTick()

  clearLogoInline()
  await nextTick()

  const afterEl = logoRef.value
  if (!afterEl) return

  const after = afterEl.getBoundingClientRect()
  if (after.width < 2 || after.height < 2) return

  const dx = before.left - after.left
  const dy = before.top - after.top
  const sx = before.width / after.width
  const sy = before.height / after.height

  const needsFlip =
    Math.abs(dx) > 0.8 ||
    Math.abs(dy) > 0.8 ||
    Math.abs(sx - 1) > 0.015 ||
    Math.abs(sy - 1) > 0.015

  if (!needsFlip) return

  logoSettling.value = true
  afterEl.style.transition = 'none'
  afterEl.style.transformOrigin = 'top left'
  afterEl.style.transform = `translate(${dx}px, ${dy}px) scale(${sx}, ${sy})`
  void afterEl.offsetWidth

  afterEl.style.transition = `transform ${SETTLE_MS}ms cubic-bezier(0.22, 1, 0.36, 1)`
  afterEl.style.transform = 'translate(0, 0) scale(1, 1)'

  await wait(SETTLE_MS + 30)
  afterEl.style.transition = ''
  afterEl.style.transform = ''
  afterEl.style.transformOrigin = ''
  logoSettling.value = false
}

watch(
  () => props.introPhase,
  async (phase, prev) => {
    if (phase === 'loading') {
      await placeSplash()
    } else if (phase === 'docking' && prev === 'loading') {
      await playDock()
    } else if (phase === 'done' && prev !== 'docking') {
      clearLogoInline()
      logoSettling.value = false
      isReady.value = true
      chromeSettled.value = true
    }
  },
)

onMounted(async () => {
  window.addEventListener('resize', updateLayout);
  if (props.introPhase === 'loading') {
    await placeSplash()
  } else if (props.introPhase === 'done') {
    isReady.value = true
    chromeSettled.value = true
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', updateLayout);
  if (nearTimer) clearTimeout(nearTimer)
  if (doneTimer) clearTimeout(doneTimer)
});
</script>

<template>
  <div v-if="!isMobile" class="menuTop" :class="{ ready: isReady, settled: chromeSettled }">
    <div class="letters">
      <div class="brand-letters">
        <img :src="`${imageStore.imagePath}/letras.webp`" alt="DRS">
        <span class="brand-shine-clip" aria-hidden="true">
          <span class="brand-shine"></span>
        </span>
      </div>
    </div>

    <div class="header-lang" :class="{ ready: isReady, settled: chromeSettled }">
      <LanguagesSelector />
    </div>

    <div ref="logoAnchorRef" class="logo-anchor" aria-hidden="true"></div>
    <Teleport :to="logoTeleportTarget">
      <div
        ref="logoRef"
        class="brand-logo"
        :class="{
          splash: introPhase === 'loading',
          docking: introPhase === 'docking',
          intro: introPhase !== 'done',
          parked: introPhase === 'done',
          settling: logoSettling,
        }"
      >
        <img :src="`${imageStore.imagePath || '/images'}/drs.webp`" alt="Logo DRS">
      </div>
    </Teleport>
  </div>

  <div v-if="isMobile" class="mobile-wrap" :class="{ ready: isReady, settled: chromeSettled }">
    <div class="menu">
      <div class="lettersMobile">
        <div class="brand-letters">
          <img :src="`${imageStore.imagePath}/letras.webp`" alt="DRS">
          <span class="brand-shine-clip" aria-hidden="true">
            <span class="brand-shine"></span>
          </span>
        </div>
      </div>

      <div class="header-lang header-lang--mobile" :class="{ ready: isReady, settled: chromeSettled }">
        <LanguagesSelector />
      </div>

      <div ref="logoAnchorRef" class="logo-anchor logo-anchor--mobile" aria-hidden="true"></div>
      <Teleport :to="logoTeleportTarget">
        <div
          ref="logoRef"
          class="brand-logo brand-logo--mobile"
          :class="{
            splash: introPhase === 'loading',
            docking: introPhase === 'docking',
            intro: introPhase !== 'done',
            parked: introPhase === 'done',
            settling: logoSettling,
          }"
        >
          <img :src="`${imageStore.imagePath || '/images'}/drs.webp`" alt="Logo DRS">
        </div>
      </Teleport>
    </div>
  </div>
</template>


<style scoped>
/* ========== Brand keyframes (elaboradas) ========== */
@keyframes lettersEntrance {
  0% {
    opacity: 0;
    transform: translateX(-4rem) scale(0.82) skewX(-8deg);
    filter: blur(10px) brightness(2.2) saturate(0.2);
  }
  45% {
    opacity: 1;
    transform: translateX(0.55rem) scale(1.06) skewX(1.5deg);
    filter: blur(0) brightness(1.35) saturate(1.15);
  }
  70% {
    transform: translateX(-0.2rem) scale(0.98) skewX(-0.5deg);
    filter: blur(0) brightness(1.05) saturate(1);
  }
  100% {
    opacity: 1;
    transform: translateX(0) scale(1) skewX(0);
    filter: blur(0) brightness(1) saturate(1);
  }
}

@keyframes lettersBreathe {
  0%, 100% {
    transform: scale(1) translateY(0);
    filter:
      drop-shadow(0 0.15rem 0.4rem rgba(0, 0, 0, 0.35))
      drop-shadow(0 0 0 rgba(170, 24, 24, 0));
  }
  50% {
    transform: scale(1.025) translateY(-0.12rem);
    filter:
      drop-shadow(0 0.3rem 0.7rem rgba(0, 0, 0, 0.4))
      drop-shadow(0 0 0.85rem rgba(170, 24, 24, 0.45));
  }
}

@keyframes shineSweep {
  0% {
    transform: translateX(-120%) skewX(-18deg);
    opacity: 0;
  }
  12% { opacity: 0.75; }
  35% {
    transform: translateX(220%) skewX(-18deg);
    opacity: 0;
  }
  100% {
    transform: translateX(220%) skewX(-18deg);
    opacity: 0;
  }
}

@keyframes introPulse {
  0%, 100% {
    filter: brightness(1) drop-shadow(0 0 0.8rem rgba(170, 24, 24, 0.2));
    transform: scale(1);
  }
  50% {
    filter: brightness(1.12) drop-shadow(0 0 1.6rem rgba(170, 24, 24, 0.45));
    transform: scale(1.015);
  }
}

/* Latido constante del dragón en su lugar */
@keyframes dragonHeartbeat {
  0%, 100% {
    transform: scale(1);
    filter: drop-shadow(0 0.35rem 1rem rgba(0, 0, 0, 0.55));
  }
  12% {
    transform: scale(1.008);
    filter:
      drop-shadow(0 0.35rem 1rem rgba(0, 0, 0, 0.55))
      drop-shadow(0 0 0.4rem rgba(170, 24, 24, 0.1));
  }
  24% {
    transform: scale(1);
    filter: drop-shadow(0 0.35rem 1rem rgba(0, 0, 0, 0.55));
  }
  36% {
    transform: scale(1.012);
    filter:
      drop-shadow(0 0.35rem 1rem rgba(0, 0, 0, 0.55))
      drop-shadow(0 0 0.5rem rgba(170, 24, 24, 0.12));
  }
  52%, 100% {
    transform: scale(1);
    filter: drop-shadow(0 0.35rem 1rem rgba(0, 0, 0, 0.55));
  }
}

@keyframes navItemIn {
  0% {
    opacity: 0;
    transform: translateY(-0.8rem);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ========== Mobile ========== */
.mobile-wrap {
  position: relative;
  z-index: 1000;
  overflow: visible;
}

/* Sin menú: no reserva alto, sus hijos son absolutos sobre el hero */
.menu {
  position: relative;
  top: 0;
  width: 100%;
  height: 0;
  z-index: 1000;
  background: transparent;
  overflow: visible;
}

/* Posición: superior izquierda sobre el hero */
.lettersMobile .brand-letters {
  position: absolute;
  left: 9%;
  top: 8.5rem;
  width: 22rem;
  z-index: 50;
}

.lettersMobile .brand-letters img {
  width: 100%;
  display: block;
  opacity: 0;
}

/* ========== Desktop ========== */
.menuTop {
  display: flex;
  flex-direction: row;
  top: 0;
  width: 100%;
  height: 5rem;
  z-index: 1000;
  position: relative;
}

.letters {
  position: absolute;
  background: transparent;
  text-align: left;
  top: 0;
  left: 0;
  width: 100%;
  height: 5rem;
  border-radius: 0;
  box-shadow: none;
  z-index: 200;
  overflow: visible;
  pointer-events: none;
}

/* Letras: superior izquierda, flotando sobre el componente 1 */
.letters .brand-letters {
  position: absolute;
  left: 6%;
  top: 7.2rem;
  width: 24rem;
  margin-left: 0;
  display: block;
  pointer-events: auto;
}

.letters .brand-letters img {
  width: 100%;
  display: block;
  opacity: 0;
}

.brand-letters {
  position: relative;
  will-change: transform, filter;
}

/* Brillo solo en la zona central de las letras */
.brand-shine-clip {
  position: absolute;
  left: 4%;
  right: 7%;
  top: 2%;
  bottom: 2%;
  overflow: hidden;
  pointer-events: none;
}

.brand-shine {
  position: absolute;
  inset: -15% auto -15% 0;
  width: 45%;
  background: linear-gradient(
    100deg,
    transparent 0%,
    rgba(255, 255, 255, 0.08) 35%,
    rgba(255, 220, 220, 0.45) 50%,
    rgba(255, 255, 255, 0.08) 65%,
    transparent 100%
  );
  pointer-events: none;
  opacity: 0;
  mix-blend-mode: screen;
}

/* Selector de idioma: esquina superior derecha */
.header-lang {
  position: absolute;
  top: 1.4rem;
  right: 2.4rem;
  z-index: 300;
  opacity: 0;
  transform: translateY(-0.4rem);
  pointer-events: none;
}

.header-lang.ready {
  pointer-events: auto;
  animation: navItemIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
  animation-delay: 0.55s;
}

.header-lang--mobile {
  top: 1.6rem;
  right: 1.6rem;
}

/* Ancla: reserva el espacio del logo en el header */
.logo-anchor {
  position: absolute;
  right: 2rem;
  top: 1rem;
  width: 25rem;
  height: 6rem;
  pointer-events: none;
}

.logo-anchor--mobile {
  position: absolute;
  right: 2rem;
  top: 2rem;
  width: 15rem;
  height: 7rem;
}

/* Logo del dragón: un solo elemento (splash → dock → #dragon-dock) */
.brand-logo {
  position: absolute;
  right: 2rem;
  top: 1rem;
  width: 25rem;
  transform-origin: center center;
  pointer-events: auto;
  opacity: 1;
  will-change: left, top, width, transform;
}

/* Parqueado: ocupa el hueco ya reservado por el spacer (sin empujar layout) */
.brand-logo.parked {
  position: absolute;
  inset: 0;
  right: auto;
  top: auto;
  left: auto;
  width: 100%;
  height: 100%;
  margin: 0;
  transform: none;
  z-index: 2;
}

.brand-logo.parked img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 0;
  clip-path: none;
  transform-origin: center center;
  animation: dragonHeartbeat 2.8s ease-in-out infinite;
  will-change: transform;
}

.brand-logo.settling img {
  animation: none !important;
}

.brand-logo--mobile {
  top: 2rem;
  width: 15rem;
  right: 2rem;
}

.brand-logo.parked.brand-logo--mobile {
  inset: 0;
  width: 100%;
  height: 100%;
  top: auto;
  right: auto;
}

.brand-logo img {
  width: 100%;
  display: block;
  border-radius: 5rem;
  padding: 0;
  clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%, 0% 50%);
  opacity: 1;
  filter: drop-shadow(0 0.25rem 0.55rem rgba(0, 0, 0, 0.4));
  transition: none;
}

.brand-logo--mobile img {
  border-radius: 0;
  clip-path: none;
}

/* Intro en body (Teleport): visible sobre el velo */
.brand-logo.intro {
  pointer-events: none;
}

.brand-logo.intro img {
  clip-path: none;
  border-radius: 0;
  filter: drop-shadow(0 0 2.5rem rgba(170, 24, 24, 0.35));
}

.brand-logo.splash img {
  animation: introPulse 2.4s ease-in-out infinite;
}

.brand-logo.docking img {
  animation: none;
}

/* Entrada + idle: letras (solo si NO vienen del intro ya cargado) */
.menuTop.ready:not(.settled) .letters .brand-letters,
.mobile-wrap.ready:not(.settled) .lettersMobile .brand-letters {
  animation:
    lettersEntrance 1.35s cubic-bezier(0.16, 1, 0.3, 1) both,
    lettersBreathe 4.8s 1.5s ease-in-out infinite;
}

.menuTop.ready:not(.settled) .letters .brand-letters img,
.mobile-wrap.ready:not(.settled) .lettersMobile .brand-letters img {
  opacity: 1;
}

.menuTop.ready:not(.settled) .letters .brand-shine,
.mobile-wrap.ready:not(.settled) .lettersMobile .brand-shine {
  animation: shineSweep 4.2s 1.8s ease-in-out infinite;
}

/* Intro: letras e idioma ya estacionados bajo el velo (sin entrada que reacomode) */
.menuTop.ready.settled .letters .brand-letters,
.mobile-wrap.ready.settled .lettersMobile .brand-letters {
  opacity: 1;
  transform: none;
  filter:
    drop-shadow(0 0.15rem 0.4rem rgba(0, 0, 0, 0.35));
  animation: lettersBreathe 4.8s 1.2s ease-in-out infinite;
}

.menuTop.ready.settled .letters .brand-letters img,
.mobile-wrap.ready.settled .lettersMobile .brand-letters img {
  opacity: 1;
}

.menuTop.ready.settled .letters .brand-shine,
.mobile-wrap.ready.settled .lettersMobile .brand-shine {
  animation: shineSweep 4.2s 1.2s ease-in-out infinite;
}

.header-lang.ready.settled {
  opacity: 1;
  transform: none;
  animation: none;
  pointer-events: auto;
}

@media (prefers-reduced-motion: reduce) {
  .menuTop.ready .letters .brand-letters,
  .menuTop.ready .letters .brand-shine,
  .mobile-wrap.ready .lettersMobile .brand-letters,
  .mobile-wrap.ready .lettersMobile .brand-shine,
  .header-lang.ready,
  .brand-logo.splash img,
  .brand-logo.parked img {
    animation: none !important;
    opacity: 1;
    transform: none;
    filter: none;
  }
}
</style>
