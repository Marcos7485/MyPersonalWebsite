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
const open = computed(() => openSoftware.value === 'zankou')
const detailsRef = ref<HTMLElement | null>(null)

const onIntersect = () => {
    visible.value = true
}

const features = computed(() => languageStore.messages.zankou.features)

const featureIcons = [
    'fa-brain',
    'fa-eye',
    'fa-diagram-project',
    'fa-microchip',
    'fa-terminal',
    'fa-globe',
    'fa-arrows-rotate',
] as const

type ModeMedia = {
    type: 'image' | 'video'
    src: string
    label?: string
}

type ModeBlock = {
    key: string
    kicker: string
    title: string
    lead: string
    points: readonly string[]
    media: ModeMedia[]
    pair: ModeMedia[]
}

const modes = computed<ModeBlock[]>(() => [
    {
        key: 'teacher',
        kicker: languageStore.t('zankou.modeTeacherKicker'),
        title: languageStore.t('zankou.roleTeacher'),
        lead: languageStore.t('zankou.roleTeacherBody'),
        points: languageStore.messages.zankou.modeTeacherPoints,
        media: [{ type: 'image', src: 'modo-profesora.png' }],
        pair: [],
    },
    {
        key: 'music',
        kicker: languageStore.t('zankou.modeMusicKicker'),
        title: languageStore.t('zankou.roleMusic'),
        lead: languageStore.t('zankou.roleMusicBody'),
        points: languageStore.messages.zankou.modeMusicPoints,
        media: [{ type: 'image', src: 'modo-musica.png' }],
        pair: [],
    },
    {
        key: 'bar',
        kicker: languageStore.t('zankou.modeBarKicker'),
        title: languageStore.t('zankou.roleBar'),
        lead: languageStore.t('zankou.roleBarBody'),
        points: languageStore.messages.zankou.modeBarPoints,
        media: [
            {
                type: 'image',
                src: 'modo-bar.png',
                label: languageStore.t('zankou.roleBarImage'),
            },
        ],
        pair: [
            {
                type: 'image',
                src: 'modo-truco.jpg',
                label: languageStore.t('zankou.roleTrucoImage'),
            },
            {
                type: 'video',
                src: 'cambio-expresion-bar-2.mp4',
                label: languageStore.t('zankou.roleBarVideo2'),
            },
        ],
    },
])

const expressions = computed(() => {
    const labels = languageStore.messages.zankou.expressionSamples
    const files = [
        'admiracion.gif',
        'confundida.gif',
        'enamorada.gif',
        'orgullosa.gif',
        'observando.gif',
        'pensativa-melancolica.gif',
        'pensativa-profundo.gif',
        'pensativa-sexy.gif',
    ]
    return files.map((file, index) => ({
        file,
        label: labels[index] ?? '',
    }))
})

const siteUrl = '' // TODO: URL real del repo/demo de Zankou (no usar github.com/)

const audioRef = ref<HTMLAudioElement | null>(null)
const muted = ref(false)

const toggle = () => {
    toggleSoftware('zankou')
}

const toggleMute = (event: Event) => {
    event.stopPropagation()
    muted.value = !muted.value
    if (audioRef.value) {
        audioRef.value.muted = muted.value
    }
}

const playTheme = async () => {
    const audio = audioRef.value
    if (!audio) return
    muted.value = false
    audio.muted = false
    audio.currentTime = 3
    try {
        await audio.play()
    } catch {
        // Algunos navegadores bloquean autoplay; el click de abrir ya suele alcanzarlo.
    }
}

const onThemeEnded = () => {
    const audio = audioRef.value
    if (!audio || !open.value) return
    audio.currentTime = 3
    void audio.play()
}

const stopTheme = () => {
    const audio = audioRef.value
    if (!audio) return
    audio.pause()
    audio.currentTime = 0
}

watch(open, async (isOpen) => {
    if (isOpen) {
        await nextTick()
        await playTheme()
        detailsRef.value?.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
    } else {
        stopTheme()
    }
})
</script>

