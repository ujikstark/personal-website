import React from 'react';
import { Helmet } from 'react-helmet';
import MessagingContainer from '../components/Messaging/MessagingContainer';

function Messaging () {
    return <>
        <Helmet><title>Messaging</title></Helmet>
        <MessagingContainer/>
    </>
            
}

export default Messaging;
