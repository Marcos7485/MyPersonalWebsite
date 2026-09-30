{{-- Contenido legible en el HTML inicial (bots / noscript). No depende del bundle Vue. --}}
<section class="seo-crawl" aria-label="Resumen del portafolio Dragon Rojo Software">
    <h1>Dragon Rojo Software</h1>
    <p>
        Estudio de desarrollo web fullstack dirigido por Marcos Gonzalez.
        Diseñamos y construimos softwares eficientes para negocios reales.
    </p>

    <h2>Proyectos / Softwares</h2>

    <article>
        <h3>iQ Athletic</h3>
        <p>Tu centro deportivo, potenciado con tecnología propia.</p>
        <p>
            <strong>Problema:</strong>
            Los centros deportivos manejan alumnos, cuotas, accesos y personal en planillas sueltas
            o sistemas rígidos que no se adaptan a su marca ni a su tamaño.
        </p>
        <p>
            <strong>Qué construí:</strong>
            Sistema multi-tenant en Laravel + Vue con roles, acceso QR, finanzas, nutrición/rutinas con IA
            y app de marca blanca.
        </p>
        <p>
            <strong>Resultado:</strong>
            En producción, con sitio oficial propio
            (<a href="https://www.iqathleticsoftware.com" rel="noopener noreferrer">iqathleticsoftware.com</a>)
            y clientes usándolo. Planes Progresivo y Total activos.
        </p>
        <p>Stack: Laravel · Vue 3 · TypeScript · MySQL · app mobile de marca blanca</p>
        <p><a href="{{ url('/#Softwares') }}">Ver iQ Athletic en este portafolio</a></p>
    </article>

    <article>
        <h3>Ecommerce</h3>
        <p>Tu tienda online, lista para vender.</p>
        <p>
            <strong>Problema:</strong>
            Comercios que quieren vender online sin depender de plantillas genéricas
            o de un tercero que se queda con el margen.
        </p>
        <p>
            <strong>Qué construí:</strong>
            Ecommerce propia con panel admin, cotización del dólar, Mercado Pago con keys del cliente,
            logística, catálogo para el comprador y chatbot de ayuda sobre el uso del administrador.
        </p>
        <p>
            <strong>Resultado:</strong>
            Una instalación = una tienda = su dominio, con hosting y soporte incluidos.
            El cliente sube productos y vende sin armar nada técnico.
        </p>
        <p>Stack: Laravel · Vue 3 · MySQL · Mercado Pago API · hosting + dominio propios · Chatbot de ayuda admin</p>
        <p><a href="{{ url('/#Ecommerce') }}">Ver Ecommerce en este portafolio</a></p>
    </article>

    <article>
        <h3>Zankou</h3>
        <p>Una IA con memoria, ojos y cerebro propio.</p>
        <p>
            <strong>Problema:</strong>
            Los asistentes de IA viven en una pestaña: no recuerdan, no ven la pantalla
            y no pueden tocar la computadora.
        </p>
        <p>
            <strong>Qué construí:</strong>
            Asistente de escritorio (Electron + Node.js) con memoria persistente, visión por computador,
            control del sistema y modelos propios entrenándose en local.
        </p>
        <p>
            <strong>Resultado:</strong>
            Un asistente que funciona todos los días: recuerda, opera la PC y aprende de sus errores.
            Laboratorio de ideas que después van a productos comerciales.
        </p>
        <p>Stack: Electron · Node.js · PowerShell · OCR · modelos propios · AWS S3 · WhatsApp</p>
        <p><a href="{{ url('/#SoftwaresZankou') }}">Ver Zankou en este portafolio</a></p>
    </article>

    <h2>Contacto</h2>
    <p>
        Contacto y redes en
        <a href="{{ url('/#Contacto') }}">la sección de contacto</a>
        de Dragon Rojo Software.
    </p>
</section>

<noscript>
    <section>
        <h1>Dragon Rojo Software</h1>
        <p>
            Desarrollo web fullstack. Softwares: iQ Athletic, Ecommerce y Zankou.
            Activá JavaScript para la experiencia completa del sitio.
        </p>
        <ul>
            <li><a href="https://www.iqathleticsoftware.com">iQ Athletic</a></li>
            <li><a href="{{ url('/#Ecommerce') }}">Ecommerce</a></li>
            <li><a href="{{ url('/#SoftwaresZankou') }}">Zankou</a></li>
            <li><a href="{{ url('/#Contacto') }}">Contacto</a></li>
        </ul>
    </section>
</noscript>

<style>
    .seo-crawl {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>
