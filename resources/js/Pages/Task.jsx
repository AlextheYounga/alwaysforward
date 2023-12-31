import { useState } from 'react'
import Navbar from "@/Components/Nav/NavBar";
import PrimaryButton from "@/Components/Buttons/PrimaryButton";
import TaskModal from "@/Components/TaskModal";


function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Task({ tasks }) {
    const [open, setOpen] = useState(false)
    const [selectedTask, setSelectedTask] = useState(null)
    const [editMode, setEditMode] = useState(false)
    
    const priority = ['LOW', 'NORMAL', 'HIGH', 'SUPER']

    const statusBubbles = {
        'backlog': ['bg-gray-500/20', 'bg-gray-500'],
        'todo': ['bg-gray-400/20', 'bg-gray-400'],
        'in-progress': ['bg-rose-500/20', 'bg-rose-500'],
        'on-hold': ['bg-violet-500/20', 'bg-violet-500'],
        'completed': ['bg-emerald-500/20', 'bg-emerald-500'],
    }

    function handleCreate() {
        setEditMode(false)
        setOpen(true)
    }

    function handleEdit(goal) {
        setSelectedTask(goal)
        setEditMode(true)
        setOpen(true)
    }

    return (
        <>
            <TaskModal
                open={open}
                setOpen={setOpen}
                task={selectedTask}
                editMode={editMode}
            />

            <Navbar />
            <main className="container mx-auto pt-12">
                <h1 className="text-sky-100 text-3xl">Tasks</h1>
                <div className="w-full flex justify-end">
                    <PrimaryButton onClick={handleCreate}>
                        Create new
                    </PrimaryButton>
                </div>
                <h2 className="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">Ongoing</h2>
                <table className="mt-6 w-full whitespace-nowrap text-left">
                    <colgroup>
                        <col className="w-full sm:w-4/12" />
                        <col className="lg:w-4/12" />
                        <col className="lg:w-2/12" />
                        <col className="lg:w-1/12" />
                        <col className="lg:w-1/12" />
                    </colgroup>
                    <thead className="border-b border-white/10 text-sm leading-6 text-white">
                        <tr>
                            <th scope="col" className="py-2 pl-4 pr-8 font-semibold sm:pl-6 lg:pl-8">
                                Title
                            </th>
                            <th scope="col" className="hidden py-2 pl-0 pr-8 font-semibold sm:table-cell">
                                Due Date
                            </th>
                            <th scope="col" className="py-2 pl-0 pr-4 text-right font-semibold sm:pr-8 sm:text-left lg:pr-20">
                                Status
                            </th>
                            <th scope="col" className="hidden py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20">
                                Type
                            </th>
                            <th scope="col" className="hidden py-2 pl-0 pr-4 text-right font-semibold sm:table-cell sm:pr-6 lg:pr-8">
                                Priority
                            </th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                        {tasks.map((task) => (
                            <tr key={task.id}
                                onClick={() => handleEdit(task)}
                            >
                                <td className="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
                                    <div className="flex items-center gap-x-4">
                                        <div className="truncate text-sm font-medium leading-6 text-white">{task.title}</div>
                                    </div>
                                </td>
                                <td className="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
                                    <div className="flex gap-x-3">
                                        <div className="font-mono text-sm leading-6 text-gray-400">{task?.due_date}</div>
                                    </div>
                                </td>
                                <td className="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    <div className="mt-1 flex items-center gap-x-1.5">
                                        <div className={classNames("flex-none rounded-full p-1", statusBubbles[task?.status][0])}>
                                            <div className={classNames("h-1.5 w-1.5 rounded-full bg-emerald-500", statusBubbles[task?.status][1])} />
                                        </div>
                                        <p className="text-xs leading-5 text-gray-400">{task?.status}</p>
                                    </div>
                                </td>
                                <td className="hidden py-4 pl-0 pr-8 text-sm leading-6 text-gray-400 md:table-cell lg:pr-20">
                                    {task.type}
                                </td>
                                <td className="hidden py-4 pl-0 pr-4 text-right text-sm leading-6 text-gray-400 sm:table-cell sm:pr-6 lg:pr-8">
                                    {priority[task.priority]}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </main>
        </>
    )
}
