<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import { useLanguageStore } from '../store/language';
import { useImageStore } from '../store/imageStore';
import LanguagesSelector from './LanguagesSelector.vue';

const props = withDefaults(defineProps<{
  /** loading: centro | docking: viaja a su lugar | done: quieto */
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

const languageStore = useLanguageStore();

const isMobile = ref(window.innerWidth <= 600);
const MenuIsActive = ref(false);
const isReady = ref(false);
const logoRef = ref<HTMLElement | null>(null);
const logoAnchorRef = ref<HTMLElement | null>(null);
/** Tras el fade-out del vuelo: reaparece suave en el header */
const logoSettling = ref(false);

const DOCK_MS = 1200
const FADE_OUT_AT = 0.58
const FADE_OUT_MS = 420
/** Espera a que terminen letras/nav; el dragón es lo último */
const LOGO_APPEAR_DELAY_MS = 1900

let nearTimer: ReturnType<typeof setTimeout> | null = null
let doneTimer: ReturnType<typeof setTimeout> | null = null
let appearTimer: ReturnType<typeof setTimeout> | null = null

const t = (key: string) => languageStore.t(key)
const updateLayout = () => {
  isMobile.value = window.innerWidth <= 600;
  if (!isMobile.value) MenuIsActive.value = false;
};

const menuMobile = () => {
  MenuIsActive.value = !MenuIsActive.value;
};

const getScrollbarWidth = () => {
  const probe = document.createElement('div')
  probe.style.cssText =
    'position:absolute;top:-9999px;width:100px;height:100px;overflow:scroll;visibility:hidden'
  document.body.appendChild(probe)
  const width = probe.offsetWidth - probe.clientWidth
  document.body.removeChild(probe)
  return width
}

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

/** Centro de pantalla, por encima del velo negro (Teleport a body) */
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

  const splashW = Math.min(window.innerWidth * 0.58, 420)
  logoSettling.value = false

  el.style.transition = 'none'
  el.style.position = 'fixed'
  el.style.left = '50%'
  el.style.top = '45%'
  el.style.width = `${splashW}px`
  el.style.height = 'auto'
  el.style.right = 'auto'
  el.style.margin = '0'
  el.style.zIndex = '100002'
  el.style.opacity = '1'
  el.style.transform = 'translate(-50%, -50%)'
  el.style.transformOrigin = 'center center'
}

/** Destino final: ancla + compensación de scrollbar */
const getDockTarget = (anchor: HTMLElement) => {
  const rect = anchor.getBoundingClientRect()
  const currentSb = window.innerWidth - document.documentElement.clientWidth
  const realSb = getScrollbarWidth()
  const compensate = Math.max(0, realSb - currentSb)

  return {
    left: rect.left - compensate,
    top: rect.top,
    width: rect.width,
  }
}

/** Mueve el logo hacia el ancla; cerca del final se desvanece */
const playDock = async () => {
  await nextTick()
  const el = logoRef.value
  const anchor = logoAnchorRef.value

  if (!el || !anchor) {
    emit('dock-near-end')
    emit('dock-done')
    return
  }

  const to = getDockTarget(anchor)
  const from = el.getBoundingClientRect()

  el.style.transition = 'none'
  el.style.position = 'fixed'
  el.style.left = `${from.left}px`
  el.style.top = `${from.top}px`
  el.style.width = `${from.width}px`
  el.style.right = 'auto'
  el.style.margin = '0'
  el.style.zIndex = '100002'
  el.style.opacity = '1'
  el.style.transform = 'none'

  void el.offsetWidth

  el.style.transition = [
    `left ${DOCK_MS}ms cubic-bezier(0.65, 0, 0.35, 1)`,
    `top ${DOCK_MS}ms cubic-bezier(0.65, 0, 0.35, 1)`,
    `width ${DOCK_MS}ms cubic-bezier(0.65, 0, 0.35, 1)`,
  ].join(', ')
  el.style.left = `${to.left}px`
  el.style.top = `${to.top}px`
  el.style.width = `${to.width}px`

  if (nearTimer) clearTimeout(nearTimer)
  if (doneTimer) clearTimeout(doneTimer)
  if (appearTimer) clearTimeout(appearTimer)

  // Se acerca al destino: fade-out + revelar página
  nearTimer = setTimeout(() => {
    const logo = logoRef.value
    if (logo) {
      logo.style.transition = [
        logo.style.transition,
        `opacity ${FADE_OUT_MS}ms ease`,
      ].filter(Boolean).join(', ')
      logo.style.opacity = '0'
    }
    emit('dock-near-end')
  }, DOCK_MS * FADE_OUT_AT)

  // Ya invisible: colocar fijo en header y reaparecer con la página
  doneTimer = setTimeout(() => finishDock(), DOCK_MS * FADE_OUT_AT + FADE_OUT_MS + 40)
}

const finishDock = async () => {
  document.body.style.overflow = ''
  await nextTick()

  const el = logoRef.value
  // Invisible en el header mientras corren el resto de animaciones
  logoSettling.value = true
  clearLogoInline()
  if (el) el.style.opacity = '0'

  isReady.value = true
  emit('dock-done')

  await nextTick()
  // Último elemento: reaparece lento cuando lo demás ya entró
  appearTimer = setTimeout(() => {
    logoSettling.value = false
    const logo = logoRef.value
    if (logo) logo.style.opacity = ''
  }, LOGO_APPEAR_DELAY_MS)
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
    }
  },
)

