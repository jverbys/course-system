import React, {ChangeEvent, useState} from "react";
import Layout from "./components/Layout";
import {Button, Card, Form, Modal} from "react-bootstrap";
import CourseList from "./components/CourseList";
import DateTimeSelector from "./components/DateTimeSelector";

const App = () => {
    const [show, setShow] = useState(false);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');

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
                            <Form.Control
                                type="text"
                                placeholder="Name"
                                onChange={(e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) =>
                                    setName(e.target.value)}
                            />
                        </Form.Group>

                        <Form.Group className="mb-3">
                            <Form.Label>Description</Form.Label>
                            <Form.Control
                                type="text"
                                placeholder="Description"
                                onChange={(e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) =>
                                    setDescription(e.target.value)}
                            />
                        </Form.Group>

                        <Form.Group className="mb-3">
                            <Form.Label>Start date</Form.Label>
                            <DateTimeSelector setDate={setStartDate} placeholder="Start date" />
                        </Form.Group>

                        <Form.Group>
                            <Form.Label>End date</Form.Label>
                            <DateTimeSelector setDate={setEndDate} placeholder="End date" />
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