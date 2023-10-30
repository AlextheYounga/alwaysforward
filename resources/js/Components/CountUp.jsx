import { useState, useEffect } from 'react';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
dayjs.extend(duration)

export default function CountUp({ title, pastDate }) {
    const timeUntil = Date.now() - Date.parse(pastDate);
    const [countup, setCountup] = useState(timeUntil);

    function buildDurationString(futureTime) {
        const d = dayjs.duration(futureTime)

        return `${d.years()} years, ${d.months()} months, ${d.days()} days, ${d.hours()}:${d.minutes()}:${d.seconds()}`
    }

    useEffect(() => {
        countup > 0 && setTimeout(() => setCountup(countup + 1000), 1000);
    }, [countup]);

    return (
        <div className="text-sky-100">
            <span className="text-sky-300">{title}: </span>
            <span className="font-semibold text-lg">{buildDurationString(countup)}</span>
        </div>
    )
}

