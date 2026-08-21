<script setup lang="ts">
import { computed, ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { useLanguageStore } from '../../../store/language'
import { useImageStore } from '../../../store/imageStore'
import LanguagesSelector from '../../../components/LanguagesSelector.vue'
import DrsBrandBackdrop from '../../../components/DrsBrandBackdrop.vue'

type Section = 'home' | 'suscripcion' | 'pagos'

interface AdminInfo {
    id: number | null
    nombre: string | null
    email: string | null
}

interface EmpresaInfo {
    id: number
    nombre: string
    email: string
    telefono: string
    direccion: string
    imagen: string | null
    imagen_url: string | null
}

interface AplicacionInfo {
    id: number
    slug: string
    nombre: string
    hintKey: string
    icon: string | null
    empresa: EmpresaInfo | null
}

interface SuscripcionInfo {
    id: number
    tipo: string
    plan_id: number | null
    status: string | null
    monto: number
    monto_formato: string
    limite_alumnos: string | null
    observaciones: string | null
    inicio: string | null
    vencimiento: string | null
    active: boolean
    expired: boolean
    dias_restantes: number
    dias_restantes_raw: number
    en_gracia: boolean
    servicio_caducado: boolean
    dias_gracia: number
}

interface PlanNivel {
    clave: string
    alumnos_max: number | null
    precio: number
}

interface PlanInfo {
    id: number
    nombre: string
    periodo: string
    duracion: number
    precio: number
    precio_tachado: number | null
    moneda: string
    niveles: PlanNivel[]
    badge: string | null
    destacado: boolean
    descripcion: string | null
}

interface PagoInfo {
    id: number
    aplicacion: number
    plan_id: number | null
    plan_nombre: string | null
    monto: number
    monto_formato: string
    moneda: string
    metodo: string | null
    referencia: string | null
    status: string
    pagado_en: string | null
    created_at: string | null
}

const props = defineProps<{
    admin: AdminInfo
    aplicaciones: AplicacionInfo[]
    suscripcion: SuscripcionInfo | null
    planes: PlanInfo[]
    pagos: PagoInfo[]
    pagoDisponible: boolean
}>()

const languageStore = useLanguageStore()
const imageStore = useImageStore()
imageStore.fetchImagePath()

const section = ref<Section>('home')
const changingPlan = ref(false)
const selectedPlanId = ref<number | null>(props.suscripcion?.plan_id ?? null)

const menuItems: { id: Section; labelKey: string; icon: string }[] = [
    { id: 'home', labelKey: 'navHome', icon: 'home' },
    { id: 'suscripcion', labelKey: 'navSubscription', icon: 'sub' },
    { id: 'pagos', labelKey: 'navPayments', icon: 'pay' },
]

const formatPrice = (price: number) =>
    new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
        minimumFractionDigits: 0,
    }).format(price)

const statusLabel = computed(() => {
    if (!props.suscripcion) return languageStore.t('iqAdmin.noSubscription')
    if (props.suscripcion.servicio_caducado) return languageStore.t('iqAdmin.expired')
    if (props.suscripcion.en_gracia) return languageStore.t('iqAdmin.expired')
    if (props.suscripcion.active) return languageStore.t('iqAdmin.active')
    return languageStore.t('iqAdmin.inactive')
})

const statusClass = computed(() => {
    if (!props.suscripcion) return 'is-muted'
    if (props.suscripcion.servicio_caducado) return 'is-danger'
    if (props.suscripcion.en_gracia) return 'is-warn'
    if (props.suscripcion.active) return 'is-ok'
    return 'is-warn'
})

const limiteAlumnosLabel = computed(() => {
    const key = props.suscripcion?.limite_alumnos
    if (!key) return languageStore.t('iqAdmin.sinDefinir')
    return languageStore.t(`iqAdmin.${key}` as 'iqAdmin.ilimitado')
})

const esLimiteHasta = computed(() => {
    const key = props.suscripcion?.limite_alumnos
    return key === 'hasta20' || key === 'hasta40'
})

const planLabel = (key: string | null | undefined) => {
    if (!key) return ''
    return languageStore.t(`iqAdmin.${key}` as 'iqAdmin.desde')
}

