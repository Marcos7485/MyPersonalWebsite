<script setup lang="ts">
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { useLanguageStore } from '../store/language.ts'
import { useImageStore } from '../store/imageStore.ts'
import { openSoftware, toggleSoftware } from '../store/softwaresExpand.ts'

const imageStore = useImageStore()
const languageStore = useLanguageStore()

onMounted(() => {
    imageStore.fetchImagePath()
})

const visible = ref(false)
const open = computed(() => openSoftware.value === 'shop')
const detailsRef = ref<HTMLElement | null>(null)

const onIntersect = () => {
    visible.value = true
}

const features = computed(() => languageStore.messages.shop.features)
const adminFeatures = computed(() => languageStore.messages.shop.adminFeatures)

const featureIcons = [
    'fa-palette',
    'fa-tags',
    'fa-coins',
    'fa-percent',
    'fa-cart-shopping',
    'fa-user',
] as const

const adminIcons = [
    'fa-box',
    'fa-arrows-rotate',
    'fa-credit-card',
    'fa-chart-line',
    'fa-truck',
    'fa-store',
] as const

const toggle = () => {
    toggleSoftware('shop')
}

watch(open, async (isOpen) => {
    if (isOpen) {
        await nextTick()
        detailsRef.value?.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
    }
})
</script>

