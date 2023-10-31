import LifeWeeks from "@/Components/LifeWeeks";
import LifeProgressBar from "@/Components/LifeProgressBar";
import Navbar from "@/Components/Nav/NavBar";
import { Head } from '@inertiajs/react';


export default function Life({ weeks, events }) {
    return (
        <>
            <Head title="Life" />
            <main>
                <Navbar route='/life' />
                <div className="pt-24">
                    <LifeProgressBar events={events} />
                </div>

                <div>
                    <LifeWeeks weeks={weeks} />
                </div>
            </main>
        </>
    );
}