const appHint = (hintKey: string) => languageStore.t(`iqAdmin.${hintKey}` as 'iqAdmin.productHint')

const pagoStatusLabel = (status: string) => {
    const map: Record<string, string> = {
        pendiente: 'paymentPending',
        aprobado: 'paymentApproved',
        rechazado: 'paymentRejected',
        reembolsado: 'paymentRefunded',
    }
    const key = map[status] ?? 'paymentPending'
    return languageStore.t(`iqAdmin.${key}` as 'iqAdmin.paymentPending')
}

const isCurrentPlan = (planId: number) => props.suscripcion?.plan_id === planId

const selectPlan = (planId: number) => {
    if (isCurrentPlan(planId)) return
    selectedPlanId.value = planId
}

const openChangePlan = () => {
    changingPlan.value = true
    selectedPlanId.value = props.suscripcion?.plan_id ?? null
}

const goSection = (id: Section) => {
    section.value = id
    if (id !== 'suscripcion') {
        changingPlan.value = false
    }
}

const logout = () => {
    router.post('/iqathletic/logout')
}
</script>

<template>
    <div class="iq-dash">
        <LanguagesSelector floating />
        <DrsBrandBackdrop />

        <aside class="iq-side" aria-label="Menú">
            <div class="iq-side-brand">
                <img :src="`${imageStore.imagePath}/drs.webp`" alt="DRS" width="40" height="40" />
                <div class="iq-side-brand-text">
                    <strong>Dragon Rojo</strong>
                    <span>{{ languageStore.t('iqAdmin.dashboardTitle') }}</span>
                </div>
            </div>

            <nav class="iq-side-nav">
                <button
                    v-for="item in menuItems"
                    :key="item.id"
                    type="button"
                    class="iq-side-link"
                    :class="{ 'is-active': section === item.id }"
                    @click="goSection(item.id)"
                >
                    <span class="iq-side-ico" aria-hidden="true">
                        <svg v-if="item.icon === 'home'" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3.2 3.5 10.2V21h6.2v-6.1h4.6V21h6.2V10.2L12 3.2Z" />
                        </svg>
                        <svg v-else-if="item.icon === 'sub'" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 3h10a2 2 0 0 1 2 2v14l-7-3.2L5 19V5a2 2 0 0 1 2-2Z" />
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v2H4v-2Zm0 4h16V17a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-6.5Zm3.2 3.2h4.1v1.8H7.2v-1.8Z" />
                        </svg>
                    </span>
                    <span class="iq-side-label">{{ languageStore.t(`iqAdmin.${item.labelKey}` as 'iqAdmin.navHome') }}</span>
                </button>
            </nav>

            <div class="iq-side-foot">
                <Link href="/" class="iq-side-link iq-side-link--ghost">
                    <span class="iq-side-ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10.5 5.5 4 12l6.5 6.5 1.4-1.4L7.8 13H20v-2H7.8l4.1-4.1-1.4-1.4Z" />
                        </svg>
                    </span>
                    <span class="iq-side-label">{{ languageStore.t('iqAdmin.backHome') }}</span>
                </Link>
                <button type="button" class="iq-side-link iq-side-link--danger" @click="logout">
                    <span class="iq-side-ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 4h8v16h-8v-2h6V6h-6V4Zm-1.2 4.3L5.5 12l3.3 3.7 1.4-1.3L9.3 13H16v-2H9.3l1.9-2.4-1.4-1.3Z" />
                        </svg>
                    </span>
                    <span class="iq-side-label">{{ languageStore.t('iqAdmin.logout') }}</span>
                </button>
            </div>
        </aside>

        <div class="iq-dash-shell">
            <header class="iq-dash-top">
                <div>
                    <h1>
                        {{
                            section === 'home'
                                ? languageStore.t('iqAdmin.navHome')
                                : section === 'suscripcion'
                                  ? languageStore.t('iqAdmin.navSubscription')
                                  : languageStore.t('iqAdmin.navPayments')
                        }}
                    </h1>
                    <p>{{ admin.nombre }} · {{ admin.email }}</p>
                </div>
            </header>

            <main class="iq-dash-main">
                <!-- HOME -->
                <template v-if="section === 'home'">
                    <section class="iq-panel iq-panel--apps iq-panel--wide">
                        <h2>{{ languageStore.t('iqAdmin.yourApps') }}</h2>
                        <div v-if="aplicaciones.length" class="iq-apps">
                            <article v-for="app in aplicaciones" :key="app.id" class="iq-app-block">
                                <div class="iq-app-card">
                                    <img
                                        v-if="app.icon"
                                        :src="`${imageStore.imagePath}/${app.icon}`"
                                        :alt="app.nombre"
                                        width="52"
                                        height="52"
                                    />
                                    <div v-else class="iq-app-fallback">{{ app.nombre.slice(0, 1) }}</div>
                                    <div>
                                        <p class="iq-product-kicker">{{ languageStore.t('iqAdmin.subscribedTo') }}</p>
                                        <h3>{{ app.nombre }}</h3>
                                        <p>{{ appHint(app.hintKey) }}</p>
                                    </div>
                                </div>

                                <div class="iq-app-empresa">
                                    <h4>{{ languageStore.t('iqAdmin.company') }}</h4>

                                    <template v-if="app.empresa">
                                        <div class="iq-empresa-row">
                                            <div class="iq-empresa-logo">
                                                <img
                                                    v-if="app.empresa.imagen_url"
                                                    :src="app.empresa.imagen_url"
                                                    :alt="app.empresa.nombre"
                                                />
                                                <div v-else class="iq-empresa-logo-fallback" aria-hidden="true">
                                                    {{ app.empresa.nombre.slice(0, 1) }}
                                                </div>
                                            </div>
                                            <div class="iq-empresa-copy">
                                                <p class="iq-panel-title">{{ app.empresa.nombre }}</p>
                                                <ul class="iq-meta">
                                                    <li><span>Email</span><strong>{{ app.empresa.email }}</strong></li>
                                                    <li>
                                                        <span>{{ languageStore.t('iqAdmin.phone') }}</span>
                                                        <strong>{{ app.empresa.telefono }}</strong>
                                                    </li>
                                                    <li>
                                                        <span>{{ languageStore.t('iqAdmin.address') }}</span>
                                                        <strong>{{ app.empresa.direccion }}</strong>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </template>
                                    <p v-else class="iq-empty">{{ languageStore.t('iqAdmin.noCompany') }}</p>
                                </div>
                            </article>
                        </div>
                        <p v-else class="iq-empty">{{ languageStore.t('iqAdmin.noApps') }}</p>
                    </section>
                </template>

                <!-- SUSCRIPCIÓN -->
                <template v-else-if="section === 'suscripcion'">
                    <section class="iq-panel iq-panel--main iq-panel--wide">
                        <div class="iq-panel-head">
                            <h2>{{ languageStore.t('iqAdmin.currentPlan') }}</h2>
                            <span class="iq-badge" :class="statusClass">{{ statusLabel }}</span>
                        </div>

                        <template v-if="suscripcion">
                            <ul class="iq-meta">
                                <li>
                                    <span>{{ languageStore.t('iqAdmin.type') }}</span>
                                    <strong>{{ suscripcion.tipo }}</strong>
                                </li>
                                <li>
                                    <span>Status</span>
                                    <strong>{{ suscripcion.status || '—' }}</strong>
                                </li>
                                <li>
                                    <span>{{ languageStore.t('iqAdmin.amount') }}</span>
                                    <strong>{{ suscripcion.monto_formato }}</strong>
                                </li>
                                <li>
                                    <span>{{ languageStore.t('iqAdmin.students') }}</span>
                                    <strong :class="{ 'is-limit': esLimiteHasta }">{{ limiteAlumnosLabel }}</strong>
                                </li>
                                <li>
                                    <span>{{ languageStore.t('iqAdmin.start') }}</span>
                                    <strong>{{ suscripcion.inicio || '—' }}</strong>
                                </li>
                                <li>
                                    <span>{{ languageStore.t('iqAdmin.end') }}</span>
                                    <strong>{{ suscripcion.vencimiento || '—' }}</strong>
                                </li>
                                <li v-if="suscripcion.observaciones">
                                    <span>{{ languageStore.t('iqAdmin.notes') }}</span>
                                    <strong>{{ suscripcion.observaciones }}</strong>
                                </li>
                            </ul>

                            <div
                                class="iq-days"
                                :class="{
                                    'is-expired': suscripcion.servicio_caducado,
                                    'is-grace': suscripcion.en_gracia,
                                }"
                            >
                                <span class="iq-days-num">{{ suscripcion.dias_restantes }}</span>
                                <div class="iq-days-copy">
                                    <template v-if="!suscripcion.servicio_caducado">
                                        <p>
                                            {{
                                                suscripcion.dias_restantes === 1
                                                    ? languageStore.t('iqAdmin.dayLeft')
                                                    : languageStore.t('iqAdmin.daysLeft')
                                            }}
                                        </p>
                                        <p v-if="suscripcion.en_gracia" class="iq-days-grace">
                                            {{ languageStore.t('iqAdmin.graceNote') }}
                                        </p>
                                    </template>
                                    <p v-else>{{ languageStore.t('iqAdmin.serviceExpired') }}</p>
                                </div>
                            </div>
                        </template>
                        <p v-else class="iq-empty">{{ languageStore.t('iqAdmin.noSubscription') }}</p>

                        <button
                            type="button"
                            class="iq-change-btn"
                            :class="{ 'is-open': changingPlan }"
                            @click="changingPlan ? (changingPlan = false) : openChangePlan()"
                        >
                            {{
                                changingPlan
                                    ? languageStore.t('iqAdmin.hidePlans')
                                    : languageStore.t('iqAdmin.changePlan')
                            }}
                        </button>
                    </section>

                    <section v-if="changingPlan" class="iq-panel iq-panel--plans iq-panel--wide">
                        <div class="iq-panel-head">
                            <h2>{{ languageStore.t('iqAdmin.choosePlan') }}</h2>
                        </div>
                        <p class="iq-plans-hint">{{ languageStore.t('iqAdmin.choosePlanHint') }}</p>

                        <div class="iq-plans">
                            <article
                                v-for="plan in planes"
                                :key="plan.id"
                                class="iq-plan"
                                :class="{
                                    'is-selected': selectedPlanId === plan.id,
                                    'is-current': isCurrentPlan(plan.id),
                                    'is-featured': plan.destacado,
                                    'is-progresivo': plan.id === 1,
                                }"
                                @click="selectPlan(plan.id)"
                            >
                                <span v-if="plan.badge" class="iq-plan-badge">{{ planLabel(plan.badge) }}</span>

                                <header class="iq-plan-head">
                                    <strong>{{ plan.nombre }}</strong>
                                    <p v-if="plan.descripcion">{{ planLabel(plan.descripcion) }}</p>
                                </header>

                                <div class="iq-plan-price">
                                    <span v-if="plan.id === 1" class="iq-plan-desde">{{ languageStore.t('iqAdmin.desde') }}</span>
                                    <span v-if="plan.precio_tachado" class="iq-plan-strike">{{ formatPrice(plan.precio_tachado) }}</span>
                                    <span class="iq-plan-amount">{{ formatPrice(plan.precio) }}</span>
                                    <span class="iq-plan-period">
                                        {{
                                            plan.periodo === 'year'
                                                ? languageStore.t('iqAdmin.periodYear')
                                                : languageStore.t('iqAdmin.periodMonth')
                                        }}
                                    </span>
                                </div>

                                <p v-if="plan.id === 3" class="iq-plan-save">{{ languageStore.t('iqAdmin.ahorras') }}</p>

                                <div v-if="plan.niveles?.length" class="iq-plan-levels">
                                    <div v-for="nivel in plan.niveles" :key="nivel.clave" class="iq-plan-level">
                                        <span>{{ planLabel(nivel.clave) }}</span>
                                        <strong>{{ formatPrice(nivel.precio) }}</strong>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="iq-plan-cta"
                                    :disabled="isCurrentPlan(plan.id) || !pagoDisponible"
                                    @click.stop="selectPlan(plan.id)"
                                >
                                    {{
                                        isCurrentPlan(plan.id)
                                            ? languageStore.t('iqAdmin.subscribed')
                                            : pagoDisponible
                                              ? languageStore.t('iqAdmin.subscribe')
                                              : languageStore.t('iqAdmin.paySoon')
                                    }}
                                </button>
                            </article>
                        </div>
                    </section>
                </template>

                <!-- PAGOS -->
                <template v-else>
                    <section class="iq-panel iq-panel--wide">
                        <div class="iq-panel-head">
                            <h2>{{ languageStore.t('iqAdmin.paymentHistory') }}</h2>
                        </div>
                        <p class="iq-plans-hint">{{ languageStore.t('iqAdmin.paymentHistoryHint') }}</p>

                        <div v-if="pagos.length" class="iq-payments">
                            <div class="iq-pay-row iq-pay-row--head">
                                <span>{{ languageStore.t('iqAdmin.paymentDate') }}</span>
                                <span>{{ languageStore.t('iqAdmin.paymentPlan') }}</span>
                                <span>{{ languageStore.t('iqAdmin.amount') }}</span>
                                <span>{{ languageStore.t('iqAdmin.paymentMethod') }}</span>
                                <span>Status</span>
                            </div>
                            <div v-for="pago in pagos" :key="pago.id" class="iq-pay-row">
                                <span>{{ pago.pagado_en || pago.created_at || '—' }}</span>
                                <strong>{{ pago.plan_nombre || '—' }}</strong>
                                <span>{{ pago.monto_formato }}</span>
                                <span>{{ pago.metodo || '—' }}</span>
                                <span class="iq-pay-status" :data-status="pago.status">
                                    {{ pagoStatusLabel(pago.status) }}
                                </span>
                            </div>
                        </div>
                        <p v-else class="iq-empty">{{ languageStore.t('iqAdmin.noPayments') }}</p>
                    </section>
                </template>
            </main>
        </div>
    </div>
