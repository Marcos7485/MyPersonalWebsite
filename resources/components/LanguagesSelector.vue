<script setup lang="ts">

import { ref, onMounted } from 'vue'
import { useLanguageStore } from '../store/language';
import { useImageStore } from '../store/imageStore';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const isActive = ref(false)

function toggleActive(): void {
  isActive.value = !isActive.value
}

const languageStore = useLanguageStore();

const loadLanguages = async (language:string) => {
  await languageStore.fetchLanguages(language);
};

const isMobile = ref(window.innerWidth <= 600);

const updateLayout = () => {
  isMobile.value = window.innerWidth <= 600;
};

onMounted(() => {
  window.addEventListener('resize', updateLayout);
});

const MenuIsActive = ref<boolean>(false);

const menuMobile = () => {
  MenuIsActive.value = !MenuIsActive.value;
};


</script>

<template>
  <div v-if="!isMobile" class="selector" :class="{ active: isActive }" @click="toggleActive">
    <div id="opc1" @click="loadLanguages('esp')"><img :src="`${imageStore.imagePath}/LanguagesSelector/esp.webp`"></div>
    <div id="opc2" @click="loadLanguages('pt')"><img :src="`${imageStore.imagePath}/LanguagesSelector/pt.webp`"></div>
    <div id="opc3" @click="loadLanguages('eng')"><img :src="`${imageStore.imagePath}/LanguagesSelector/eng.webp`"></div>
    <div class="arrow" :class="{ active: isActive }">
      <i class="fa-solid fa-caret-left"></i>
    </div>
  </div>
  <!-- :class="{ active: MenuIsActive }" @click="menuMobile" -->

  <div v-if="isMobile" class="selectorMobile" :class="{ active: isActive }" @click="toggleActive">
    <div id="opc1" @click="loadLanguages('esp')"><img :src="`${imageStore.imagePath}/LanguagesSelector/esp.webp`"></div>
    <div id="opc2" @click="loadLanguages('pt')"><img :src="`${imageStore.imagePath}/LanguagesSelector/pt.webp`"></div>
    <div id="opc3" @click="loadLanguages('eng')"><img :src="`${imageStore.imagePath}/LanguagesSelector/eng.webp`"></div>
    <div class="arrow" :class="{ active: isActive }">
      <i class="fa-solid fa-caret-left"></i>
    </div>
  </div>
</template>

<style scoped>

.selectorMobile.active{
  transform: translateX(7rem);
}

.selectorMobile {
  position: fixed;
  top: 11rem;
  left: -8rem;
  height: 10rem;
  width: 10rem;
  background-color: rgba(255, 255, 255, 0.5);
  font-size: var(--fontsize);
  border-radius: 1rem;
  z-index: 1000;
  transition: transform 1s ease;
}

.selectorMobile img {
  width: 4rem;
  margin-bottom: 1rem;
  z-index: 10;
  cursor: pointer;
  animation: traslate 5s ease forwards;
}


.selector {
  position: fixed;
  top: 11rem;
  left: -8rem;
  height: 10rem;
  width: 10rem;
  background-color: rgba(255, 255, 255, 0.5);
  font-size: var(--fontsize);
  border-radius: 1rem;
  z-index: 1000;
  transition: transform 1s ease;
}

@keyframes traslate {
    from {
        transform: translateX(50rem);
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.selector img {
  width: 4rem;
  margin-bottom: 1rem;
  z-index: 10;
  cursor: pointer;
  animation: traslate 5s ease forwards;
}

.arrow {
  position: absolute;
  right: .5rem;
  top: 3.7rem;
}

.selector:hover {
  transform: translateX(7rem);
  transition: transform .5s ease;
}
</style>
