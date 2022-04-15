import React from "react";
import { Col, Container, Row, Image, Button } from "react-bootstrap";
import { Helmet } from 'react-helmet';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEnvelope, faMapMarker } from "@fortawesome/free-solid-svg-icons";
import img from '../assets/img';


function Resume() {
    return (
        <>
        <Helmet><title>Resume</title></Helmet>
        <div className="bg-light p-5">
            <Container>
                <Row className="d-flex align-items-center justify-content-around">
                    <Col md={3} className="h6 small fw-normal d-none d-md-block">
                        <ul className="list-unstyled text-start">
                            <li className="mb-2">
                                <FontAwesomeIcon className="me-2" icon={faEnvelope}/>
                                ujikboo@gmail.com
                            </li>
                            <li className="mb-2">
                                <FontAwesomeIcon className="me-2" icon={faMapMarker}/>
                                Indonesia
                            </li>
                            <li>
                                <img className="me-1" height={20} width={20} src={img.tech.github} alt="github"/>
                                <a target="_blank" rel="noopener noreferrer" href="https://github.com/ujikstark">Github</a>
                            </li>
                        </ul>
                    </Col>
                    <Col md={5} className="text-start">
                        <div className="h1 fw-normal">Ahmad Fauzi</div>
                        <div className="h4 fw-lighter">
                            I can't create a new technology but I have an idea to apply the current techologies in right place
                        </div>      
                        <div className="d-block d-md-none">
                            <img className="mr-1" height={20} width={20} src={img.tech.github} alt="github"/>
                            <a target="_blank" rel="noopener noreferrer" href="https://github.com/pbourdet">Github</a>
                        </div>
                    </Col>
                    <Col md={4} className="d-none d-md-block text-end">
                        <Image src={img.id} thumbnail roundedCircle height="200" width="200"/>
                    </Col>
                </Row>
            </Container>
        
        </div>
            

        <Container>
            <h3 className="text-start mt-5 mb-4">Projects</h3>
            <Row className="border p-5 d-flex align-items-center justify-content-around">
                <Col md={3}>
                <Image src={img.projects} width="100" height="100"></Image>
                </Col>
                <Col md={8} className="text-start">
                    <h4>Odinte - Digital Payment History</h4>
                    <p>Odinte will make it easier for you to process your payment history, you can also create your own payment history and give it to other people/consumers and get information about your expenses and income.</p>
                    <Button href="https://play.google.com/store/apps/details?id=com.ujik.Odinte&hl=en&gl=US" target="_blank" className="mt-3">Detail</Button>
                </Col>
            </Row>

            <Row>
                <Col className="text-start">
                    <h3 className="mt-5">Skills</h3>
                
                    <ul>
                        <li>PHP</li>
                        <li>Symfony</li>
                        <li>API Platform</li>
                        <li>Git</li>
                </ul>
               </Col>

               <Col className="text-start">
                    <h3 className="mt-5">Familiar</h3>
                
                    <ul>
                        <li>React</li>
                        <li>Flutter</li>
                    </ul>
               </Col>
            </Row>


               

        </Container>
        </>
    
        
    );

}

export default Resume;