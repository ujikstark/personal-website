import React, { useState } from "react"
import { Button, Collapse, Row } from "react-bootstrap";
import TodoForm from "./TodoForm";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPen, faPlus, faTimes } from '@fortawesome/free-solid-svg-icons';


function TodoTable({ todos, setTodos }) {
    const [open, setOpen] = useState(false);


    if (!Object.keys(todos).length) {
        return <TodoForm todos={todos} setTodos={setTodos} todo={{}}/>
    }

    return (
        <>
            <Row className="justify-content-end p-2">
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
                    <div className="pt-2 pb-2 border-bottom text-left" key={index}>
                        <h5>{todo.name}</h5>
                        
                    </div>
                ))}
            </div>
            
        </>
    );

    

}   

export default TodoTable;