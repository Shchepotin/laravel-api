export default {
    async login(params) {
        const result = await window.axios.post('/api/v1/login', params);

        return result.data;
    },
    async register(params) {
        const result = await window.axios.post('/api/v1/register', params);

        return result.data;
    },
    async passwordEmail(params) {
        const result = await window.axios.post('/api/v1/password/email', params);

        return result.data;
    },
    async resetPassword(params) {
        const result = await window.axios.post('/api/v1/reset/password', params);

        return result.data;
    },
    async getUserCurrent() {
        const result = await window.axios.get('/api/v1/user/current');

        return result.data;
    },
};
