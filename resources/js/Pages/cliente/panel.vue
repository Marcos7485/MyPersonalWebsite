<script setup lang="ts">
import { Ref, onMounted, computed } from 'vue';
import { useImageStore } from '../../../store/imageStore';
import { Link } from '@inertiajs/vue3';

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

const progressProject = computed(() => Number(props.project.progreso));


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
          <Link href="/"><img :src="`${imageStore.imagePath}/letras.webp`" alt=""></Link>
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

        <div class="item-progreso">
          <h1>Progreso del proyecto</h1>
          <div class="progress-bar-progreso">
            <div class="progress-fill-progreso" :style="{ width: progressProject + '%' }">
              <p>{{ progressProject.toFixed(2) }}%</p>
            </div>
          </div>
        </div>

        <div class="item-plazo">
          <h1>Plazo de entrega del proyecto</h1>
          <div class="progress-bar">
            <div class="progress-fill" :style="{ width: progress + '%' }">
              <p>{{ progress.toFixed(2) }}%</p>
            </div>
          </div>
          <h1> Plazo {{ getDaysPassed(props.project.created_at) > Number(props.project.plazo) ? props.project.plazo :
            getDaysPassed(props.project.created_at) }}/{{ props.project.plazo }} días</h1>
        </div>


        <div class="item-finalizado">
          <h1>Finalizado: {{ props.project.finalizado == null ? '-' : formatDate(props.project.finalizado) }}</h1>
        </div>

        <div class="item-mantenimiento">
          <h1>Mantenimiento: ${{ props.project.mantenimiento }}/mes</h1>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>
.item-mantenimiento h1 {
  color: white;
}

