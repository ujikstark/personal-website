import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useState } from "react";
import { Button, Modal } from "react-bootstrap";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import { deleteTodos } from "../../requests/todos";

export default function DeleteButton ({ todo, todos, setTodos }) {
    const [show, setShow] = useState(false);
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const handleDelete = async (todo) => {
        const newTodos = await deleteTodos(todo, todos, auth, updateAuth);
        setTodos(newTodos);

        handleClose();
    }

    return (     
        <>
        <Button onClick={handleShow} className="rounded-circle m-2" size="sm" variant="danger"><FontAwesomeIcon icon={faTrash}/></Button>
        
        <Modal show={show} onHide={handleClose}>
            <Modal.Header closeButton>
            <Modal.Title>Delete a Todo</Modal.Title>
            </Modal.Header>
            <Modal.Body>Are you sure delete this <b>{todo.name}?</b></Modal.Body>
            <Modal.Footer>
            <Button variant="danger" onClick={() => handleDelete(todo)}>
                Delete
            </Button>
            </Modal.Footer>
        </Modal>
        </>   
       
    );
}