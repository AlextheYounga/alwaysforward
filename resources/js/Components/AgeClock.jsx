import { useState, useEffect } from 'react';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
dayjs.extend(duration)

export default function AgeClock({ birthday }) {
    const timeSinceBorn = Date.now() - Date.parse(birthday);
    const [birthClock, setBirthClock] = useState(timeSinceBorn);

    function buildDurationString(currentTimeAlive) {
        const d = dayjs.duration(currentTimeAlive)

        return `${d.years()} years, ${d.months()} months, ${d.days()} days, ${d.hours()} hours, ${d.minutes()} minutes, ${d.seconds()} seconds`
    }

    useEffect(() => {
        birthClock > 0 && setTimeout(() => setBirthClock(birthClock + 1000), 1000);
    }, [birthClock]);

    return (
        <div className="text-sky-100">
            <span className="text-sky-300">Time Alive: </span>
            <span className="font-semibold text-lg">{buildDurationString(birthClock)}</span>
        </div>
    )
}