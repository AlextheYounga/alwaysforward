import Navbar from "@/Components/Nav/NavBar";



function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Task({ tasks }) {
    const statusBubbles = {
        'todo': ['bg-gray-500/20', 'bg-gray-500'],
        'in-progress': ['bg-rose-500/20', 'bg-rose-500'],
        'on-hold': ['bg-violet-500/20', 'bg-violet-500'],
        'completed': ['bg-emerald-500/20', 'bg-emerald-500'],
    }

    return (
        <>
            <Navbar />
            <main className="container mx-auto pt-12">
                <section className="max-w-5xl mx-auto">
                    <h1 className="text-sky-100 text-3xl">Tasks</h1>
                    <div className="py-12">
                        {
                            tasks.length > 0 ? (
                                <ul role="list" className="divide-y divide-gray-800">
                                    {tasks.map((task) => (
                                        <li key={task?.id} className="flex justify-between gap-x-6 py-5">
                                            <div className="flex min-w-0 gap-x-4">
                                                <div className="min-w-0 flex-auto">
                                                    <p className="text-sm font-semibold leading-6 text-white">{task?.title}</p>
                                                    <p className="mt-1 truncate text-xs leading-5 text-gray-400">{task?.description}</p>
                                                </div>
                                            </div>
                                            <div className="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                                <p className="text-sm leading-6 text-white">
                                                    Due Date: {task?.due_date}
                                                </p>
                                                <div className="mt-1 flex items-center gap-x-1.5">
                                                    <div className={classNames("flex-none rounded-full p-1", statusBubbles[task?.status][0])}>
                                                        <div className={classNames("h-1.5 w-1.5 rounded-full bg-emerald-500", statusBubbles[task?.status][1])} />
                                                    </div>
                                                    <p className="text-xs leading-5 text-gray-400">{task?.status}</p>
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) :
                                (
                                    <div className="text-sky-100">No Tasks Yet</div>
                                )
                        }
                    </div>
                </section>
            </main>
        </>

    )
}
