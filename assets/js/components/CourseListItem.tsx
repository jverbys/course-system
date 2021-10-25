import React from "react";
import {Button, Col, ListGroup, Row} from "react-bootstrap";

const CourseListItem = () => {
    return (
        <ListGroup.Item
            as="li"
            className="d-flex justify-content-between align-items-center flex-wrap"
        >
            <div className="ms-2 me-auto">
                <div className="fw-bold">Course name</div>
                Course description
            </div>
            <div className="ms-2 me-auto">
                <div>
                    <b>Starts:</b> 1970-01-01
                </div>
                <div>
                    <b>Ends: </b> 1970-01-01
                </div>
            </div>
            <div className="ms-2 me-auto">
                <Button variant="primary" size="sm">
                    View
                </Button>
                <Button variant="success" size="sm">
                    Enroll
                </Button>
                <Button variant="danger" size="sm">
                    Unroll
                </Button>
            </div>
        </ListGroup.Item>
    )
}

export default CourseListItem;