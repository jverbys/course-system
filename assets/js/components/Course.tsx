import React, {useEffect, useState} from "react";
import {useParams, useNavigate} from "react-router-dom";
import {Button, Card} from "react-bootstrap";
import Folder from "./Folder";
import FolderModal from "./FolderModal";
import client from "../Client";
import FolderModalOpenBtn from "./FolderModalOpenBtn";
import CourseEditModal from "./CourseEditModal";
import {Simulate} from "react-dom/test-utils";
import load = Simulate.load;

export interface ICourse {
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
    const navigate = useNavigate();
    const [courseEditModalOpen, setCourseEditModalOpen] = useState(false);
    const [courseForEdit, setCourseForEdit] = useState<ICourse>();

    useEffect(() => {
        const getCourse = async () => {
            return await client.get(`/courses/${courseId}`);
        }

        getCourse().then(response => {
            setCourse(response.data);
            setCourseForEdit(response.data);
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
                // @ts-ignore
                setCourseForEdit(existingCourse);
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

    const deleteCourse = async () => {
        await client.delete(`/courses/${course?.id}`)
            .then(() => {
                navigate('/');
            });
    }

    const updateCourseProp = (prop: string, value: string) => {
        let existingCourse = {
            ...courseForEdit
        };

        // @ts-ignore
        existingCourse[prop] = value;

        // @ts-ignore
        setCourseForEdit(existingCourse);
    }

    const updateCourse = async () => {
        await client.patch(`/courses/${course?.id}`, {
            name: courseForEdit?.name,
            description: courseForEdit?.description,
            startDate: courseForEdit?.startDate,
            endDate: courseForEdit?.endDate,
        }).then(response => {
            setCourse(response.data);
            setCourseForEdit(response.data);
            setFolders(response.data.folders);

            setCourseEditModalOpen(false);
        });
    }

    const closeCourseEditModal = () => {
        setCourseEditModalOpen(false);
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
                        <Card.Header className="d-flex justify-content-between align-items-center">
                            Course information

                            <div>
                                {
                                    (course?.userIsModerator || course?.userIsOwner) &&
                                    <Button
                                        variant="success"
                                        size="sm"
                                        onClick={() => setCourseEditModalOpen(true) }
                                    >
                                        Edit
                                    </Button>
                                }

                                {
                                    course?.userIsOwner &&
                                    <Button
                                        variant="danger"
                                        style={{ marginLeft: '10px' }}
                                        size="sm"
                                        onClick={() => { if (window.confirm('Are you sure ?')) deleteCourse() }}
                                    >
                                        Delete
                                    </Button>
                                }
                            </div>
                        </Card.Header>
                        <Card.Body>
                            <div>{course?.description}</div>
                            <div>Starts: {course?.startDate}</div>
                            <div>Ends: {course?.endDate}</div>
                        </Card.Body>
                    </Card>
                    <Card style={{ marginTop: '16px'}}>
                        <Card.Header>Folders and files</Card.Header>
                        <Card.Body>
                            {
                                (course?.userIsModerator || course?.userIsOwner) &&
                                <FolderModalOpenBtn onClick={() => changeFolderModalVisibility()} />
                            }
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

            <CourseEditModal
                show={courseEditModalOpen}
                course={courseForEdit}
                updateCourseProp={updateCourseProp}
                updateCourse={updateCourse}
                closeModal={closeCourseEditModal}
            />
        </>
    )
}

export default Course;