import { mapTwoWayState } from 'schepotin-vuex-helpers';

export default {
    computed: {
        /**
         * Generates two way {@link https://vuejs.org/v2/guide/computed.html#Computed-Setter | computed properties}
         *
         * Documentation {@link https://www.npmjs.com/package/schepotin-vuex-helpers#maptwowaystate | mapTwoWayState}
         */
        ...mapTwoWayState({
            namespace: 'auth',
            prefix: true,
        }, [
            'logged',
            'name',
            'email',
            'password',
            'passwordConfirmation',
            'currentLang',
            'currentDirection',
        ]),
        languages() {
            return [{
                language: 'en',
                direction: 'ltr',
            }, {
                language: 'he',
                direction: 'rtl',
            }];
        },
    },
    methods: {
        lang(language, direction) {
            /**
             * Set language.
             */
            this.$i18n.locale = language;
            this.authCurrentLang = language;
            this.$validator.locale = language;
            document.querySelector('html').setAttribute('lang', language);

            /**
             * Set direction.
             */
            this.authCurrentDirection = direction;
            document.querySelector('html').setAttribute('dir', direction);
        },
        async logout() {
            await this.$store.dispatch('auth/logout');

            this.$router.push({
                name: 'auth.login',
            });
        },
    },
};
