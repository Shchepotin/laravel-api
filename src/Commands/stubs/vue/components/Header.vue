<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
        <div class="container">
            <router-link :to="{ name: 'home' }" class="navbar-brand">
                {{ $t("translation.appName") }}
            </router-link>

            <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#app-navbar"
                    aria-controls="app-navbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="app-navbar">
                <ul class="navbar-nav mr-auto">
                    <router-link :to="{ name: 'home' }" tag="li" class="nav-item" exact>
                        <a class="nav-link">
                            {{ $t("translation.homepage") }}
                        </a>
                    </router-link>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a
                                class="nav-link dropdown-toggle"
                                href="#" id="language-dropdown"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                        >
                            {{ $t(`translation.${authCurrentLang}`) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language-dropdown">
                            <a
                                    v-for="language in languages"
                                    :key="language.language"
                                    class="dropdown-item"
                                    href="#"
                                    @click.prevent="lang(language.language, language.direction)"
                            >
                                {{ $t(`translation.${language.language}`) }}
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown" v-if="authLogged">
                        <a
                                class="nav-link dropdown-toggle"
                                href="#" id="user-dropdown"
                                role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                        >
                            {{ authName }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user-dropdown">
                            <router-link
                                    :to="{ name: 'auth.profile' }"
                                    class="dropdown-item"
                            >
                                {{ $t("translation.profile") }}
                            </router-link>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" @click.prevent="logout">
                                {{ $t("translation.logout") }}
                            </a>
                        </div>
                    </li>
                    <template v-else>
                        <router-link :to="{ name: 'auth.login' }" tag="li" class="nav-item">
                            <a class="nav-link">
                                {{ $t("translation.login") }}
                            </a>
                        </router-link>
                        <router-link
                                :to="{ name: 'auth.register' }"
                                tag="li" class="nav-item"
                        >
                            <a class="nav-link">
                                {{ $t("translation.register") }}
                            </a>
                        </router-link>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import authMixin from '../mixins/auth';

    export default {
        mixins: [
            authMixin,
        ],
    };
</script>
