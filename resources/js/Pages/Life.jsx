import LifeWeeks from "@/Components/LifeWeeks";
import DeathClock from "@/Components/DeathClock";
import AgeClock from "@/Components/AgeClock";

export default function Life({ weeks, birthday, deathDate }) {

    return (
        <main>

            <div className="container mx-auto mb-6">
                <div className="pt-4 pb-2"><AgeClock birthday={birthday} /></div>
                <div className="pb-2"><DeathClock death={deathDate} /></div>
            </div>

            <LifeWeeks weeks={weeks} />
        </main>
    );
}