<template>
    <section id="Ecommerce" class="shop" :class="{ 'is-open': open }">
        <div class="shop-trigger" v-intersect="onIntersect" aria-hidden="true"></div>
        <div class="shop-mesh" aria-hidden="true"></div>
        <div class="shop-wash" aria-hidden="true"></div>

        <div class="shop-shell" :class="{ active: visible }">
            <button
                type="button"
                class="shop-cover"
                :aria-expanded="open"
                aria-controls="shop-details"
                @click="toggle"
            >
                <div class="shop-cover-media" aria-hidden="true">
                    <video
                        class="shop-cover-video shop-cover-video--desk"
                        :src="`${imageStore.imagePath}/cliente/video.mp4`"
                        autoplay
                        muted
                        loop
                        playsinline
                    ></video>
                    <video
                        class="shop-cover-video shop-cover-video--phone"
                        :src="`${imageStore.imagePath}/cliente/video-cel.mp4`"
                        autoplay
                        muted
                        loop
                        playsinline
                    ></video>
                    <div class="shop-cover-grid"></div>
                    <div class="shop-cover-glow"></div>
                    <div class="shop-cover-vignette"></div>
                </div>

                <div class="shop-cover-content">
                    <div class="shop-cover-brand">
                        <img
                            :src="`${imageStore.imagePath}/drs.webp`"
                            alt=""
                            width="48"
                            height="48"
                        />
                        <span>{{ languageStore.t('shop.brand') }}</span>
                    </div>
                    <h2 class="shop-cover-title">{{ languageStore.t('shop.headline') }}</h2>
                    <p class="shop-cover-price">{{ languageStore.t('shop.priceLabel') }}</p>
                    <p class="shop-cover-hint">
                        <span>{{ open ? languageStore.t('apps.close') : languageStore.t('apps.tap') }}</span>
                        <i
                            class="fa-solid"
                            :class="open ? 'fa-chevron-up' : 'fa-arrow-right'"
                            aria-hidden="true"
                        ></i>
                    </p>
                </div>
            </button>

            <div
                id="shop-details"
                ref="detailsRef"
                class="shop-collapse"
                :class="{ open }"
            >
                <div class="shop-collapse-inner">
                    <div class="shop-details">
                        <header class="shop-intro">
                            <div class="shop-intro-top">
                                <p class="shop-kicker">{{ languageStore.t('shop.whatTitle') }}</p>
                            </div>

                            <div class="shop-intro-main">
                                <div class="shop-intro-copy">
                                    <h1>{{ languageStore.t('shop.headline') }}</h1>
                                    <p>{{ languageStore.t('shop.whatBody') }}</p>
                                </div>

                                <aside class="shop-price-pill">
                                    <span class="shop-price-amount">{{ languageStore.t('shop.priceLabel') }}</span>
                                    <span class="shop-price-note">{{ languageStore.t('shop.priceBody') }}</span>
                                </aside>
                            </div>
                        </header>

                        <div class="shop-block">
                            <div class="shop-block-head">
                                <h2>{{ languageStore.t('shop.featuresLead') }}</h2>
                            </div>

                            <ul class="shop-mosaic">
                                <li
                                    v-for="(feature, index) in features"
                                    :key="feature.title"
                                    class="shop-tile"
                                    :style="{ '--i': index }"
                                >
                                    <i
                                        class="fa-solid shop-tile-icon"
                                        :class="featureIcons[index] || 'fa-circle-check'"
                                        aria-hidden="true"
                                    ></i>
                                    <h3>{{ feature.title }}</h3>
                                    <p>{{ feature.body }}</p>
                                </li>
                            </ul>
                        </div>

                        <div class="shop-block shop-block--admin">
                            <div class="shop-admin-copy">
                                <h2>{{ languageStore.t('shop.rolesTitle') }}</h2>
                                <p>{{ languageStore.t('shop.rolesLead') }}</p>

                                <ul class="shop-admin-list">
                                    <li
                                        v-for="(feature, index) in adminFeatures"
                                        :key="feature.title"
                                    >
                                        <i
                                            class="fa-solid"
                                            :class="adminIcons[index] || 'fa-circle-check'"
                                            aria-hidden="true"
                                        ></i>
                                        <div>
                                            <h3>{{ feature.title }}</h3>
                                            <p>{{ feature.body }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="shop-admin-visuals">
                                <figure>
                                    <video
                                        :src="`${imageStore.imagePath}/cliente/video.mp4`"
                                        autoplay
                                        muted
                                        loop
                                        playsinline
                                    ></video>
                                    <figcaption>{{ languageStore.t('shop.roleAdmin') }}</figcaption>
                                </figure>
                                <figure>
                                    <video
                                        :src="`${imageStore.imagePath}/cliente/video-cel.mp4`"
                                        autoplay
                                        muted
                                        loop
                                        playsinline
                                    ></video>
                                    <figcaption>{{ languageStore.t('shop.roleCustomer') }}</figcaption>
                                </figure>
                            </div>
                        </div>

                        <div class="shop-offer">
                            <div class="shop-offer-copy">
                                <h2>{{ languageStore.t('shop.plansTitle') }}</h2>
                                <p class="shop-offer-lead">{{ languageStore.t('shop.plansLead') }}</p>
                                <div class="shop-offer-split">
                                    <div>
                                        <h3>{{ languageStore.t('shop.planIncludeTitle') }}</h3>
                                        <p>{{ languageStore.t('shop.planIncludeBody') }}</p>
                                    </div>
                                    <div>
                                        <h3>{{ languageStore.t('shop.planPriceTitle') }}</h3>
                                        <p>{{ languageStore.t('shop.planPriceBody') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="shop-offer-cta">
                                <h2>{{ languageStore.t('shop.ctaTitle') }}</h2>
                                <p>{{ languageStore.t('shop.ctaBody') }}</p>
                                <a href="#Contacto">{{ languageStore.t('shop.ctaButton') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.shop {
    --shop-bg: #2a2f36;
    --shop-ink: #f2f4f6;
    --shop-muted: rgba(242, 244, 246, 0.72);
    --shop-gold: #c9a66b;
    --shop-red: var(--color-first);
    --shop-line: rgba(242, 244, 246, 0.16);
    --shop-pad: clamp(1.6rem, 3vw, 3.2rem);
    position: relative;
    width: 100%;
    color: var(--shop-ink);
    background:
        radial-gradient(ellipse 80% 55% at 50% -10%, #3a4049 0%, transparent 55%),
        radial-gradient(ellipse 60% 50% at 100% 80%, #1a1d22 0%, transparent 50%),
        radial-gradient(ellipse 50% 40% at 0% 70%, #1c2026 0%, transparent 45%),
        linear-gradient(180deg, #2a2f36 0%, #22262c 45%, #181b20 100%);
    overflow: hidden;
}

.shop-trigger {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 10rem;
    pointer-events: none;
}

.shop-mesh {
    pointer-events: none;
    position: absolute;
    inset: 0;
    opacity: 0.55;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
    background-size: 4.8rem 4.8rem;
}

.shop-wash {
    pointer-events: none;
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 40% 35% at 8% 12%, rgba(170, 24, 24, 0.14), transparent 60%),
        radial-gradient(ellipse 45% 40% at 92% 25%, rgba(180, 190, 205, 0.08), transparent 55%),
        linear-gradient(180deg, transparent 60%, rgba(8, 9, 11, 0.55));
}

.shop-shell {
    position: relative;
    z-index: 1;
    width: min(118rem, calc(100% - 2 * var(--shop-pad)));
    margin: 0 auto;
    padding: clamp(4rem, 7vw, 7rem) 0;
    display: flex;
    flex-direction: column;
}

/* —— Cover —— */
.shop-cover {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 21 / 9;
    min-height: 28rem;
    padding: 0;
    border: 1px solid rgba(201, 166, 107, 0.28);
    background: #111418;
    color: inherit;
    cursor: pointer;
    overflow: hidden;
    text-align: left;
    isolation: isolate;
}

.shop-cover-media {
    position: absolute;
    inset: 0;
}

.shop-cover-video {
    position: absolute;
    object-fit: cover;
    transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s ease;
}

.shop-cover-video--desk {
    inset: 0;
    width: 100%;
    height: 100%;
    filter: saturate(0.9) brightness(0.72);
}

.shop-cover-video--phone {
    right: 8%;
    bottom: -4%;
    width: min(22%, 18rem);
    aspect-ratio: 9 / 16;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.55);
    transform: translateY(1.2rem) rotate(3deg);
    z-index: 1;
}

.shop-cover:hover .shop-cover-video--desk,
.shop-cover:focus-visible .shop-cover-video--desk {
    transform: scale(1.08);
    filter: saturate(1.05) brightness(0.85);
}

.shop-cover:hover .shop-cover-video--phone,
.shop-cover:focus-visible .shop-cover-video--phone {
    transform: translateY(0) rotate(-2deg) scale(1.04);
}

.shop-cover-grid {
    position: absolute;
    inset: 0;
    opacity: 0.25;
    background-image:
        linear-gradient(rgba(201, 166, 107, 0.18) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201, 166, 107, 0.18) 1px, transparent 1px);
    background-size: 3.2rem 3.2rem;
    pointer-events: none;
    transition: opacity 0.4s ease;
}

.shop-cover:hover .shop-cover-grid,
.shop-cover:focus-visible .shop-cover-grid {
    opacity: 0.45;
}

.shop-cover-glow {
    position: absolute;
    inset: -20%;
    background: radial-gradient(circle at 70% 40%, rgba(201, 166, 107, 0.28), transparent 42%);
    opacity: 0;
    transition: opacity 0.45s ease;
    pointer-events: none;
}

.shop-cover:hover .shop-cover-glow,
.shop-cover:focus-visible .shop-cover-glow {
    opacity: 1;
}

.shop-cover-vignette {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(18, 20, 24, 0.88) 0%, rgba(18, 20, 24, 0.35) 55%, rgba(18, 20, 24, 0.55) 100%),
        linear-gradient(0deg, rgba(18, 20, 24, 0.8) 0%, transparent 48%);
    pointer-events: none;
}

.shop-cover-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    gap: 0.9rem;
    padding: clamp(2rem, 4vw, 3.6rem);
}

.shop-cover-brand {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
}

.shop-cover-brand img {
    width: 4.4rem;
    height: 4.4rem;
    object-fit: contain;
    transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.shop-cover:hover .shop-cover-brand img,
.shop-cover:focus-visible .shop-cover-brand img {
    transform: scale(1.08);
}

.shop-cover-brand span {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.4rem, 3.4vw, 3.6rem);
    color: var(--shop-gold);
}

.shop-cover-title {
    margin: 0;
    max-width: 16ch;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.2rem, 3.2vw, 3.4rem);
    font-weight: 400;
    line-height: 1.12;
    color: #fff;
    transform: translateY(0.5rem);
    transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.shop-cover:hover .shop-cover-title,
.shop-cover:focus-visible .shop-cover-title {
    transform: translateY(0);
}

.shop-cover-price {
    margin: 0;
    font-size: clamp(1.5rem, 1.7vw, 1.85rem);
    color: rgba(201, 166, 107, 0.95);
}

.shop-cover-hint {
    margin: 0.5rem 0 0;
    display: inline-flex;
    align-items: center;
    gap: 0.9rem;
    font-size: 1.45rem;
    color: rgba(255, 255, 255, 0.82);
}

.shop-cover-hint i {
    transition: transform 0.35s ease;
}

.shop-cover:hover .shop-cover-hint i,
.shop-cover:focus-visible .shop-cover-hint i {
    transform: translateX(0.5rem);
}

.shop.is-open .shop-cover-hint i {
    transform: none;
}

.shop-cover:focus-visible {
    outline: 2px solid var(--shop-gold);
    outline-offset: 3px;
}

/* —— Collapse —— */
.shop-collapse {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.55s cubic-bezier(0.22, 1, 0.36, 1);
}

.shop-collapse.open {
    grid-template-rows: 1fr;
}

.shop-collapse-inner {
    overflow: hidden;
    min-height: 0;
}

.shop-details {
    padding-top: clamp(3rem, 5vw, 4.5rem);
    display: flex;
    flex-direction: column;
    gap: clamp(4rem, 6vw, 6rem);
    opacity: 0;
    transform: translateY(1.2rem);
    transition: opacity 0.4s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.shop-collapse.open .shop-details {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.12s;
}

/* —— Content —— */
.shop-intro {
    display: flex;
    flex-direction: column;
    gap: clamp(2rem, 3vw, 2.8rem);
}

.shop-intro-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.6rem;
}

.shop-kicker {
    margin: 0;
    font-size: 1.3rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(238, 241, 244, 0.55);
}

.shop-intro-main {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(18rem, 0.7fr);
    gap: clamp(2rem, 4vw, 4rem);
    align-items: end;
}

.shop-intro-copy h1 {
    margin: 0 0 1.4rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(3rem, 5vw, 5.2rem);
    font-weight: 400;
    line-height: 1.08;
    color: #fff;
    max-width: 14ch;
}

.shop-intro-copy p {
    margin: 0;
    max-width: 48rem;
    font-size: clamp(1.5rem, 1.55vw, 1.75rem);
    line-height: 1.65;
    color: var(--shop-muted);
}

.shop-price-pill {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
    padding: 2rem 2.2rem;
    border: 1px solid rgba(201, 166, 107, 0.45);
    background:
        linear-gradient(145deg, rgba(201, 166, 107, 0.14), transparent 55%),
        rgba(0, 0, 0, 0.25);
}

.shop-price-amount {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.9rem, 2.2vw, 2.4rem);
    line-height: 1.2;
    color: var(--shop-gold);
}

.shop-price-note {
    font-size: 1.4rem;
    line-height: 1.5;
    color: var(--shop-muted);
}

.shop-block-head h2,
.shop-admin-copy > h2,
.shop-offer-copy > h2,
.shop-offer-cta h2 {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.4rem, 3.2vw, 3.4rem);
    font-weight: 400;
    line-height: 1.15;
    color: #fff;
}

.shop-block-head {
    margin-bottom: 2.4rem;
}

.shop-mosaic {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.2rem;
}

.shop-tile {
    padding: 2.2rem 2rem 2.4rem;
    border-top: 2px solid rgba(201, 166, 107, 0.55);
    background: rgba(255, 255, 255, 0.03);
    transition: background 0.25s ease, transform 0.25s ease;
}

.shop-tile:hover {
    background: rgba(201, 166, 107, 0.08);
    transform: translateY(-3px);
}

.shop-tile-icon {
    display: block;
    margin-bottom: 1.4rem;
    color: var(--shop-red);
    font-size: 1.7rem;
}

.shop-tile h3 {
    margin: 0 0 0.8rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.7rem, 1.85vw, 2rem);
    font-weight: 400;
    color: #fff;
}

.shop-tile p {
    margin: 0;
    font-size: clamp(1.35rem, 1.4vw, 1.5rem);
    line-height: 1.55;
    color: var(--shop-muted);
}

.shop-block--admin {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
    gap: clamp(2.4rem, 5vw, 5rem);
    align-items: start;
    padding: clamp(2.4rem, 4vw, 3.6rem);
    border: 1px solid var(--shop-line);
    background:
        linear-gradient(120deg, rgba(170, 24, 24, 0.08), transparent 45%),
        rgba(0, 0, 0, 0.2);
}

.shop-admin-copy > p {
    margin: 1.2rem 0 2.4rem;
    max-width: 46rem;
    font-size: clamp(1.45rem, 1.5vw, 1.65rem);
    line-height: 1.6;
    color: var(--shop-muted);
}

.shop-admin-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.shop-admin-list li {
    display: grid;
    grid-template-columns: 2.4rem minmax(0, 1fr);
    gap: 1.2rem;
    padding: 1.5rem 0;
    border-top: 1px solid var(--shop-line);
}

.shop-admin-list li:last-child {
    border-bottom: 1px solid var(--shop-line);
}

.shop-admin-list i {
    color: var(--shop-gold);
    font-size: 1.45rem;
    padding-top: 0.25rem;
}

.shop-admin-list h3 {
    margin: 0 0 0.35rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.6rem, 1.7vw, 1.85rem);
    font-weight: 400;
    color: #fff;
}

