import { useState, useEffect } from 'react';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
dayjs.extend(duration)

export default function CountDown({ title, futureDate }) {
    const timeUntil = Date.parse(futureDate) - Date.now();
    const [countdown, setCountdown] = useState(timeUntil);

    function buildDurationString(futureTime) {
        const d = dayjs.duration(futureTime)

        return `${d.years()} years, ${d.months()} months, ${d.days()} days, ${d.hours()}:${d.minutes()}:${d.seconds()}`
    }

    useEffect(() => {
        countdown > 0 && setTimeout(() => setCountdown(countdown - 1000), 1000);
    }, [countdown]);

    return (
        <div className="text-sky-100">
            <span className="text-sky-300">{title}: </span>
            <span className="font-semibold text-lg">{buildDurationString(countdown)}</span>
        </div>
    )
}

