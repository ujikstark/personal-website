import React from "react";
import { Form, Col, Row, Button } from "react-bootstrap";
import useTodoForm from "../hooks/useTodoForm";

function TodoForm ({ todos, setTodos, todo, setOpen }) {

    const { currentTodo, errors, handleChange, clearAll } = useTodoForm(todo);
    
    const isTouched = currentTodo !== todo;
    const isFormValid = isTouched && currentTodo.name !== '' && Object.keys(errors).length === 0;

   
    const handleSubmit =  (e) => {
        e.preventDefault();
       
        setTodos([
            ...todos,
            currentTodo
        ])

        setOpen(false);

        clearAll();
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
                        isInvalid={errors.date} onChange={handleChange} value={currentTodo.date} 
                        id="date" name="date" type="datetime-local"/>
                    <Form.Control.Feedback type="invalid">{errors.date}</Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-5" as={Col} sm={6} >
                    <Form.Label>Reminder</Form.Label>
                    <Form.Control
                        isInvalid={errors.reminder} onChange={handleChange} value={currentTodo.reminder} 
                        id="reminder" name="reminder" type="datetime-local"/>
                    <Form.Control.Feedback type="invalid">{errors.reminder}</Form.Control.Feedback>
                </Form.Group>
                <Col>   
                    <Button disabled={!isFormValid} type="submit">Add Todo</Button>
                </Col>
            </Row>
        </Form>
    )
}

export default TodoForm;