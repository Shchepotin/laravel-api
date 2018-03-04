<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ $t("translation.resetPassword") }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div v-if="progress" class="loading"></div>
                            <div class="col-12">
                                <form @submit.prevent="resetPassword">
                                    <div class="form-group">
                                        <label for="email">
                                            {{ $t("translation.email") }}
                                        </label>
                                        <input
                                            id="email"
                                            type="email"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('email') }"
                                            name="email"
                                            v-model="userEmail"
                                            v-validate="'required|email|max:255'"
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
                                            type="password"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('password') }"
                                            name="password"
                                            v-model="userPassword"
                                            v-validate="'required|min:6'"
                                        >
                                        <div v-show="errors.has('password')" class="invalid-feedback">
                                            {{ errors.first('password') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirmation">
                                            {{ $t("translation.confirmPassword") }}
                                        </label>
                                        <input
                                            id="password-confirmation"
                                            type="password"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('passwordConfirmation') }"
                                            name="passwordConfirmation"
                                            v-model="userPasswordConfirmation"
                                            v-validate="'required|confirmed:password'"
                                        >
                                        <div v-show="errors.has('passwordConfirmation')" class="invalid-feedback">
                                            {{ errors.first('passwordConfirmation') }}
                                        </div>
                                    </div>
                                    <button type="submit" class="login-btn btn btn-success" :disabled="this.progress">
                                        {{ $t("translation.resetPassword") }}
                                    </button>
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
                title: this.$t('translation.resetPassword'),
            };
        },
        data() {
            return {
                progress: false,
            };
        },
        beforeDestroy() {
            this.userPassword = null;
            this.userPasswordConfirmation = null;
        },
        methods: {
            async resetPassword() {
                const valid = await this.$validator.validateAll();

                if (valid) {
                    this.progress = true;

                    try {
                        await this.$store.dispatch('user/resetPassword', {
                            email: this.userEmail,
                            password: this.userPassword,
                            password_confirmation: this.userPasswordConfirmation,
                            token: this.$route.params.token,
                        });

                        this.$toast.success({
                            title: this.$t('translation.success'),
                            message: this.$t('translation.passwordHasBeenChanged'),
                        });

                        this.$router.push({
                            name: 'auth.login',
                        });
                    } catch (e) {
                        this.$toast.error({
                            title: this.$t('translation.error'),
                            message: this.$t('translation.tryAgain'),
                        });

                        this.progress = false;
                    }
                }
            },
        },
    };
</script>
