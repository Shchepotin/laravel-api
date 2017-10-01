import Vue from 'vue';
import CxltToastr from 'cxlt-vue2-toastr';
import VeeValidate, { Validator } from 'vee-validate';

import en from '../lang/en/en';
import he from '../lang/he/he';
import validationEn from '../lang/en/validator';
import validationHe from '../lang/he/validator';

const toastrConfigs = {
    position: 'top right',
    showDuration: 1000,
    timeOut: 7500,
};

Validator.extend('login', {
    messages: {
        en() {
            return en.translation.userNotExistCredentials;
        },
        he() {
            return he.translation.userNotExistCredentials;
        },
    },
    validate(value) {
        return new Promise((resolve) => {
            resolve({
                valid: value !== 0,
            });
        });
    },
});

Validator.extend('unique', {
    messages: {
        en() {
            return en.translation.emailAlreadyExists;
        },
        he() {
            return he.translation.emailAlreadyExists;
        },
    },
    validate(value) {
        return new Promise((resolve) => {
            resolve({
                valid: value !== 0,
            });
        });
    },
});

Vue.use(CxltToastr, toastrConfigs);

Vue.use(VeeValidate, {
    fieldsBagName: 'fieldsValidation',
    locale: (localStorage.getItem('locale') === null) ? 'en' : localStorage.getItem('locale'),
    dictionary: {
        en: validationEn,
        he: validationHe,
    },
});
