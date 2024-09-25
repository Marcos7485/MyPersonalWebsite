<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useLanguageStore } from '../../../store/language.ts';
import { useImageStore } from '../../../store/imageStore.ts';
import LanguagesSelector from '../../../components/LanguagesSelector.vue'


// Tipar las props explícitamente
const props = defineProps<{ hash: string }>();

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const languageStore = useLanguageStore();

// v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0"


interface FormData {
    name: string;
    email: string;
    code: string;
    stars: number;
    opinion: string;
    language: string;
}

const formData = ref<FormData>({
    name: '',
    email: '',
    code: props.hash,
    stars: 0,
    opinion: '',
    language: '',
});

const errorMessage = ref<string | null>(null);
const responseMessage = ref<string | null>(null);
const isDisabled = ref<boolean>(false);


const submitForm = async () => {
    if (isDisabled.value) return;

    isDisabled.value = true;
    formData.value.language = languageStore.languages;

    try {
        const response = await axios.post('/review', formData.value);

        if (response.status === 200 && response.data.message) {
            responseMessage.value = response.data.message;
            errorMessage.value = null;
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


const selectedRating = ref(0);

const hoverIndex = ref(0);

const setHover = (index: number) => {
    hoverIndex.value = index;
};

const clearHover = () => {
    hoverIndex.value = 0;
};

const selectRating = (index: number) => {
    formData.value.stars = index;
    console.log(index);
};

</script>

<template>
    <LanguagesSelector></LanguagesSelector>
    <div class="reviews-content">
        <div id="letters">
            <img :src="`${imageStore.imagePath}/letras.webp`" alt="">
        </div>
        <div id="logo">
            <img :src="`${imageStore.imagePath}/drs.webp`">
        </div>
        <div class="fondo">
            <div class="title" v-if="!responseMessage">
                <h1 v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Gracias por ser
                    parte de nosotros</h1>
                <h1 v-if="languageStore.languages === 'pt'">Obrigado por fazer parte de nós</h1>
                <h1 v-if="languageStore.languages === 'eng'">Thank you for being part of us</h1>
            </div>

            <div v-if="!responseMessage" class="form-contacto">
                <form @submit.prevent="submitForm">
                    <div>
                        <input type="hidden" v-model="formData.language" />
                    </div>
                    <div>
                        <input v-model="formData.name" type="text"
                            :placeholder="languageStore.languages === 'pt' ? 'Nome' : (languageStore.languages === 'eng' ? 'Name' : 'Nombre')"
                            required>
                    </div>
                    <div>
                        <input v-model="formData.email" type="email" placeholder="E-mail" required>
                    </div>

                    <div class="stars">
                        <span v-for="star in 5" :key="star"
                            :class="{ 'hovered': star <= hoverIndex, 'selected': star <= formData.stars }"
                            @mouseover="setHover(star)" @mouseleave="clearHover" @click="selectRating(star)">
                            <i class="fa-solid fa-star"></i>
                        </span>
                    </div>

                    <div>
                        <textarea v-model="formData.opinion" maxlength="50"
                            style="width: 35rem;height: 5rem;font-size: 1.5rem;" placeholder="Su opinión"></textarea>
                    </div>
                    <div>
                        <button class="btn" type="submit" :disabled="isDisabled">
                            <span
                                v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Calificar</span>
                            <span v-if="languageStore.languages === 'pt'" style="padding: 2rem;">Avaliar</span>
                            <span v-if="languageStore.languages === 'eng'" style="padding: 2rem;">Rate</span>
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="errorMessage">
                <p style="color: red;font-size: var(--fontsize);">{{ errorMessage }}</p>
            </div>
            <div v-if="responseMessage" class="responseFormSuccess">
                <h1 v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Gracias por tu
                    opinión</h1>
                <h1 v-if="languageStore.languages === 'pt'">Obrigado pela sua opinião</h1>
                <h1 v-if="languageStore.languages === 'eng'">Thank you for your opinion</h1>
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>
</template>

<style scoped>
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