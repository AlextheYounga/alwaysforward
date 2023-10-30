import ProgressBar from "@ramonak/react-progress-bar";
import { useState, useEffect } from 'react';
import dayjs from 'dayjs';
import duration from "dayjs/plugin/duration";
dayjs.extend(duration)


export default function LifeProgressBar({ events }) {
    const birthday = Date.parse(events.birth);
    const death = Date.parse(events.death);

    function calculateLifeLived() {
        const today = Date.now();
        const totalLife = death - birthday;
        const lifeLived = today - birthday;

        return Math.round((lifeLived / totalLife * 100) * 10000) / 10000
    }

    const [percentComplete, setPercentComplete] = useState(calculateLifeLived());
    const [age, setAge] = useState(Date.now() - birthday);
    const [timeLeft, setTimeLeft] = useState(Date.now() - birthday);

    function formatDate(date) {
        return dayjs.duration(date).format('Y:M:D H:m:s')
    }

    useEffect(() => {
        percentComplete > 0 && setTimeout(() => setPercentComplete(calculateLifeLived()), 1000);
        age > 0 && setTimeout(() => setAge(Date.now() - birthday), 1000);
        timeLeft > 0 && setTimeout(() => setTimeLeft(death - Date.now()), 1000);
    }, [percentComplete, age, timeLeft]);

    return (
        <section className="container mx-auto">
            <div className="flex justify-around">
                <span className="text-cyan-100 w-32">{formatDate(age)}</span>
                <div className="w-3/4">
                    <ProgressBar
                        completed={percentComplete}
                        labelColor="#000"
                        bgColor="#38bdf8"
                        labelSize={'14px'}
                        animateOnRender={true}
                    />;
                </div>
                <span className="text-cyan-100 w-32">{formatDate(timeLeft)}</span>
            </div>

        </section>
    );
}
