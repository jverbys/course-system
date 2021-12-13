import React, {useEffect, useState} from "react";
import {ListGroup} from "react-bootstrap";
import CourseListItem from "./CourseListItem";
import axios from "axios";

const client = axios.create({
    baseURL: '/api'
});

export interface ICourseIndex {
    id: number,
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

    const changeEnrollmentStatus = (id: number) => {
        const course = courses.find(course => course.id === id);

        let url = `/courses/${course?.id}/enroll`;
        if (course?.userIsEnrolled) {
            url = `/courses/${course?.id}/unroll`;
        }

        const existingCourses = [...courses];
        client.post(url)
            .then(() => {
                existingCourses.forEach(existingCourse => {
                    if (existingCourse.id === course?.id) {
                        existingCourse.userIsEnrolled = !existingCourse.userIsEnrolled;
                    }
                })
                setCourses(existingCourses);
            });
    }

    return (
        <ListGroup as="ul">
            {courses.map(course => {
                return (
                    <CourseListItem key={course.id} course={course} changeEnrollmentStatus={changeEnrollmentStatus} />
                )
            })}
        </ListGroup>
    )
}

export default CourseList;