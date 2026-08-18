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
const open = computed(() => openSoftware.value === 'iq')
const detailsRef = ref<HTMLElement | null>(null)

const onIntersect = () => {
    visible.value = true
}

const features = computed(() => languageStore.messages.iq.features)

const featureIcons = [
    'fa-users',
    'fa-mobile',
    'fa-qrcode',
    'fa-chart-line',
    'fa-utensils',
    'fa-calendar-check',
    'fa-bell',
] as const

const siteUrl = 'https://www.iqathleticsoftware.com'

const toggle = () => {
    toggleSoftware('iq')
}

watch(open, async (isOpen) => {
    if (isOpen) {
        await nextTick()
        detailsRef.value?.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
    }
})
</script>

<template>
    <section id="Softwares" class="iq" :class="{ 'is-open': open }">
        <div class="iq-trigger" v-intersect="onIntersect" aria-hidden="true"></div>
        <div class="iq-glow" aria-hidden="true"></div>

        <div class="iq-shell" :class="{ active: visible }">
            <button
                type="button"
                class="iq-cover"
                :aria-expanded="open"
                aria-controls="iq-details"
                @click="toggle"
            >
                <div class="iq-cover-media" aria-hidden="true">
                    <img
                        class="iq-cover-logo"
                        :src="`${imageStore.imagePath}/iqathletic/icon.png`"
                        alt=""
                    />
                    <div class="iq-cover-shine"></div>
                    <div class="iq-cover-vignette"></div>
                </div>

                <div class="iq-cover-content">
                    <p class="iq-cover-brand">{{ languageStore.t('iq.brand') }}</p>
                    <h2 class="iq-cover-title">{{ languageStore.t('iq.headline') }}</h2>
                    <p class="iq-cover-hint">
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
                id="iq-details"
                ref="detailsRef"
                class="iq-collapse"
                :class="{ open }"
            >
                <div class="iq-collapse-inner">
                    <div class="iq-details">
                        <header class="iq-intro">
                            <div class="iq-intro-copy">
                                <p class="iq-kicker">{{ languageStore.t('iq.whatTitle') }}</p>
                                <p class="iq-lead">{{ languageStore.t('iq.whatBody') }}</p>
                            </div>
                            <div class="iq-intro-visual" aria-hidden="true">
                                <img :src="`${imageStore.imagePath}/seccion-4/1.jpg`" alt="" />
                            </div>
                        </header>

                        <div class="iq-section">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('iq.featuresLead') }}</h2>
                            </div>

                            <div class="iq-features-row">
                                <ul class="iq-grid">
                                    <li
                                        v-for="(feature, index) in features"
                                        :key="feature.title"
                                        class="iq-item"
                                    >
                                        <span class="iq-item-index" aria-hidden="true">
                                            {{ String(index + 1).padStart(2, '0') }}
                                        </span>
                                        <div class="iq-item-body">
                                            <div class="iq-item-top">
                                                <i
                                                    class="fa-solid iq-item-icon"
                                                    :class="featureIcons[index] || 'fa-circle-check'"
                                                    aria-hidden="true"
                                                ></i>
                                                <h3>{{ feature.title }}</h3>
                                            </div>
                                            <p>{{ feature.body }}</p>
                                        </div>
                                    </li>
                                </ul>

                                <figure class="iq-menu-shot">
                                    <img :src="`${imageStore.imagePath}/seccion-4/2.jpg`" alt="" />
                                </figure>
                            </div>
                        </div>

                        <div class="iq-section">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('iq.rolesTitle') }}</h2>
                                <p class="iq-section-sub">{{ languageStore.t('iq.rolesLead') }}</p>
                            </div>

                            <div class="iq-roles">
                                <figure class="iq-role">
                                    <div class="iq-role-frame">
                                        <img :src="`${imageStore.imagePath}/seccion-4/4.jpg`" alt="" />
                                    </div>
                                    <figcaption>{{ languageStore.t('iq.roleStudent') }}</figcaption>
                                </figure>
                                <figure class="iq-role">
                                    <div class="iq-role-frame">
                                        <img :src="`${imageStore.imagePath}/seccion-4/3.jpg`" alt="" />
                                    </div>
                                    <figcaption>{{ languageStore.t('iq.roleStaff') }}</figcaption>
                                </figure>
                            </div>
                        </div>

                        <div class="iq-section">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('iq.plansTitle') }}</h2>
                                <p class="iq-section-sub">{{ languageStore.t('iq.plansLead') }}</p>
                            </div>

                            <div class="iq-plans">
                                <article class="iq-plan">
                                    <h3>{{ languageStore.t('iq.planProTitle') }}</h3>
                                    <p>{{ languageStore.t('iq.planProBody') }}</p>
                                </article>
                                <article class="iq-plan iq-plan--main">
                                    <h3>{{ languageStore.t('iq.planTotalTitle') }}</h3>
                                    <p>{{ languageStore.t('iq.planTotalBody') }}</p>
                                </article>
                            </div>
                        </div>

                        <div class="iq-cta">
                            <div class="iq-cta-copy">
                                <h2>{{ languageStore.t('iq.ctaTitle') }}</h2>
                                <p>{{ languageStore.t('iq.ctaBody') }}</p>
                            </div>
                            <a
                                class="iq-cta-btn"
                                :href="siteUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ languageStore.t('iq.ctaButton') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.iq {
    --iq-bg: #02060c;
    --iq-ink: #f2f5f8;
    --iq-muted: rgba(242, 245, 248, 0.7);
    --iq-red: var(--color-first);
    --iq-line: rgba(255, 255, 255, 0.12);
    --iq-pad: clamp(1.6rem, 3vw, 3.2rem);
    position: relative;
    width: 100%;
    color: var(--iq-ink);
    background: var(--iq-bg);
    overflow: hidden;
}