</template>

<style scoped>
.iq-dash {
    --side-collapsed: 7.2rem;
    --side-expanded: 24rem;
    position: relative;
    width: 100vw;
    max-width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    align-self: stretch;
    display: flex;
    align-items: stretch;
    padding: 0;
    box-sizing: border-box;
    background: #000;
    color: #fff;
    overflow-x: hidden;
    overflow-y: auto;
}

.iq-side {
    position: sticky;
    top: 0;
    z-index: 5;
    flex: 0 0 var(--side-collapsed);
    width: var(--side-collapsed);
    height: 100vh;
    height: 100dvh;
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
    padding: 1.6rem 0.9rem;
    box-sizing: border-box;
    border-right: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(0, 0, 0, 0.28);
    backdrop-filter: blur(8px);
    overflow: hidden;
    transition:
        flex-basis 0.35s cubic-bezier(0.22, 1, 0.36, 1),
        width 0.35s cubic-bezier(0.22, 1, 0.36, 1),
        background 0.35s ease,
        box-shadow 0.35s ease;
}

.iq-side:hover,
.iq-side:focus-within {
    flex-basis: var(--side-expanded);
    width: var(--side-expanded);
    background: rgba(0, 0, 0, 0.42);
    box-shadow: 0 0 0 1px rgba(255, 80, 80, 0.12), 12px 0 40px rgba(0, 0, 0, 0.35);
}

