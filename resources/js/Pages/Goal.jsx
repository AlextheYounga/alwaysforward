import Navbar from "@/Components/Nav/NavBar";



function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Goal({ goals }) {
    const statusBubbles = {
        'active': ['bg-amber-500/20', 'bg-amber-500'],
        'completed': ['bg-emerald-500/20', 'bg-emerald-500'],
        'aborted': ['bg-rose-500/20', 'bg-rose-500'],
    }

    return (
        <>
            <Navbar />
            <main className="container mx-auto pt-12">
                <section className="max-w-5xl mx-auto">
                    <h1 className="text-sky-100 text-3xl">Goals</h1>
                    <div className="py-12">
                        {
                            goals.length > 0 ? (
                                <ul role="list" className="divide-y divide-gray-800">
                                    {goals.map((goal) => (
                                        <li key={goal?.id} className="flex justify-between gap-x-6 py-5">
                                            <div className="flex min-w-0 gap-x-4">
                                                <div className="min-w-0 flex-auto">
                                                    <p className="text-sm font-semibold leading-6 text-white">{goal?.title}</p>
                                                    <p className="mt-1 truncate text-xs leading-5 text-gray-400">{goal?.description}</p>
                                                </div>
                                            </div>
                                            <div className="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                                <p className="text-sm leading-6 text-white">
                                                    Metric: {goal?.target_value} {goal?.target_units} {goal?.due_date ? `by ${goal?.due_date}` : ''}
                                                </p>
                                                <div className="mt-1 flex items-center gap-x-1.5">
                                                    <div className={classNames("flex-none rounded-full p-1", statusBubbles[goal?.status][0])}>
                                                        <div className={classNames("h-1.5 w-1.5 rounded-full bg-emerald-500", statusBubbles[goal?.status][1])} />
                                                    </div>
                                                    <p className="text-xs leading-5 text-gray-400">{goal?.status}</p>
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) :
                                (
                                    <div className="text-sky-100">No Goals Yet</div>
                                )
                        }
                    </div>
                </section>
            </main>
        </>

    )
}
