import { Button, Col } from "react-bootstrap";
import { editTodo } from "../../requests/todos";

export default function DoneButton ({ todo, todos, setTodos }) {
    
    const handleEdit = (todo) => {
        todo.isDone = !todo.isDone;

        const newTodos = editTodo(todo, todos);
        
        if (newTodos === todos) {
            return;
        }

        setTodos(newTodos);
    }

    return (
        <Col className="d-flex justify-content-center align-items-center pr-1 pl-1" xs={1} sm={1}>
            <Button className="rounded-circle p-2" size="lg" variant={todo.isDone === true ? 'dark' : 'primary'} onClick={() => handleEdit(todo)}>
                
            </Button>
        </Col>
    );
}