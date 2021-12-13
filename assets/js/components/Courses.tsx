import React, {ChangeEvent, useState} from "react";
import {Button, Card, Form, Modal} from "react-bootstrap";
import CourseList from "./CourseList";
import DateTimeSelector from "./DateTimeSelector";
import client from "../Client";

const Courses = () => {
    const [show, setShow] = useState(false);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');
    const [keyForRender, setKeyForRender] = useState(0);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    const createCourse = async () => {
        client.post('/courses', {
            name: name,
            description: description,
            startDate: startDate,
            endDate: endDate,
        }).then(() => {
            handleClose();
            setKeyForRender(keyForRender + 1);
        });
    }

    return (
        <>
            <Card>
                <Card.Header className="d-flex justify-content-between align-items-center">
                    Courses
                    <Button variant="primary" size="sm" onClick={handleShow}>
                        Create
                    </Button>
                </Card.Header>
                <Card.Body>
                    <CourseList key={keyForRender}/>
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
                                as="textarea"
                                rows={4}
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
                    <Button
                        variant="primary"
                        onClick={createCourse}
                        disabled={name === '' || description === '' || startDate === '' || endDate === ''}>
                        Save Changes
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export default Courses;