.item-mantenimiento {
  position: absolute;
  color: var(--color-base);
  top: 60rem;
  right: 20%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-finalizado h1 {
  color: white;
}

.item-finalizado {
  position: absolute;
  color: var(--color-base);
  top: 60rem;
  left: 20%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.progress-bar-progreso {
  width: 110rem;
  height: 3rem;
  background-color: #e0e0e0;
  border-radius: 4rem 1rem;
  overflow: hidden;
}

.progress-fill-progreso p {
  color: black;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.progress-fill-progreso {
  height: 100%;
  background-color: goldenrod;
  transition: width 0.5s;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-progreso h1 {
  color: white;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-progreso {
  position: absolute;
  color: var(--color-base);
  top: 35rem;
  left: 15%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

.item-plazo h1 {
  color: white;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-plazo {
  position: absolute;
  color: var(--color-base);
  top: 45rem;
  left: 15%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

.progress-bar {
  width: 110rem;
  height: 3rem;
  background-color: #e0e0e0;
  border-radius: 4rem 1rem;
  overflow: hidden;
}

.progress-fill p {
  color: black;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.progress-fill {
  height: 100%;
  background-color: var(--color-first);
  transition: width 0.5s;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-pago3 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 74.5%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-pago2 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 37%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-pago1 p {
  position: absolute;
  color: var(--color-base);
  top: 25rem;
  left: 6rem;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-inicio h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 65.5%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}


.item-tipo h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 33%;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

.item-cliente h1 {
  position: absolute;
  color: var(--color-base);
  top: 8rem;
  left: 7rem;
  text-align: center;
  opacity: 0;
  animation: appear 2s 3s forwards;
}

#figura10 img {
  position: absolute;
  width: 40rem;
  height: 12rem;
  top: 20rem;
  left: 66%;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura9 img {
  position: absolute;
  width: 40rem;
  height: 12rem;
  top: 20rem;
  left: 33%;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura8 img {
  position: absolute;
  width: 40rem;
  height: 12rem;
  top: 20rem;
  left: 0;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura7 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 60%;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura6 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 30%;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura5 img {
  position: absolute;
  width: 40rem;
  top: 5rem;
  left: 0;
  opacity: 0;
  animation: appear 2s 2.5s forwards;
}

#figura4 img {
  position: absolute;
  width: 40rem;
  top: 94.5vh;
  left: 30rem;
  opacity: 0;
  animation: appear 2s 1.4s forwards;
}

#figura3 img {
  position: absolute;
  width: 20rem;
  top: 79.2vh;
  left: 0;
  opacity: 0;
  animation: appear 2s 1.8s forwards;
}

#figura2 img {
  position: absolute;
  width: 20rem;
  top: 82.7vh;
  right: 0;
  opacity: 0;
  animation: appear 2s 1.2s forwards;
}

#figura1 img {
  position: absolute;
  width: 20rem;
  top: 4rem;
  right: 0;
  opacity: 0;
  animation: appear 2s 1s forwards;
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
  height: 68rem;
  opacity: 0;
  animation: appear 3s forwards;
}

@keyframes appear {
  100% {
    opacity: 1;
  }
}

@media (max-width: 600px) {

  .content {
    height: 150rem;
  }

  .content p {
  font-size: 2.2rem;
}

  .banner img {
    width: 25rem;
  }

  .item-mantenimiento {
    position: absolute;
    color: var(--color-base);
    top: 125rem;
    width: 100vw;
    left: 0vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  .item-finalizado {
    position: absolute;
    color: var(--color-base);
    top: 118rem;
    left: 25vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  .progress-bar-progreso {
    width: 70vw;
    height: 3rem;
    background-color: #e0e0e0;
    border-radius: 4rem 1rem;
    overflow: hidden;
  }

  .item-plazo {
    position: absolute;
    color: var(--color-base);
    top: 101rem;
    left: 15%;
    text-align: center;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .progress-bar {
    width: 70vw;
    height: 3rem;
    background-color: #e0e0e0;
    border-radius: 4rem 1rem;
    overflow: hidden;
  }

  .item-progreso {
    position: absolute;
    color: var(--color-base);
    top: 89rem;
    left: 15%;
    text-align: center;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }


  #figura11 img {
    position: absolute;
    width: 40rem;
    top: 77rem;
    left: 0;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-pago3 p {
    position: absolute;
    color: var(--color-base);
    top: 79.5rem;
    left: 36vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  #figura10 img {
    position: absolute;
    width: 40rem;
    top: 74rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-pago2 p {
    position: absolute;
    color: var(--color-base);
    top: 68rem;
    left: 21vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  #figura9 img {
    position: absolute;
    width: 40rem;
    top: 62rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-pago1 p {
    position: absolute;
    color: var(--color-base);
    top: 56rem;
    left: 21vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  #figura8 img {
    position: absolute;
    width: 40rem;
    top: 50rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-inicio h1 {
    position: absolute;
    color: var(--color-base);
    top: 41.5rem;
    left: 32vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  #figura7 img {
    position: absolute;
    width: 40rem;
    top: 38rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-tipo h1 {
    position: absolute;
    color: var(--color-base);
    top: 29.5rem;
    left: 23vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }


  #figura6 img {
    position: absolute;
    width: 40rem;
    top: 26rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  .item-cliente h1 {
    position: absolute;
    color: var(--color-base);
    top: 17rem;
    left: 28vw;
    text-align: center;
    opacity: 0;
    animation: appear 2s 3s forwards;
  }

  #figura5 img {
    position: absolute;
    width: 40rem;
    top: 14rem;
    left: 15vw;
    opacity: 0;
    animation: appear 2s 2.5s forwards;
  }

  #figura4 {
    display: none;
  }


  #figura1 img {
    position: absolute;
    width: 15rem;
    top: 5.5rem;
    right: 0;
    opacity: 0;
    animation: appear 2s 1s forwards;
  }

  #figura2 img {
    position: absolute;
    width: 20rem;
    top: 138rem;
    right: 0;
    opacity: 0;
    animation: appear 2s 1.2s forwards;
  }

  #figura3 img {
    top: 135.5rem;
  }

}
</style>