.iq-trigger {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 10rem;
    pointer-events: none;
}

.iq-glow {
    pointer-events: none;
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 55% 35% at 10% 8%, rgba(170, 24, 24, 0.16), transparent 60%),
        radial-gradient(ellipse 50% 40% at 95% 20%, rgba(20, 45, 80, 0.35), transparent 55%);
}

.iq-shell {
    position: relative;
    z-index: 1;
    width: min(112rem, calc(100% - 2 * var(--iq-pad)));
    margin: 0 auto;
    padding: clamp(4rem, 7vw, 7rem) 0 clamp(4rem, 7vw, 7rem);
    display: flex;
    flex-direction: column;
    gap: 0;
}

/* —— Cover —— */
.iq-cover {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 21 / 9;
    min-height: 28rem;
    padding: 0;
    border: 1px solid var(--iq-line);
    background: #000;
    color: inherit;
    cursor: pointer;
    overflow: hidden;
    text-align: left;
    isolation: isolate;
}

.iq-cover-media {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    background: #000;
}

.iq-cover-logo {
    width: min(58%, 40rem);
    height: auto;
    object-fit: contain;
    filter: drop-shadow(0 0 2.4rem rgba(170, 24, 24, 0.35));
    transform: scale(1.02);
    transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s ease;
}

.iq-cover:hover .iq-cover-logo,
.iq-cover:focus-visible .iq-cover-logo {
    transform: scale(1.08);
    filter: drop-shadow(0 0 3.2rem rgba(170, 24, 24, 0.55));
}

.iq-cover-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        115deg,
        transparent 30%,
        rgba(255, 255, 255, 0.16) 48%,
        transparent 62%
    );
    transform: translateX(-120%);
    transition: transform 0.85s cubic-bezier(0.22, 1, 0.36, 1);
    pointer-events: none;
    z-index: 1;
}

.iq-cover:hover .iq-cover-shine,
.iq-cover:focus-visible .iq-cover-shine {
    transform: translateX(120%);
}

.iq-cover-vignette {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(0, 0, 0, 0.72) 0%, rgba(0, 0, 0, 0.15) 42%, rgba(0, 0, 0, 0.45) 100%),
        linear-gradient(0deg, rgba(0, 0, 0, 0.78) 0%, transparent 48%);
    transition: background 0.4s ease;
    pointer-events: none;
    z-index: 1;
}

.iq-cover:hover .iq-cover-vignette,
.iq-cover:focus-visible .iq-cover-vignette {
    background:
        linear-gradient(90deg, rgba(0, 0, 0, 0.8) 0%, rgba(70, 8, 8, 0.28) 48%, rgba(0, 0, 0, 0.55) 100%),
        linear-gradient(0deg, rgba(0, 0, 0, 0.82) 0%, transparent 52%);
}

