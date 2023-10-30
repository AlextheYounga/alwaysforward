import LifeWeeks from "@/Components/LifeWeeks";
import CountDown from "@/Components/CountDown";
import CountUp from "@/Components/CountUp";
import LifeProgressBar from "@/Components/LifeProgressBar";


export default function Life({ weeks, events }) {

    return (
        <main>
            <div className="pt-24">
                <LifeProgressBar events={events} />
            </div>

            <div>
                <LifeWeeks weeks={weeks} />
            </div>
        </main>
    );
}
