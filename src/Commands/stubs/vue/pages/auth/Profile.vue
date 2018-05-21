<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            {{ $t('translation.name')}}: <strong>{{ authName }}</strong>
                        </li>
                        <li class="list-group-item">
                            {{ $t('translation.email')}}: <strong>{{ authEmail }}</strong>
                        </li>
                    </ul>
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
                title: this.authName,
            };
        },
    };
</script>
