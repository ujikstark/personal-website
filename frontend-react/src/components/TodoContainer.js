import React, { useEffect, useState } from "react";
import { Alert, Container } from "react-bootstrap";
import { useAuth, useAuthUpdate } from "../contexts/AuthContext";
import { getTodos } from "../requests/todos";
import TodoTable from "./TodoTable";
import { Spinner } from 'react-bootstrap';


function TodoContainer() {

    const [loading, setLoading] = useState(true);
    const [todos, setTodos] = useState([]);
    const [showAlert, setShowAlert] = useState(true);
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    useEffect(() => {
        (async () => {
            const currentTodos = await getTodos(auth, updateAuth);
            setLoading(false);
            setTodos(currentTodos);
        })();

        return () => setLoading(false);
    }, [auth, updateAuth]);


    return (
        <Container className="shadow border rounded p-5">
            {loading
                ? <div className="m-5">
                    <Spinner animation="grow" variant="success"/>
                    <Spinner animation="grow" variant="danger"/>
                    <Spinner animation="grow" variant="warning"/>                </div>
                : <>
                    {(auth === null && showAlert) &&
                    <Alert className="mb-3" variant="warning" onClose={() => setShowAlert(false)} dismissible>
                        Your todos will only be saved on this device. Log in to access your todos anywhere !
                    </Alert>
                    }
                    <TodoTable todos={todos} setTodos={setTodos}/>
                    </>
            }    
        </Container>
    );
}

export default TodoContainer;