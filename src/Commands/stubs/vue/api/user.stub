export default {
    async login(params) {
        const { data } = await window.axios.post('/api/v1/login', params);

        return data;
    },
    async register(params) {
        const { data } = await window.axios.post('/api/v1/register', params);

        return data;
    },
    async passwordEmail(params) {
        const { data } = await window.axios.post('/api/v1/password/email', params);

        return data;
    },
    async resetPassword(params) {
        const { data } = await window.axios.post('/api/v1/reset/password', params);

        return data;
    },
    async getUserCurrent() {
        const { data } = await window.axios.get('/api/v1/user/current');

        return data;
    },
};
