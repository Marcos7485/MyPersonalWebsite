<script setup lang="ts">
import { ref, onMounted, defineProps } from 'vue';
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination, Autoplay } from 'swiper/modules';
import { useLanguageStore } from '../store/language.ts';
import { inject } from 'vue';
import { useImageStore } from '../store/imageStore.ts';


const props = defineProps<{ reviews: Review[] }>()

const imageStore = useImageStore();
onMounted(() => {
    imageStore.fetchImagePath();
});


interface Review {
    id: number;
    name: string;
    stars: number;
    opinion: string;
}

const reviews = inject<Array<Review> | undefined>('reviews', []);

const languageStore = useLanguageStore();

const ReviewsObs = ref<boolean>(false);

const ReviewsAnimate = (el: HTMLElement) => {
    ReviewsObs.value = true;
};

const cantidadDeReviews = Array.isArray(props.reviews) ? props.reviews.length : 0;

</script>

<template>
    <section id="Reviews" v-intersect="ReviewsAnimate">
        <div class="content-5">
            <h1 :class="{ active: ReviewsObs }">Reviews ({{ cantidadDeReviews }})</h1>
            <h2 :class="{ active: ReviewsObs }"
                v-if="languageStore.languages === 'esp' || languageStore.languages.length === 0">Únete a las personas
                satisfechas luego de concretar tu proyecto con
                nosotros!, tu opinión si nos importa!</h2>
            <h2 :class="{ active: ReviewsObs }" v-if="languageStore.languages === 'pt'">Junte-se às pessoas satisfeitas
                após concretizar seu projeto conosco! A sua opinião é importante para nós!</h2>
            <h2 :class="{ active: ReviewsObs }" v-if="languageStore.languages === 'eng'">
                Join the satisfied people after
                completing your project with us! Your opinion matters to us!</h2>
            <div class="path3" :class="{ active: ReviewsObs }"></div>
            <div id="form-arrowRight" :class="{ active: ReviewsObs }"></div>
            <div id="form-arrowLeft" :class="{ active: ReviewsObs }"></div>

            <Swiper :modules="[Pagination, Autoplay]" :grabCursor="true" :loop="true" :pagination="false"
                :autoplay="{ delay: 2000, disableOnInteraction: false }">
                <SwiperSlide v-for="review in props.reviews" :key="review.id">
                    <div class="item-review" :class="{ active: ReviewsObs }">
                        <div class="stars" v-for="puntuacion in review.stars">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="element">
                            <div class="nombre">
                                <p>{{ review.name }}</p>
                            </div>
                            <div class="opinion">
                                <p>{{ review.opinion }}</p>
                            </div>
                        </div>
                    </div>
                </SwiperSlide>
            </Swiper>

            <div id="fig1" :class="{ active: ReviewsObs }"><img :src="`${imageStore.imagePath}/seccion-5/figura1.svg`">
            </div>
            <div id="fig2" :class="{ active: ReviewsObs }"><img :src="`${imageStore.imagePath}/seccion-5/figura2.svg`">
            </div>
            <div id="fig3" :class="{ active: ReviewsObs }"><img :src="`${imageStore.imagePath}/seccion-5/figura3.svg`">
            </div>
            <div id="fig4" :class="{ active: ReviewsObs }"><img :src="`${imageStore.imagePath}/seccion-5/figura4.svg`">
            </div>
            <div class="path4" :class="{ active: ReviewsObs }"></div>
        </div>
    </section>
</template>

<style scoped>
.swiper {
    top: 15rem;
    width: 60rem;
    height: 25rem;
    overflow: hidden;
}

.swiper-slide p {
    width: 100%;
    height: 100%;
}


#fig4.active img {
    animation: translateImgOposite 1s 1.5s forwards;
}

#fig4 img {
    position: absolute;
    width: 35rem;
    bottom: 9rem;
    right: 5rem;
    opacity: 0;
}

#fig3.active img {
    animation: translateImgOposite 1s 1s forwards;
}

#fig3 img {
    position: absolute;
    width: 25rem;
    bottom: 9rem;
    right: 5rem;
    opacity: 0;
}

#fig2.active img {
    animation: translateImg 1s 1.5s ease forwards;
}

#fig2 img {
    position: absolute;
    width: 10rem;
    bottom: 10.5rem;
    left: 15rem;
    opacity: 0;
}

#fig1.active img {
    animation: translateImg 1s 1s ease forwards;
}

#fig1 img {
    position: absolute;
    width: 25rem;
    top: 10rem;
    left: 2rem;
    opacity: 0;
}

#form-arrowRight.active {
    animation: latitud 2s 2.5s ease infinite;
}

#form-arrowRight {
    position: absolute;
    height: 5rem;
    width: 4rem;
    top: 22rem;
    right: 26rem;
    clip-path: polygon(0 0, 100% 50%, 0 100%, 0% 100%, 25% 50%, 0% 0%);
    background-color: white;
    z-index: 100;
    opacity: 0;
}

