import LifeWeeks from "@/Components/LifeWeeks";
import LifeProgressBar from "@/Components/LifeProgressBar";
import Navbar from "@/Components/Nav/NavBar";


export default function Life({ route, weeks, events }) {

    return (
        <main>
            <Navbar route={route} />
            <div className="pt-24">
                <LifeProgressBar events={events} />
            </div>

            <div>
                <LifeWeeks weeks={weeks} />
            </div>
        </main>
    );
}