<template>
    <section id="SoftwaresZankou" class="iq" :class="{ 'is-open': open }">
        <div class="iq-trigger" v-intersect="onIntersect" aria-hidden="true"></div>
        <div class="iq-glow" aria-hidden="true"></div>

        <div class="iq-shell" :class="{ active: visible }">
            <div class="iq-cover-wrap">
                <button
                    type="button"
                    class="iq-cover"
                    :aria-expanded="open"
                    aria-controls="zankou-details"
                    @click="toggle"
                >
                    <div class="iq-cover-media" aria-hidden="true">
                        <video
                            class="iq-cover-video"
                            :src="`${imageStore.imagePath}/seccion-4-2/cambio-expresion-bar.mp4`"
                            autoplay
                            muted
                            loop
                            playsinline
                        ></video>
                        <div class="iq-cover-shine"></div>
                        <div class="iq-cover-vignette"></div>
                    </div>

                    <div class="iq-cover-content">
                        <p class="iq-cover-brand">{{ languageStore.t('zankou.brand') }}</p>
                        <h2 class="iq-cover-title">{{ languageStore.t('zankou.headline') }}</h2>
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

                <button
                    v-if="open"
                    type="button"
                    class="iq-mute"
                    :aria-label="muted ? languageStore.t('zankou.unmute') : languageStore.t('zankou.mute')"
                    :title="muted ? languageStore.t('zankou.unmute') : languageStore.t('zankou.mute')"
                    @click="toggleMute"
                >
                    <i
                        class="fa-solid"
                        :class="muted ? 'fa-volume-xmark' : 'fa-volume-high'"
                        aria-hidden="true"
                    ></i>
                </button>
            </div>

            <audio
                ref="audioRef"
                :src="`${imageStore.imagePath}/seccion-4-2/hold-on.m4a`"
                preload="auto"
                @ended="onThemeEnded"
            ></audio>

            <div
                id="zankou-details"
                ref="detailsRef"
                class="iq-collapse"
                :class="{ open }"
            >
                <div class="iq-collapse-inner">
                    <div class="iq-details">
                        <header class="iq-intro">
                            <div class="iq-intro-copy">
                                <p class="iq-kicker">{{ languageStore.t('zankou.whatTitle') }}</p>
                                <p class="iq-lead">{{ languageStore.t('zankou.whatBody') }}</p>
                            </div>
                        </header>

                        <div class="iq-section iq-case">
                            <div class="iq-case-grid">
                                <article class="iq-case-card">
                                    <h3>{{ languageStore.t('zankou.caseProblemTitle') }}</h3>
                                    <p>{{ languageStore.t('zankou.caseProblemBody') }}</p>
                                </article>
                                <article class="iq-case-card">
                                    <h3>{{ languageStore.t('zankou.caseWorkTitle') }}</h3>
                                    <p>{{ languageStore.t('zankou.caseWorkBody') }}</p>
                                </article>
                                <article class="iq-case-card">
                                    <h3>{{ languageStore.t('zankou.caseResultTitle') }}</h3>
                                    <p>{{ languageStore.t('zankou.caseResultBody') }}</p>
                                </article>
                            </div>
                            <p class="iq-case-stack">{{ languageStore.t('zankou.caseStack') }}</p>
                        </div>

                        <div class="iq-section">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('zankou.featuresLead') }}</h2>
                            </div>

                            <ul class="iq-grid">
                                <li
                                    v-for="(feature, index) in features"
                                    :key="feature.title"
                                    class="iq-item"
                                >
                                    <i
                                        class="fa-solid iq-item-icon"
                                        :class="featureIcons[index] || 'fa-circle-check'"
                                        aria-hidden="true"
                                    ></i>
                                    <div class="iq-item-body">
                                        <span class="iq-item-index" aria-hidden="true">
                                            {{ String(index + 1).padStart(2, '0') }}
                                        </span>
                                        <h3>{{ feature.title }}</h3>
                                        <p>{{ feature.body }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="iq-section iq-modes">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('zankou.rolesTitle') }}</h2>
                                <p class="iq-section-sub">{{ languageStore.t('zankou.rolesLead') }}</p>
                            </div>

                            <article
                                v-for="(mode, index) in modes"
                                :key="mode.key"
                                class="iq-mode"
                                :class="{
                                    'iq-mode--flip': index % 2 === 1,
                                    'iq-mode--has-pair': mode.pair.length > 0,
                                }"
                            >
                                <div class="iq-mode-copy">
                                    <p class="iq-mode-kicker">{{ mode.kicker }}</p>
                                    <h3>{{ mode.title }}</h3>
                                    <p class="iq-mode-lead">{{ mode.lead }}</p>
                                    <ul class="iq-mode-points">
                                        <li v-for="point in mode.points" :key="point">{{ point }}</li>
                                    </ul>
                                </div>

                                <div
                                    class="iq-mode-media"
                                    :class="{ 'iq-mode-media--stack': mode.media.length > 1 }"
                                >
                                    <figure
                                        v-for="item in mode.media"
                                        :key="item.src"
                                        class="iq-mode-shot"
                                    >
                                        <div class="iq-mode-frame">
                                            <video
                                                v-if="item.type === 'video'"
                                                :src="`${imageStore.imagePath}/seccion-4-2/${item.src}`"
                                                autoplay
                                                muted
                                                loop
                                                playsinline
                                                :aria-label="item.label || mode.title"
                                            ></video>
                                            <img
                                                v-else
                                                :src="`${imageStore.imagePath}/seccion-4-2/${item.src}`"
                                                :alt="item.label || mode.title"
                                            />
                                        </div>
                                        <figcaption v-if="item.label">
                                            {{ item.label }}
                                        </figcaption>
                                    </figure>
                                </div>

                                <div v-if="mode.pair.length" class="iq-mode-pair">
                                    <figure
                                        v-for="item in mode.pair"
                                        :key="item.src"
                                        class="iq-mode-shot"
                                    >
                                        <div class="iq-mode-frame">
                                            <video
                                                v-if="item.type === 'video'"
                                                :src="`${imageStore.imagePath}/seccion-4-2/${item.src}`"
                                                autoplay
                                                muted
                                                loop
                                                playsinline
                                                :aria-label="item.label || mode.title"
                                            ></video>
                                            <img
                                                v-else
                                                :src="`${imageStore.imagePath}/seccion-4-2/${item.src}`"
                                                :alt="item.label || mode.title"
                                            />
                                        </div>
                                        <figcaption v-if="item.label">
                                            {{ item.label }}
                                        </figcaption>
                                    </figure>
                                </div>
                            </article>
                        </div>

                        <div class="iq-section iq-expressions">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('zankou.expressionsTitle') }}</h2>
                                <p class="iq-section-sub">{{ languageStore.t('zankou.expressionsLead') }}</p>
                            </div>

                            <p class="iq-expressions-body">
                                {{ languageStore.t('zankou.expressionsBody') }}
                            </p>

                            <ul class="iq-expressions-row">
                                <li
                                    v-for="expression in expressions"
                                    :key="expression.file"
                                    class="iq-expression"
                                >
                                    <div class="iq-expression-frame">
                                        <img
                                            :src="`${imageStore.imagePath}/seccion-4-2/expresiones/${expression.file}`"
                                            :alt="expression.label"
                                        />
                                    </div>
                                    <span>{{ expression.label }}</span>
                                </li>
                                <li class="iq-expression iq-expression--more">
                                    <div class="iq-expression-frame iq-expression-more">
                                        <strong>{{ languageStore.t('zankou.expressionsEtc') }}</strong>
                                        <span>{{ languageStore.t('zankou.expressionsEtcBody') }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="iq-section">
                            <div class="iq-section-head">
                                <h2>{{ languageStore.t('zankou.plansTitle') }}</h2>
                                <p class="iq-section-sub">{{ languageStore.t('zankou.plansLead') }}</p>
                            </div>

                            <div class="iq-plans">
                                <article class="iq-plan">
                                    <h3>{{ languageStore.t('zankou.planProTitle') }}</h3>
                                    <p>{{ languageStore.t('zankou.planProBody') }}</p>
                                </article>
                                <article class="iq-plan iq-plan--main">
                                    <h3>{{ languageStore.t('zankou.planTotalTitle') }}</h3>
                                    <p>{{ languageStore.t('zankou.planTotalBody') }}</p>
                                </article>
                            </div>
                        </div>

                        <div class="iq-cta">
                            <div class="iq-cta-copy">
                                <h2>{{ languageStore.t('zankou.ctaTitle') }}</h2>
                                <p>{{ languageStore.t('zankou.ctaBody') }}</p>
                            </div>
                            <a
                                v-if="siteUrl"
                                class="iq-cta-btn"
                                :href="siteUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ languageStore.t('zankou.ctaButton') }}
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

/* â€”â€” Cover â€”â€” */
.iq-cover-wrap {
    position: relative;
}

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

.iq-cover-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transform: scale(1.02);
    transition: transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
}

