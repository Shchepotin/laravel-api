import messages from 'vee-validate/dist/locale/he';

export default {
    messages: {
        ...messages.messages,
        login() {
            return 'המשתמש שצוין אינו קיים.';
        },
        unique() {
            return 'האימייל כבר קיים';
        },
    },
    attributes: {
        name: 'שם',
        email: 'דוא"ל',
        password: 'סיסמה',
        passwordConfirmation: 'אשר סיסמה',
    },
};
