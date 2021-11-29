import React, {useState} from "react";
import "react-datetime/css/react-datetime.css";
import Datetime from 'react-datetime';

type Props = {
    placeholder?: string
}

const DateTimeSelector = ({ placeholder }: Props) => {
    const [date, setDate] = useState();

    return (
        <Datetime
            dateFormat={'YYYY-MM-DD'}
            timeFormat={'HH:mm:ss'}
            inputProps={{ placeholder: placeholder ?? ''}}
            onChange={(date: any) => setDate(date)}
        />
    )
}

export default DateTimeSelector;