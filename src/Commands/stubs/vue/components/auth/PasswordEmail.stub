<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $t("translation.login") }}</h3>
                    </div>
                    <div class="panel-body">
                        <div v-if="progress" class="loading"></div>
                        <div class="col-md-12">
                            <form class="form-horizontal" @submit.prevent="passwordEmail">
                                <div :class="{ 'form-group': true, 'has-error': errors.has('email') }">
                                    <label for="email" class="control-label">{{ $t("translation.email") }}</label>
                                    <input id="email" type="email" class="form-control" name="email" v-model="email" v-validate="'required|email|max:255'" autofocus>
                                    <span v-show="errors.has('email')" class="help-block">{{ errors.first('email') }}</span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="login-btn btn btn-success" :disabled="this.progress">
                                        {{ $t("translation.sendPasswordResetLink") }}
                                    </button>
                                </div>
                            </form>
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
        methods: {
            async passwordEmail() {
                this.$validator.validateAll();

                if (!this.errors.any()) {
                    this.progress = true;

                    await this.$store.dispatch('passwordEmail', {
                        email: this.email,
                    });

                    try {
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
        data() {
            return {
                progress: false,
            };
        },
    };
</script>