.iq-cover:hover .iq-cover-video,
.iq-cover:focus-visible .iq-cover-video {
    transform: scale(1.06);
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

.iq-mute {
    position: absolute;
    top: 1.4rem;
    right: 1.4rem;
    z-index: 4;
    width: 4.4rem;
    height: 4.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--iq-line);
    background: rgba(0, 0, 0, 0.72);
    color: #fff;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.iq-mute i {
    font-size: 1.7rem;
}

.iq-mute:hover {
    background: rgba(170, 24, 24, 0.85);
    transform: translateY(-1px);
}

.iq-mute:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 3px;
}

/* â€”â€” Collapse â€”â€” */
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

/* â€”â€” Content (mismo lenguaje visual que antes) â€”â€” */
.iq-intro {
    display: grid;
    grid-template-columns: 1fr;
    gap: clamp(2.5rem, 5vw, 5rem);
    align-items: center;
    padding-bottom: clamp(3rem, 5vw, 4.5rem);
    border-bottom: 1px solid var(--iq-line);
    text-align: center;
}

.iq-intro-copy {
    max-width: 72rem;
    margin-inline: auto;
}

.iq-kicker {
    margin: 0 0 0.8rem;
    font-size: 1.35rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--iq-red);
}

.iq-lead {
    margin: 0 auto;
    max-width: 64rem;
    font-size: clamp(1.5rem, 1.55vw, 1.75rem);
    line-height: 1.65;
    color: var(--iq-muted);
}

