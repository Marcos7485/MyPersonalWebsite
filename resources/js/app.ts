import './bootstrap';
import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import vIntersect from '../directives/v-intersect';

createInertiaApp({
    resolve: (name: string): DefineComponent => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const component = pages[`./Pages/${name}.vue`];
        return (component as { default: DefineComponent }).default;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .directive('intersect', vIntersect)
            .component('Link', Link)
            .mount(el);
    },
});