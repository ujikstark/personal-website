import React, { useEffect, useState } from "react";
import { Col, Nav, Row, Tab } from "react-bootstrap";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import { createMercureEventSource, getConversations } from "../../requests/messaging";
import ConversationTab from "./ConversationTab";
import PropTypes from 'prop-types';
import ConversationDisplay from "./ConversationDisplay";
import CreateConversationModal from "./CreateConversationalModal";
import Loader from "../Loader";


function ConversationTabs () {
    const user = JSON.parse(localStorage.getItem('user'));
    const auth = useAuth();
    const updateAuth = useAuthUpdate();
    const [conversations, setConversations] = useState([]);
    const [activeKey, setActiveKey] = useState('');
    const [showMessages, setShowMessages] = useState(false);
    const [isMobile] = useState(Number(window.innerWidth) < 768);
    const [eventSource, setEventSource] = useState(null);
    const [anotherEventSource, setAnotherEventSource] = useState(null);

    const [tempConversation, setTempConversation] = useState([]);
    const [newMessages, setNewMessages] = useState({});
    const [loading, setLoading] = useState(true);
    let conversationsLocal = JSON.parse(localStorage.getItem('conversations'));

    useEffect(() => {
        const newEventSource = eventSource ?? createMercureEventSource('http://localhost:8000/api/conversations');
        newEventSource.onmessage = function (event) {
            const newConversation = JSON.parse(event.data);
            console.log(newConversation);
            let isCurrentConversation = false;
                if (conversationsLocal.hasOwnProperty(newConversation.id)) 
                    isCurrentConversation = true;
              
            
            if (isCurrentConversation) {
                const newConversations = conversations.map(conversation =>
                    conversation.id === newConversation.id
                    ? newConversation
                    : conversation);
                    
                    newConversations.sort(function (c1, c2) {
                        if (c1.lastMessage === null) return 1;
                        if (c2.lastMessage === null) return -1;
                        
                        return c2.lastMessage.date - c1.lastMessage.date;
                    });                    
                    setConversations(newConversations);
                
            } else {
                
                setConversations([...conversations, newConversation]);
            }
            
        };

        const newAnotherEventSource = anotherEventSource ?? createMercureEventSource('http://localhost:8000/api/conversations/{id}');

        newAnotherEventSource.onmessage = function (event) {
            
            const message = JSON.parse(event.data);
            console.log(message);
            let isRightConversation = false;
            for (let i = 0; i < conversations.length; i++) {
                if (conversations[i].id === message.conversation.id) {
                    isRightConversation = true;
                }
            }
            console.log(isRightConversation);

            if (isRightConversation) {
                
                    conversationsLocal = JSON.parse(localStorage.getItem('conversations'));
                    conversationsLocal[message.conversation.id] = [];
                    conversationsLocal[message.conversation.id] = message;
                    console.log(conversationsLocal[message.conversation.id]);

                    setNewMessages(conversationsLocal);
                    
                    // for testing new notif purpose
                    // console.log(conversationsLocal);
                    // conversationsLocal[message.conversation.id].messages.push(message);
                    // let setConversation = conversationsLocal;
                    // setConversation[message.conversation.id].messages = [];
                    // setConversation[message.conversation.id].count += 1;                    
                    // localStorage.setItem('conversations', JSON.stringify(conversationsLocal));

            } 


        };

        
        
        setEventSource(newEventSource);
        setAnotherEventSource(newAnotherEventSource);

    }, [conversations]);


    

    useEffect(() => {
        (async () => {
            const currentConversations = await getConversations(auth, updateAuth);
            currentConversations.sort(function (c1, c2) {
                if (c1.lastMessage === null) return 1;
                if (c2.lastMessage === null) return -1;
                
                const date1 = new Date(c1.lastMessage.date).getTime();
                const date2 = new Date(c2.lastMessage.date).getTime();
                
                return date2 - date1;
            });

            let newM = {};

            for (let i = 0; i < currentConversations.length; i++) {
                newM[currentConversations[i].id] = [];

            }

            localStorage.setItem('conversations', JSON.stringify(newM));
            
            

            setConversations(currentConversations);
            setNewMessages(newM);

            setLoading(false);  
        })();
             
        return () => setLoading(false);


    }, [auth, updateAuth]);


    if (loading) {
        return <Loader/>
    }

    if (conversations.length === 0) {
        return <div className="border-bottom p-2">
            <div>No conversation</div>
            <CreateConversationModal user={user} tempConversation={tempConversation} setTempConversation={setTempConversation} setShowMessages={setShowMessages} setActiveKey={setActiveKey} conversations={conversations} setConversations={setConversations}/>
            <Row className="m-0">
                <Col className="p-0"><hr/></Col>
                <Col className="col-auto">
                    OR
                </Col>
                <Col className="p-0"><hr/></Col>
            </Row>
        </div>;
    }


    return (
        <>
            <Tab.Container activeKey={activeKey} onSelect={(k) => setActiveKey(k)} mountOnEnter>
                <Row className="m-0 h-100">
                    <Col style={{ overflow: 'scroll'}} className={`p-0 h-100 border-end ${(!isMobile || (isMobile && !showMessages)) ? '' : 'd-none'}`} md={4}>
                        <div style={{ backgroundColor: 'azure' }} className="border-bottom pt-2 pb-2">
                            <CreateConversationModal user={user} tempConversation={tempConversation} setTempConversation={setTempConversation} setShowMessages={setShowMessages} setActiveKey={setActiveKey} conversations={conversations} setConversations={setConversations}/>
                            <Row className="m-0">
                                <Col className="p-0"><hr/></Col>
                                <Col className="col-auto">
                                    OR
                                </Col>
                                <Col className="p-0"><hr/></Col>
                            </Row>
                        </div>
                        {!loading &&
                            <Nav onClick={() => setShowMessages(true)} variant="pills" className="flex-column flex-nowrap">
                            {conversations.map((conversation) => (
                                    <ConversationTab key={conversation.id} user={user} conversation={conversation} tempConversation={tempConversation} activeKey={activeKey}/>
                                )
                                )}
                            </Nav>
                        }
                        
                    </Col>
                    <Col style={{ overflow: 'scroll'}} className={`p-0 h-100 ${(!isMobile || (isMobile && showMessages)) ? '' : 'd-none'}`} md={8}>
                        <div className="h-100" >
                        {conversations.map((conversation) => (
                            <Tab.Content key={conversation.id}>
                                <Tab.Pane eventKey={conversation.id}>
                                    <ConversationDisplay newMessages={newMessages} setNewMessages={setNewMessages} setTempConversation={setTempConversation} tempConversation={tempConversation} conversations={conversations} setConversations={setConversations} setShowMessages={setShowMessages} conversation={conversation} user={user}/>
                                </Tab.Pane>
                            </Tab.Content>
                        ))}
                        </div>

                    </Col>
                </Row>
            </Tab.Container>
        </>
    );
}

ConversationTabs.propTypes = {
    user: PropTypes.shape({
        id: PropTypes.string,
        createdAt: PropTypes.string,
        email: PropTypes.string,
        name: PropTypes.string
    })
};

export default ConversationTabs;