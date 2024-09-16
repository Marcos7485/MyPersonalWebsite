<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useLanguageStore } from '../store/language.ts';
import axios from 'axios';
import { useImageStore } from '../store/imageStore.ts';

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});

const backgroundAContacto = computed(() => ({
    backgroundImage: `url(${imageStore.imagePath}/seccion-6/fondo.webp)`
}));


const languageStore = useLanguageStore();

const ContactObs = ref<boolean>(false);

const ContactoAnimate = (el: HTMLElement) => {
    ContactObs.value = true;
};

interface FormData {
    name: string;
    phone: string;
    email: string;
    language: string;
}

const formData = ref<FormData>({
    name: '',
    phone: '',
    email: '',
    language: '',
});

const responseMessage = ref<string | null>(null);
const isDisabled = ref<boolean>(false);

const submitForm = async () => {
    if (isDisabled.value) return; // Evita enviar el formulario de nuevo si ya está deshabilitado

    isDisabled.value = true; // Deshabilita el botón al hacer clic
    formData.value.language = languageStore.languages;

    try {
        const response = await axios.post('/contact', formData.value);
        responseMessage.value = response.data.message;
    } catch (error) {
        console.error('Error enviando formulario', error);
        responseMessage.value = 'Error.';
    } finally {
        isDisabled.value = false; // Vuelve a habilitar el botón si lo deseas
    }
};

</script>

<template>
    <section id="Contacto" v-intersect="ContactoAnimate">
        <div class="content-6">
            <div :style="backgroundAContacto" id="area-contacto" :class="{ active: ContactObs }"></div>
            <div id="fondo-contacto" :class="{ active: ContactObs }"></div>
            <div class="texto-contacto">
                <div>
                    <h1 :class="{ active: ContactObs }"
                        v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Contacto</h1>
                    <h1 :class="{ active: ContactObs }" v-if="languageStore.languages === 'pt'"
                        style="margin-left: 1.5rem;">Contato</h1>
                    <h1 :class="{ active: ContactObs }" v-if="languageStore.languages === 'eng'"
                        style="margin-left: 1.8rem;">Contact</h1>
                    <h5 :class="{ active: ContactObs }"
                        v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Sea parte de
                        nuestros clientes. Complete sus datos,
                        y nos comunicaremos a la brevedad.
                    </h5>
                    <h5 :class="{ active: ContactObs }" v-if="languageStore.languages === 'pt'">Seja parte dos nossos
                        clientes. Preencha seus dados e entraremos em contato o mais breve possível.</h5>
                    <h5 :class="{ active: ContactObs }" v-if="languageStore.languages === 'eng'">Become one of our
                        clients. Fill in your details, and we will get in touch as soon as possible.</h5>
                </div>
                <div class="path2" :class="{ active: ContactObs }"></div>
                <div>
                    <p :class="{ active: ContactObs }">&copy;2024</p>
                </div>
            </div>

            <div v-if="!responseMessage" class="form-contacto" :class="{ active: ContactObs }">
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
                        <input v-model="formData.phone" type="number"
                            :placeholder="languageStore.languages === 'pt' ? 'Telefone' : (languageStore.languages === 'eng' ? 'Phone' : 'Telefono')"
                            required>
                    </div>
                    <div>
                        <input v-model="formData.email" type="email" placeholder="E-mail" required>
                    </div>
                    <div>
                        <button class="btn" type="submit" :disabled="isDisabled">
                            <span
                                v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Contactarme</span>
                            <span v-if="languageStore.languages === 'pt'" style="padding: 2rem;">Contato</span>
                            <span v-if="languageStore.languages === 'eng'" style="padding: 2rem;">Contact</span>
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="responseMessage" class="responseFormSuccess">
                <i class="fa-solid fa-circle-check"></i>
                <div v-if="responseMessage">
                    <p v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Enviado con
                        éxito!</p>
                    <p v-if="languageStore.languages === 'pt'">Pronto!</p>
                    <p v-if="languageStore.languages === 'eng'">Success!</p>
                </div>
            </div>
            <div class="socialmedia">
                <div class="instagram" :class="{ active: ContactObs }">
                    <a href="https://www.instagram.com/dragonrojosoftware" target="_blank">
                        <img :src="`${imageStore.imagePath}/seccion-6/instagram.svg`">
                        <p :class="{ active: ContactObs }">@Dragonrojosoftware</p>
                    </a>
                </div>
                <div class="email" :class="{ active: ContactObs }">
                    <a href="mailto:support@dragonrojosoftware.com">
                        <img :src="`${imageStore.imagePath}/seccion-6/email.svg`">
                        <p :class="{ active: ContactObs }">support@dragonrojosoftware.com</p>
                    </a>
                </div>
            </div>
            <div id="figura5" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/1.svg`">
            </div>
            <div id="figura6" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/2.svg`">
            </div>
            <div id="figura7" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/3.svg`">
            </div>
            <div id="figura8" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/4.svg`">
            </div>
            <div id="figura9" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/5.svg`">
            </div>
            <div id="figura10" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/6.svg`">
            </div>
            <div id="figura11" :class="{ active: ContactObs }">
                <img :src="`${imageStore.imagePath}/seccion-6/7.svg`">
            </div>
        </div>
    </section>
