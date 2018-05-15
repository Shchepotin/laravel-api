<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            {{ $t('translation.name')}}: <strong>{{ userName }}</strong>
                        </li>
                        <li class="list-group-item">
                            {{ $t('translation.email')}}: <strong>{{ userEmail }}</strong>
                        </li>
                    </ul>
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
                title: this.userName,
            };
        },
    };
</script>
