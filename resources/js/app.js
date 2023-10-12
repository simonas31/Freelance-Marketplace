import "./config";

import { plugin, defaultConfig } from "@formkit/vue";
import config from "/formkit.config";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import Store from "./Store";
import "@formkit/themes/genesis";
import "flowbite";
import titleMixin from "./mixins";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props }) {
        createApp({ render: () => h(App, props) })
            .use(plugin, defaultConfig(config))
            .use(Store)
            .mixin(titleMixin)
            .mount(el);
    },
});
