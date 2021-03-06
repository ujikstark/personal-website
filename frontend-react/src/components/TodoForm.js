import React from "react";
import { Form, Col, Row, Button } from "react-bootstrap";
import useTodoForm from "../hooks/useTodoForm";
import { format } from 'date-fns';
import PropTypes from 'prop-types';
import { createTodo, editTodo } from "../requests/todos";
import {useAuth, useAuthUpdate} from "../contexts/AuthContext";


function TodoForm ({ todos, setTodos, todo, setOpen, setTodoEdited, isFirstTodo, isEdit }) {

    const { currentTodo, errors, handleChange, clearAll } = useTodoForm(todo);
    
    const isTouched = currentTodo !== todo;
    const isFormValid = isTouched && currentTodo.name !== '' && Object.keys(errors).length === 0;

    const initialDate = currentTodo.date ? format(currentTodo.date, "yyyy-MM-dd'T'HH:mm") : '';
    const initialReminder = currentTodo.reminder ? format(currentTodo.reminder, "yyyy-MM-dd'T'HH:mm") : '';
    const minDate = currentTodo.date
        ? format(Math.min(currentTodo.date, new Date().getTime()), "yyyy-MM-dd'T'HH:mm")
        : format(new Date().getTime(), "yyyy-MM-dd'T'HH:mm");

    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    if (auth === null) {
        currentTodo.reminder = '';
    }

    if (currentTodo.description == null) currentTodo.description = '';

    const handleSubmit =  async (e) => {
        e.preventDefault();
        
        const newTodos = isEdit ? await editTodo(currentTodo, todos, auth, updateAuth) : await createTodo(currentTodo, todos, auth, updateAuth);
        
        if (newTodos === todos) {
            return;
        }

        setTodos(newTodos);
        

        isEdit ? handleEdit() : handleCreate();
    }

    const handleEdit = () => {
        setTodoEdited(0);
    }

    const handleCreate = () => {
        setOpen(false);

        if (!isFirstTodo) {
            clearAll();
        }
    }


    return (

        <Form onSubmit={handleSubmit}>
            <Row>
                <Form.Group className="mb-4" as={Col} md={6} >
                    <Form.Label>Task<span className="ml-1 text-danger">*</span></Form.Label>
                    <Form.Control
                        isInvalid={errors.name} onChange={handleChange} value={currentTodo.name} 
                        id="name" name="name" type="text" placeholder="A task" />
                    <Form.Control.Feedback type="invalid">{errors.name}</Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-4" as={Col} md={6} >
                    <Form.Label>Description</Form.Label>
                    <Form.Control
                        isInvalid={errors.description} onChange={handleChange} value={currentTodo.description} 
                        id="description" name="description" type="text" placeholder="A Description"/>
                    <Form.Control.Feedback type="invalid">{errors.description}</Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-5" as={Col} sm={6} >
                    <Form.Label>Date</Form.Label>
                    <Form.Control
                        isInvalid={errors.date} onChange={handleChange} 
                        value={initialDate} id="date" name="date" type="datetime-local"
                        min={minDate}/>
                    <Form.Control.Feedback type="invalid">{errors.date}</Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-5" as={Col} sm={6} >
                    <Form.Label>Reminder</Form.Label>
                    <Form.Control
                        isInvalid={errors.reminder} onChange={handleChange} value={initialReminder} 
                        id="reminder" name="reminder" type="datetime-local" 
                        min={format(new Date().getTime(), "yyyy-MM-dd'T'HH:mm")}
                        max={currentTodo.date && format(currentTodo.date, "yyyy-MM-dd'T'HH:mm")}
                        disabled={auth === null}
                    />
                    <Form.Control.Feedback type="invalid">{errors.reminder}</Form.Control.Feedback>
                    {auth === null &&
                        <span className="text-info small">
                            You must be logged in to use the reminder!
                        </span>
                    }   
                </Form.Group>
                <Col>   
                    <Button disabled={!isFormValid} type="submit">{isEdit ? 'Save Edit' : 'Add Todo'}</Button>
                </Col>
            </Row>
        </Form>
    )
}

TodoForm.propTypes = {
    todos: PropTypes.array,
    setTodos: PropTypes.func,
    todo: PropTypes.object,
    setOpen: PropTypes.func,
    setTodoEdited: PropTypes.func,
    isFirstTodo: PropTypes.bool,
    isEdit: PropTypes.bool
};

export default TodoForm;

