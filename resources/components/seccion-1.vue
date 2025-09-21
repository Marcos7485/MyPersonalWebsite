<script setup lang="ts">
import { onMounted } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import { useImageStore } from '../store/imageStore.ts';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const languageStore = useLanguageStore();

</script>

<template>
    <section id="Home">
        <div class="content">
            <div class="fondo">
                <div>
                    <img :src="`${imageStore.imagePath}/seccion-1/picture.webp`" alt="Marcos">
                </div>
                <div>
                    <h1 v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Desarrollador
                    </h1>
                    <h1 v-if="languageStore.languages === 'pt'">Desenvolvedor</h1>
                    <h1 v-if="languageStore.languages === 'eng'">Developer</h1>
                    <p id="esp" v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">¡Hola!
                        Soy Marcos Gonzalez,
                        soy desarrollador web fullstack dedicado a realizar las soluciones mas eficientes a los
                        problemas de todas las personas
                        con un software que entiendan todas las personas.
                        Menos es mas, solucionar es mejor que lucir, facilidad, no complejidad, si deberia decir la
                        descripcion en una sola palabra
                        es - eficiencia -.
                    </p>

                    <p v-if="languageStore.languages === 'pt'">
                        Olá!
                        Sou Marcos Gonzalez,
                        sou desenvolvedor web fullstack dedicado a criar as soluções mais eficientes para os problemas
                        de todas as pessoas,
                        com um software que qualquer pessoa possa entender.
                        Menos é mais, facilidade, não complexidade.
                        Se eu tivesse que descrever em uma única palavra, seria - eficiência -.
                    </p>

                    <p v-if="languageStore.languages === 'eng'">
                        Hello!
                        I'm Marcos Gonzalez,
                        a full-stack web developer dedicated to creating the most efficient solutions to people's
                        problems,
                        with software that anyone can understand.
                        Less is more, solving is better than showing off, simplicity over complexity.
                        If I had to describe it in a single word, it would be - efficiency -.
                    </p>
                </div>
            </div>
            <div id="fig-content1">
                <img :src="`${imageStore.imagePath}/seccion-1/figura1.svg`">
            </div>
            <div id="fig-content2">
                <img :src="`${imageStore.imagePath}/seccion-1/figura2.svg`">
            </div>
            <div id="fig-content3">
                <img :src="`${imageStore.imagePath}/seccion-1/figura3.svg`">
            </div>
        </div>
    </section>
</template>

<style scoped>
.content {
    position: relative;
    /* Necesario para posicionar los elementos hijos absolutamente */
    width: 100%;
    height: 69rem;
    margin-top: -5rem;
    padding: 10rem;
    animation: appear 2s forwards;
    overflow-y: hidden;
    overflow-X: hidden;
}

.fondo {
    position: relative;
    top: 5rem;
    left: 5rem;
    background-color: black;
    border-radius: 1rem 4rem;
    box-shadow: 0 0 .5rem white;
    opacity: 90%;
    align-items: center;
    text-align: left;
    display: flex;
    padding: 5rem;
    width: 90%;
    z-index: 20;
}


#fig-content3 img {
    position: absolute;
    bottom: 0;
    left: 80%;
    width: 10rem;
    z-index: 99;
    opacity: 0;
    animation: translateImgUp 1s 1s ease forwards;
}

#fig-content2 img {
    position: absolute;
    width: 8rem;
    bottom: 0;
    left: 78%;
    z-index: 100;
    opacity: 0;
    animation: translateImgUp 1s .5s ease forwards;
}

#fig-content1 img {
    position: absolute;
    top: 0;
    left: 5rem;
    width: 22rem;
    z-index: 100;
    opacity: 0;
    animation: translateImgDown 1s .5s ease forwards;
}

.fondo h1 {
    font-size: var(--fontsizeTitles);
    color: var(--color-first);
}

.fondo h2 {
    color: var(--color-first);
}

.fondo div img {
    max-width: 25rem;
    border-radius: 3rem;
    margin-right: 3rem;
}

.fondo div p {
    font-size: var(--fontsize);
    color: white;
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

@keyframes translateImgDown {
    from {
        transform: translateY(-10rem);
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes appear {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}


@media (max-width: 600px) {


    .content {
        position: relative;
        width: 100%;
        height: 110rem;
        padding: 0;
        margin-top: -5rem;
        animation: appear 2s forwards;
        overflow-y: hidden;
        overflow-X: hidden;
    }

    .fondo {
        display: flex;
        flex-direction: column;
        position: relative;
        top: 15rem;
        left: 3rem;
        background-color: black;
        border-radius: 1rem 4rem;
        box-shadow: 0 0 .5rem white;
        opacity: 90%;
        align-items: center;
        text-align: center;
        display: flex;
        padding: 5rem;
        width: 90%;
        z-index: 20;
    }

    .fondo div img {
        max-width: 25rem;
        border-radius: 3rem;
        margin-right: 0;
    }


    #fig-content3 img {
        position: absolute;
        bottom: 0;
        left: 80%;
        width: 10rem;
        z-index: 9;
        opacity: 0;
        animation: translateImgUp 1s 1s ease forwards;
    }

    #fig-content2 img {
        position: absolute;
        width: 8rem;
        bottom: 0;
        left: 78%;
        z-index: 100;
        opacity: 0;
        animation: translateImgUp 1s .5s ease forwards;
    }

    #fig-content1 img {
        position: absolute;
        top: 5rem;
        left: 1rem;
        width: 22rem;
        z-index: 100;
        opacity: 0;
        animation: translateImgDown 1s .5s ease forwards;
    }


}
</style>