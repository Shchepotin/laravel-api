import { mapGettersFromStates } from 'schepotin-vuex-helpers';
import states from './state';

export default {
    /**
     * Generates {@link https://vuex.vuejs.org/en/getters.html | getters} from
     * {@link https://vuex.vuejs.org/en/state.html | states}
     *
     * Documentation
     * {@link https://www.npmjs.com/package/schepotin-vuex-helpers#mapgettersfromstates | mapGettersFromStates}
     */
    ...mapGettersFromStates({
        states,
    }),
};
