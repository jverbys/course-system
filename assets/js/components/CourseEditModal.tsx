import React from "react";
import {ICourse} from "./Course";
import {Button, Form, Modal} from "react-bootstrap";
import DateTimeSelector from "./DateTimeSelector";

type Props = {
    show: boolean,
    course?: ICourse,
    updateCourseProp: (prop: string, value: string) => void,
    updateCourse: () => void,
    closeModal: () => void,
}

const CourseEditModal = ({ show, course, updateCourseProp, updateCourse, closeModal }: Props) => {
    return (
        <Modal show={show} onHide={closeModal}>
            <Modal.Header closeButton>
                <Modal.Title>Edit course</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form>
                    <Form.Group className="mb-3">
                        <Form.Label>Name</Form.Label>
                        <Form.Control
                            type="text"
                            placeholder="Name"
                            onChange={(e: any) => updateCourseProp('name', e.target.value)}
                            value={course?.name}
                        />
                    </Form.Group>
                    <Form.Group className="mb-3">
                        <Form.Label>Description</Form.Label>
                        <Form.Control
                            type="text"
                            placeholder="Description"
                            onChange={(e: any) => updateCourseProp('description', e.target.value)}
                            value={course?.description}
                        />
                    </Form.Group>
                    <Form.Group className="mb-3">
                        <Form.Label>Start date</Form.Label>
                        <DateTimeSelector
                            setDate={(date: string) => updateCourseProp('startDate', date)}
                            value={course?.startDate}
                            placeholder="Start date"
                        />
                    </Form.Group>
                    <Form.Group className="mb-3">
                        <Form.Label>End date</Form.Label>
                        <DateTimeSelector
                            setDate={(date: string) => updateCourseProp('endDate', date)}
                            value={course?.endDate}
                            placeholder="Start date"
                        />
                    </Form.Group>
                </Form>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={() => closeModal()}>
                    Close
                </Button>
                <Button
                    variant="primary"
                    disabled={
                        course?.name === ''
                        || course?.description === ''
                        || course?.startDate === ''
                        || course?.endDate === ''
                    }
                    onClick={() => updateCourse()}
                >
                    Save Changes
                </Button>
            </Modal.Footer>
        </Modal>
    )
}

export default CourseEditModal;
