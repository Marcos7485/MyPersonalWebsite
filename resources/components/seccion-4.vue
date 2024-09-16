<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const backgroundAPortalofio = computed(() => ({
    backgroundImage: `url(${imageStore.imagePath}/seccion-4/fondo.webp)`
}));

const languageStore = useLanguageStore();

const PortafolioObs = ref<boolean>(false);

const PortafolioAnimate = (el: HTMLElement) => {
    PortafolioObs.value = true;
};

</script>

<template>
    <section id="Portafolio" v-intersect="PortafolioAnimate">
        <div class="content-4">
            <div :style="backgroundAPortalofio" id="area-portafolio" :class="{ active: PortafolioObs }"></div>
            <div class="fondo-portfolio"></div>
            <div class="group-images">
                <div id="portafolio-img1" :class="{ active: PortafolioObs }">
                    <img :src="`${imageStore.imagePath}/seccion-4/img1.webp`">
                </div>
                <div id="portafolio-img2" :class="{ active: PortafolioObs }">
                    <img :src="`${imageStore.imagePath}/seccion-4/img2.webp`">
                </div>
                <div id="portafolio-img3" :class="{ active: PortafolioObs }">
                    <img :src="`${imageStore.imagePath}/seccion-4/img3.webp`">
                </div>
                <div id="portafolio-img4" :class="{ active: PortafolioObs }">
                    <img :src="`${imageStore.imagePath}/seccion-4/img4.webp`">
                </div>
            </div>
            <div>
                <h1 :class="{ active: PortafolioObs }"
                    v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Nuestro Portafolio
                </h1>
                <h1 :class="{ active: PortafolioObs }" v-if="languageStore.languages === 'pt'">Nosso Portfólio</h1>
                <h1 :class="{ active: PortafolioObs }" v-if="languageStore.languages === 'eng'">Our Portfolio</h1>
            </div>
            <div>
                <p :class="{ active: PortafolioObs }"
                    v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">En Dragon Rojo
                    Software, desarrollamos una amplia gama de
                    soluciones digitales, incluyendo páginas
                    web de comercio electrónico, landing pages para perfiles profesionales, aplicaciones SaaS (Software
                    as a Service), y soluciones administrativas. Nuestra versatilidad nos permite adaptarnos a las
                    necesidades específicas de cada cliente, ofreciendo siempre resultados innovadores y de alta
                    calidad.</p>
                <p :class="{ active: PortafolioObs }" v-if="languageStore.languages === 'pt'">Na Dragon Rojo Software,
                    desenvolvemos uma ampla gama de soluções digitais, incluindo sites de comércio eletrônico, landing
                    pages para perfis profissionais, aplicações SaaS (Software as a Service) e soluções administrativas.
                    Nossa versatilidade nos permite adaptar às necessidades específicas de cada cliente, sempre
                    oferecendo resultados inovadores e de alta qualidade.</p>
                <p :class="{ active: PortafolioObs }" v-if="languageStore.languages === 'eng'">At Dragon Rojo Software,
                    we develop a wide range of digital solutions, including e-commerce websites, landing pages for
                    professional profiles, SaaS (Software as a Service) applications, and administrative solutions. Our
                    versatility allows us to adapt to the specific needs of each client, always delivering innovative
                    and high-quality results.</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
#portafolio-img4.active {
    animation: translateImgUp 1s 2s forwards;
}

#portafolio-img4 {
    opacity: 0;
}

#portafolio-img3.active {
    animation: translateImgUp 1s 1.5s forwards;
}

#portafolio-img3 {
    opacity: 0;
}

#portafolio-img2.active {
    animation: translateImgUp 1s 1s forwards;
}

#portafolio-img2 {
    opacity: 0;
}

#portafolio-img1.active {
    animation: translateImgUp 1s .5s forwards;
}

#portafolio-img1 {
    opacity: 0;
}

.group-images img {
    width: 21rem;
    height: 70rem;
    margin-right: 1rem;
}

.group-images {
    display: flex;
    position: absolute;
    width: 60%;
    right: -3rem;
}

.fondo-portfolio {
    position: absolute;
    left: 3rem;
    top: 4rem;
    background-color: black;
    height: 60rem;
    width: 54rem;
    z-index: 100;
    opacity: 70%;
    border-radius: 3rem;
}

#area-portafolio.active {
    animation: translateImgOposite 2s 2.5s forwards;
}

#area-portafolio {
    position: absolute;
    background-size: 'cover';
    background-position: center;
    background-repeat: 'no-repeat';
    background-color: #f5f5f5;
    left: 0;
    top: 0;
    height: 70rem;
    width: 61rem;
    opacity: 0;
}

.content-4 p.active {
    animation: appear 3s 1s forwards;
}

.content-4 p {
    position: absolute;
    text-align: left;
    width: 44rem;
    top: 32rem;
    left: 8rem;
    font-size: var(--fontsize);
    color: white;
    z-index: 100;
    opacity: 0;
}

.content-4 h1.active {
    animation: appear 3s forwards;
}

.content-4 h1 {
    position: absolute;
    text-align: left;
    width: 25rem;
    top: 10rem;
    left: 8rem;
    font-size: 8rem;
    color: white;
    z-index: 100;
    opacity: 0;
}

.content-4 {
    background-color: Black;
    height: 70rem;
    width: 100%;
    position: relative;
    overflow-y: hidden;
    overflow-X: hidden;
}

@keyframes appear {
    100% {
        opacity: 1;
    }
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

@keyframes translateImgUp {
    from {
        transform: translateY(10rem);
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 600px) {


    .group-images img {
        width: 13rem;
        height: 45rem;
        margin-right: 1rem;
    }

    .group-images {
        display: flex;
        position: absolute;
        width: 60%;
        left: 0;
        top: 71rem;
    }

    .fondo-portfolio {
        position: absolute;
        left: 5%;
        top: 4rem;
        background-color: black;
        height: 62rem;
        width: 90%;
        z-index: 100;
        opacity: 70%;
        border-radius: 3rem;
    }

    #area-portafolio.active {
        animation: translateImgOposite 2s .5s forwards;
    }

    #area-portafolio {
        position: absolute;
        background-size: 'cover';
        background-position: center;
        background-repeat: 'no-repeat';
        background-color: #f5f5f5;
        left: 0;
        top: 0;
        height: 70rem;
        width: 100%;
        opacity: 0;
    }

    .content-4 p {
        position: absolute;
        text-align: center;
        width: 75%;
        top: 32rem;
        left: 8rem;
        font-size: var(--fontsize);
        color: white;
        z-index: 100;
        opacity: 0;
    }

    .content-4 h1 {
        position: absolute;
        text-align: left;
        width: 25rem;
        top: 10rem;
        left: 8rem;
        font-size: 8rem;
        color: white;
        z-index: 100;
        opacity: 0;
    }

    .content-4 {
        background-color: Black;
        height: 115rem;
        width: 100%;
        position: relative;
        overflow-y: hidden;
        overflow-X: hidden;
    }

}
</style>