.shop-admin-list p {
    margin: 0;
    font-size: 1.4rem;
    line-height: 1.5;
    color: var(--shop-muted);
}

.shop-admin-visuals {
    display: grid;
    grid-template-columns: 1.35fr 0.85fr;
    gap: 1.2rem;
    align-items: end;
}

.shop-admin-visuals figure {
    margin: 0;
    overflow: hidden;
    background: #000;
    border: 1px solid var(--shop-line);
}

.shop-admin-visuals figure:first-child {
    aspect-ratio: 16 / 11;
}

.shop-admin-visuals figure:last-child {
    aspect-ratio: 9 / 16;
}

.shop-admin-visuals video {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}

.shop-admin-visuals figcaption {
    margin: 0;
    padding: 1rem 0 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.55rem;
    color: var(--shop-gold);
}

.shop-offer {
    display: grid;
    grid-template-columns: minmax(0, 1.25fr) minmax(22rem, 0.75fr);
    gap: 0;
    border: 1px solid var(--shop-line);
    overflow: hidden;
}

.shop-offer-copy {
    padding: clamp(2.4rem, 4vw, 3.6rem);
}

.shop-offer-lead {
    margin: 1.2rem 0 2.4rem;
    max-width: 48rem;
    font-size: clamp(1.45rem, 1.5vw, 1.65rem);
    line-height: 1.6;
    color: var(--shop-muted);
}

