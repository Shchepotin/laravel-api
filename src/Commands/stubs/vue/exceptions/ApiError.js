class ApiError {
    constructor(error) {
        this.error = error;
    }

    getStatus() {
        return this.error.status;
    }

    getError() {
        return this.error.error;
    }
}

export default ApiError;
