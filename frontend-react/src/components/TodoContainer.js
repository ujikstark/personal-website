import React, { useState } from "react";
import { Alert, Container } from "react-bootstrap";
import TodoTable from "./TodoTable";

function TodoContainer() {

    const [todos, setTodos] = useState([]);


    return (
        <Container className="shadow border rounded p-5">
            <Alert className="mb-5" variant="warning" dismissible>
                Your todos will only be saved on this device. Log in to access your todos anywhere !
            </Alert>
            <TodoTable todos={todos} setTodos={setTodos}/>
        </Container>
    );
}

export default TodoContainer;