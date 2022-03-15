import React from "react"
import TodoForm from "./TodoForm";

function TodoTable({ todos, setTodos }) {

    if (!Object.keys(todos).length) {
        return <TodoForm todos={todos} setTodos={setTodos} todo={{}}/>
    }

    return <h1>wow ada 1</h1>

    

}   

export default TodoTable;