.iq-side-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-height: 4.4rem;
    padding: 0 0.55rem;
}

.iq-side-brand img {
    width: 3.6rem;
    height: auto;
    flex-shrink: 0;
}

.iq-side-brand-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
    opacity: 0;
    transform: translateX(-0.6rem);
    transition: opacity 0.25s ease 0.05s, transform 0.3s ease;
    white-space: nowrap;
}

.iq-side:hover .iq-side-brand-text,
.iq-side:focus-within .iq-side-brand-text {
    opacity: 1;
    transform: translateX(0);
}

.iq-side-brand-text strong {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.55rem;
    font-weight: 400;
}

.iq-side-brand-text span {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.55);
}

.iq-side-nav,
.iq-side-foot {
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}

.iq-side-nav {
    flex: 1;
}

.iq-side-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-height: 4.2rem;
    padding: 0 0.85rem;
    border: 1px solid transparent;
    border-radius: 1rem 1.8rem;
    background: transparent;
    color: rgba(255, 255, 255, 0.82);
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}

.iq-side-link:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.1);
}

.iq-side-link.is-active {
    background: linear-gradient(135deg, rgba(170, 24, 24, 0.45), rgba(70, 10, 10, 0.35));
    border-color: rgba(255, 120, 120, 0.35);
    color: #fff;
}

