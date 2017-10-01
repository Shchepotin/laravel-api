export default {
    login(params, callback) {
        window.axios.post('/api/v1/login', params).then((data) => {
            callback(data.data);
        });
    },
    register(params, callback) {
        window.axios.post('/api/v1/register', params).then((data) => {
            callback(data.data);
        });
    },
    passwordEmail(params, callback) {
        window.axios.post('/api/v1/password/email', params).then((data) => {
            callback(data.data);
        });
    },
    resetPassword(params, callback) {
        window.axios.post('/api/v1/reset/password', params).then((data) => {
            callback(data.data);
        });
    },
    getUserCurrent(callback) {
        window.axios.get('/api/v1/user/current').then((data) => {
            callback(data.data);
        });
    },
};
