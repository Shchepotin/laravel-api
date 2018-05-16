import messages from 'vee-validate/dist/locale/en';

export default {
    messages: {
        ...messages.messages,
        login() {
            return 'The specified user does not exist.';
        },
        unique() {
            return 'Email already exists';
        },
    },
    attributes: {
        name: 'name',
        email: 'email',
        password: 'password',
        passwordConfirmation: 'confirm password',
    },
};
