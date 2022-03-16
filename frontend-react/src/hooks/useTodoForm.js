import { useEffect, useState } from "react";
import validateTodoForm from "../helper/validateTodoForm";

export default function useTodoForm (todo) {
    const [errors, setErrors] = useState({});
    const [currentTodo, setCurrentTodo] = useState({
        name: '',
        description: '',
        date: '',
        reminder: '',
        isDone: false
    });

    useEffect(() => {
        setErrors(validateTodoForm(currentTodo));
    }, [todo]);

    const handleChange = e => {
        let { name, value } = e.target;

        setCurrentTodo({
            ...currentTodo,
            [name]: value
        })
    }

    const clearAll = () => {
        setErrors({});
        setCurrentTodo({
            name: '',
            description: '',
            date: '',
            reminder: '',
            isDone: false
        });
    }

    return { currentTodo, errors, handleChange, clearAll };
}