.iq-section {
    display: flex;
    flex-direction: column;
    gap: 2.8rem;
}

.iq-case-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.4rem;
}

.iq-case-card {
    padding: 1.6rem 1.5rem;
    border: 1px solid var(--iq-line);
    background: rgba(255, 255, 255, 0.03);
}

.iq-case-card h3 {
    margin: 0 0 0.8rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.85rem;
    font-weight: 400;
    color: #ffb4b4;
}

.iq-case-card p {
    margin: 0;
    font-size: 1.4rem;
    line-height: 1.5;
    color: var(--iq-muted);
}

.iq-case-stack {
    margin: 0;
    font-size: 1.25rem;
    letter-spacing: 0.04em;
    color: rgba(255, 180, 180, 0.85);
}

.iq-section-head {
    max-width: 64rem;
    margin-inline: auto;
    text-align: center;
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
    margin: 1rem auto 0;
    max-width: 52rem;
    font-size: clamp(1.45rem, 1.5vw, 1.7rem);
    line-height: 1.6;
    color: var(--iq-muted);
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
    grid-template-columns: clamp(4.8rem, 6vw, 6.5rem) minmax(0, 1fr);
    gap: clamp(1.4rem, 2.5vw, 2.4rem);
    align-items: stretch;
    padding: 1.9rem 0;
    border-bottom: 1px solid var(--iq-line);
    text-align: center;
}

.iq-item-icon {
    color: var(--iq-red);
    font-size: clamp(2.8rem, 3.4vw, 4rem);
    width: 100%;
    height: 100%;
    min-height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    align-self: stretch;
    flex-shrink: 0;
}

.iq-item-body {
    max-width: 56rem;
    width: 100%;
    margin-inline: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.55rem;
}

.iq-item-index {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.5rem;
    line-height: 1.2;
    color: rgba(255, 255, 255, 0.35);
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
    margin: 0 auto;
    max-width: 52rem;
    font-size: clamp(1.35rem, 1.4vw, 1.55rem);
    line-height: 1.55;
    color: var(--iq-muted);
}

.iq-modes {
    display: flex;
    flex-direction: column;
    gap: clamp(3.5rem, 6vw, 6rem);
}

.iq-mode {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(0, 1.2fr);
    gap: clamp(2rem, 4vw, 4rem);
    align-items: start;
}

.iq-mode--flip {
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.05fr);
}

.iq-mode--flip .iq-mode-copy {
    order: 2;
}

.iq-mode--flip .iq-mode-media {
    order: 1;
}

.iq-mode-kicker {
    margin: 0 0 0.7rem;
    font-size: 1.2rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 214, 170, 0.72);
}

.iq-mode-copy h3 {
    margin: 0 0 1.2rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.4rem, 3vw, 3.4rem);
    font-weight: 400;
    line-height: 1.15;
    color: #fff;
}

