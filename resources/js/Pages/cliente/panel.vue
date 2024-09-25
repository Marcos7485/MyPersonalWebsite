<script setup lang="ts">

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
  hash: string
}

// Tipar las props explícitamente
const props = defineProps<{ project: Project }>();

</script>

<template>
  <div>
    <div>
      <div class="content">
        <h1>Panel</h1>
        <p>id: {{ props.project.id }}</p>
        <p>Nombre: {{ props.project.cliente }}</p>
        <p>Tipo: {{ props.project.tipo }}</p>
        <p>Pago de Entrada: {{ props.project.pago_entrada }} (${{ Math.round(Number(props.project.valor_total)/2) }})</p>
        <p>Pago Final: {{ props.project.pago_final }} (${{ Math.round(Number(props.project.valor_total)/2) }})</p>
        <p>Valor Total: ${{ props.project.valor_total }}</p>
        <p>Plazo: {{ props.project.plazo }}</p>
        <p>Progreso del proyecto: {{ props.project.progreso }} %</p>
        <p>Finalizado: {{ props.project.finalizado }}</p>
        <p>Mantenimiento: {{ props.project.mantenimiento }}</p>
        <p>Codigo del proyecto: {{ props.project.hash }}</p>
      </div>
    </div>
  </div>

</template>

<style scoped>

.content h1{
    color: var(--color-first);
}

.content p {
    font-size: var(--fontsize);
    color: white;
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