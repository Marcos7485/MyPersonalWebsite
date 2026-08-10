<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { Swiper as SwiperInstance } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay } from 'swiper/modules'
import { useLanguageStore } from '../store/language.ts'
import { useImageStore } from '../store/imageStore.ts'

interface Review {
    id: number
    name: string
    stars: number
    opinion: string
}

const props = defineProps<{ reviews: Review[] }>()

const imageStore = useImageStore()
const languageStore = useLanguageStore()

onMounted(() => {
    imageStore.fetchImagePath()
})

const ReviewsObs = ref(false)
const swiperRef = ref<SwiperInstance | null>(null)

const ReviewsAnimate = () => {
    ReviewsObs.value = true
}

const onSwiper = (swiper: SwiperInstance) => {
    swiperRef.value = swiper
}

const goPrev = () => swiperRef.value?.slidePrev()
const goNext = () => swiperRef.value?.slideNext()

const reviewCount = computed(() =>
    Array.isArray(props.reviews) ? props.reviews.length : 0,
)

const hasReviews = computed(() => reviewCount.value > 0)
</script>

<template>
    <section id="Reviews" class="reviews" v-intersect="ReviewsAnimate">
        <div class="reviews-inner" :class="{ active: ReviewsObs }">
            <div class="reviews-line reviews-line--top" aria-hidden="true"></div>
            <div class="reviews-line reviews-line--bottom" aria-hidden="true"></div>

            <div class="reviews-art" aria-hidden="true">
                <div class="fig fig-1">
                    <img :src="`${imageStore.imagePath}/seccion-5/figura1.svg`" alt="">
                </div>
                <div class="fig fig-2">
                    <img :src="`${imageStore.imagePath}/seccion-5/figura2.svg`" alt="">
                </div>
                <div class="fig fig-3">
                    <img :src="`${imageStore.imagePath}/seccion-5/figura3.svg`" alt="">
                </div>
                <div class="fig fig-4">
                    <img :src="`${imageStore.imagePath}/seccion-5/figura4.svg`" alt="">
                </div>
            </div>

            <header class="reviews-head">
                <h1>
                    {{ languageStore.t('reviews.title') }}
                    <span class="reviews-count">({{ reviewCount }})</span>
                </h1>
                <p class="reviews-sub">{{ languageStore.t('reviews.subtitle') }}</p>
            </header>

            <div class="reviews-stage">
                <button
                    type="button"
                    class="reviews-nav reviews-nav--prev"
                    :disabled="!hasReviews"
                    :aria-label="languageStore.t('reviews.title') + ' anterior'"
                    @click="goPrev"
                >
                    <span class="reviews-nav-shape" aria-hidden="true"></span>
                </button>

                <div class="reviews-slider">
                    <Swiper
                        v-if="hasReviews"
                        :modules="[Autoplay]"
                        :grab-cursor="true"
                        :loop="reviewCount > 1"
                        :autoplay="{
                            delay: 3500,
                            disableOnInteraction: true,
                            pauseOnMouseEnter: true,
                        }"
                        @swiper="onSwiper"
                    >
                        <SwiperSlide v-for="review in props.reviews" :key="review.id">
                            <article class="review-item">
                                <div
                                    class="review-stars"
                                    :aria-label="`${review.stars} estrellas`"
                                >
                                    <i
                                        v-for="n in review.stars"
                                        :key="`${review.id}-star-${n}`"
                                        class="fa-solid fa-star"
                                    ></i>
                                </div>
                                <p class="review-name">{{ review.name }}</p>
                                <p class="review-opinion">{{ review.opinion }}</p>
                            </article>
                        </SwiperSlide>
                    </Swiper>

                    <p v-else class="reviews-empty">—</p>
                </div>

                <button
                    type="button"
                    class="reviews-nav reviews-nav--next"
                    :disabled="!hasReviews"
                    :aria-label="languageStore.t('reviews.title') + ' siguiente'"
                    @click="goNext"
                >
                    <span class="reviews-nav-shape" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.reviews {
    --reviews-bg: #02060c;
    --reviews-red: var(--color-first);
    position: relative;
    width: 100%;
    color: white;
    background:
        radial-gradient(ellipse 70% 50% at 50% 20%, rgba(18, 40, 72, 0.35), transparent 65%),
        var(--reviews-bg);
    overflow: hidden;
}

