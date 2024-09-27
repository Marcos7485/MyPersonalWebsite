<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useLanguageStore } from '../../../store/language.ts';
import { useImageStore } from '../../../store/imageStore.ts';
import LanguagesSelector from '../../../components/LanguagesSelector.vue';
import { router } from '@inertiajs/vue3';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const languageStore = useLanguageStore();
const showVideo = ref(false);
const isMobile = ref(false); 


interface FormData {
    code: string;
}

const formData = ref<FormData>({
    code: '',
});

const onVideoEnd = () => {
    router.visit(redirectUrl.value); // Redirige después de que el video termina
};

onMounted(() => {
    isMobile.value = window.innerWidth < 600;
});

const errorMessage = ref<string | null>(null);
const responseMessage = ref<string | null>(null);
const isDisabled = ref<boolean>(false);
const redirectUrl = ref('');

const submitForm = async () => {
    if (isDisabled.value) return;

    isDisabled.value = true;

    try {
        const response = await axios.post('/cliente/load', formData.value);

        if (response.status === 200 && response.data.message) {
            redirectUrl.value = response.data.redirect;

            showVideo.value = true;

            // Redirige a la nueva página usando Inertia
            // router.visit(response.data.redirect);
        } else if (response.data.error) {
            throw new Error(response.data.error);
        }
    } catch (error: any) {
        errorMessage.value = error.response?.data?.error || error.message;
        responseMessage.value = null;
    } finally {
        isDisabled.value = false;
    }

};


</script>

<template>
    <LanguagesSelector></LanguagesSelector>

    <transition name="fade">
        <div v-if="showVideo" class="video-overlay">
            <!-- Cargar solo el video basado en el ancho de la pantalla -->
            <video v-if="!isMobile" ref="transitionVideo" @ended="onVideoEnd" autoplay class="transition-video">
                <source src="/public/images/cliente/video.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            <video v-if="isMobile" ref="transitionVideo" @ended="onVideoEnd" autoplay class="transition-video">
                <source src="/public/images/cliente/video-cel.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>
    </transition>

    <div class="reviews-content" :class="{ active: showVideo }">
        <div id="letters">
            <img :src="`${imageStore.imagePath}/letras.webp`" alt="">
        </div>
        <div id="logo">
            <img :src="`${imageStore.imagePath}/drs.webp`">
        </div>
        <div class="fondo">
            <div class="title" v-if="!responseMessage">
                <h1 v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Bienvenido</h1>
                <h1 v-if="languageStore.languages === 'pt'">Bem-vindo</h1>
                <h1 v-if="languageStore.languages === 'eng'">Welcome</h1>
            </div>

            <div v-if="!responseMessage" class="form-contacto">
                <form @submit.prevent="submitForm">
                    <div>
                        <input v-model="formData.code" type="text"
                            :placeholder="languageStore.languages === 'pt' ? 'Projeto' : (languageStore.languages === 'eng' ? 'Project' : 'Proyecto')"
                            required>
                    </div>
                    <div>
                        <button class="btn" type="submit" :disabled="isDisabled">
                            <span
                                v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Iniciar</span>
                            <span v-if="languageStore.languages === 'pt'" style="padding: 2rem;">Começar</span>
                            <span v-if="languageStore.languages === 'eng'" style="padding: 2rem;">Start</span>
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="errorMessage">
                <p style="color: red;font-size: var(--fontsize);">{{ errorMessage }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

.transition-video {
    width: 100%;
    max-width: 100vw;
    height: auto;
    max-height: 100vh;
    object-fit: cover;
}

.video-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: black;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

#logo img {
    position: absolute;
    width: 20rem;
    z-index: 1000;
    right: 18rem;
    top: -1rem;
}

#letters img {
    position: absolute;
    width: 35rem;
    z-index: 1000;
    top: 1rem;

}

.stars {
    display: flex;
    justify-content: center;
    cursor: pointer;
    margin-bottom: 1rem;
}

.stars span {
    font-size: 2rem;
    color: gray;
    opacity: 0.5;
    transition: opacity 0.3s;
}

.stars span.hovered,
.stars span.selected {
    opacity: 1;
    color: gold;
}

.responseFormSuccess {
    position: relative;
    z-index: 10;
    opacity: 0;
    animation: appear 2s ease forwards;
}

.responseFormSuccess i {
    position: relative;
    font-size: 15rem;
    color: green;
}

.form-contacto button {
    background: linear-gradient(to right, var(--color-first), transparent);
    border: solid 2px white;
    font-size: var(--fontsize);
    color: white;
    border-radius: 1rem 4rem;
    padding-inline: 5rem;
    margin-top: 1rem;
    transition: box-shadow .3s;
}

.form-contacto button:hover {
    box-shadow: 2px 2px 20px var(--color-first);
}

.form-contacto input {
    font-size: var(--fontsize);
    border-radius: 1rem 4rem;
    padding: .5rem;
    width: 35rem;
    margin-bottom: 1rem;
}

.form-contacto {
    position: relative;
    z-index: 10;
    opacity: 1;
    animation: appear 2s 2s ease forwards;
}

.reviews-content.active {
    opacity: 0;
}

.reviews-content {
    position: relative;
    padding: 5rem;
    width: 100vw;
    height: 68rem;
    text-align: center;
    display: flex;
    overflow-y: hidden;
    overflow-x: hidden;
    justify-content: center;
    transition: opacity 1s;
}

.fondo {
    position: relative;
    background-color: black;
    border-radius: 1rem 4rem;
    box-shadow: 0 0 .5rem white;
    opacity: 90%;
    align-items: center;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-top: 5rem;
    width: 88%;
    height: 85%;
    z-index: 20;
}

.fondo h1 {
    font-size: var(--fontsizeTitles);
    color: white;
    margin-bottom: 5rem;
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

    .desktop-video {
        display: none;
    }

    .mobile-video {
        display: block;
    }

    .transition-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* O `cover` si prefieres que el video llene completamente la pantalla */
    }

    #logo img {
        position: absolute;
        width: 15rem;
        z-index: 1000;
        right: 1rem;
        top: -1rem;
    }

    #letters img {
        position: absolute;
        width: 35rem;
        z-index: 1000;
        left: 0;
        top: 1rem;
    }

    .fondo h1 {
        font-size: 2rem;
        color: white;
        margin-bottom: 5rem;
    }

}
</style>