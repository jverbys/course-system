import React from "react";
import "react-datetime/css/react-datetime.css";
import Datetime from 'react-datetime';

type Props = {
    setDate: (date: string) => void,
    placeholder?: string
}

const DateTimeSelector = ({ setDate, placeholder }: Props) => {
    return (
        <Datetime
            dateFormat={'YYYY-MM-DD'}
            timeFormat={'HH:mm:ss'}
            inputProps={{ placeholder: placeholder ?? '', readOnly: true }}
            onChange={(date: any) => setDate(date.format('YYYY-MM-DD HH:mm:ss'))}
        />
    )
}

export default DateTimeSelector;