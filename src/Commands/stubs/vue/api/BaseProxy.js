import ApiError from '../exceptions/ApiError';

class BaseProxy {
    constructor(endpoint, parameters = {}) {
        this.endpoint = endpoint;
        this.parameters = parameters;
    }

    async submit(requestType, url, params = null) {
        let request = null;

        try {
            if (['get', 'delete'].includes(requestType)) {
                request = window.axios[requestType](`${this.endpoint}${url}`, { params });
            } else {
                request = window.axios[requestType](`${this.endpoint}${url}`, params);
            }

            const response = await request;

            if (response.status >= 200 && response.status < 300) {
                return response.data;
            }
        } catch (e) {
            throw new ApiError({
                status: e.response.status,
                error: e.response.data,
            });
        }

        return false;
    }

    find(id) {
        return this.submit('get', `/${id}`);
    }

    findAll(params = null) {
        return this.submit('get', '/', params);
    }

    destroy(id, params = null) {
        return this.submit('delete', `/${id}`, params);
    }

    update(id, params) {
        return this.submit('put', `/${id}`, params);
    }

    store(params) {
        return this.submit('post', '/', params);
    }
}

export default BaseProxy;
