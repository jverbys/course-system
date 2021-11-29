import React, {useState} from "react";
import Layout from "./components/Layout";
import {Button, Card, Form, Modal} from "react-bootstrap";
import CourseList from "./components/CourseList";
import DateTimeSelector from "./components/DateTimeSelector";

const App = () => {
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    return (
        <Layout>
            <Card>
                <Card.Header className="d-flex justify-content-between align-items-center">
                    Courses
                    <Button variant="primary" size="sm" onClick={handleShow}>
                        Create
                    </Button>
                </Card.Header>
                <Card.Body>
                    <CourseList />
                </Card.Body>
            </Card>

            <Modal show={show} onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>New course</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form>
                        <Form.Group className="mb-3">
                            <Form.Label>Name</Form.Label>
                            <Form.Control type="text" placeholder="Name" />
                        </Form.Group>

                        <Form.Group className="mb-3">
                            <Form.Label>Description</Form.Label>
                            <Form.Control type="text" placeholder="Description" />
                        </Form.Group>

                        <Form.Group className="mb-3">
                            <Form.Label>Start date</Form.Label>
                            <DateTimeSelector placeholder="Start date" />
                        </Form.Group>

                        <Form.Group>
                            <Form.Label>End date</Form.Label>
                            <DateTimeSelector placeholder="End date" />
                        </Form.Group>
                    </Form>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                    <Button variant="primary" onClick={handleClose}>
                        Save Changes
                    </Button>
                </Modal.Footer>
            </Modal>
        </Layout>
    )
};

export default App;