import Vue from 'vue';
import VueI18n from 'vue-i18n';

import en from './en/en';
import he from './he/he';

Vue.use(VueI18n);

export default new VueI18n({
    locale: (localStorage.getItem('locale') === null) ? 'en' : localStorage.getItem('locale'),
    messages: {
        en,
        he,
    },
});
