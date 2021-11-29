import React, {useState} from "react";
import {Button, ListGroup} from "react-bootstrap";
import {ICourseIndex} from "./CourseList";
import {Link} from "react-router-dom";

type Props = {
    course: ICourseIndex,
}

const CourseListItem = ({ course }: Props) => {
    const [userIsEnrolled, setUserIsEnrolled] = useState(course.userIsEnrolled);

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
                <Link to={`/courses/${course.id}`}>
                    <Button variant="primary" size="sm">
                        View
                    </Button>
                </Link>
                <Button variant={userIsEnrolled ? 'danger' : 'success'} style={{ marginLeft: '10px' }} size="sm">
                    {userIsEnrolled ? 'Unroll' : 'Enroll'}
                </Button>
            </div>
        </ListGroup.Item>
    )
}

export default CourseListItem;