onMounted(async () => {
  window.addEventListener('resize', updateLayout);
  if (props.introPhase === 'loading') {
    await placeSplash()
  } else if (props.introPhase === 'done') {
    isReady.value = true
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', updateLayout);
  if (nearTimer) clearTimeout(nearTimer)
  if (doneTimer) clearTimeout(doneTimer)
  if (appearTimer) clearTimeout(appearTimer)
});
</script>

<template>
  <div v-if="!isMobile" class="menuTop" :class="{ ready: isReady }">
    <div class="letters">
      <div class="brand-letters">
        <img :src="`${imageStore.imagePath}/letras.webp`" alt="DRS">
        <span class="brand-shine" aria-hidden="true"></span>
      </div>
    </div>
    <div class="banner">
      <!-- Ancla: reserva el lugar final. El dragón (único) vive aquí / Teleport a body en intro -->
      <div ref="logoAnchorRef" class="logo-anchor">
        <Teleport to="body" :disabled="introPhase === 'done'">
          <div
            ref="logoRef"
            class="brand-logo"
            :class="{
              splash: introPhase === 'loading',
              docking: introPhase === 'docking',
              intro: introPhase !== 'done',
              settling: logoSettling,
              appear: introPhase === 'done' && !logoSettling,
            }"
          >
            <img :src="`${imageStore.imagePath || '/images'}/drs.webp`" alt="Logo DRS">
          </div>
        </Teleport>
      </div>
      <div>
        <ul>
          <a href="#Home" class="link-style" aria-label="Inicio" style="--i: 0">
            <li><i class="fa-solid fa-house"></i></li>
          </a>
          <li class="lang-slot" style="--i: 0">
            <LanguagesSelector />
          </li>
          <a href="#Softwares" class="link-style" style="--i: 1">
            <li>{{ t('nav.softwares') }}</li>
          </a>
          <a href="#Reviews" class="link-style" style="--i: 2">
            <li>{{ t('nav.reviews') }}</li>
          </a>
          <a href="#Contacto" class="link-style" style="--i: 3">
            <li>{{ t('nav.contact') }}</li>
          </a>
        </ul>
      </div>
    </div>
  </div>

  <div v-if="isMobile" class="mobile-wrap" :class="{ ready: isReady }">
    <div class="menu">
      <div class="toggleMobile" :class="{ active: MenuIsActive }" @click="menuMobile">
        <i class="fa-solid fa-bars"></i>
      </div>
      <div class="lettersMobile">
        <div class="brand-letters">
          <img :src="`${imageStore.imagePath}/letras.webp`" alt="DRS">
          <span class="brand-shine" aria-hidden="true"></span>
        </div>
      </div>
      <div class="logoMobile">
        <div ref="logoAnchorRef" class="logo-anchor logo-anchor--mobile">
          <Teleport to="body" :disabled="introPhase === 'done'">
            <div
              ref="logoRef"
              class="brand-logo brand-logo--mobile"
              :class="{
                splash: introPhase === 'loading',
                docking: introPhase === 'docking',
                intro: introPhase !== 'done',
                settling: logoSettling,
                appear: introPhase === 'done' && !logoSettling,
              }"
            >
              <img :src="`${imageStore.imagePath || '/images'}/drs.webp`" alt="Logo DRS">
            </div>
          </Teleport>
        </div>
      </div>
    </div>
    <div class="list" :class="{ active: MenuIsActive }">
      <ul @click="menuMobile">
        <li class="lang-slot-mobile" @click.stop>
          <LanguagesSelector />
        </li>
        <li style="--i: 0">
          <a href="#Softwares" class="link-style">{{ t('nav.softwares') }}</a>
        </li>
        <li style="--i: 1">
          <a href="#Reviews" class="link-style">{{ t('nav.reviews') }}</a>
        </li>
        <li style="--i: 2">
          <a href="#Contacto" class="link-style">{{ t('nav.contact') }}</a>
        </li>
      </ul>
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
    transform: translateX(-130%) skewX(-18deg);
    opacity: 0;
  }
  12% { opacity: 0.75; }
  35% {
    transform: translateX(130%) skewX(-18deg);
    opacity: 0;
  }
  100% {
    transform: translateX(130%) skewX(-18deg);
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

@keyframes mobileBarIn {
  0% {
    opacity: 0;
    transform: translateY(-1rem);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes mobileItemIn {
  0% {
    opacity: 0;
    transform: translateX(-1rem);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

/* ========== Mobile ========== */
.mobile-wrap {
  position: relative;
  z-index: 1000;
  overflow: visible;
}

.menu {
  position: relative;
  top: 0;
  width: 100%;
  height: 7rem;
  z-index: 1000;
  background: transparent;
  overflow: visible;
}

.list.active {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: auto;
}

.list.active li {
  animation: mobileItemIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
  animation-delay: calc(0.06s * var(--i, 0) + 0.08s);
}

.list {
  position: absolute;
  padding: 1.2rem 1.6rem;
  transform: translateY(-1.5rem) scale(0.97);
  top: 7rem;
  left: 4%;
  right: 4%;
  font-size: 2.6rem;
  font-family: var(--familyTitles), Georgia, serif;
  z-index: 100;
  background: linear-gradient(160deg, rgba(5, 11, 20, 0.92), rgba(20, 12, 18, 0.94));
  border: 0.05rem solid rgba(170, 24, 24, 0.35);
  border-radius: 1rem;
  opacity: 0;
  pointer-events: none;
  transition:
    opacity 0.35s ease,
    transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  box-shadow: 0 0.8rem 2rem rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(10px);
}

.list ul {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.list .link-style {
  display: block;
  padding: 0.7rem 0.6rem;
  border-radius: 0.6rem;
  color: #f2f2f2;
  transition: background-color 0.25s ease, color 0.25s ease, padding-left 0.25s ease;
}

.list .link-style:hover,
.list .link-style:active {
  background-color: rgba(170, 24, 24, 0.2);
  color: #ff6b6b;
  padding-left: 1.2rem;
}

.toggleMobile.active {
  color: var(--color-first);
  transform: rotate(90deg);
}

.toggleMobile {
  position: absolute;
  left: 10%;
  font-size: 4rem;
  top: 0.5rem;
  z-index: 1001;
  color: #e8e8e8;
  transition: color 0.35s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
  cursor: pointer;
  text-shadow: 0 0.1rem 0.4rem rgba(0, 0, 0, 0.5);
}

.mobile-wrap.ready .toggleMobile {
  animation: mobileBarIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
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

.logoMobile {
  position: relative;
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

.brand-shine {
  position: absolute;
  inset: -10% auto -10% 0;
  width: 35%;
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

.link-style {
  color: var(--color-first);
  text-decoration: none;
}

.banner {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 4.5rem;
  border-radius: 0.1rem 5rem;
  background: transparent;
  box-shadow: none;
  z-index: 100;
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

/* Logo del dragón: un solo elemento (splash → dock → header) */
.brand-logo {
  position: absolute;
  right: 2rem;
  top: 1rem;
  width: 25rem;
  transform-origin: center center;
  pointer-events: auto;
  opacity: 1;
}

.brand-logo.settling {
  opacity: 0 !important;
  transition: none !important;
}

.brand-logo.appear {
  animation: logoSettleIn 1.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

@keyframes logoSettleIn {
  0% {
    opacity: 0;
    filter:
      drop-shadow(0 0 0 rgba(0, 0, 0, 0))
      brightness(1.4);
    transform: scale(0.9);
  }
  55% {
    opacity: 1;
    filter:
      drop-shadow(0 0.35rem 0.9rem rgba(0, 0, 0, 0.45))
      brightness(1.08);
    transform: scale(1.03);
  }
  100% {
    opacity: 1;
    filter:
      drop-shadow(0 0.25rem 0.55rem rgba(0, 0, 0, 0.4))
      brightness(1);
    transform: scale(1);
  }
}

.brand-logo--mobile {
  top: 2rem;
  width: 15rem;
  right: 2rem;
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

/* Entrada + idle: letras */
.menuTop.ready .letters .brand-letters,
.mobile-wrap.ready .lettersMobile .brand-letters {
  animation:
    lettersEntrance 1.35s cubic-bezier(0.16, 1, 0.3, 1) both,
    lettersBreathe 4.8s 1.5s ease-in-out infinite;
}

.menuTop.ready .letters .brand-letters img,
.mobile-wrap.ready .lettersMobile .brand-letters img {
  opacity: 1;
}

.menuTop.ready .letters .brand-shine,
.mobile-wrap.ready .lettersMobile .brand-shine {
  animation: shineSweep 4.2s 1.8s ease-in-out infinite;
}

.banner ul {
  list-style: none;
  color: var(--color-first);
  justify-content: center;
  display: flex;
  font-size: 1.5rem;
  font-family: var(--familyTitles), Georgia, serif;
  letter-spacing: 0.02em;
  margin-top: 1rem;
  margin-right: 15rem;
}

.banner a.link-style {
  opacity: 0;
}

.menuTop.ready .banner a.link-style,
.menuTop.ready .banner .lang-slot {
  animation: navItemIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
  animation-delay: calc(0.08s * var(--i, 0) + 0.55s);
}

.banner .lang-slot {
  list-style: none;
  display: flex;
  align-items: center;
  margin-right: 1.2rem;
  opacity: 0;
}

.lang-slot-mobile {
  padding: 0.4rem 0.6rem 1rem;
}

.banner li {
  transition:
    text-shadow 0.3s ease,
    transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
    color 0.25s ease;
  margin-right: 3rem;
}

.banner a:hover li {
  text-shadow:
    0 0 0.6rem rgba(170, 24, 24, 0.55),
    0 0.1rem 0.35rem rgba(170, 24, 24, 0.4);
  transform: translateY(-0.15rem) scale(1.04);
  cursor: pointer;
  color: #d42222;
}

.banner a {
  position: relative;
}

.banner a::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: -0.3rem;
  width: 0;
  height: 0.14rem;
  background: linear-gradient(90deg, transparent, var(--color-first), transparent);
  border-radius: 1rem;
  transform: translateX(-50%);
  transition: width 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.banner a:hover::after {
  width: 70%;
}

@media (prefers-reduced-motion: reduce) {
  .menuTop.ready .letters .brand-letters,
  .menuTop.ready .letters .brand-shine,
  .mobile-wrap.ready .lettersMobile .brand-letters,
  .mobile-wrap.ready .lettersMobile .brand-shine,
  .brand-logo.splash img {
    animation: none !important;
    opacity: 1;
    transform: none;
    filter: none;
  }
}
</style>
