import React, {useEffect, useState} from "react";
import {useParams} from "react-router-dom";
import {Button, Card} from "react-bootstrap";
import Folder from "./Folder";
import FolderModal from "./FolderModal";
import client from "../Client";

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
    const [folders, setFolders] = useState<IFolder[]>([]);
    const [folderModalOpen, setFolderModalOpen] = useState(false);
    const [parentFolderId, setParentFolderId] = useState<number>();

    useEffect(() => {
        const getCourse = async () => {
            return await client.get(`/courses/${courseId}`);
        }

        getCourse().then(response => {
            setCourse(response.data);
            setFolders(response.data.folders);
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

    const changeFolderModalVisibility = (folderId?: number) => {
        setParentFolderId(folderId);
        setFolderModalOpen(!folderModalOpen);
    }

    const reloadFolders = async () => {
        await client.get(`/courses/${courseId}/folders`)
            .then((response) => {
                setFolders(response.data);
            });
    }

    return (
        <>
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
                            {folders.map(folder => {
                                return (
                                    <Folder
                                        key={folder.id}
                                        folder={folder}
                                        canEdit={course?.userIsModerator || course?.userIsOwner}
                                        openModal={changeFolderModalVisibility}
                                    />
                                )
                            })}
                        </Card.Body>
                    </Card>
                </Card.Body>
            </Card>

            <FolderModal
                show={folderModalOpen}
                courseId={course?.id}
                parentFolderId={parentFolderId}
                closeModal={changeFolderModalVisibility}
                reloadFolders={reloadFolders}
            />
        </>
    )
}

export default Course;