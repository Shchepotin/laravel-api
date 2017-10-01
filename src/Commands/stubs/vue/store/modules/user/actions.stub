import user from '../../../api/user';
import * as types from '../../mutation-types';

export const login = ({ dispatch, commit }, payload) => {
    user.login(payload.data, (json) => {
        if (json.status === 1) {
            commit(types.LOGIN, {
                token: json.token,
            });

            if (payload !== undefined && payload.success !== undefined) {
                dispatch('getUserCurrent', {
                    success: payload.success,
                });
            } else {
                dispatch('getUserCurrent');
            }
        } else if (json.status === 0) {
            if (payload !== undefined && payload.error !== undefined) {
                payload.error();
            }
        }
    });
};

export const logout = ({ commit }, payload) => {
    commit(types.ID, 0);
    commit(types.NAME, '');
    commit(types.EMAIL, '');
    commit(types.CREATED_AT, '');
    commit(types.UPDATED_AT, '');
    commit(types.LOGOUT);

    if (payload !== undefined && payload.success !== undefined) {
        payload.success();
    }
};

export const register = ({ dispatch, commit }, payload) => {
    user.register(payload.data, (json) => {
        if (json.status === 1) {
            commit(types.LOGIN, {
                token: json.token,
            });

            if (payload !== undefined && payload.success !== undefined) {
                dispatch('getUserCurrent', {
                    success: payload.success,
                });
            } else {
                dispatch('getUserCurrent');
            }
        } else if (json.status === 0) {
            if (payload !== undefined && payload.error !== undefined) {
                payload.error();
            }
        }
    });
};

export const passwordEmail = (context, payload) => {
    user.passwordEmail(payload.data, (json) => {
        if (json.status === 1) {
            if (payload !== undefined && payload.success !== undefined) {
                payload.success();
            }
        } else if (json.status === 0) {
            if (payload !== undefined && payload.error !== undefined) {
                payload.error();
            }
        }
    });
};

export const resetPassword = (context, payload) => {
    user.resetPassword(payload.data, (json) => {
        if (json.status === 1) {
            if (payload !== undefined && payload.success !== undefined) {
                payload.success();
            }
        } else if (json.status === 0) {
            if (payload !== undefined && payload.error !== undefined) {
                payload.error();
            }
        }
    });
};

export const getUserCurrent = ({ commit }, payload) => {
    user.getUserCurrent((json) => {
        if (json.status === 1) {
            commit(types.ID, json.data.id);
            commit(types.NAME, json.data.name);
            commit(types.EMAIL, json.data.email);
            commit(types.CREATED_AT, json.data.created_at);
            commit(types.UPDATED_AT, json.data.updated_at);

            if (payload !== undefined && payload.success !== undefined) {
                payload.success();
            }
        }
    });
};

export const checkLogged = ({ dispatch, commit }) => {
    const token = window.localStorage.getItem('token');

    if (token !== null) {
        commit(types.LOGIN, {
            token,
        });

        dispatch('getUserCurrent');
    }
};

export default {
    login,
    logout,
    register,
    passwordEmail,
    resetPassword,
    getUserCurrent,
    checkLogged,
};
