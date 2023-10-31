import { useState, useEffect } from 'react';

export default function CountDown({ futureDate }) {
    const timeUntil = Date.parse(futureDate) - Date.now();
    const [countdown, setCountdown] = useState(timeUntil);

    useEffect(() => {
        countdown > 0 && setTimeout(() => setCountdown(countdown - 1000), 1000);
    }, [countdown]);

    return countdown
}

