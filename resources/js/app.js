require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3'
import moment from 'moment/moment';
import SimpleInput from "@/Components/SimpleInput.vue";
import SimpleSubmit from "@/Components/SimpleSubmit.vue";
import SimpleDelete from '@/Components/SimpleDelete.vue';

require('moment/locale/de')


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'SP';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(moment)
            .mixin({ methods: { route } })
            .component("SimpleInput", SimpleInput)
            .component("SimpleSubmit", SimpleSubmit)
            .component("SimpleDelete", SimpleDelete)
            .mount(el);
    },
    progress: {
      color: '#4B5563',
    }
});


