export default {

    
    email: {
        placeholder: 'Your email address',
        label: 'Email'
    },
    password: {
        placeholder: 'Your password',
        label: 'Password'
    },
    confirmPassword: {
        placeholder: 'Confirm your password',
        label: 'Password confirmation'
    },
    currentPassword: {
        placeholder: 'Your current password',
        label: 'Current password'
    },
    newPassword: {
        placeholder: 'Your new password',
        label: 'New password'
    },
    name: {
        placeholder: 'Your full name',
        label: 'Full Name'
    },
    error: {
        email: {
            invalid: 'The email address is not valid.'
        },
        password: {
            invalid: 'The password must contains at least one number.',
            short: 'The password must be at least 4 characters long.'
        },
        name: {
            invalid: 'The name can only contain letters or digits.',
            short: 'The name must be at least 3 characters long.'
        },
        newPassword: {
            invalid: 'The password must contains at least one number',
            short: 'The password must be at least 4 characters long.',
            different: 'The new password must be different from the previous one'
        },
        confirmPassword: {
            different: 'The passwords do not match.'
        }
    }

};