</template>

<style scoped>
.responseFormSuccess {
    position: absolute;
    top: 20rem;
    right: 35rem;
    z-index: 10;
    opacity: 0;
    animation: appear 2s ease forwards;
}

.responseFormSuccess p {
    position: absolute;
    width: 25rem;
    top: 18rem;
    right: 0rem;
    z-index: 10;
    opacity: 0;
    animation: appear 2s ease forwards;
}

.responseFormSuccess i {
    position: absolute;
    right: 5rem;
    font-size: 15rem;
    color: green;
}

.form-contacto button {
    background: linear-gradient(to right, var(--color-first), transparent);
    border: solid 2px white;
    font-size: var(--fontsize);
    color: white;
    border-radius: 1rem 4rem;
    padding: .5rem;
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

.form-contacto.active {
    animation: appear 2s 2s ease forwards;
}

.form-contacto {
    position: absolute;
    top: 20rem;
    right: 35rem;
    z-index: 10;
    opacity: 0;
}

.email p.active {
    animation: translateImg 2s 1s ease forwards;
}

.email p {
    font-size: var(--fontsize);
    position: absolute;
    bottom: 7.5rem;
    left: 70rem;
    z-index: 10;
    transition: text-shadow .3s;
    opacity: 0;
}

.email p:hover {
    text-shadow: 0 0 2rem white;
}

.instagram p:hover {
    text-shadow: 0 0 2rem white;
}

.instagram p.active {
    animation: translateImgOposite 2s 1s forwards;
}

.instagram p {
    font-size: var(--fontsize);
    position: absolute;
    bottom: 17.5rem;
    left: 70rem;
    z-index: 10;
    transition: text-shadow .3s;
    opacity: 0;
}

.email.active img {
    animation: translateImg 2s 1s ease forwards;
}

.email img {
    position: absolute;
    bottom: 5rem;
    left: 60.5rem;
    z-index: 10;
    width: 9rem;
    opacity: 0;
}

.instagram.active img {
    animation: translateImgOposite 2s 1s forwards;
}

.instagram img {
    position: absolute;
    bottom: 15rem;
    left: 60rem;
    z-index: 10;
    width: 10rem;
    opacity: 0;
}

.socialmedia a {
    text-decoration: none;
    color: white;
}

.path2.active {
    animation: drawLineSecond 1s 2s linear forwards;
}

.path2 {
    position: absolute;
    bottom: 24rem;
    left: 20rem;
    width: 15%;
    height: .4rem;
    background: linear-gradient(to right, white, transparent) no-repeat;
    background-size: 200% 100%;
    z-index: 10;
    opacity: 0;
}

.texto-contacto h5.active {
    animation: translateImgOposite 2s 1s forwards;
}

.texto-contacto h5 {
    position: absolute;
    top: 37rem;
    width: 35rem;
    font-size: var(--fontsize);
    left: 14rem;
    z-index: 10;
    opacity: 0;
}

.texto-contacto p.active {
    animation: translateImgOposite 2s 1s forwards;
}

.texto-contacto p {
    position: absolute;
    top: 47rem;
    font-size: var(--fontsize);
    left: 28rem;
    z-index: 10;
    opacity: 0;
}

.texto-contacto h1.active {
    animation: translateImgOposite 2s .2s forwards;
}

.texto-contacto h1 {
    position: absolute;
    top: 25rem;
    left: 15rem;
    font-size: 8rem;
    z-index: 10;
    opacity: 0;
}

#fondo-contacto.active {
    animation: translateFondoContacto 2s 1s forwards;
}

#fondo-contacto {
    position: absolute;
    left: 10rem;
    top: 9.9rem;
    background-color: black;
    height: 55rem;
    width: 130rem;
    z-index: 10;
    opacity: 0;
}

#area-contacto.active {
    animation: translateImg 2s 1s ease forwards;
}

#area-contacto {
    position: absolute;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    left: 10rem;
    top: 10rem;
    height: 54.6rem;
    width: 130rem;
    z-index: 0;
    opacity: 0;
}

#figura11.active img {
    animation: translateImgOposite 2s .5s forwards;
}

#figura11 img {
    position: absolute;
    top: 10rem;
    width: 25rem;
    left: 10rem;
    z-index: 100;
    opacity: 0;
}

#figura10.active img {
    animation: translateImg 2s .5s ease forwards;
}

#figura10 img {
    position: absolute;
    top: 27rem;
    width: 12rem;
    right: 0;
    z-index: 100;
    opacity: 0;
}

#figura5.active img {
    animation: translateImg 2s 1s ease forwards;
}

#figura5 img {
    position: absolute;
    top: 0;
    width: 25rem;
    right: 0;
    z-index: 100;
    opacity: 0;
}

#figura6.active img {
    animation: translateImg 2s .2s ease forwards;
}