.iq-cover-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    gap: 1rem;
    padding: clamp(2rem, 4vw, 3.6rem);
}

.iq-cover-brand {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.4rem, 3.4vw, 3.6rem);
    line-height: 1;
    color: var(--iq-red);
}

.iq-cover-title {
    margin: 0;
    max-width: 18ch;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.2rem, 3.2vw, 3.4rem);
    font-weight: 400;
    line-height: 1.15;
    color: #fff;
    transform: translateY(0.4rem);
    transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.iq-cover:hover .iq-cover-title,
.iq-cover:focus-visible .iq-cover-title {
    transform: translateY(0);
}

.iq-cover-hint {
    margin: 0.4rem 0 0;
    display: inline-flex;
    align-items: center;
    gap: 0.9rem;
    font-size: 1.45rem;
    color: rgba(255, 255, 255, 0.8);
}

.iq-cover-hint i {
    transition: transform 0.35s ease;
}

.iq-cover:hover .iq-cover-hint i,
.iq-cover:focus-visible .iq-cover-hint i {
    transform: translateX(0.5rem);
}

.iq.is-open .iq-cover-hint i {
    transform: none;
}

.iq-cover:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 3px;
}

/* —— Collapse —— */
.iq-collapse {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.55s cubic-bezier(0.22, 1, 0.36, 1);
}

.iq-collapse.open {
    grid-template-rows: 1fr;
}

.iq-collapse-inner {
    overflow: hidden;
    min-height: 0;
}

.iq-details {
    padding-top: clamp(3rem, 5vw, 4.5rem);
    display: flex;
    flex-direction: column;
    gap: clamp(4rem, 6vw, 6rem);
    opacity: 0;
    transform: translateY(1.2rem);
    transition: opacity 0.4s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}

.iq-collapse.open .iq-details {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.12s;
}

/* —— Content (mismo lenguaje visual que antes) —— */
.iq-intro {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
    gap: clamp(2.5rem, 5vw, 5rem);
    align-items: center;
    padding-bottom: clamp(3rem, 5vw, 4.5rem);
    border-bottom: 1px solid var(--iq-line);
}

.iq-intro-copy {
    max-width: 58rem;
}

.iq-kicker {
    margin: 0 0 0.8rem;
    font-size: 1.35rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--iq-red);
}

.iq-lead {
    margin: 0;
    font-size: clamp(1.5rem, 1.55vw, 1.75rem);
    line-height: 1.65;
    color: var(--iq-muted);
}

.iq-intro-visual {
    justify-self: end;
    width: min(100%, 28rem);
    aspect-ratio: 9 / 19;
    overflow: hidden;
    background: #000;
    border: 1px solid var(--iq-line);
}

.iq-intro-visual img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}

.iq-section {
    display: flex;
    flex-direction: column;
    gap: 2.8rem;
}

.iq-section-head {
    max-width: 64rem;
}

.iq-section-head h2 {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.2rem, 2.8vw, 3rem);
    font-weight: 400;
    line-height: 1.2;
    color: #fff;
}

.iq-section-sub {
    margin: 1rem 0 0;
    max-width: 52rem;
    font-size: clamp(1.45rem, 1.5vw, 1.7rem);
    line-height: 1.6;
    color: var(--iq-muted);
}

.iq-features-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(22rem, 28rem);
    gap: clamp(2rem, 4vw, 4rem);
    align-items: start;
}

.iq-grid {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    border-top: 1px solid var(--iq-line);
}

.iq-item {
    display: grid;
    grid-template-columns: 4.2rem minmax(0, 1fr);
    gap: 1.4rem;
    padding: 1.9rem 0;
    border-bottom: 1px solid var(--iq-line);
}

.iq-item-index {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.7rem;
    line-height: 1.2;
    color: rgba(255, 255, 255, 0.35);
    padding-top: 0.15rem;
}

.iq-item-top {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.55rem;
}

.iq-item-icon {
    color: var(--iq-red);
    font-size: 1.55rem;
    width: 1.6rem;
    text-align: center;
    flex-shrink: 0;
}

.iq-item-body h3 {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.7rem, 1.8vw, 2rem);
    font-weight: 400;
    line-height: 1.25;
    color: #fff;
}

