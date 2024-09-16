<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});


const languageStore = useLanguageStore();

const DesarrolloWebObs = ref<boolean>(false);

const DesarrolloWebAnimate = (el: HTMLElement) => {
    DesarrolloWebObs.value = true;
};

</script>

<template>
    <section id="DesarrolloWeb" v-intersect="DesarrolloWebAnimate">
        <div class="content-2">
            <div>
                <h1 :class="{ active: DesarrolloWebObs }"
                    v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Desarrollo Web</h1>
                <h1 :class="{ active: DesarrolloWebObs }" v-if="languageStore.languages === 'pt'">Desenvolvimento Web
                </h1>
                <h1 :class="{ active: DesarrolloWebObs }" v-if="languageStore.languages === 'eng'">Web Development</h1>
            </div>
            <div id="texto-1" :class="{ active: DesarrolloWebObs }">
                <p v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Desarrollamos
                    páginas web a medida con la más alta tecnología del mercado para satisfacer tus
                    expectativas y exigencias. <br>
                    En Dragon Rojo nos colocamos al límite continuamente con las tecnologías para que cada desarrollo
                    sea de la más alta calidad.
                </p>

                <p v-if="languageStore.languages === 'pt'">
                    Desenvolvemos sites sob medida com a mais alta tecnologia do mercado para atender suas expectativas
                    e exigências. <br>
                    Na Dragon Rojo, nos desafiamos continuamente com as tecnologias para garantir que cada
                    desenvolvimento seja da mais alta qualidade.
                </p>

                <p v-if="languageStore.languages === 'eng'">We develop custom websites with the latest technology on the
                    market to meet your expectations and demands. <br>
                    At Dragon Rojo, we continuously push the limits of technology to ensure that every development is of
                    the highest quality.</p>
            </div>
            <div id="texto-2" :class="{ active: DesarrolloWebObs }">
                <p v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Con responsividad
                    para todos los dispositivos, incluidos en todos nuestros desarrollos,
                    porque entendemos que el alcance de tu página web, creada por nosotros, habla de nosotros.
                </p>

                <p v-if="languageStore.languages === 'pt'">Com responsividade para todos os dispositivos, incluída em
                    todos os nossos desenvolvimentos, porque entendemos que o alcance do seu site, criado por nós, fala
                    sobre nós.</p>
                <p v-if="languageStore.languages === 'eng'">With responsiveness for all devices, included in all our
                    developments, because we understand that the reach of your website, created by us, reflects on us.
                </p>
            </div>
            <div id="img-1" :class="{ active: DesarrolloWebObs }">
                <img :src="`${imageStore.imagePath}/seccion-2/desarrolloWebImg.webp`" alt="">
            </div>
            <div id="img-2" :class="{ active: DesarrolloWebObs }">
                <img :src="`${imageStore.imagePath}/seccion-2/telefono.webp`" alt="">
            </div>
            <div class="path" :class="{ active: DesarrolloWebObs }"></div>
            <div id="figura1" :class="{ active: DesarrolloWebObs }"></div>
            <div id="figura2" :class="{ active: DesarrolloWebObs }"></div>
            <div id="figura3" :class="{ active: DesarrolloWebObs }"></div>
            <div id="figura4" :class="{ active: DesarrolloWebObs }"></div>
        </div>
    </section>
</template>

<style scoped>
#texto-2.active p {
    animation: appear 3s 2s forwards;
}

#texto-2 p {
    position: absolute;
    font-size: var(--fontsize);
    color: white;
    margin-right: 40rem;
    width: 60rem;
    left: 52rem;
    bottom: 15rem;
    opacity: 0;
}

#texto-1.active p {
    animation: appear 3s 1s forwards;
}

#texto-1 p {
    position: absolute;
    font-size: var(--fontsize);
    color: white;
    width: 60rem;
    left: 50rem;
    top: 20rem;
    opacity: 0;
}

.content-2 h1.active {
    animation: appear 3s 1s forwards;
}

.content-2 h1 {
    position: relative;
    top: 7rem;
    left: 3rem;
    font-size: var(--fontsizeTitles);
    color: var(--color-first);
    opacity: 0;
}

.path.active {
    animation: drawLine 3.5s linear forwards;
}

.path {
    position: absolute;
    bottom: 10rem;
    width: 100%;
    height: .4rem;
    background: linear-gradient(to right, var(--color-first), transparent) no-repeat;
    background-size: 200% 100%;
    opacity: 0;
}

#img-2.active img {
    animation: translateImg 1s 2s ease forwards;
}

#img-2 img {
    position: absolute;
    border-radius: 1rem;
    width: 45rem;
    left: 100rem;
    bottom: 0;
    z-index: 10;
    opacity: 0;
}