#figura6 img {
    position: absolute;
    bottom: 0;
    width: 25rem;
    right: 0;
    z-index: 100;
    opacity: 0;
}

#figura9.active img {
    animation: translateImg 2s .5s ease forwards;
}

#figura9 img {
    position: absolute;
    bottom: 0;
    width: 25rem;
    right: 28rem;
    z-index: 100;
    opacity: 0;
}

#figura8.active img {
    animation: translateImgOposite 2s .5s forwards;
}

#figura8 img {
    position: absolute;
    bottom: 0;
    width: 50rem;
    left: 24rem;
    z-index: 100;
    opacity: 0;
}

#figura7.active img {
    animation: translateImgOposite 2s forwards;
}

#figura7 img {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 25rem;
    z-index: 100;
    opacity: 0;
}

.content-6 {
    background-color: Black;
    display: flex;
    height: 70rem;
    width: 100%;
    position: relative;
    color: white;
    font-size: var(--fontsize);
    overflow-y: hidden;
    overflow-X: hidden;
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

@keyframes translateImgOposite {
    from {
        transform: translateX(-10rem);
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

@keyframes drawLineSecond {
    0% {
        background-position: -100% 0;
    }

    100% {
        background-position: 100% 0;
        opacity: 1;
    }
}

@keyframes translateFondoContacto {
    from {
        transform: translateX(-10rem);
    }

    to {
        transform: translateY(0);
        opacity: 90%;
    }
}


@media (max-width: 600px) {

    .responseFormSuccess {
        position: absolute;
        top: 45%;
        left: 20rem;
        z-index: 10;
        opacity: 0;
        animation: appear 2s ease forwards;
    }

    .responseFormSuccess p {
        display: none;
    }

    .responseFormSuccess i {
        position: absolute;
        top: 45%;
        left: 0;
        font-size: 15rem;
        color: green;
    }

    .form-contacto {
        position: absolute;
        display: flex;
        flex-direction: column;
        top: 42%;
        left: 0;
        width: 100%;
        z-index: 10;
        opacity: 0;
    }


    .email p {
        font-size: var(--fontsize);
        position: absolute;
        top: 76%;
        left: 25%;
        z-index: 10;
        transition: text-shadow .3s;
        opacity: 0;
    }

    .instagram p {
        font-size: var(--fontsize);
        position: absolute;
        top: 67%;
        left: 34%;
        z-index: 10;
        transition: text-shadow .3s;
        opacity: 0;
    }

    .email img {
        position: absolute;
        top: 73%;
        left: 5%;
        z-index: 10;
        width: 10rem;
        opacity: 0;
    }

    .instagram img {
        position: absolute;
        top: 62%;
        left: 10%;
        z-index: 10;
        width: 12rem;
        opacity: 0;
    }

    .path2 {
        position: absolute;
        top: 84%;
        left: 40%;
        width: 25%;
        height: .4rem;
        background: linear-gradient(to right, white, transparent) no-repeat;
        background-size: 200% 100%;
        z-index: 10;
        opacity: 0;
    }

    .texto-contacto h5 {
        position: absolute;
        top: 35rem;
        width: 70%;
        font-size: var(--fontsize);
        left: 15%;
        z-index: 10;
        opacity: 0;
    }

    .texto-contacto p {
        position: absolute;
        top: 85%;
        font-size: var(--fontsize);
        left: 45%;
        z-index: 10;
        opacity: 0;
    }

    .texto-contacto h1 {
        position: absolute;
        top: 23rem;
        left: 11.5rem;
        font-size: 8rem;
        z-index: 10;
        opacity: 0;
    }

    #fondo-contacto {
        position: absolute;
        left: 4.8%;
        top: 9.8%;
        background-color: black;
        height: 85.4%;
        width: 90.4%;
        z-index: 10;
        opacity: 0;
    }

    #area-contacto {
        position: absolute;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        left: 5%;
        top: 10%;
        height: 85%;
        width: 90%;
        z-index: 0;
        opacity: 0;
    }

    #figura11 img {
        position: absolute;
        top: 12rem;
        width: 25rem;
        left: 4rem;
        z-index: 100;
        opacity: 0;
    }

    #figura10 img {
        position: absolute;
        top: 70rem;
        width: 10rem;
        right: 0;
        z-index: 100;
        opacity: 0;
    }

    #figura5 img {
        position: absolute;
        top: 0;
        width: 25rem;
        right: 0;
        z-index: 100;
        opacity: 0;
    }

    #figura6 img {
        display: none;
    }

    #figura9 img {
        display: none;
    }

    #figura8 img {
        position: absolute;
        bottom: 0;
        width: 30rem;
        left: 24rem;
        z-index: 100;
        opacity: 0;
    }

    #figura7 img {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 25rem;
        z-index: 100;
        opacity: 0;
    }

    .content-6 {
        background-color: Black;
        display: flex;
        height: 110rem;
        width: 100%;
        position: relative;
        color: white;
        font-size: var(--fontsize);
        overflow-y: hidden;
        overflow-X: hidden;
    }


}
</style>