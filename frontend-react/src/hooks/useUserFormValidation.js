import { useEffect, useState } from 'react';
import validateUserForm from "../helper/validateUserForm";

export default function useUserFormValidation () {
    const [values, setValues] = useState({
        email: '',
        name: '',
        currentPassword: '',
        password: '',
        newPassword: '',
        confirmPassword: ''
    });
    const [touched, setTouched] = useState({});
    const [errors, setErrors] = useState({});

    useEffect(() => {
        setErrors(validateUserForm(values));
    }, [values]);

    const handleChange = e => {
        const { name, value } = e.target;
        setValues({
            ...values,
            [name]: value
        });
        setTouched({
            ...touched,
            [name]: value !== ''
        });
    };

    const clearAll = () => {
        setErrors({});
        setTouched({});
        setValues({
            email: '',
            name: '',
            currentPassword: '',
            password: '',
            newPassword: '',
            confirmPassword: ''
        });
    };

    return { values, errors, touched, handleChange, clearAll };
};