#img-1.active img {
    animation: translateImg 1s 1s ease forwards;
}

#img-1 img {
    position: absolute;
    border-radius: 1rem;
    width: 30rem;
    left: 16rem;
    top: 15rem;
    z-index: 10;
    opacity: 0;
}

#figura1.active {
    animation: translateFigura .5s .6s ease forwards;
}

#figura1 {
    clip-path: polygon(0% 0%, 65% 0, 100% 100%, 50% 100%);
    background: linear-gradient(to right, white, transparent);
    width: 45rem;
    height: 20rem;
    bottom: 7rem;
    left: -13rem;
    position: absolute;
    z-index: 5;
    opacity: 0;
}

#figura2.active {
    animation: translateFigura .5s .9s ease forwards;
}

#figura2 {
    clip-path: polygon(0% 0%, 100% 0, 50% 100%, 0% 100%);
    background: linear-gradient(to right, transparent, var(--color-first));
    width: 40rem;
    height: 20rem;
    bottom: 1rem;
    position: absolute;
    opacity: 0;
}

#figura3.active {
    animation: translateFigura .5s ease forwards;
}

#figura3 {
    clip-path: polygon(0% 0%, 65% 0, 100% 100%, 50% 100%);
    background: linear-gradient(to right, transparent, white);
    width: 45rem;
    height: 20rem;
    top: 6rem;
    right: -13rem;
    position: absolute;
    z-index: 0;
    opacity: 0;
}

#figura4.active {
    animation: translateFigura .5s .3s ease forwards;
}

#figura4 {
    clip-path: polygon(0% 0%, 100% 0, 50% 100%, 0% 100%);
    background: linear-gradient(to right, transparent, var(--color-first));
    width: 40rem;
    height: 20rem;
    right: -5rem;
    top: 10rem;
    position: absolute;
    opacity: 0;
}

.content-2 {
    background-color: Black;
    height: 69rem;
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

@keyframes drawLine {
    0% {
        background-position: -100% 0;
    }

    100% {
        background-position: 100% 0;
        opacity: 1;
    }
}


@keyframes translateImg {
    from {
        transform: translateX(20rem);
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes translateFigura {
    from {
        transform: translateY(10rem);
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 600px) {

    #texto-2 p {
        position: absolute;
        font-size: var(--fontsize);
        color: white;
        margin-right: 40rem;
        width: 55%;
        left: 5rem;
        bottom: 30rem;
        opacity: 0;
    }

    #texto-1.active p {
        animation: appear 3s 1s forwards;
    }

    #texto-1 p {
        position: absolute;
        font-size: var(--fontsize);
        color: white;
        width: 55%;
        left: 24rem;
        top: 23rem;
        opacity: 0;
        z-index: 100;
    }

    .content-2 h1 {
        position: relative;
        top: 10rem;
        left: 0rem;
        font-size: var(--fontsizeTitles);
        color: var(--color-first);
        opacity: 0;
        z-index: 100;
    }

    #img-2 img {
        position: absolute;
        border-radius: 1rem;
        width: 35rem;
        left: 28rem;
        bottom: 20rem;
        z-index: 10;
        opacity: 0;
    }

    #img-1 img {
        position: absolute;
        border-radius: 1rem;
        width: 20rem;
        left: 2rem;
        top: 25rem;
        z-index: 10;
        opacity: 0;
    }

    #figura1 {
        clip-path: polygon(0% 0%, 65% 0, 100% 100%, 50% 100%);
        background: linear-gradient(to right, white, transparent);
        width: 35rem;
        height: 15rem;
        bottom: 7rem;
        left: -13rem;
        position: absolute;
        z-index: 5;
        opacity: 0;
    }

    #figura2 {
        clip-path: polygon(0% 0%, 100% 0, 50% 100%, 0% 100%);
        background: linear-gradient(to right, transparent, var(--color-first));
        width: 20rem;
        height: 15rem;
        bottom: 5rem;
        position: absolute;
        opacity: 0;
    }

    #figura3 {
        clip-path: polygon(0% 0%, 65% 0, 100% 100%, 50% 100%);
        background: linear-gradient(to right, transparent, white);
        width: 35rem;
        height: 15rem;
        top: 5rem;
        right: -13rem;
        position: absolute;
        z-index: 0;
        opacity: 0;
    }

    #figura4 {
        clip-path: polygon(0% 0%, 100% 0, 50% 100%, 0% 100%);
        background: linear-gradient(to right, transparent, var(--color-first));
        width: 20rem;
        height: 15rem;
        right: -5rem;
        top: 10rem;
        position: absolute;
        opacity: 0;
    }

    .content-2 {
        background-color: Black;
        height: 110rem;
        width: 100%;
        position: relative;
        overflow-y: hidden;
        overflow-X: hidden;
    }

}
</style>