.shop-offer-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.shop-offer-split h3 {
    margin: 0 0 0.8rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.8rem, 2vw, 2.1rem);
    font-weight: 400;
    color: var(--shop-gold);
}

.shop-offer-split p {
    margin: 0;
    font-size: 1.4rem;
    line-height: 1.55;
    color: var(--shop-muted);
}

.shop-offer-cta {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 1.2rem;
    padding: clamp(2.4rem, 4vw, 3.6rem);
    background:
        linear-gradient(165deg, rgba(170, 24, 24, 0.92), rgba(90, 12, 12, 0.98));
}

.shop-offer-cta h2 {
    font-size: clamp(2.2rem, 2.6vw, 2.8rem);
}

.shop-offer-cta p {
    margin: 0;
    font-size: 1.45rem;
    line-height: 1.55;
    color: rgba(255, 255, 255, 0.82);
}

.shop-offer-cta a {
    align-self: flex-start;
    margin-top: 0.6rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 4.6rem;
    padding: 0 2.4rem;
    background: #fff;
    color: #1a1210;
    text-decoration: none;
    font-size: 1.55rem;
    transition: transform 0.2s ease, filter 0.2s ease;
}

.shop-offer-cta a:hover {
    transform: translateY(-2px);
    filter: brightness(0.96);
}