.iq-mode-lead {
    margin: 0 0 1.6rem;
    font-size: clamp(1.45rem, 1.55vw, 1.7rem);
    line-height: 1.65;
    color: rgba(255, 255, 255, 0.82);
}

.iq-mode-points {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.iq-mode-points li {
    position: relative;
    padding-left: 1.5rem;
    font-size: clamp(1.3rem, 1.4vw, 1.5rem);
    line-height: 1.55;
    color: rgba(255, 255, 255, 0.7);
}

.iq-mode-points li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.65em;
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 50%;
    background: #c45c3a;
}

.iq-mode-media {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

.iq-mode-media--stack {
    gap: 1.6rem;
}

.iq-mode-pair {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(1rem, 2vw, 1.6rem);
    align-items: start;
}

.iq-mode-shot {
    margin: 0;
}

.iq-mode-frame {
    width: 100%;
    overflow: hidden;
    background: #000;
    border: 1px solid var(--iq-line);
}

.iq-mode-frame img,
.iq-mode-frame video {
    display: block;
    width: 100%;
    height: auto;
}

.iq-mode-shot figcaption {
    margin: 0.8rem 0 0;
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.55);
}

.iq-expressions-body {
    margin: 0 auto clamp(2rem, 3vw, 2.8rem);
    max-width: 72rem;
    font-size: clamp(1.4rem, 1.55vw, 1.7rem);
    line-height: 1.65;
    color: rgba(255, 255, 255, 0.78);
    text-align: center;
}

.iq-expressions-row {
    margin: 0;
    padding: 0.4rem 0 0.2rem;
    list-style: none;
    display: flex;
    flex-wrap: nowrap;
    align-items: stretch;
    gap: 0.9rem;
    overflow-x: auto;
    scrollbar-width: thin;
}

.iq-expression {
    margin: 0;
    flex: 1 1 0;
    min-width: 9.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}

.iq-expression-frame {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #050505;
    border: 1px solid var(--iq-line);
}

.iq-expression-frame img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

.iq-expression > span {
    font-size: clamp(1.1rem, 1.15vw, 1.3rem);
    line-height: 1.3;
    color: rgba(255, 255, 255, 0.72);
    text-align: center;
}

.iq-expression--more {
    min-width: 10.5rem;
    max-width: 12rem;
    flex: 0 0 auto;
}

.iq-expression-more {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-end;
    gap: 0.65rem;
    padding: 1.2rem;
    background:
        linear-gradient(180deg, rgba(170, 24, 24, 0.18), rgba(0, 0, 0, 0.55)),
        #0a0a0a;
}

.iq-expression-more strong {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2rem, 2.4vw, 2.8rem);
    font-weight: 400;
    color: #fff;
}

.iq-expression-more span {
    font-size: clamp(1.1rem, 1.15vw, 1.25rem);
    line-height: 1.4;
    color: rgba(255, 255, 255, 0.7);
    text-align: left;
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
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2.4rem;
    padding: 2.8rem 3rem;
    border: 1px solid var(--iq-line);
    background: linear-gradient(90deg, rgba(170, 24, 24, 0.14), rgba(255, 255, 255, 0.02));
    text-align: center;
}

.iq-cta-copy {
    max-width: 62rem;
    margin-inline: auto;
}

.iq-cta h2 {
    margin: 0 0 0.8rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.1rem, 2.6vw, 2.8rem);
    font-weight: 400;
    color: #fff;
}

.iq-cta p {
    margin: 0 auto;
    max-width: 56rem;
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

@media (max-width: 980px) {
    .iq-cover {
        aspect-ratio: 4 / 5;
        min-height: 34rem;
    }

    .iq-case-grid {
        grid-template-columns: 1fr;
    }

    .iq-cta {
        flex-direction: column;
        align-items: flex-start;
    }

    .iq-cta-btn {
        width: 100%;
    }

    .iq-mode,
    .iq-mode--flip {
        grid-template-columns: 1fr;
    }

    .iq-mode--flip .iq-mode-copy,
    .iq-mode--flip .iq-mode-media {
        order: initial;
    }
}

@media (max-width: 720px) {
    .iq-expression {
        min-width: 8.5rem;
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
    .iq-cover-video,
    .iq-cover-shine,
    .iq-collapse,
    .iq-details {
        transition: none;
    }
}
</style>

