<script setup lang="ts">
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useLanguageStore } from '../../../store/language'
import { useImageStore } from '../../../store/imageStore'
import LanguagesSelector from '../../../components/LanguagesSelector.vue'
import DrsBrandBackdrop from '../../../components/DrsBrandBackdrop.vue'

const languageStore = useLanguageStore()
const imageStore = useImageStore()
imageStore.fetchImagePath()

const showPassword = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/iqathletic/login', {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="iq-login">
        <LanguagesSelector floating />
        <DrsBrandBackdrop />

        <div class="iq-login-shell">
            <form class="iq-login-card" @submit.prevent="submit">
                <Link href="/" class="iq-login-back">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    {{ languageStore.t('iqAdmin.backHome') }}
                </Link>

                <div class="iq-login-brand">
                    <img
                        :src="`${imageStore.imagePath}/drs.webp`"
                        alt="Dragon Rojo Software"
                        width="72"
                        height="72"
                    />
                    <h1>{{ languageStore.t('iqAdmin.loginTitle') }}</h1>
                    <p>{{ languageStore.t('iqAdmin.loginSubtitle') }}</p>
                </div>

                <label class="iq-field">
                    <span>{{ languageStore.t('iqAdmin.email') }}</span>
                    <input
                        v-model="form.email"
                        type="email"
                        autocomplete="username"
                        required
                    />
                    <small v-if="form.errors.email">{{ form.errors.email }}</small>
                </label>

                <label class="iq-field">
                    <span>{{ languageStore.t('iqAdmin.password') }}</span>
                    <div class="iq-field-row">
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="current-password"
                            required
                        />
                        <button
                            type="button"
                            class="iq-eye"
                            :aria-label="languageStore.t('iqAdmin.togglePassword')"
                            @click="showPassword = !showPassword"
                        >
                            <i
                                class="fa-solid"
                                :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                    <small v-if="form.errors.password">{{ form.errors.password }}</small>
                </label>

                <label class="iq-check">
                    <input v-model="form.remember" type="checkbox" />
                    <span>{{ languageStore.t('iqAdmin.remember') }}</span>
                </label>

                <button type="submit" class="iq-submit" :disabled="form.processing">
                    <span>{{ languageStore.t('iqAdmin.loginButton') }}</span>
                </button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.iq-login {
    position: relative;
    width: 100vw;
    max-width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    align-self: stretch;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: clamp(1.6rem, 3vw, 3rem);
    box-sizing: border-box;
    background: #000;
    color: #fff;
    overflow-x: hidden;
    overflow-y: auto;
}

.iq-login-shell {
    position: relative;
    z-index: 2;
    width: min(48rem, 100%);
    margin: 0 auto;
}

.iq-login-card {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
    padding: clamp(2rem, 3vw, 3rem);
    border: 1px solid rgba(255, 255, 255, 0.14);
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(6px);
    box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.45);
    box-sizing: border-box;
}

.iq-login-back {
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.7rem;
    color: rgba(255, 255, 255, 0.78);
    text-decoration: none;
    font-size: 1.35rem;
}

.iq-login-back:hover {
    color: #fff;
    text-shadow: 0 0 1.2rem rgba(255, 255, 255, 0.45);
}

.iq-login-brand {
    text-align: center;
}

.iq-login-brand img {
    width: clamp(5.6rem, 8vw, 7.2rem);
    height: auto;
    margin-bottom: 1.1rem;
    filter: drop-shadow(0 0 1.4rem rgba(170, 24, 24, 0.35));
}

.iq-login-brand h1 {
    margin: 0 0 0.6rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.2rem, 3.2vw, 3.2rem);
    font-weight: 400;
    color: #fff;
}

.iq-login-brand p {
    margin: 0 auto;
    max-width: 34rem;
    font-size: clamp(1.3rem, 1.5vw, 1.55rem);
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.75);
}

.iq-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    font-size: 1.35rem;
}

.iq-field > span {
    color: rgba(255, 255, 255, 0.82);
}

.iq-field input {
    width: 100%;
    min-height: 4.4rem;
    padding: 0.85rem 1.6rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem 3.5rem;
    background: rgba(255, 255, 255, 0.96);
    color: #15171a;
    font-size: var(--fontsize);
    box-sizing: border-box;
    box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.18);
}

.iq-field input:focus {
    outline: none;
    background: #fff;
    border-color: var(--color-first);
    box-shadow:
        0 0.5rem 1.4rem rgba(0, 0, 0, 0.22),
        0 0 0 0.25rem rgba(170, 24, 24, 0.22);
}

.iq-field small {
    color: #ff8f8f;
}

.iq-field-row {
    display: flex;
    gap: 0.6rem;
}

.iq-field-row input {
    flex: 1;
    min-width: 0;
}

.iq-eye {
    flex-shrink: 0;
    width: 4.4rem;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 1rem 2.2rem;
    background: rgba(0, 0, 0, 0.45);
    color: #fff;
    cursor: pointer;
}

.iq-check {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 1.35rem;
    color: rgba(255, 255, 255, 0.8);
}

.iq-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 4.8rem;
    margin-top: 0.4rem;
    padding: 0.85rem 1.6rem;
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 1rem 3.5rem;
    background: linear-gradient(135deg, var(--color-first) 0%, #7a1010 55%, #4a0808 100%);
    color: #fff;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: var(--fontsize);
    cursor: pointer;
    box-shadow: 0 0.6rem 1.6rem rgba(0, 0, 0, 0.35);
    transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.25s ease;
}

.iq-submit:hover:not(:disabled) {
    filter: brightness(1.08);
    transform: translateY(-0.15rem);
    box-shadow:
        0 0.8rem 2rem rgba(0, 0, 0, 0.4),
        0 0 1.6rem rgba(170, 24, 24, 0.45);
}

.iq-submit:disabled {
    opacity: 0.55;
    cursor: wait;
}

@media (max-width: 600px) {
    .iq-login {
        align-items: flex-start;
        padding: 1.4rem 1.2rem 2.4rem;
    }

    .iq-login-shell {
        width: 100%;
        margin-top: 5.5rem;
    }

    .iq-login-card {
        gap: 1.2rem;
        padding: 1.8rem 1.4rem;
        background: rgba(0, 0, 0, 0.62);
    }
}
</style>
