import React, { useState } from "react";
import { Form, Col, Row, Button } from "react-bootstrap";

function TodoForm ({ todos, setTodos, todo }) {

    const [newTodo, setNewTodo] = useState({});
    
   
    const handleSubmit =  (e) => {
        e.preventDefault();
       
        setTodos([
            ...todos,
            newTodo
        ])

        console.log(newTodo);
    }

    const handleChange = e => {
        let { name, value } = e.target;
        setNewTodo({
            ...newTodo,
            [name]: value
        });
    }

    return (

        <Form onSubmit={handleSubmit}>
            <Row>
                <Form.Group className="mb-4" as={Col} md={6} >
                    <Form.Label>Task<span className="ml-1 text-danger">*</span></Form.Label>
                    <Form.Control
                        onChange={handleChange} value={newTodo.name} id="name" name="name" type="text" placeholder="A task" />
                    <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-4" as={Col} md={6} >
                    <Form.Label>Description</Form.Label>
                    <Form.Control
                        onChange={handleChange} value={newTodo.description} id="description" name="description" type="text" placeholder="A Description"/>
                    <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-5" as={Col} sm={6} >
                    <Form.Label>Date</Form.Label>
                    <Form.Control
                        onChange={handleChange} value={newTodo.date} id="date" name="date" type="datetime-local"/>
                    <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
                </Form.Group>
                <Form.Group className="mb-5" as={Col} sm={6} >
                    <Form.Label>Reminder</Form.Label>
                    <Form.Control
                        onChange={handleChange} value={newTodo.reminder} id="reminder" name="reminder" type="datetime-local"/>
                    <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
                </Form.Group>
                <Col>   
                    <Button type="submit">Add Todo</Button>
                </Col>
            </Row>
        </Form>
    )
}

export default TodoForm;