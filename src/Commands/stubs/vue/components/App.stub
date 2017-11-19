<template>
    <div id="app">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <router-link :to="{ name: 'home' }" class="navbar-brand">
                        Laravel
                    </router-link>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <router-link :to="{ name: 'home' }" tag="li" active-class="active" exact>
                            <router-link :to="{ name: 'home' }" exact>
                                {{ $t("translation.homepage") }}
                            </router-link>
                        </router-link>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ $t(`translation.${currentLang}`) }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li @click="lang('en')">
                                    <a href="javascript:;">{{ $t("translation.en") }}</a>
                                </li>
                                <li @click="lang('he')">
                                    <a href="javascript:;">{{ $t("translation.he") }}</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown" v-if="logged">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ name }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <router-link :to="{ name: 'auth.profile' }" tag="li" active-class="active">
                                    <router-link :to="{ name: 'auth.profile' }">
                                        {{ $t("translation.profile") }}
                                    </router-link>
                                </router-link>
                                <li>
                                    <a href="javascript:;" @click="logout">
                                        {{ $t("translation.logout") }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <router-link :to="{ name: 'auth.login' }" tag="li" active-class="active" v-if="!logged">
                            <router-link :to="{ name: 'auth.login' }">
                                {{ $t("translation.login") }}
                            </router-link>
                        </router-link>
                        <router-link :to="{ name: 'auth.register' }" tag="li" active-class="active" v-if="!logged">
                            <router-link :to="{ name: 'auth.register' }">
                                {{ $t("translation.register") }}
                            </router-link>
                        </router-link>
                    </ul>
                </div>
            </div>
        </nav>
        <transition name="slide-fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    import userMixin from '../mixins/user';

    export default {
        mixins: [
            userMixin,
        ],
        methods: {
            lang(data) {
                localStorage.setItem('locale', data);

                this.$i18n.locale = data;
                this.currentLang = data;
                this.$validator.locale = data;
            },
            async logout() {
                await this.$store.dispatch('logout');

                this.$router.push({
                    name: 'auth.login',
                });
            },
        },
        data() {
            return {
                currentLang: (localStorage.getItem('locale') === null) ? 'en' : localStorage.getItem('locale'),
            };
        },
    };
</script>

<style lang="scss">
    .slide-fade-enter-active {
        transition: all .2s ease;
    }

    .slide-fade-leave-active {
        transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-enter, .slide-fade-leave-active {
        transform: translateX(10px);
        opacity: 0;
    }
</style>
