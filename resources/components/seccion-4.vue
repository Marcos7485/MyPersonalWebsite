<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '../store/language.ts'
import { useImageStore } from '../store/imageStore.ts'

const imageStore = useImageStore()
const languageStore = useLanguageStore()

onMounted(() => {
    imageStore.fetchImagePath()
})

const visible = ref(false)
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
</script>

<template>
    <section id="Softwares" class="iq">
        <div class="iq-trigger" v-intersect="onIntersect" aria-hidden="true"></div>
        <div class="iq-glow" aria-hidden="true"></div>

        <div class="iq-shell" :class="{ active: visible }">
            <!-- 1. Presentación -->
            <header class="iq-intro">
                <div class="iq-intro-copy">
                    <div class="iq-brand">
                        <img
                            class="iq-brand-icon"
                            :src="`${imageStore.imagePath}/iqathletic/icon.png`"
                            alt=""
                            width="64"
                            height="64"
                        />
                        <span class="iq-brand-name">{{ languageStore.t('iq.brand') }}</span>
                    </div>

                    <h1 class="iq-title">{{ languageStore.t('iq.headline') }}</h1>
                    <p class="iq-lead">{{ languageStore.t('iq.whatBody') }}</p>
                </div>

                <div class="iq-intro-visual" aria-hidden="true">
                    <img
                        :src="`${imageStore.imagePath}/seccion-4/1.jpg`"
                        alt=""
                    />
                </div>
            </header>

            <!-- 2. Capacidades -->
            <div class="iq-section">
                <div class="iq-section-head">
                    <p class="iq-kicker">{{ languageStore.t('iq.whatTitle') }}</p>
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
                        <img
                            :src="`${imageStore.imagePath}/seccion-4/2.jpg`"
                            alt=""
                        />
                    </figure>
                </div>
            </div>

            <!-- 3. Perfiles multi-tenant -->
            <div class="iq-section">
                <div class="iq-section-head">
                    <h2>{{ languageStore.t('iq.rolesTitle') }}</h2>
                    <p class="iq-section-sub">{{ languageStore.t('iq.rolesLead') }}</p>
                </div>

                <div class="iq-roles">
                    <figure class="iq-role">
                        <div class="iq-role-frame">
                            <img
                                :src="`${imageStore.imagePath}/seccion-4/4.jpg`"
                                alt=""
                            />
                        </div>
                        <figcaption>{{ languageStore.t('iq.roleStudent') }}</figcaption>
                    </figure>
                    <figure class="iq-role">
                        <div class="iq-role-frame">
                            <img
                                :src="`${imageStore.imagePath}/seccion-4/3.jpg`"
                                alt=""
                            />
                        </div>
                        <figcaption>{{ languageStore.t('iq.roleStaff') }}</figcaption>
                    </figure>
                </div>
            </div>

            <!-- 4. Planes -->
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

            <!-- 4. CTA -->
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
    padding: clamp(5rem, 8vw, 8rem) 0 clamp(5.5rem, 8vw, 8.5rem);
    display: flex;
    flex-direction: column;
    gap: clamp(4.5rem, 7vw, 7rem);
}

/* —— Intro —— */
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

.iq-brand {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    margin-bottom: 1.8rem;
}

.iq-brand-icon {
    width: 5.6rem;
    height: 5.6rem;
    object-fit: contain;
    flex-shrink: 0;
}

.iq-brand-name {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.8rem, 4vw, 4.4rem);
    line-height: 1;
    color: var(--iq-red);
}

.iq-title {
    margin: 0 0 1.6rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.4rem, 3.4vw, 3.6rem);
    font-weight: 400;
    line-height: 1.2;
    color: #fff;
    max-width: 22ch;
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

/* —— Bloques —— */
.iq-section {
    display: flex;
    flex-direction: column;
    gap: 2.8rem;
}

.iq-section-head {
    max-width: 64rem;
}

.iq-kicker {
    margin: 0 0 0.8rem;
    font-size: 1.35rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--iq-red);
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

/* —— Features + captura del menú —— */
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

/* —— Perfiles alumno / personal —— */
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

/* —— Planes —— */
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

/* —— CTA —— */
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

/* Animación suave (contenido siempre visible) */
.iq-shell.active .iq-intro,
.iq-shell.active .iq-section,
.iq-shell.active .iq-cta {
    animation: iqIn 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.iq-shell.active .iq-section:nth-of-type(1) {
    animation-delay: 0.08s;
}

.iq-shell.active .iq-section:nth-of-type(2) {
    animation-delay: 0.16s;
}

.iq-shell.active .iq-cta {
    animation-delay: 0.24s;
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
    .iq-intro {
        grid-template-columns: 1fr;
    }

    .iq-intro-visual {
        justify-self: center;
        width: min(100%, 26rem);
        aspect-ratio: 9 / 19;
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
</style>
