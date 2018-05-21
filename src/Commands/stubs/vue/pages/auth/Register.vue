<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{ $t("translation.register") }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div v-if="progress" class="loading"></div>
                            <div class="col-12">
                                <form @submit.prevent="register">
                                    <div class="form-group">
                                        <label for="name">
                                            {{ $t("translation.name") }}
                                        </label>
                                        <input
                                            id="name"
                                            v-model="authName"
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('name') }"
                                            name="name"
                                            v-validate="'required|max:255'"
                                            autofocus
                                        >
                                        <div v-show="errors.has('name')" class="invalid-feedback">
                                            {{ errors.first('name') }}
                                        </div>
                                    </div>
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
                                            @input="$validator.validate('email', 1)"
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
                                            v-model="authPassword"
                                            type="password"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('password') }"
                                            name="password"
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
                                            v-model="authPasswordConfirmation"
                                            type="password"
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.has('passwordConfirmation') }"
                                            name="passwordConfirmation"
                                            v-validate="'required|confirmed:password'"
                                        >
                                        <div v-show="errors.has('passwordConfirmation')" class="invalid-feedback">
                                            {{ errors.first('passwordConfirmation') }}
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        class="login-btn btn btn-success"
                                        :disabled="this.progress"
                                    >
                                        {{ $t("translation.register") }}
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
                title: this.$t('translation.register'),
            };
        },
        data() {
            return {
                progress: false,
            };
        },
        created() {
            this.$validator.attach({
                name: 'email',
                rules: 'unique',
            });
        },
        beforeDestroy() {
            this.authPassword = null;
            this.authPasswordConfirmation = null;
        },
        methods: {
            async register() {
                const valid = await this.$validator.validateAll();

                if (valid) {
                    this.progress = true;

                    try {
                        await this.$store.dispatch('auth/register', {
                            name: this.authName,
                            email: this.authEmail,
                            password: this.authPassword,
                            password_confirmation: this.authPasswordConfirmation,
                        });

                        this.$router.push({
                            name: 'home',
                        });
                    } catch (e) {
                        this.$validator.validate('email', 0);

                        this.progress = false;
                    }
                }
            },
        },
    };
</script>
