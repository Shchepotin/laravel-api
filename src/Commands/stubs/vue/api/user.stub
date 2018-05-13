import BaseProxy from './BaseProxy';

class User extends BaseProxy {
    constructor(parameters = {}) {
        super('/api/v1/user', parameters);
    }

    async getCurrent() {
        return this.submit('get', '/current');
    }
}

export default User;