#form-arrowLeft.active {
    animation: latitud 2s 2.5s ease infinite;
}

#form-arrowLeft {
    position: absolute;
    height: 5rem;
    width: 4rem;
    top: 22rem;
    left: 26rem;
    clip-path: polygon(100% 0%, 75% 50%, 100% 100%, 100% 100%, 0% 50%, 100% 0);
    background-color: white;
    z-index: 100;
    opacity: 0;
}

.nombre {
    color: var(--color-first);
}

.opinion {
    color: white;
    font-size: var(--fontsize);
}

.item-review.active {
    animation: appear 1s ease forwards;
}

.item-review {
    margin-top: 5rem;
    opacity: 0;
}

.stars {
    display: inline;
    margin-right: 5px;
}

.stars i {
    color: gold;
}

.element {
    display: flex;
    flex-direction: column;
    margin-top: 1rem;
    align-items: center;
}

.path4.active {
    animation: drawLineSecond 1s 1s linear forwards;
}

.path4 {
    position: absolute;
    top: 10rem;
    left: 0;
    width: 100%;
    height: .4rem;
    background: linear-gradient(to right, white, transparent) no-repeat;
    background-size: 200% 100%;
    z-index: 10;
    opacity: 0;
}

.path3.active {
    animation: drawLineSecond 1s 1s linear forwards;
}

.path3 {
    position: absolute;
    bottom: 10rem;
    left: 0;
    width: 100%;
    height: .4rem;
    background: linear-gradient(to right, white, transparent) no-repeat;
    background-size: 200% 100%;
    z-index: 10;
    opacity: 0;
}

.content-5 h2.active {
    animation: translateImgOposite 1s 2s forwards;
}

.content-5 h2 {
    color: white;
    font-size: var(--fontsize);
    position: absolute;
    top: 15rem;
    text-align: center;
    left: 8rem;
    width: 90%;
    opacity: 0;
}

.content-5 h1.active {
    animation: translateImgOposite 1s 2s forwards;
}

.content-5 h1 {
    color: white;
    font-size: var(--fontsizeTitles);
    position: absolute;
    top: 5rem;
    left: 2rem;
    opacity: 0;
}

.content-5 {
    height: 50rem;
    width: 100%;
    position: relative;
    font-size: var(--fontsize);
    overflow-y: hidden;
    overflow-X: hidden;
}

@keyframes latitud {

    50% {
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

@keyframes translateImgOposite {
    from {
        transform: translateX(-10rem);
    }

    to {
        transform: translateY(0);
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

@media (max-width: 600px) {


    .swiper {
        top: 15rem;
        width: 55rem;
        height: 35rem;
        overflow: hidden;
    }

    .swiper-slide p {
        width: 100%;
        height: 100%;
    }


    .nombre {
        color: var(--color-first);
        margin-left: -1rem;
    }


    #fig4 img {
        position: absolute;
        width: 15rem;
        bottom: 9rem;
        right: 5rem;
        opacity: 0;
    }

    #fig3 img {
        position: absolute;
        width: 15rem;
        bottom: 9rem;
        left: 60%;
        opacity: 0;
    }

    #fig2 img {
        position: absolute;
        width: 8rem;
        bottom: 10.5rem;
        left: 5rem;
        opacity: 0;
    }

    #fig1 img {
        position: absolute;
        width: 15rem;
        top: 10rem;
        left: 2rem;
        opacity: 0;
    }

    #form-arrowRight {
        position: absolute;
        height: 5rem;
        width: 2rem;
        top: 22rem;
        right: 7rem;
        clip-path: polygon(0 0, 100% 50%, 0 100%, 0% 100%, 25% 50%, 0% 0%);
        background-color: white;
        z-index: 100;
        opacity: 0;
    }

    #form-arrowLeft {
        position: absolute;
        height: 5rem;
        width: 2rem;
        top: 22rem;
        left: 7rem;
        clip-path: polygon(100% 0%, 75% 50%, 100% 100%, 100% 100%, 0% 50%, 100% 0);
        background-color: white;
        z-index: 100;
        opacity: 0;
    }

    .item-review {
        margin-top: 5rem;
        width: 100%;
        opacity: 0;
        z-index: 100;
    }

    .content-5 h2 {
        color: white;
        font-size: var(--fontsize);
        position: absolute;
        top: 12rem;
        text-align: center;
        left: 3rem;
        width: 90%;
        z-index: 100;
        opacity: 0;
    }

    .content-5 h1 {
        color: white;
        font-size: var(--fontsizeTitles);
        position: absolute;
        top: 5rem;
        left: 2rem;
        opacity: 0;
    }

    .content-5 {
        height: 50rem;
        width: 100%;
        position: relative;
        font-size: var(--fontsize);
        overflow-y: hidden;
        overflow-X: hidden;
    }

}
</style>