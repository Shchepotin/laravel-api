<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ $t("translation.forgotPassword") }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div v-if="progress" class="loading"></div>
                            <div class="col-12">
                                <form @submit.prevent="passwordEmail">
                                    <div class="form-group">
                                        <label for="email">
                                            {{ $t("translation.email") }}
                                        </label>
                                        <input
                                            id="email"
                                            v-model="authEmail"
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
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                        :disabled="this.progress"
                                    >
                                        {{ $t("translation.sendPasswordResetLink") }}
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
    import authMixin from '../../mixins/auth';

    export default {
        mixins: [
            authMixin,
        ],
        metaInfo() {
            return {
                title: this.$t('translation.forgotPassword'),
            };
        },
        data() {
            return {
                progress: false,
            };
        },
        methods: {
            async passwordEmail() {
                const valid = await this.$validator.validateAll();

                if (valid) {
                    this.progress = true;

                    try {
                        await this.$store.dispatch('auth/passwordEmail', {
                            email: this.authEmail,
                        });

                        this.$toast.success({
                            title: this.$t('translation.emailHasBeenSent'),
                            message: this.$t('translation.checkEmailBox'),
                        });

                        this.$router.push({
                            name: 'auth.login',
                        });
                    } catch (e) {
                        this.$toast.error({
                            title: this.$t('translation.emailHasNotBeenSent'),
                            message: this.$t('translation.tryAgain'),
                        });

                        this.progress = false;
                    }
                }
            },
        },
    };
</script>