.shop-offer-cta a:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 3px;
}

.shop-shell.active .shop-cover {
    animation: shopIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.shop-collapse.open .shop-tile {
    animation: shopTile 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
    animation-delay: calc(0.15s + var(--i) * 0.05s);
}

@keyframes shopIn {
    from {
        opacity: 0.4;
        transform: translateY(1.4rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shopTile {
    from {
        opacity: 0;
        transform: translateY(1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 1100px) {
    .shop-mosaic {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .shop-block--admin {
        grid-template-columns: 1fr;
    }

    .shop-admin-visuals {
        max-width: 56rem;
    }

    .shop-offer {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 980px) {
    .shop-cover {
        aspect-ratio: 4 / 5;
        min-height: 34rem;
    }

    .shop-cover-video--phone {
        width: min(34%, 14rem);
        right: 6%;
    }
}

@media (max-width: 860px) {
    .shop-intro-main {
        grid-template-columns: 1fr;
    }

    .shop-offer-split {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 620px) {
    .shop-mosaic {
        grid-template-columns: 1fr;
    }

    .shop-admin-visuals {
        grid-template-columns: 1fr;
    }

    .shop-admin-visuals figure:last-child {
        width: min(100%, 26rem);
        margin-inline: auto;
    }

    .shop-offer-cta a {
        width: 100%;
    }

    .shop-cover-video--phone {
        display: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    .shop-cover-video,
    .shop-collapse,
    .shop-details {
        transition: none;
    }
}
</style>
