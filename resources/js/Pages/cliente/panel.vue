<script setup lang="ts">
import { Ref, onMounted, computed } from 'vue';
import { useImageStore } from '../../../store/imageStore';

const imageStore = useImageStore();
onMounted(() => {
  imageStore.fetchImagePath();
});

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES'); // Formato dd/mm/yyyy para español
};

interface Project {
  id: number,
  cliente: string,
  tipo: string,
  pago_entrada: string,
  pago_final: string,
  valor_total: string,
  plazo: string,
  progreso: string,
  finalizado: string,
  mantenimiento: string,
  hash: string,
  created_at: string
}

// Tipar las props explícitamente
const props = defineProps<{ project: Project }>();


// Calcula los días transcurridos desde created_at hasta hoy
const getDaysPassed = (startDate: string) => {
  const start = new Date(startDate);
  const today = new Date();

  // Calcula la diferencia en milisegundos y conviértela a días
  const timeDifference = today.getTime() - start.getTime();
  return Math.floor(timeDifference / (1000 * 3600 * 24));
};

// Calcula el porcentaje de la barra
const calculateProgress = (startDate: string, plazo: number) => {
  const daysPassed = getDaysPassed(startDate);
  const validDaysPassed = daysPassed > plazo ? plazo : daysPassed;
  const progress = (validDaysPassed / plazo) * 100;

  // Asegurarse de que el progreso no supere el 100%
  return Math.min(progress, 100);
};

const progress = computed(() => calculateProgress(props.project.created_at, Number(props.project.plazo)));


</script>

<template>
  <div>
    <div>
      <div class="content">
        <div id="figura1" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/1.svg`">
        </div>
        <div id="figura2" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/2.svg`">
        </div>
        <div id="figura3" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/3.svg`">
        </div>
        <div id="figura4" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/4.svg`">
        </div>
        <div id="figura5" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input.png`">
        </div>
        <div id="figura6" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input.png`">
        </div>
        <div id="figura7" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input.png`">
        </div>
        <div id="figura8" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input-dinero.png`">
        </div>
        <div id="figura9" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input-dinero.png`">
        </div>
        <div id="figura10" :class="{ active: 'variableboolean' }">
          <img :src="`${imageStore.imagePath}/cliente/panel/input-dinero.png`">
        </div>
        <div class="banner">
          <img :src="`${imageStore.imagePath}/letras.webp`" alt="">
        </div>
        <div class="item-cliente">
          <h1>{{ props.project.cliente }}</h1>
        </div>

        <div class="item-tipo">
          <h1> Proyecto {{ props.project.tipo }}</h1>
        </div>

        <div class="item-inicio">
          <h1> Inicio {{ formatDate(props.project.created_at) }}</h1>
        </div>

        <div class="item-pago1">
          <p>Entrada (50%): ${{ Math.round(Number(props.project.valor_total) / 2) }} - {{
            formatDate(props.project.pago_entrada) }}</p>
        </div>

        <div class="item-pago2">
          <p>Restante (50%): ${{ Math.round(Number(props.project.valor_total) / 2) }} - {{
            formatDate(props.project.pago_final) }}</p>
        </div>

        <div class="item-pago3">
          <p>Valor Total: ${{ props.project.valor_total }}</p>
        </div>

        <div class="item-plazo">
          <p>Plazo de entrega del proyecto</p>
          <div class="progress-bar">
            <div class="progress-fill" :style="{ width: progress + '%' }">
              <p>{{ progress.toFixed(2) }}%</p>
            </div>
          </div>
          <p> Plazo {{ getDaysPassed(props.project.created_at) > Number(props.project.plazo) ? props.project.plazo : getDaysPassed(props.project.created_at) }}/{{ props.project.plazo }} días</p>
        </div>

        <p>Plazo: </p>
        <p>Progreso del proyecto: {{ props.project.progreso }} %</p>
        <p>Finalizado: {{ props.project.finalizado }}</p>
        <p>Mantenimiento: {{ props.project.mantenimiento }}</p>
      </div>
    </div>
  </div>

</template>

<style scoped>

.item-plazo p {
  color: white;
}

.item-plazo{
  position: absolute;
  color: var(--color-base);
  top: 35rem;
  left: 15%;
  text-align: center;
}

.progress-bar {
    width: 100rem;
    height: 3rem;
    background-color: #e0e0e0;
    border-radius: 4rem 1rem;
    overflow: hidden;
}

.progress-fill p{
  color: black;
}

.progress-fill {
    height: 100%;
    background-color: var(--color-first);
    transition: width 0.5s;
}

.item-pago3 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 75%;
  text-align: center;
}

.item-pago2 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 38%;
  text-align: center;
}

.item-pago1 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 7rem;
  text-align: center;
}

.item-inicio h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 65.5%;
  text-align: center;
}


.item-tipo h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 33%;
  text-align: center;
}

.item-cliente h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 7rem;
  text-align: center;
}

#figura10 img {
  position: absolute;
  width: 45rem;
  height: 12rem;
  top: 20rem;
  left: 66%;
}

#figura9 img {
  position: absolute;
  width: 45rem;
  height: 12rem;
  top: 20rem;
  left: 33%;
}

#figura8 img {
  position: absolute;
  width: 45rem;
  height: 12rem;
  top: 20rem;
  left: 0;
}

#figura7 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 60%;
}

#figura6 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 30%;
}


#figura5 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 0;
}

#figura4 img {
  position: absolute;
  width: 40rem;
  bottom: 0;
  left: 30rem;
}

#figura3 img {
  position: absolute;
  width: 20rem;
  bottom: 0;
  left: 0;
}

#figura2 img {
  position: absolute;
  width: 20rem;
  bottom: 0;
  right: 0;
}

#figura1 img {
  position: absolute;
  width: 20rem;
  top: 4rem;
  right: 0;
}

.banner img {
  width: 19rem;
}

.banner {
  position: fixed;
  color: white;
  top: 0;
  width: 100%;
  background-color: rgba(36, 36, 36, 0.263);
  box-shadow: 0 .3rem 1rem var(--color-first);
}

.content h1 {
  font-family: var(--familyTitles);
}

.content p {
  font-size: var(--fontsize);
  font-family: var(--familyTitles);
}

.content {
  opacity: 0;
  animation: appear 3s forwards;
}

@keyframes appear {
  100% {
    opacity: 1;
  }
}
</style>