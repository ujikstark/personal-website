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
        Object.keys(todo).length && setCurrentTodo(todo);
    }, [todo]);

    useEffect(() => {
        setErrors(validateTodoForm(currentTodo));
    }, [currentTodo]);

    const handleChange = e => {
        let { name, value } = e.target;

        if (['date', 'reminder'].includes(name)) {
            value = new Date(value).getTime();
        }

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