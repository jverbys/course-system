import React, {useState} from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";

const DateSelector = () => {
    const [date, setDate] = useState();

    return (
        <DatePicker
            dateFormat={'yyyy-MM-dd'}
            selected={date}
            onChange={(date: any) => setDate(date)}
        />
    )
}

export default DateSelector;