.iq-side-link--danger:hover {
    background: rgba(170, 24, 24, 0.28);
}

.iq-side-ico {
    width: 2.2rem;
    height: 2.2rem;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.iq-side-ico svg {
    width: 2rem;
    height: 2rem;
}

.iq-side-label {
    opacity: 0;
    transform: translateX(-0.5rem);
    white-space: nowrap;
    font-size: 1.4rem;
    transition: opacity 0.25s ease 0.05s, transform 0.3s ease;
}

.iq-side:hover .iq-side-label,
.iq-side:focus-within .iq-side-label {
    opacity: 1;
    transform: translateX(0);
}

.iq-dash-shell {
    position: relative;
    z-index: 2;
    flex: 1;
    min-width: 0;
    width: min(108rem, 100%);
    margin: 0 auto;
    padding: clamp(1.6rem, 3vw, 3.2rem);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: clamp(1.6rem, 2.2vw, 2.4rem);
}

.iq-dash-top h1 {
    margin: 0;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2.2rem, 3vw, 3rem);
    font-weight: 400;
}

.iq-dash-top p {
    margin: 0.4rem 0 0;
    font-size: 1.35rem;
    color: rgba(255, 255, 255, 0.7);
    overflow-wrap: anywhere;
}

.iq-dash-main {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 1.6rem;
    align-content: start;
}

