import Vue from 'vue';
import CxltToastr from 'cxlt-vue2-toastr';

const toastrConfigs = {
    position: 'top right',
    showDuration: 1000,
    timeOut: 7500,
};

Vue.use(CxltToastr, toastrConfigs);
