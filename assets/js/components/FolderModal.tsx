import React, {useState} from "react";
import {Button, Form, Modal} from "react-bootstrap";
import client from "../Client";

type Props = {
    show: boolean,
    courseId?: number,
    parentFolderId?: number,
    closeModal: (folderId?: number) => void,
    reloadFolders: () => void,
}

const FolderModal = ({ show, courseId, parentFolderId, closeModal, reloadFolders }: Props) => {
    const [type, setType] = useState('');
    const [name, setName] = useState('');

    const saveItem = async () => {
        let url: string;
        let data: {};
        if (type === 'folder') {
            url = `/courses/${courseId}/folders`;
            data = {
                name: name,
                parentFolder: parentFolderId ?? null,
            }
        } else {
            url = `/folders/${parentFolderId}/files`;
            data = {
                name: name,
            }
        }

        await client.post(url, data)
            .then(() => {
                closeModal();
                reloadFolders();
            });
    }

    return (
        <Modal show={show} onHide={closeModal}>
            <Modal.Header closeButton>
                <Modal.Title>New item</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <Form>
                    <Form.Group className="mb-3">
                        <Form.Label>Type</Form.Label>
                        <Form.Select onChange={(e: any) => {setType(e.target.value)}}>
                            <option selected disabled hidden>Select type</option>
                            <option value="folder">Folder</option>
                            <option value="file">File</option>
                        </Form.Select>
                    </Form.Group>

                    <Form.Group className="mb-3">
                        <Form.Label>Name</Form.Label>
                        <Form.Control
                            type="text"
                            placeholder="Name"
                            onChange={(e: any) => setName(e.target.value)}
                        />
                    </Form.Group>
                </Form>
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={() => closeModal}>
                    Close
                </Button>
                <Button
                    variant="primary"
                    disabled={name === '' || type === ''}
                    onClick={saveItem}
                >
                    Create
                </Button>
            </Modal.Footer>
        </Modal>
    )
}

export default FolderModal;