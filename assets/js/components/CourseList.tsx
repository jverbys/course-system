import React, {useEffect, useState} from "react";
import {ListGroup} from "react-bootstrap";
import CourseListItem from "./CourseListItem";
import axios from "axios";

const client = axios.create({
    baseURL: '/api'
});

export interface ICourseIndex {
    id: string,
    name: string,
    description: string,
    startDate: string,
    endDate: string,
    userIsEnrolled: boolean,
}

const CourseList = () => {
    const [courses, setCourses] = useState<ICourseIndex[]>([]);

    useEffect(() => {
        const getCourses = async () => {
            return await client.get('/courses');
        }

        getCourses().then(response => {
            setCourses(response.data);
        });
    }, []);

    return (
        <ListGroup as="ol" numbered>
            {courses.map(course => {
                return (
                    <CourseListItem key={course.id} course={course} />
                )
            })}
        </ListGroup>
    )
}

export default CourseList;