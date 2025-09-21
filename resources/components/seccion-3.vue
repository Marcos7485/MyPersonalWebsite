<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

const imageStore = useImageStore();
imageStore.fetchImagePath();

const backgroundSoftware = computed(() => ({
    backgroundImage: `url(${imageStore.imagePath}/seccion-3/agenda.webp)`
}));

const isMobile = ref(window.innerWidth <= 600);

const updateLayout = () => {
    isMobile.value = window.innerWidth <= 600;
};

onMounted(() => {
    window.addEventListener('resize', updateLayout);
});


const languageStore = useLanguageStore();

const SoftwaresObs = ref<boolean>(false);

const SoftwaresAnimate = (el: HTMLElement) => {
    SoftwaresObs.value = true;
};

</script>

<template>
    <section id="Softwares" v-intersect="SoftwaresAnimate">
        <div class="content-3">

            <div class="slider">
                <!-- <img v-if="!isMobile" :src="`${imageStore.imagePath}/seccion-3/icon-512x512.png`"> --> Slide de footos hablando del software y lo que hace.
            </div>

            <div>
                <a href="https://www.iqathleticsoftware.com" target="_blank"
                    :class="{ active: SoftwaresObs }">www.iqathleticsoftware.com</a>
            </div>

            <div id="img-4" :style="backgroundSoftware" :class="{ active: SoftwaresObs }">
                <img v-if="!isMobile" :src="`${imageStore.imagePath}/seccion-3/icon-512x512.png`">
            </div>
        </div>
    </section>
</template>

<style scoped>


.slider img{
    width: 70rem;
    height: 70rem;
}

.content-3 a.active {
    animation: translateImgOposite 2s 1.5s forwards;
}

.content-3 a {
    text-decoration: none;
    color: var(--color-first);
    font-size: 3rem;
    position: absolute;
    bottom: 7rem;
    left: 32rem;
    opacity: 0;
}

#texto-3.active p {
    animation: translateImgOposite 1s 1s forwards;
}

#texto-3 p {
    position: absolute;
    color: white;
    font-size: var(--fontsize);
    bottom: 12rem;
    left: 5rem;
    width: 90rem;
    opacity: 0;
}

.content-3 h1.active {
    animation: translateImgOposite 1s .3s forwards;
}

.content-3 h1 {
    position: absolute;
    top: 3.5rem;
    left: 22rem;
    color: var(--color-first);
    font-size: 15rem;
    opacity: 0;
}

#img-3.active img {
    animation: translateImgOposite 1s .6s forwards;
}

#img-3 img {
    position: absolute;
    width: 45rem;
    left: 40rem;
    top: 18rem;
    opacity: 0;
}

#img-4.active img {
    animation: translateImgOposite 1s forwards;
}

#img-4 img {
    position: absolute;
    border-radius: 1rem 10rem;
    width: 45rem;
    bottom: 10rem;
    right: 5rem;
    opacity: 0;
}

.content-3 {
    background-color: Black;
    height: 70rem;
    width: 100%;
    position: relative;
}


@keyframes translateImgOposite {
    from {
        transform: translateX(-10rem);
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 600px) {


    .content-3 a {
        text-decoration: none;
        color: var(--color-first);
        font-size: 3rem;
        position: absolute;
        top: 120rem;
        left: 8rem;
        opacity: 0;
    }

    #texto-3 p {
        position: absolute;
        color: white;
        font-size: var(--fontsize);
        top: 65rem;
        left: 1rem;
        width: 95%;
        opacity: 0;
    }

    .content-3 h1 {
        position: absolute;
        top: 5rem;
        left: 4rem;
        color: var(--color-first);
        font-size: 10rem;
        opacity: 0;
    }

    #img-3 img {
        position: absolute;
        width: 30rem;
        left: 20rem;
        top: 14.8rem;
        opacity: 0;
    }

    #img-4.active {
        animation: translateImgOposite 1s forwards;
    }

    #img-4 {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        border-radius: 1rem 10rem;
        width: 100%;
        height: 35rem;
        top: 25rem;
        left: 0;
        opacity: 0;
    }

    .content-3 {
        background-color: Black;
        height: 130rem;
        width: 100%;
        position: relative;
        overflow-y: hidden;
        overflow-X: hidden;
    }


}
</style>