.iq-panel--wide,
.iq-panel--apps,
.iq-panel--plans {
    grid-column: 1 / -1;
}

.iq-panel {
    padding: clamp(1.8rem, 2.4vw, 2.4rem);
    border: 1px solid rgba(255, 255, 255, 0.14);
    background: rgba(0, 0, 0, 0.42);
    backdrop-filter: blur(6px);
    box-sizing: border-box;
    min-width: 0;
}

.iq-panel--main {
    background:
        linear-gradient(180deg, rgba(170, 24, 24, 0.18), transparent 45%),
        rgba(0, 0, 0, 0.42);
}

.iq-panel h2 {
    margin: 0 0 1.4rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 2rem;
    font-weight: 400;
}

.iq-panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.4rem;
}

.iq-panel-head h2 {
    margin: 0;
}

.iq-panel-title {
    margin: 0 0 1.4rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: clamp(2rem, 2.4vw, 2.4rem);
    color: #ffb4b4;
}

.iq-apps {
    display: grid;
    gap: 1.4rem;
}

.iq-app-block {
    display: grid;
    gap: 0;
    border: 1px solid rgba(255, 255, 255, 0.14);
    background: rgba(255, 255, 255, 0.03);
    overflow: hidden;
}

.iq-app-card {
    display: flex;
    align-items: center;
    gap: 1.4rem;
    padding: 1.4rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background:
        linear-gradient(135deg, rgba(170, 24, 24, 0.16), transparent 55%),
        rgba(0, 0, 0, 0.2);
}

.iq-app-card img {
    width: 5.2rem;
    height: auto;
    flex-shrink: 0;
}

.iq-app-fallback {
    width: 5.2rem;
    height: 5.2rem;
    display: grid;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, 0.18);
    background: rgba(170, 24, 24, 0.25);
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 2.2rem;
}

.iq-app-empresa {
    padding: 1.4rem 1.5rem 1.6rem;
}

.iq-app-empresa > h4 {
    margin: 0 0 1rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 1.7rem;
    font-weight: 400;
}

.iq-empresa-row {
    display: flex;
    align-items: flex-start;
    gap: 1.4rem;
}

.iq-empresa-logo {
    flex-shrink: 0;
    width: 7.2rem;
    height: 7.2rem;
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: rgba(255, 255, 255, 0.04);
    overflow: hidden;
}

.iq-empresa-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.iq-empresa-logo-fallback {
    width: 100%;
    height: 100%;
    display: grid;
    place-items: center;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 2.6rem;
    color: #ffb4b4;
    background: rgba(170, 24, 24, 0.22);
}

