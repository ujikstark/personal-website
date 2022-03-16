export function getTodos () {
    return JSON.parse(localStorage.getItem('todos')) ?? [];
}

export function createTodo(todo, todos) {
    todo.id = Math.floor(Math.random() * Math.pow(10, 7));

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

function updateLocalTodos (todos) {
    localStorage.setItem('todos', JSON.stringify(todos));

    return todos;
}



