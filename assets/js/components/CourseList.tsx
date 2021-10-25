import React from "react";
import { ListGroup } from "react-bootstrap";
import CourseListItem from "./CourseListItem";

const CourseList = () => {
    return (
        <ListGroup as="ol" numbered>
            <CourseListItem />
        </ListGroup>
    )
}

export default CourseList;