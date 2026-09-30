# PASO 3 — Casos de portafolio (problema → qué hice → resultado)

> Para Cursor. Fuente de verdad acordada: **i18n para el copy** (esp/eng/pt) y **cards solo como interruptor/metadatos**.
> Este archivo define el copy. No reescribe marketing ni duplica: se agrega como bloque nuevo en `resources/lang/{esp,eng,pt}.ts`
> y se consume desde `seccion-4.vue` (iQ Athletic) y `seccion-4-1.vue` (Ecommerce).

---

## 1) iQ Athletic

**Problema**
Los centros deportivos manejan alumnos, cuotas, accesos y personal en planillas sueltas o sistemas rígidos que no se adaptan a su marca ni a su tamaño.

**Qué hice**
Sistema de gestión **multi-tenant** en Laravel + Vue: cada centro opera con su propia identidad, con roles separados (administrador, personal, alumno), control de acceso por QR, caja y finanzas con reportes PDF, planes de nutrición y rutinas con IA, gestión de actividades y cupos, y app de marca blanca.

**Resultado**
En producción, con sitio oficial propio (`iqathleticsoftware.com`) y clientes usándolo. Dos planes comerciales activos: Progresivo (pago según alumnos activos) y Total (precio fijo, alumnos ilimitados).

**Stack:** Laravel · Vue 3 · TypeScript · MySQL · app mobile de marca blanca

---

## 2) Ecommerce

**Problema**
Comercios que quieren vender online pero no tienen cómo manejar catálogo, stock, precios y cobros sin depender de plantillas genéricas o de un tercero que se queda con el margen.

**Qué hice**
Ecommerce propia con panel de administración completo: alta/edición de productos y stock, cotización del dólar que actualiza todos los precios de una vez, **Mercado Pago integrado con las keys del cliente** (el dinero entra directo a su cuenta), ventas en tiempo real, logística (choferes, vehículos, despacho), boletas e identidad de tienda configurable. Vista de cliente con catálogo por categorías, ofertas y carrito.

**Resultado**
Producto listo para vender: **una instalación = una tienda = su dominio**, con dominio, hosting, mantenimiento y soporte incluidos. El cliente sube productos y empieza a vender sin armar nada técnico.

**Stack:** Laravel · Vue 3 · MySQL · Mercado Pago API · hosting + dominio propios

---

## Dato de impacto — PENDIENTE

Falta un número real por proyecto para cerrar el "Resultado" con métrica. Opciones válidas (elegir una por proyecto):
- cantidad de clientes / centros activos
- fecha de puesta en producción ("en producción desde MM/AAAA")
- un caso concreto ("lo usa <nombre del centro>")

Sin métrica, queda como está arriba ("en producción", "clientes usándolo"), que ya sirve.

---

## Cómo integrarlo (para Cursor)

1. **Agregar bloque i18n** en `resources/lang/esp.ts`, `eng.ts` y `pt.ts`, dentro de cada proyecto
   (`iq:` y `shop:`), con las claves del caso:

```ts
// dentro de `iq: { ... }`
caseProblemTitle: 'El problema',
caseProblemBody: '...',
caseWorkTitle: 'Qué construí',
caseWorkBody: '...',
caseResultTitle: 'Resultado',
caseResultBody: '...',
caseStack: 'Laravel · Vue 3 · TypeScript · MySQL · app mobile de marca blanca',
```

```ts
// dentro de `shop: { ... }`
caseProblemTitle: 'El problema',
caseProblemBody: '...',
caseWorkTitle: 'Qué construí',
caseWorkBody: '...',
caseResultTitle: 'Resultado',
caseResultBody: '...',
caseStack: 'Laravel · Vue 3 · MySQL · Mercado Pago API · hosting + dominio propios',
```

2. **Consumirlo** en `seccion-4.vue` (iQ) y `seccion-4-1.vue` (Ecommerce) con
   `languageStore.t('iq.caseProblemTitle')`, etc. — mismo patrón que ya usan esas vistas.

3. **`cards` no cambia**: sigue como interruptor/metadatos (`project`, `card`, `icon`, `hover_text`,
   `descripcion`, `active`). El copy largo NO va a `cards`.

4. **Bloque SEO** (`resources/views/partials/seo-body.blade.php`): opcional sumar una línea del
   problema/resultado por proyecto en el HTML inicial, para que los bots lean el caso completo.
