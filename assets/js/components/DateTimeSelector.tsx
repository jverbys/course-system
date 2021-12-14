import React from "react";
import "react-datetime/css/react-datetime.css";
import Datetime from 'react-datetime';

type Props = {
    setDate: (date: string) => void,
    placeholder?: string,
    value?: string,
}

const DateTimeSelector = ({ setDate, placeholder, value }: Props) => {
    return (
        <Datetime
            dateFormat={'YYYY-MM-DD'}
            timeFormat={'HH:mm:ss'}
            inputProps={{ placeholder: placeholder ?? '', readOnly: true }}
            onChange={(date: any) => setDate(date.format('YYYY-MM-DD HH:mm:ss'))}
            value={value}
        />
    )
}

export default DateTimeSelector;