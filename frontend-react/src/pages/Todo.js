import React from "react";
import { Helmet } from 'react-helmet';
import TodoContainer from "../components/TodoContainer";


function Todo () {
    return <>
        <Helmet><title>Todo list</title></Helmet>
        <h1 className="mt-4 mb-4">Todo list</h1>
        <TodoContainer/>
    </>
       
}

export default Todo;