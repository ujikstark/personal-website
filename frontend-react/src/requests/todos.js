import axios from "../config/axios";
import refreshToken from "./refreshToken";

export function getTodos () {
    const todos = JSON.parse(localStorage.getItem('todos')) ?? [];
    todos.sort((td1, td2) => td1.date -td2.date);

    return todos;
}

export async function createTodo(todo, todos, auth, updateAuth) {
    
    if (auth == null) {
        todo.id = Math.floor(Math.random() * Math.pow(10, 7));
        todos.push(todo);

        return updateLocalTodos(todos);
    }
    
    await refreshToken(auth, updateAuth);

    const date = todo.date ? todo.date : null;
    const reminder = todo.reminder ? todo.reminder : null;

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

    return updateLocalTodos([...todos, todo]);
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



