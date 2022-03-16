export function getTodos () {
    const todos = JSON.parse(localStorage.getItem('todos')) ?? [];
    todos.sort((td1, td2) => td1.date -td2.date);

    return todos;
}

export function createTodo(todo, todos) {
    todo.id = Math.floor(Math.random() * Math.pow(10, 7));
    
    // this is not best implementation
    // will do insertion sort later
    todos.push(todo);
    todos.sort((td1, td2) => td1.date -td2.date);

    return updateLocalTodos(todos);
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