.iq-item-body p {
    margin: 0;
    font-size: clamp(1.35rem, 1.4vw, 1.55rem);
    line-height: 1.55;
    color: var(--iq-muted);
}

.iq-menu-shot {
    margin: 0;
    position: sticky;
    top: 8rem;
    width: 100%;
    aspect-ratio: 9 / 19;
    overflow: hidden;
    background: #000;
    border: 1px solid var(--iq-line);
}

.iq-menu-shot img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}

.iq-roles {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(2rem, 4vw, 4rem);
    justify-items: center;
}

.iq-role {
    margin: 0;
    width: min(100%, 28rem);
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

.iq-role-frame {
    width: 100%;
    aspect-ratio: 9 / 19;
    overflow: hidden;
    background: #000;
    border: 1px solid var(--iq-line);
}

.iq-role-frame img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}

.iq-role figcaption {
    margin: 0;
    text-align: center;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(1.7rem, 1.9vw, 2.1rem);
    color: #fff;
}

.iq-plans {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border: 1px solid var(--iq-line);
}

.iq-plan {
    padding: 2.6rem 2.8rem 3rem;
}

.iq-plan + .iq-plan {
    border-left: 1px solid var(--iq-line);
}

.iq-plan--main {
    background: linear-gradient(180deg, rgba(170, 24, 24, 0.12), transparent 80%);
}

.iq-plan h3 {
    margin: 0 0 1rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2rem, 2.2vw, 2.4rem);
    font-weight: 400;
    color: #fff;
}

.iq-plan--main h3 {
    color: #ffb4b4;
}

.iq-plan p {
    margin: 0;
    font-size: clamp(1.4rem, 1.45vw, 1.6rem);
    line-height: 1.6;
    color: var(--iq-muted);
}

.iq-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2.4rem;
    padding: 2.8rem 3rem;
    border: 1px solid var(--iq-line);
    background: linear-gradient(90deg, rgba(170, 24, 24, 0.14), rgba(255, 255, 255, 0.02));
}

.iq-cta-copy {
    max-width: 62rem;
}

.iq-cta h2 {
    margin: 0 0 0.8rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.1rem, 2.6vw, 2.8rem);
    font-weight: 400;
    color: #fff;
}

.iq-cta p {
    margin: 0;
    font-size: clamp(1.4rem, 1.45vw, 1.6rem);
    line-height: 1.55;
    color: var(--iq-muted);
}

.iq-cta-btn {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 4.6rem;
    padding: 0 2.4rem;
    background: var(--iq-red);
    color: #fff;
    text-decoration: none;
    font-size: 1.55rem;
    white-space: nowrap;
    transition: transform 0.2s ease, filter 0.2s ease;
}

.iq-cta-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.08);
}

.iq-cta-btn:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 3px;
}

.iq-shell.active .iq-cover {
    animation: iqIn 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
}

@keyframes iqIn {
    from {
        opacity: 0.55;
        transform: translateY(1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 1100px) {
    .iq-features-row {
        grid-template-columns: 1fr;
    }

    .iq-menu-shot {
        position: static;
        width: min(100%, 28rem);
        margin-inline: auto;
    }
}

@media (max-width: 980px) {
    .iq-cover {
        aspect-ratio: 4 / 5;
        min-height: 34rem;
    }

    .iq-cover-logo {
        width: min(78%, 28rem);
    }

    .iq-intro {
        grid-template-columns: 1fr;
    }

    .iq-intro-visual {
        justify-self: center;
        width: min(100%, 26rem);
        order: -1;
    }

    .iq-cta {
        flex-direction: column;
        align-items: flex-start;
    }

    .iq-cta-btn {
        width: 100%;
    }
}

@media (max-width: 720px) {
    .iq-roles {
        grid-template-columns: 1fr;
    }

    .iq-role {
        width: min(100%, 26rem);
    }

    .iq-plans {
        grid-template-columns: 1fr;
    }

    .iq-plan + .iq-plan {
        border-left: none;
        border-top: 1px solid var(--iq-line);
    }

    .iq-cta {
        padding: 2.2rem 1.8rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .iq-cover-logo,
    .iq-cover-shine,
    .iq-collapse,
    .iq-details {
        transition: none;
    }
}
</style>
