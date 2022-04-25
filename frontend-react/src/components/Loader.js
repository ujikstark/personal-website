import React from 'react';
import { Spinner } from 'react-bootstrap';

function Loader () {
    return (
        <div className="m-5">
            <Spinner animation="grow" variant="success"/>
            <Spinner animation="grow" variant="danger"/>
            <Spinner animation="grow" variant="warning"/>
        </div>
    );
}

export default Loader;
