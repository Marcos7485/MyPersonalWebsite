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
            <div>
                <h1 :class="{ active: SoftwaresObs }">Agenda</h1>
            </div>
            <div id="texto-3" :class="{ active: SoftwaresObs }">
                <p v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">¡Gestiona tu tiempo
                    y tu negocio de manera eficiente!
                    Nuestra aplicación ofrece una agenda personal ilimitada, permitiéndote organizar tus compromisos sin
                    restricciones. Crea enlaces personalizados para compartir tus horarios con los clientes de forma
                    práctica. Además, puedes generar un enlace general perfecto para promocionar tus servicios en las
                    redes sociales. <br>
                    ¡Si tienes un equipo, tenemos la solución ideal! Con nuestra herramienta, puedes crear tu propia
                    empresa y añadir hasta 4 colaboradores. Ajusta los horarios de cada colaborador según las
                    necesidades individuales y personaliza la presentación de cada uno. También ofrecemos la opción de
                    generar un enlace empresarial, donde puedes mostrar a todo tu equipo en las redes sociales.
                    Y para facilitar aún más la gestión, tus colaboradores tendrán acceso a una página personalizada
                    donde podrán gestionar sus propios horarios y clientes de forma remota.
                </p>
                <p v-if="languageStore.languages === 'pt'">Gerencie seu tempo e seu negócio de forma eficiente!
                    Nossa aplicação oferece uma agenda pessoal ilimitada, permitindo que você organize seus compromissos
                    sem restrições. Crie links personalizados para compartilhar seus horários com os clientes de forma
                    prática. Além disso, você pode gerar um link geral perfeito para promover seus serviços nas redes
                    sociais. <br>
                    Se você tem uma equipe, temos a solução ideal! Com nossa ferramenta, você pode criar sua própria
                    empresa e adicionar até 4 colaboradores. Ajuste os horários de cada colaborador conforme as
                    necessidades individuais e personalize a apresentação de cada um. Também oferecemos a opção de gerar
                    um link empresarial, onde você pode mostrar toda a sua equipe nas redes sociais. E para facilitar
                    ainda mais a gestão, seus colaboradores terão acesso a uma página personalizada onde poderão
                    gerenciar seus próprios horários e clientes de forma remota.</p>
                <p v-if="languageStore.languages === 'eng'">Manage your time and business efficiently!
                    Our application offers an unlimited personal calendar, allowing you to organize your appointments
                    without restrictions. Create customized links to share your schedules with clients conveniently.
                    Additionally, you can generate a general link perfect for promoting your services on social media.
                    <br>
                    If you have a team, we have the ideal solution! With our tool, you can create your own company and
                    add up to 4 collaborators. Adjust each collaborator's schedule according to their individual needs
                    and customize their presentation. We also offer the option to generate a business link where you can
                    showcase your entire team on social media. And to make management even easier, your collaborators
                    will have access to a personalized page where they can manage their own schedules and clients
                    remotely.
                </p>
            </div>
            <div>
                <a href="https://www.agendasoftware.online" target="_blank"
                    :class="{ active: SoftwaresObs }">www.agendasoftware.online</a>
            </div>
            <div id="img-3" :class="{ active: SoftwaresObs }">
                <img :src="`${imageStore.imagePath}/seccion-3/subtitle.webp`" alt="">
            </div>
            <div id="img-4" :style="backgroundSoftware" :class="{ active: SoftwaresObs }">
                <img v-if="!isMobile" :src="`${imageStore.imagePath}/seccion-3/agenda.webp`">
            </div>
        </div>
    </section>
</template>

<style scoped>
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