<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useLanguageStore } from '../store/language';
import { useImageStore } from '../store/imageStore';

const imageStore = useImageStore();
onMounted(() => {
  imageStore.fetchImagePath();
});

const languageStore = useLanguageStore();

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
  <div v-if="!isMobile" class="menuTop">
    <div class="letters">
      <img :src="`${imageStore.imagePath}/letras.webp`" alt="">
    </div>
    <div class="banner">
      <div>
        <img :src="`${imageStore.imagePath}/drs.webp`">
      </div>
      <div>
        <ul>
          <a href="#" class="link-style">
            <li><i class="fa-solid fa-house"></i></li>
          </a>
          <a href="#DesarrolloWeb" class="link-style">
            <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Desarrollo Web</li>
            <li v-if="languageStore.languages === 'pt'">Desenvolvimento</li>
            <li v-if="languageStore.languages === 'eng'">Web Development</li>
          </a>
          <a href="#Softwares" class="link-style">
            <li>Softwares</li>
          </a>
          <a href="#Portafolio" class="link-style">
            <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Portafolio</li>
            <li v-if="languageStore.languages === 'pt'">Portfólio</li>
            <li v-if="languageStore.languages === 'eng'">Portfolio</li>
          </a>
          <a href="#Reviews" class="link-style">
            <li>Reviews</li>
          </a>
          <a href="#Contacto" class="link-style">
            <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Contacto</li>
            <li v-if="languageStore.languages === 'pt'">Contato</li>
            <li v-if="languageStore.languages === 'eng'">Contact</li>
          </a>
        </ul>
      </div>
    </div>
  </div>

  <div v-if="isMobile">
    <div class="menu">
      <div class="toggleMobile" :class="{ active: MenuIsActive }" @click="menuMobile">
        <i class="fa-solid fa-bars"></i>
      </div>
      <div class="lettersMobile">
        <img :src="`${imageStore.imagePath}/letras.webp`" alt="">
      </div>
      <div class="logoMobile">
        <img :src="`${imageStore.imagePath}/drs.webp`">
      </div>
    </div>
    <div class="list" :class="{ active: MenuIsActive }">
      <ul @click="menuMobile">
        <li> <a href="#DesarrolloWeb" class="link-style">
        <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Desarrollo Web</li>
        <li v-if="languageStore.languages === 'pt'">Desenvolvimento</li>
        <li v-if="languageStore.languages === 'eng'">Web Development</li>
        </a></li>
        <li> <a href="#Softwares" class="link-style">
        <li>Softwares</li>
        </a></li>
        <li> <a href="#Portafolio" class="link-style">
        <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Portafolio</li>
        <li v-if="languageStore.languages === 'pt'">Portfólio</li>
        <li v-if="languageStore.languages === 'eng'">Portfolio</li>
        </a></li>
        <li> <a href="#Reviews" class="link-style">
        <li>Reviews</li>
        </a></li>
        <li> <a href="#Contacto" class="link-style">
        <li v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Contacto</li>
        <li v-if="languageStore.languages === 'pt'">Contato</li>
        <li v-if="languageStore.languages === 'eng'">Contact</li>
        </a></li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
/* Mobile */

.list.active {
  opacity: 1;
  transform: translateY(-.5rem);
}

.list {
  position: absolute;
  padding: 1rem;
  transform: translateY(-50rem);
  top: 7rem;
  font-size: 3rem;
  z-index: 100;
  background-color: rgba(0, 0, 0, 0.8);
  border-radius: 1rem;
  opacity: 0;
  transition: opacity 2s, transform 1s;
}

.list ul {
  list-style: none;
}

.toggleMobile.active {
  color: var(--color-first);
  transform: rotate(360deg);
}

.toggleMobile {
  position: absolute;
  left: 10%;
  font-size: 4rem;
  top: .5rem;
  z-index: 1001;
  transition: color 2s, transform 1s;
}

.menu {
  position: relative;
  top: 0;
  width: 100%;
  background: linear-gradient(to right, grey, white);
  height: 7rem;
  z-index: 1000;
}

.lettersMobile img {
  position: absolute;
  left: 25%;
  top: 1rem;
  width: 25rem;
}

.logoMobile img {
  position: absolute;
  right: 0;
  width: 15rem;
}


/* Mobile */



.menuTop {
  display: flex;
  flex-direction: row;
  top: 0;
  width: 100%;
  height: 5rem;
  z-index: 1000;
}

@keyframes slideInFromRight {
  0% {
    transform: translateX(50%);
    /* Comienza fuera de la pantalla a la derecha */
    opacity: 0;
  }

  100% {
    transform: translateX(0);
    /* Termina en su posición original */
    opacity: 1;
  }
}

.letters {
  position: absolute;
  background-color: var(--color-base);
  text-align: left;
  top: 0;
  left: 1%;
  width: 95%;
  height: 5rem;
  border-radius: .1rem 5rem;
  box-shadow: 0 0 .2rem var(--color-first);
  animation: boxShadowAppear 2s 1.5s forwards;
  z-index: 100;
}

@keyframes boxShadowAppear {
  to {
    box-shadow: 0 .5rem .2rem grey;
  }
}

.letters img {
  width: 25rem;
  margin-left: 25rem;
  opacity: 0;
  animation: appear 2s forwards;
}

.link-style {
  color: var(--color-first);
}

.banner {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 4.5rem;
  opacity: 0;
  border-radius: .1rem 5rem;
  background: linear-gradient(to right, grey, white);
  animation: slideInFromRight 1.5s ease-out forwards;
  /* Define la animación */
  z-index: 100;
}

.banner img {
  width: 25rem;
  margin-top: -1rem;
  border-radius: 5rem;
  padding: 0rem;
  clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%, 0% 50%);
  position: absolute;
  right: 0;
  opacity: 0;
  animation: appear 2s 1.5s forwards;
}

.banner ul {
  list-style: none;
  color: var(--color-first);
  justify-content: center;
  display: flex;
  font-size: 1.5rem;
  margin-top: 1rem;
  margin-right: 15rem;
  opacity: 0;
  animation: appear 3s 1s forwards;
}

.banner li {
  transition: text-shadow .3s ease;
  margin-right: 3rem;
}

.banner li:hover {
  text-shadow: 0rem .1rem .3rem var(--color-first);
  cursor: pointer;
}


@keyframes appear {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}
</style>
