<script setup>
import { useLanguageStore } from '../store/language.ts';

const languageStore = useLanguageStore();

</script>

<template>
  <div class="whatsapp-container">
    <a href="https://wa.me/541136190245?text=¡Hola!%20Quisiera%20más%20información" class="whatsapp-float"
      target="_blank">

      <span class="whatsapp-text"  v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0"><i class="fab fa-whatsapp whatsapp-icon"></i> Contacto</span>
      <span class="whatsapp-text"  v-if="languageStore.languages === 'pt'"><i class="fab fa-whatsapp whatsapp-icon"></i> Contato</span>
      <span class="whatsapp-text"  v-if="languageStore.languages === 'eng'"><i class="fab fa-whatsapp whatsapp-icon"></i> Contact</span>
    </a>
  </div>
</template>

<style>

.whatsapp-container {
  position: fixed;
  bottom: .5rem;
  right: 5rem;
  z-index: 1000;
  display: flex;
  align-items: center;
  background-color: #25d366;
  border-radius: 5rem;
  box-shadow: .2rem .2rem .5rem rgba(0, 0, 0, 0.3);
  padding: 1rem 1.5rem;
  color: white;
  text-decoration: none;
  animation: subirBajar 2s infinite;
}


.whatsapp-float {
  display: flex;
  align-items: center;
  text-decoration: none;
}


.whatsapp-text {
  font-size: var(--fontsize);
  color: white;
}


@keyframes subirBajar {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-2rem);
  }

  100% {
    transform: translateY(0);
  }
}

</style>