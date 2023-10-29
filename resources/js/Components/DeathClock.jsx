import { useState, useEffect } from 'react';
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
dayjs.extend(duration)

export default function DeathClock({ death }) {
    const timeUntilDeath = Date.parse(death) - Date.now();
    const [deathClock, setDeathClock] = useState(timeUntilDeath);

    function buildDurationString(deathTime) {
        const d = dayjs.duration(deathTime)

        return `${d.years()} years, ${d.months()} months, ${d.days()} days, ${d.hours()} hours, ${d.minutes()} minutes, ${d.seconds()} seconds`
    }

    useEffect(() => {
        deathClock > 0 && setTimeout(() => setDeathClock(deathClock - 1000), 1000);
    }, [deathClock]);

    return (
        <div className="text-sky-100">
            <span className="text-sky-300">Time Until Death: </span>
            <span className="font-semibold text-lg">{buildDurationString(deathClock)}</span>
        </div>
    )
}