.iq-empresa-copy {
    min-width: 0;
    flex: 1;
}

.iq-empresa-copy .iq-panel-title {
    margin-bottom: 1rem;
}

.iq-product-kicker {
    margin: 0 0 0.3rem;
    font-size: 1.2rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255, 180, 180, 0.9);
}

.iq-app-card h3 {
    margin: 0 0 0.35rem;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 2rem;
    font-weight: 400;
}

.iq-app-card > div > p:last-child {
    margin: 0;
    font-size: 1.35rem;
    color: rgba(255, 255, 255, 0.68);
}

.iq-meta {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 1rem;
}

.iq-meta li {
    display: grid;
    grid-template-columns: minmax(10rem, 12rem) minmax(0, 1fr);
    gap: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 1.4rem;
}

.iq-meta span {
    color: rgba(255, 255, 255, 0.55);
}

.iq-meta strong {
    font-weight: 500;
    overflow-wrap: anywhere;
}

.iq-meta strong.is-limit {
    color: #ffe0a8;
}

.iq-badge {
    display: inline-flex;
    align-items: center;
    min-height: 3rem;
    padding: 0 1.2rem;
    border: 1px solid transparent;
    border-radius: 0.8rem 1.6rem;
    font-size: 1.3rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.iq-badge.is-ok {
    color: #9dffb5;
    border-color: rgba(80, 200, 120, 0.45);
    background: rgba(40, 120, 70, 0.25);
}

.iq-badge.is-danger {
    color: #ffb0b0;
    border-color: rgba(200, 60, 60, 0.45);
    background: rgba(120, 30, 30, 0.3);
}

.iq-badge.is-warn,
.iq-badge.is-muted {
    color: #ffe0a8;
    border-color: rgba(200, 160, 60, 0.4);
    background: rgba(90, 70, 20, 0.28);
}

.iq-days {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    margin-top: 1.6rem;
    padding: 1.2rem 1.4rem;
    border: 1px solid rgba(255, 255, 255, 0.14);
    background: rgba(255, 255, 255, 0.04);
}

.iq-days-num {
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 3rem;
    line-height: 1;
    color: #ffb4b4;
}

.iq-days-copy p {
    margin: 0;
    font-size: 1.35rem;
    color: rgba(255, 255, 255, 0.78);
}

.iq-days-grace {
    margin-top: 0.25rem !important;
    color: rgba(255, 180, 180, 0.9) !important;
}

.iq-days.is-grace .iq-days-num {
    color: #ffe0a8;
}

.iq-days.is-expired .iq-days-num {
    color: #ffb0b0;
}

.iq-change-btn {
    margin-top: 1.8rem;
    min-height: 4.2rem;
    padding: 0 1.8rem;
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 0.8rem 1.6rem;
    background: linear-gradient(135deg, var(--color-first) 0%, #7a1010 55%, #4a0808 100%);
    color: #fff;
    font-size: 1.45rem;
    cursor: pointer;
}

.iq-change-btn.is-open {
    background: rgba(255, 255, 255, 0.08);
}

.iq-plans-hint {
    margin: -0.4rem 0 1.6rem;
    font-size: 1.4rem;
    color: rgba(255, 255, 255, 0.65);
}

.iq-plans {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.2rem;
}

.iq-plan {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.8rem 1.6rem 1.6rem;
    border: 1px solid rgba(255, 255, 255, 0.18);
    background: rgba(255, 255, 255, 0.04);
    cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease;
}

.iq-plan:hover,
.iq-plan.is-selected {
    border-color: rgba(255, 120, 120, 0.7);
    background: rgba(170, 24, 24, 0.18);
}

.iq-plan.is-featured {
    border-color: rgba(255, 140, 140, 0.55);
}

.iq-plan.is-progresivo {
    border-color: rgba(180, 40, 40, 0.55);
}

.iq-plan.is-current {
    outline: 1px solid rgba(157, 255, 181, 0.45);
}

.iq-plan-badge {
    position: absolute;
    top: -0.85rem;
    right: 1.2rem;
    padding: 0.35rem 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: linear-gradient(135deg, var(--color-first) 0%, #7a1010 100%);
    font-size: 1.15rem;
    font-weight: 600;
}

.iq-plan-head strong {
    display: block;
    font-family: var(--familyTitles), Georgia, serif;
    font-size: 2rem;
    font-weight: 400;
}

.iq-plan-head p {
    margin: 0.35rem 0 0;
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.6);
}

.iq-plan-price {
    text-align: center;
}

.iq-plan-desde {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.55);
}

.iq-plan-strike {
    display: block;
    font-size: 1.35rem;
    color: rgba(255, 255, 255, 0.45);
    text-decoration: line-through;
}

.iq-plan-amount {
    display: block;
    font-size: 2.4rem;
    font-weight: 600;
    color: #ffb4b4;
    line-height: 1.1;
}

.iq-plan-period {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.62);
}

