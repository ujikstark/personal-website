import React from "react";
import { Container } from "react-bootstrap";
import { Helmet } from 'react-helmet';


function Todo () {
    return (
        <Container>
            <Helmet><title>Todo list</title></Helmet>
            <h1>Todo</h1>

        </Container>
    );
}

export default Todo;