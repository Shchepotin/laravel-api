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
    },
};
