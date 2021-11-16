import React from "react";
import {Button, ListGroup} from "react-bootstrap";
import {ICourse} from "./CourseList";

type Props = {
    course: ICourse,
}

const CourseListItem = ({ course }: Props) => {
    return (
        <ListGroup.Item
            as="li"
            className="d-flex justify-content-between align-items-center flex-wrap"
        >
            <div className="ms-2 me-auto">
                <div className="fw-bold">{course.name}</div>
                {course.description}
            </div>
            <div className="ms-2 me-auto">
                <div>
                    <b>Starts:</b> {course.startDate}
                </div>
                <div>
                    <b>Ends: </b> {course.endDate}
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