.reviews-inner {
    position: relative;
    width: 100%;
    min-height: 52rem;
    padding: 6rem 4rem 7rem;
    box-sizing: border-box;
}

.reviews-line {
    position: absolute;
    left: 0;
    width: 100%;
    height: 0.35rem;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.85), transparent) no-repeat;
    background-size: 200% 100%;
    background-position: -100% 0;
    opacity: 0;
    z-index: 2;
    pointer-events: none;
}

.reviews-line--top {
    top: 4.5rem;
}

.reviews-line--bottom {
    bottom: 4.5rem;
}

.reviews-inner.active .reviews-line {
    animation: drawLine 0.9s 0.35s linear forwards;
}

/* Figuras de marca */
.reviews-art {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
}

.fig {
    position: absolute;
    opacity: 0;
}

.fig img {
    display: block;
    width: 100%;
    height: auto;
}

.fig-1 {
    top: 8rem;
    left: 1.5rem;
    width: 22rem;
}

.fig-2 {
    bottom: 8rem;
    left: 12rem;
    width: 9rem;
}

.fig-3 {
    bottom: 7rem;
    right: 6rem;
    width: 22rem;
}

.fig-4 {
    bottom: 7rem;
    right: 4rem;
    width: 30rem;
}

.reviews-inner.active .fig-1 {
    animation: slideInLeft 0.85s 0.45s ease forwards;
}

.reviews-inner.active .fig-2 {
    animation: slideInLeft 0.85s 0.7s ease forwards;
}

.reviews-inner.active .fig-3 {
    animation: slideInRight 0.85s 0.55s ease forwards;
}

.reviews-inner.active .fig-4 {
    animation: slideInRight 0.85s 0.75s ease forwards;
}

/* Cabecera */
.reviews-head {
    position: relative;
    z-index: 3;
    max-width: 70rem;
    margin: 0 auto 3rem;
    text-align: center;
    opacity: 0;
    transform: translateY(1.2rem);
}

.reviews-inner.active .reviews-head {
    animation: fadeUp 0.75s 0.2s ease forwards;
}

.reviews-head h1 {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(3.2rem, 5vw, var(--fontsizeTitles));
    font-weight: 900;
    color: #f4f7fb;
    letter-spacing: 0.02em;
}

.reviews-count {
    color: var(--reviews-red);
    font-weight: 400;
}

.reviews-sub {
    margin: 1.2rem auto 0;
    max-width: 56rem;
    font-size: clamp(1.5rem, 2vw, var(--fontsize));
    line-height: 1.45;
    color: rgba(220, 230, 245, 0.78);
}

/* Stage + swiper */
.reviews-stage {
    position: relative;
    z-index: 4;
    display: grid;
    grid-template-columns: auto minmax(0, 58rem) auto;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    margin: 0 auto;
    opacity: 0;
    transform: translateY(1.4rem);
}

.reviews-inner.active .reviews-stage {
    animation: fadeUp 0.75s 0.4s ease forwards;
}

.reviews-slider {
    min-width: 0;
    width: min(58rem, 100%);
}

.reviews-slider :deep(.swiper) {
    width: 100%;
    overflow: hidden;
}

.review-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 2.5rem 1.5rem 1rem;
    min-height: 18rem;
    box-sizing: border-box;
}

.review-stars {
    display: flex;
    gap: 0.45rem;
    margin-bottom: 1.4rem;
}

