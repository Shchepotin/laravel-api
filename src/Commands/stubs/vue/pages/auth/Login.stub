<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ $t("translation.login") }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div v-if="progress" class="loading"></div>
                            <div class="col-12">
                                <form @submit.prevent="auth">
                                    <div class="form-group">
                                        <label for="email">
                                            {{ $t("translation.email") }}
                                        </label>
                                        <input
                                            id="email"
                                            v-model="userEmail"
                                            type="email"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('email') }"
                                            name="email"
                                            v-validate="'required|email|max:255'"
                                            autofocus
                                        >
                                        <div v-show="errors.has('email')" class="invalid-feedback">
                                            {{ errors.first('email') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">
                                            {{ $t("translation.password") }}
                                        </label>
                                        <input
                                            id="password"
                                            v-model="userPassword"
                                            type="password"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('password') }"
                                            name="password"
                                            v-validate="'required|min:6'"
                                            @input="$validator.validate('password', 1)"
                                        >
                                        <div v-show="errors.has('password')" class="invalid-feedback">
                                            {{ errors.first('password') }}
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        :disabled="this.progress"
                                    >
                                        {{ $t("translation.login") }}
                                    </button>
                                    <router-link :to="{ name: 'auth.password.email' }" class="btn btn-link">
                                        {{ $t("translation.forgotPassword") }}
                                    </router-link>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import userMixin from '../../mixins/user';

    export default {
        mixins: [
            userMixin,
        ],
        metaInfo() {
            return {
                title: this.$t('translation.login'),
            };
        },
        data() {
            return {
                progress: false,
            };
        },
        created() {
            this.$validator.attach({
                name: 'password',
                rules: 'login',
            });
        },
        beforeDestroy() {
            this.userPassword = null;
        },
        methods: {
            async auth() {
                const valid = await this.$validator.validateAll();

                if (valid) {
                    this.progress = true;

                    try {
                        await this.$store.dispatch('user/login', {
                            email: this.userEmail,
                            password: this.userPassword,
                        });

                        this.$router.push(this.$route.query.redirect || { name: 'home' });
                    } catch (e) {
                        this.$validator.validate('password', 0);

                        this.progress = false;
                    }
                }
            },
        },
    };
</script>
