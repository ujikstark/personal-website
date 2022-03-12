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
        confirmPassword: {
            different: 'The passwords do not match.'
        }
    }

};
