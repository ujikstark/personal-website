import React from 'react';
import { Col } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCalendarAlt } from '@fortawesome/free-regular-svg-icons';
import { faBell } from '@fortawesome/free-solid-svg-icons';

function TodoDetails ({ todo }) {
    return (
        <Col className="d-flex flex-column align-items-start" xs={8} sm={9}>
            <div>
                <div className="h5 text-left font-weight-normal mb-0">
                    {todo.isDone
                        ? <del>{todo.name}</del>
                        : <span>{todo.name}</span>
                    }
                </div>
            </div>
            {todo.description &&
                <div>{todo.description}</div>
            }
            {todo.date &&
                <div>
                    <small className="text-success">
                        <FontAwesomeIcon className="me-1" icon={faCalendarAlt}/>
                        {todo.date}
                    </small>
                </div>
            }
            {todo.reminder &&
                <div>
                    <small className="text-info">
                        <FontAwesomeIcon className="me-1" icon={faBell}/>
                        {todo.reminder}
                    </small>
                </div>
            }
        </Col>
    );
}

export default TodoDetails;
