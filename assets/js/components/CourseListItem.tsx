import React from "react";
import {Button, Col, ListGroup, Row} from "react-bootstrap";
import {ICourseIndex} from "./CourseList";
import {Link} from "react-router-dom";

type Props = {
    course: ICourseIndex,
    changeEnrollmentStatus: (courseId: number) => void,
}

const CourseListItem = ({ course, changeEnrollmentStatus }: Props) => {
    return (
        <ListGroup.Item
            as="li"
        >
            <Row>
                <Col md="4">
                    <div className="fw-bold">{course.name}</div>
                    {course.description}
                </Col>
                <Col md="4">
                    <div>
                        <b>Starts:</b> {course.startDate}
                    </div>
                    <div>
                        <b>Ends: </b> {course.endDate}
                    </div>
                </Col>
                <Col md="4" className="d-flex align-items-center">
                    <Link to={`/courses/${course.id}`}>
                        <Button variant="primary" size="sm">
                            View
                        </Button>
                    </Link>
                    <Button
                        variant={course.userIsEnrolled ? 'danger' : 'success'}
                        style={{ marginLeft: '10px' }}
                        size="sm"
                        onClick={() => changeEnrollmentStatus(course.id)}
                    >
                        {course.userIsEnrolled ? 'Unroll' : 'Enroll'}
                    </Button>
                </Col>
            </Row>
        </ListGroup.Item>
    )
}

export default CourseListItem;