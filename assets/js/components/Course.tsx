import React, {useEffect, useState} from "react";
import {useParams} from "react-router-dom";
import axios from "axios";
import {Button, Card} from "react-bootstrap";
import Folder from "./Folder";

const client = axios.create({
    baseURL: '/api'
});

interface ICourse {
    id: number,
    name: string,
    description: string,
    startDate: string,
    endDate: string,
    folders: IFolder[],
    userIsOwner: boolean,
    userIsModerator: boolean,
    userIsEnrolled: boolean,
    userCanEnroll: boolean,
}

export interface IFolder {
    id: number,
    name: string,
    files: IFile[],
    subFolders: IFolder[],
}

export interface IFile {
    id: number,
    name: string,
}

const Course = () => {
    const { courseId } = useParams();
    const [course, setCourse] = useState<ICourse>();

    useEffect(() => {
        const getCourse = async () => {
            return await client.get(`/courses/${courseId}`);
        }

        getCourse().then(response => {
            setCourse(response.data);
        });
    }, []);

    const changeEnrollmentStatus = () => {
        let url = `/courses/${course?.id}/enroll`;
        if (course?.userIsEnrolled) {
            url = `/courses/${course?.id}/unroll`;
        }

        let existingCourse = {
            ...course
        };

        client.post(url)
            .then(() => {
                existingCourse.userIsEnrolled = !existingCourse.userIsEnrolled;
                // @ts-ignore
                setCourse(existingCourse);
            });
    }

    return (
        <Card>
            <Card.Header className="d-flex justify-content-between align-items-center">
                {course?.name}
                <Button
                    variant={course?.userIsEnrolled ? 'danger' : 'success'}
                    style={{ marginLeft: '10px' }}
                    size="sm"
                    onClick={() => changeEnrollmentStatus()}
                >
                    {course?.userIsEnrolled ? 'Unroll' : 'Enroll'}
                </Button>
            </Card.Header>
            <Card.Body>
                <Card>
                    <Card.Body>
                        <div>{course?.description}</div>
                        <div>Starts: {course?.startDate}</div>
                        <div>Ends: {course?.endDate}</div>
                    </Card.Body>
                </Card>
                <Card style={{ marginTop: '16px'}}>
                    <Card.Body>
                        {course?.folders.map(folder => {
                            return (
                                <Folder key={folder.id} folder={folder} />
                            )
                        })}
                    </Card.Body>
                </Card>
            </Card.Body>
        </Card>
    )
}

export default Course;