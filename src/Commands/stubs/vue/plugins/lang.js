import Vue from 'vue';
import VueI18n from 'vue-i18n';
import store from '../store';

import en from '../lang/en/en';
import he from '../lang/he/he';

Vue.use(VueI18n);

document.querySelector('html').setAttribute('lang', store.getters['user/currentLang']);
document.querySelector('html').setAttribute('dir', store.getters['user/currentDirection']);

export default new VueI18n({
    locale: store.getters['user/currentLang'],
    messages: {
        en,
        he,
    },
});
