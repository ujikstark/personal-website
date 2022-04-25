import userFormText from './userFormText';

export default function validateUserForm (values) {
    const errors = {};

    if (values.name) {
        if (values.name && values.name.trim().length < 3) {
            errors.name = userFormText.error.name.short;
        } else if (!/^[a-zA-Z0-9]{3,}/.test(values.name)) {
            errors.name = userFormText.error.name.invalid;
        }
    }

    if (values.email) {
        if (!/^[a-zA-Z0-9.!#$%&'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/.test(values.email)) {
            errors.email = userFormText.error.email.invalid;
        }
    }

    if (values.password) {
        if (values.password.length < 4) {
            errors.password = userFormText.error.password.short;
        } else if (!/\d/.test(values.password)) {
            errors.password = userFormText.error.password.invalid;
        }
    }

    if (values.confirmPassword) {
        if (values.confirmPassword !== values.password && values.confirmPassword !== values.newPassword) {
            errors.confirmPassword = userFormText.error.confirmPassword.different;
        }
    }

    return errors;
}

