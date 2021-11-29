import React, {useEffect, useState} from "react";
import {useParams} from "react-router-dom";
import axios from "axios";
import {Button, Card, ListGroup} from "react-bootstrap";
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

    return (
        <Card>
            <Card.Header className="d-flex justify-content-between align-items-center">
                {course?.name}
                <Button variant="success" size="sm">
                    Enroll
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