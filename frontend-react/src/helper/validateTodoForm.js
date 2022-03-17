import { format } from "date-fns";

export default function validateTodoForm (todo) {
    const errors = {};

    if (todo.name.length > 50) {
        errors.name = 'The task must contains less than 50 characters.';
    }

    if (todo.id === undefined && todo.date) {
        if (new Date(todo.date).getTime() < new Date().getTime()) {
            errors.date = 'The date cannot be in the past.';
        }
    }

    if (todo.date && todo.reminder) {
        console.log(format(todo.date, "yyyy - MM - dd HH:mm a"));
        console.log(format(todo.reminder, "yyyy - MM - dd HH:mm a"));

        if (new Date(todo.date).getTime() <= new Date(todo.reminder).getTime()) {
            errors.reminder = 'The reminder must be before the date.';
        }

        if (new Date() > new Date(todo.reminder).getTime()) {
            errors.reminder = 'The reminder cannot be in the past.';
        }

   }

   return errors;

}

