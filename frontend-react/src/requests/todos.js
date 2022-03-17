import { format, getTime, toDate } from "date-fns";
import axios from "../config/axios";
import refreshToken from "./refreshToken";

export async function getTodos (auth, updateAuth) {
    if (auth === null) {

        const todos = JSON.parse(localStorage.getItem('todos')) ?? [];
        return updateLocalTodos(todos);
    }

    await refreshToken(auth, updateAuth);
    const todos = await axios.get('/api/todos')
        .then(response => response.data)
        .catch(() => []);
    

    return updateLocalTodos(todos.map(function (todo) {
        todo.date = todo.date && new Date(todo.date).getTime();
        todo.reminder = todo.reminder && new Date(todo.reminder).getTime();
        
        return todo;
    }), auth);

}

export async function createTodo(todo, todos, auth, updateAuth) {
    
    if (auth == null) {
        todo.id = Math.floor(Math.random() * Math.pow(10, 7));
        todos.push(todo);

        return updateLocalTodos(todos);
    }
    
    await refreshToken(auth, updateAuth);

    const date = todo.date ? format(todo.date, 'yyyy-MM-dd hh:mm:ss a') : null;
    const reminder = todo.reminder ? format(todo.reminder, 'yyyy-MM-dd hh:mm:ss a') : null;

    const payload = {
        name: todo.name,
        description: todo.description,
        date: date,
        reminder: reminder,
        isDone: todo.isDone
    };
    const newTodo = await axios.post('/api/todos', JSON.stringify(payload))
        .then(response => response.data)
        .catch(() => null);
    
    if (newTodo === null) return todos;
    
    todo.id = newTodo.id;

    return updateLocalTodos([...todos, todo], auth);
}

export function deleteTodos(todo, todos) {
    const newTodos = todos.filter((td) => td.id !== todo.id);

    return updateLocalTodos(newTodos);
}

export function editTodo(editedTodo, todos) {
    const newTodos = todos.map(todo =>
        todo.id === editedTodo.id ? editedTodo : todo
    );
  
    return updateLocalTodos(newTodos);
}

function updateLocalTodos (todos, auth) {

    todos.sort((td1, td2) => td1.date - td2.date);

    if (auth == null) {
        localStorage.setItem('todos', JSON.stringify(todos));
    }

    return todos;
}   