.iq-plan-save {
    margin: 0;
    text-align: center;
    font-size: 1.25rem;
    color: #9dffb5;
}

.iq-plan-levels {
    display: grid;
    gap: 0.55rem;
    padding: 1rem;
    border: 1px solid rgba(180, 40, 40, 0.35);
    background: rgba(156, 2, 2, 0.12);
}

.iq-plan-level {
    display: flex;
    justify-content: space-between;
    gap: 0.8rem;
    font-size: 1.3rem;
}

.iq-plan-level strong {
    color: #ffb4b4;
    white-space: nowrap;
}

.iq-plan-cta {
    margin-top: auto;
    min-height: 3.8rem;
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 0.8rem 1.6rem;
    background: linear-gradient(135deg, var(--color-first) 0%, #7a1010 55%, #4a0808 100%);
    color: #fff;
    font-size: 1.35rem;
    cursor: pointer;
}

.iq-plan-cta:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.iq-payments {
    display: grid;
    gap: 0.7rem;
}

.iq-pay-row {
    display: grid;
    grid-template-columns: 1.3fr 1.2fr 0.9fr 1fr 1fr;
    gap: 1rem;
    align-items: center;
    padding: 1.1rem 1.2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.03);
    font-size: 1.35rem;
}

.iq-pay-row--head {
    border-color: rgba(255, 255, 255, 0.18);
    background: rgba(170, 24, 24, 0.16);
    color: rgba(255, 255, 255, 0.72);
    font-size: 1.2rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

.iq-pay-status[data-status='aprobado'] {
    color: #9dffb5;
}

.iq-pay-status[data-status='pendiente'] {
    color: #ffe0a8;
}

.iq-pay-status[data-status='rechazado'],
.iq-pay-status[data-status='reembolsado'] {
    color: #ffb0b0;
}

.iq-empty {
    margin: 0;
    color: rgba(255, 255, 255, 0.65);
    font-size: 1.45rem;
}

@media (max-width: 1100px) {
    .iq-plans {
        grid-template-columns: 1fr;
    }

    .iq-pay-row,
    .iq-pay-row--head {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 860px) {
    .iq-dash {
        flex-direction: column;
    }

    .iq-side {
        position: sticky;
        top: 0;
        z-index: 8;
        flex: none;
        width: 100%;
        height: auto;
        flex-direction: row;
        align-items: center;
        gap: 0.8rem;
        padding: 0.9rem 1rem;
        border-right: 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        overflow-x: auto;
    }

    .iq-side:hover,
    .iq-side:focus-within {
        flex-basis: auto;
        width: 100%;
        box-shadow: none;
    }

    .iq-side-brand-text,
    .iq-side-label {
        opacity: 1;
        transform: none;
    }

    .iq-side-brand {
        display: none;
    }

    .iq-side-nav,
    .iq-side-foot {
        flex-direction: row;
        flex: initial;
    }

    .iq-side-foot {
        margin-left: auto;
    }

    .iq-side-link {
        min-height: 3.6rem;
        padding: 0 1rem;
        white-space: nowrap;
    }

    .iq-dash-shell {
        margin-top: 0;
        padding-top: 1.4rem;
    }

    .iq-dash-main {
        grid-template-columns: 1fr;
    }

    .iq-meta li {
        grid-template-columns: 1fr;
        gap: 0.35rem;
    }

    .iq-empresa-row {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .iq-empresa-copy .iq-meta li {
        justify-items: center;
    }
}
</style>