.review-stars i {
    color: gold;
    font-size: 1.8rem;
    filter: drop-shadow(0 0 0.35rem rgba(255, 200, 50, 0.35));
}

.review-name {
    margin: 0 0 1rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2rem, 2.4vw, 2.6rem);
    color: var(--reviews-red);
}

.review-opinion {
    margin: 0;
    max-width: 48rem;
    font-size: clamp(1.6rem, 2vw, var(--fontsize));
    line-height: 1.5;
    color: rgba(245, 248, 255, 0.92);
}

.reviews-empty {
    text-align: center;
    color: rgba(255, 255, 255, 0.35);
    font-size: 2rem;
    padding: 4rem 0;
}

/* Flechas de marca (clip-path) */
.reviews-nav {
    appearance: none;
    border: none;
    background: transparent;
    padding: 0.6rem;
    cursor: pointer;
    opacity: 0.85;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.reviews-nav:hover:not(:disabled) {
    opacity: 1;
    transform: scale(1.08);
}

.reviews-nav:disabled {
    opacity: 0.25;
    cursor: default;
}

.reviews-nav-shape {
    display: block;
    width: 3.2rem;
    height: 4.2rem;
    background: #fff;
}

.reviews-nav--prev .reviews-nav-shape {
    clip-path: polygon(100% 0%, 75% 50%, 100% 100%, 100% 100%, 0% 50%, 100% 0);
}

.reviews-nav--next .reviews-nav-shape {
    clip-path: polygon(0 0, 100% 50%, 0 100%, 0% 100%, 25% 50%, 0% 0%);
}

.reviews-inner.active .reviews-nav {
    animation: pulseNav 2.2s 1.1s ease-in-out infinite;
}

@keyframes drawLine {
    0% {
        background-position: -100% 0;
        opacity: 0;
    }
    100% {
        background-position: 100% 0;
        opacity: 1;
    }
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(1.4rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-8rem);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(8rem);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulseNav {
    0%,
    100% {
        opacity: 0.75;
    }
    50% {
        opacity: 1;
    }
}

@media (max-width: 900px) {
    .reviews-inner {
        min-height: 54rem;
        padding: 5rem 2rem 6rem;
    }

    .fig-1 {
        width: 16rem;
        top: 7rem;
        left: 0.5rem;
        opacity: 0;
    }

    .fig-2 {
        width: 7rem;
        left: 4rem;
        bottom: 6rem;
    }

    .fig-3 {
        width: 14rem;
        right: 2rem;
        bottom: 5.5rem;
    }

    .fig-4 {
        width: 18rem;
        right: 1rem;
        bottom: 5.5rem;
    }

    .reviews-stage {
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 1rem;
        width: 100%;
    }

    .reviews-slider {
        width: 100%;
    }
}

@media (max-width: 600px) {
    .reviews-inner {
        min-height: 58rem;
        padding: 5rem 1.2rem 5.5rem;
    }

    .reviews-line--top {
        top: 3.5rem;
    }

    .reviews-line--bottom {
        bottom: 3.5rem;
    }

    .fig-1 {
        width: 12rem;
        top: 6rem;
        left: -1rem;
        opacity: 0.9;
    }

    .fig-2 {
        width: 5.5rem;
        left: 1rem;
        bottom: 5rem;
    }

    .fig-3 {
        width: 11rem;
        right: -0.5rem;
        bottom: 4.5rem;
    }

    .fig-4 {
        width: 14rem;
        right: -1rem;
        bottom: 4.5rem;
    }

    .reviews-head {
        margin-bottom: 2rem;
    }

    .reviews-stage {
        gap: 0.4rem;
    }

    .reviews-nav-shape {
        width: 2.2rem;
        height: 3.2rem;
    }

    .review-item {
        padding: 1.5rem 0.4rem;
        min-height: 20rem;
    }

    .review-stars i {
        font-size: 1.5rem;
    }
}
</style>
