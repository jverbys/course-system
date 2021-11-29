import React from "react";
import {IFolder} from "./Course";

type Props = {
    folder: IFolder,
}

const Folder = ({ folder }: Props) => {
    return (
        <div style={{ marginLeft: '20px' }}>
            <div className="d-flex align-items-center">
                <img style={{ marginRight: '5px' }} src="/images/folder.png" alt="icon"/>

                {folder.name}

                <a style={{ marginLeft: '5px' }} href="#">
                    +
                </a>
            </div>
            {folder.files.map(file => {
                return (
                    <div style={{ marginLeft: '20px' }} key={file.id}>
                        <img src="/images/file.png" alt="icon"/>
                        {file.name}
                    </div>
                )
            })}
            {folder.subFolders.map(subFolder => {
                return (
                    <Folder key={subFolder.id} folder={subFolder} />
                )
            })}
        </div>
    )
}

export default Folder;