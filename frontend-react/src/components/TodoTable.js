import React from "react"
import { Button, Collapse, Row } from "react-bootstrap";
import TodoForm from "./TodoForm";

function TodoTable({ todos, setTodos }) {

    if (!Object.keys(todos).length) {
        return <TodoForm todos={todos} setTodos={setTodos} todo={{}}/>
    }

    return (
        <>
            <TodoForm todos={todos} setTodos={setTodos} todo={{}}/>
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