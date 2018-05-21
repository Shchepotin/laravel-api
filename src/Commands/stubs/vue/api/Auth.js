import BaseProxy from './BaseProxy';

class Auth extends BaseProxy {
    constructor(parameters = {}) {
        super('/api/v1', parameters);
    }

    async login(params = {}) {
        return this.submit('post', '/login', params);
    }

    async register(params = {}) {
        return this.submit('post', '/register', params);
    }

    async passwordEmail(params = {}) {
        return this.submit('post', '/password/email', params);
    }

    async resetPassword(params = {}) {
        return this.submit('post', '/reset/password', params);
    }

    async getCurrent() {
        return this.submit('get', '/user/current');
    }
}

export default Auth;
