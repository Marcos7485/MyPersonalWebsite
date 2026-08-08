<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useLanguageStore } from '../store/language'
import { useImageStore } from '../store/imageStore'
import { localeMeta, type LocaleCode } from '../lang'

const props = defineProps<{
  /** Posición fija para páginas sin header */
  floating?: boolean
}>()

const languageStore = useLanguageStore()
const imageStore = useImageStore()
imageStore.fetchImagePath()

const open = ref(false)
const rootRef = ref<HTMLElement | null>(null)

const flagSrc = (code: LocaleCode) =>
  `${imageStore.imagePath || '/images'}/LanguagesSelector/${localeMeta[code].flag}`

const selectLocale = (code: LocaleCode) => {
  languageStore.setLocale(code)
  open.value = false
}

const toggle = () => {
  open.value = !open.value
}

const onDocClick = (e: MouseEvent) => {
  if (!rootRef.value) return
  if (!rootRef.value.contains(e.target as Node)) open.value = false
}

onMounted(() => document.addEventListener('click', onDocClick))
onUnmounted(() => document.removeEventListener('click', onDocClick))
</script>

<template>
  <div ref="rootRef" class="lang-switch" :class="{ open, floating }">
    <button
      type="button"
      class="lang-switch__btn"
      :aria-expanded="open"
      :aria-label="languageStore.t('language.label')"
      @click.stop="toggle"
    >
      <img
        :src="flagSrc(languageStore.locale)"
        :alt="localeMeta[languageStore.locale].label"
        class="lang-switch__flag"
      >
      <i class="fa-solid fa-caret-down lang-switch__caret"></i>
    </button>

    <ul v-show="open" class="lang-switch__menu" role="listbox">
      <li
        v-for="code in languageStore.availableLocales"
        :key="code"
        role="option"
        :aria-selected="languageStore.locale === code"
      >
        <button
          type="button"
          class="lang-switch__option"
          :class="{ active: languageStore.locale === code }"
          @click.stop="selectLocale(code)"
        >
          <img :src="flagSrc(code)" :alt="localeMeta[code].label" class="lang-switch__flag">
          <span>{{ languageStore.t(`language.${code}`) }}</span>
        </button>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.lang-switch {
  position: relative;
  display: inline-flex;
  align-items: center;
  z-index: 20;
}

.lang-switch.floating {
  position: fixed;
  top: 2rem;
  right: 2rem;
  z-index: 1000;
}

.lang-switch__btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.25rem 0.45rem;
  border: none;
  background: transparent;
  cursor: pointer;
  border-radius: 0.5rem;
  transition: background-color 0.2s ease;
}

.lang-switch__btn:hover {
  background: rgba(170, 24, 24, 0.12);
}

.lang-switch__flag {
  width: 2.2rem;
  height: 2.2rem;
  object-fit: cover;
  border-radius: 50%;
  display: block;
  box-shadow: 0 0 0 0.08rem rgba(0, 0, 0, 0.15);
}

.lang-switch__caret {
  font-size: 1rem;
  color: var(--color-first);
  transition: transform 0.25s ease;
}

.lang-switch.open .lang-switch__caret {
  transform: rotate(180deg);
}

.lang-switch__menu {
  position: absolute;
  top: calc(100% + 0.45rem);
  left: 50%;
  transform: translateX(-50%);
  list-style: none;
  margin: 0;
  padding: 0.45rem;
  min-width: 14rem;
  background: rgba(10, 14, 22, 0.94);
  border: 0.05rem solid rgba(170, 24, 24, 0.35);
  border-radius: 0.8rem;
  box-shadow: 0 0.6rem 1.6rem rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(8px);
}

.lang-switch__option {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 0.55rem 0.7rem;
  border: none;
  background: transparent;
  color: #f0f0f0;
  font-family: var(--familyTitles), Georgia, serif;
  font-size: 1.35rem;
  cursor: pointer;
  border-radius: 0.55rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.lang-switch__option:hover,
.lang-switch__option.active {
  background: rgba(170, 24, 24, 0.2);
  color: #ff8a8a;
}
</style>
