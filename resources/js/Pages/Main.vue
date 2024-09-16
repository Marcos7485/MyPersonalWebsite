<script setup lang="ts">
import { DefineProps } from 'vue'
import Header from '../../components/HeaderView.vue'
import Footer from '../../components/FooterView.vue'
import LanguagesSelector from '../../components/LanguagesSelector.vue'
import Seccion1 from '../../components/seccion-1.vue'
import Seccion2 from '../../components/seccion-2.vue'
import Seccion3 from '../../components/seccion-3.vue'
import Seccion4 from '../../components/seccion-4.vue'
import Seccion5 from '../../components/seccion-5.vue'
import Seccion6 from '../../components/seccion-6.vue'


interface Review {
  id: number;
  name: string;
  stars: number;
  opinion: string;
}

// Tipar las props explícitamente
const props = defineProps<{ reviews: Review[] }>()


</script>

<template>
    <Header></Header>
    <LanguagesSelector></LanguagesSelector>
    <Seccion1></Seccion1>
    <Seccion2></Seccion2>
    <Seccion3></Seccion3>
    <Seccion4></Seccion4>
    <Seccion5 :reviews="props.reviews"></Seccion5>
    <Seccion6></Seccion6>
    <Footer></Footer>
</template>

<style scoped></style>