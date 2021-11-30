import React from "react";

type Props = {
    onClick: any
}

const FolderModalOpenBtn = ({ onClick }: Props) => {
    return (
        <a style={{ marginLeft: '5px', textDecoration: 'none' }}
           href="#"
           onClick={onClick}
        >
            +
        </a>
    )
}

export default FolderModalOpenBtn;