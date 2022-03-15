import React from "react";
import { Form, Col, Row, Button } from "react-bootstrap";

function TodoForm () {
    return (
        <Form as={Row}>
            <Form.Group className="mb-4" as={Col} md={6} >
                <Form.Label>Task<span className="ml-1 text-danger">*</span></Form.Label>
                <Form.Control
                    id="name" name="name" type="text" placeholder="A task"/>
                <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
            </Form.Group>
            <Form.Group className="mb-4" as={Col} md={6} >
                <Form.Label>Description</Form.Label>
                <Form.Control
                    id="description" name="description" type="text" placeholder="A Description"/>
                <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
            </Form.Group>
            <Form.Group className="mb-5" as={Col} sm={6} >
                <Form.Label>Date</Form.Label>
                <Form.Control
                    id="date" name="date" type="datetime-local"/>
                <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
            </Form.Group>
            <Form.Group className="mb-5" as={Col} sm={6} >
                <Form.Label>Reminder</Form.Label>
                <Form.Control
                    id="reminder" name="reminder" type="datetime-local"/>
                <Form.Control.Feedback type="invalid"></Form.Control.Feedback>
            </Form.Group>
            <Col>
                <Button>Add Todo</Button>
            </Col>
        </Form>
    )
}

export default TodoForm;