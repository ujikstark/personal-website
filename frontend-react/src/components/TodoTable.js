import React, { useState } from "react"
import { Button, Col, Collapse, Row } from "react-bootstrap";
import TodoForm from "./TodoForm";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPen, faPlus, faTimes, faTrash } from '@fortawesome/free-solid-svg-icons';
import TodoDetails from "./TodoDetails";
import DeleteButton from "./DeleteButton";

function TodoTable({ todos, setTodos }) {
    const [open, setOpen] = useState(false);


    if (!Object.keys(todos).length) {
        return <TodoForm todos={todos} setTodos={setTodos} todo={{}}/>
    }


    return (
        <>
            <Row className="p-2 mb-2">
                <Button variant={open ? 'danger' : 'primary'} onClick={() => setOpen(!open)} aria-controls="create-todo-form" aria-expanded={open}>
                    <FontAwesomeIcon className="me-2" icon={open ? faTimes : faPlus}/>
                    {open ? 'Cancel' : 'Add More Todo'}
                </Button>
            </Row>
            <Collapse in={open}>
                <div id="create-todo-form">
                    <TodoForm todos={todos} setTodos={setTodos} setOpen={setOpen} todo={{}} isFirstTodo={false} isEdit={false}/>
                </div>
            </Collapse>
            <div className="border-top mt-3">
                {todos.map((todo, index) => (
                    <div className="pt-2 pb-2 border-bottom" key={index}>
                        <Row>
                            <Col className="d-flex justify-content-center align-items-center pr-1 pl-1" xs={1} sm={1}>
                                <Button className="rounded-circle p-2" size="lg" variant={todo.isDone === true ? 'dark' : 'primary'}>
                                    
                                </Button>
                            </Col>
                            <TodoDetails todo={todo}/>
                            <Col className="d-flex justify-content-center align-items-center pl-0 pr-1" xs={3} sm={2}>
                                <div className="me-1">
                                    <Button className="rounded-circle" size="sm">
                                        <FontAwesomeIcon icon={faPen}/>
                                    </Button>
                                    <DeleteButton todo={todo} todos={todos} setTodos={setTodos}/>
                                </div>
                            </Col>
                        </Row>
                    </div>
                ))}
            </div>
            
        </>
    );

    

}   

export default TodoTable;