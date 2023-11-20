import { Fragment, useState, useEffect } from 'react'
import { Dialog, Transition } from '@headlessui/react'
import { router } from '@inertiajs/react'
import { XMarkIcon } from '@heroicons/react/24/outline'
import axios from 'axios'

export default function TaskModal({ open, setOpen, task, editMode }) {
  const form = {
    id: task?.id ?? null,
    goal_id: editMode ? task.goal_id : "",
    title: editMode ? task.title : "",
    description: editMode ? task.description : "",
    priority: editMode ? task.priority : "0",
    due_date: editMode ? task.due_date : "",
    notes: editMode ? task.notes : "",
    status: editMode ? task.status : "todo",
  }

  const modalTitle = editMode ? 'Edit' : 'Create';
  const [goals, setGoals] = useState([])
  const [values, setValues] = useState(form)

  function handleChange(e) {
    const key = e.target.id;
    const value = e.target.value
    setValues(values => ({
      ...values,
      [key]: value,
    }))

    console.log(values)
  }

  function handleSubmit(e) {
    e.preventDefault()
    if (editMode) {
      router.post('/tasks/update', values)
    } else {
      router.post('/tasks/new', values)
    }
    setOpen(false)
  }

  useEffect(() => {
    setValues(form)

    const url = `/goals/fetch`;
    axios.get(url)
      .then(response => {
        const goalsData = response.data ?? [];
        console.log(goalsData)
        setGoals(goalsData);
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, [editMode])

  return (
    <Transition.Root show={open} as={Fragment}>
      <Dialog as="div" className="relative z-10" onClose={setOpen}>
        <Transition.Child
          as={Fragment}
          enter="ease-out duration-300"
          enterFrom="opacity-0"
          enterTo="opacity-100"
          leave="ease-in duration-200"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
        >
          <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </Transition.Child>

        <div className="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <Transition.Child
              as={Fragment}
              enter="ease-out duration-300"
              enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enterTo="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leaveFrom="opacity-100 translate-y-0 sm:scale-100"
              leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <Dialog.Panel className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                <div className="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                  <button
                    type="button"
                    className="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    onClick={() => setOpen(false)}
                  >
                    <span className="sr-only">Close</span>
                    <XMarkIcon className="h-6 w-6" aria-hidden="true" />
                  </button>
                </div>
                <div className="sm:flex sm:items-start">
                  <div className="w-5/6 mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <Dialog.Title as="h3" className="text-base font-semibold leading-6 text-gray-900">
                      {modalTitle} Task
                    </Dialog.Title>
                    <div className="mt-2">
                      <div className="mt-4 w-full">
                        <div className="py-4">
                          <label htmlFor="title" className="block text-sm font-medium leading-6 text-gray-700">
                            Title
                          </label>
                          <div className="mt-2">
                            <div className="flex rounded-md bg-white/5 border border-gray-100 px-2 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                              <input
                                type="text"
                                name="title"
                                id="title"
                                autoComplete="title"
                                className="flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-800 focus:ring-0 sm:text-sm sm:leading-6"
                                placeholder="A New Task"
                                onChange={handleChange}
                                defaultValue={values.title}
                              />
                            </div>
                          </div>
                        </div>

                        <div className="py-4">
                          <label htmlFor="description" className="block text-sm font-medium leading-6 text-gray-700">
                            Description
                          </label>
                          <div className="mt-2">
                            <textarea
                              id="description"
                              name="description"
                              rows={2}
                              className="block w-full rounded-md border border-gray-100 bg-white/5 py-1.5 text-gray-800 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                              onChange={handleChange}
                              defaultValue={values.description}
                            />
                          </div>
                          <p className="mt-3 text-sm leading-6 text-gray-400">What's this task about?</p>
                        </div>

                        <div className="py-4">
                          <label htmlFor="Goal" className="block text-sm font-medium leading-6 text-gray-900">
                            Goal (Optional)
                          </label>
                          <select
                            id="Goal"
                            name="Goal"
                            className="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            onChange={handleChange}
                            value={values.goal_id ?? ""}
                          >
                          <option value={null}>No Goal</option>
                            {
                              goals.map((goal) => (
                                <option key={goal.id} value={`goal.id`}>{goal.title}</option>
                              ))
                            }
                          </select>
                        </div>

                        <div className="flex flex-wrap w-full py-4">
                          <div className="w-1/2 pr-1">
                            <label htmlFor="priority" className="block text-sm font-medium leading-6 text-gray-900">
                              Priority
                            </label>
                            <select
                              id="priority"
                              name="priority"
                              className="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                              onChange={handleChange}
                              value={values.priority ?? "0"}
                            >
                              <option value="0">Low</option>
                              <option value="1">Normal</option>
                              <option value="2">High</option>
                              <option value="3">Super</option>
                            </select>
                          </div>

                          <div className="w-1/2 px=l-1">
                            <label htmlFor="status" className="block text-sm font-medium leading-6 text-gray-900">
                              Status
                            </label>
                            <select
                              id="status"
                              name="status"
                              className="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                              onChange={handleChange}
                              value={values.status ?? 'active'}
                            >
                              <option value="active">Active</option>
                              <option value="aborted">Aborted</option>
                              <option value="failed">Failed</option>
                              <option value="completed">Completed</option>
                            </select>
                          </div>
                        </div>
                        <div className="py-4">
                          <label htmlFor="due_date" className="block text-sm font-medium leading-6 text-gray-700">
                            Due Date
                          </label>
                          <div className="mt-2">
                            <div className="flex border border-gray-100 rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                              <input
                                type="date"
                                name="due_date"
                                id="due_date"
                                autoComplete="due_date"
                                className="flex-1 border-0 px-2 bg-transparent py-1.5 pl-1 text-gray-800 focus:ring-0 sm:text-sm sm:leading-6"
                                placeholder="Months"
                                onChange={handleChange}
                                defaultValue={values.due_date}
                              />
                            </div>
                          </div>
                        </div>
                        <div className="py-4">
                          <label htmlFor="notes" className="block text-sm font-medium leading-6 text-gray-700">
                            Notes
                          </label>
                          <div className="mt-2">
                            <textarea
                              id="notes"
                              name="notes"
                              rows={4}
                              className="block w-full rounded-md border border-gray-100 bg-white/5 py-1.5 text-gray-800 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                              onChange={handleChange}
                              defaultValue={values.notes}
                            />
                          </div>
                          <p className="mt-3 text-sm leading-6 text-gray-400">Write any notes on the task here</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                  <button
                    type="button"
                    className="inline-flex w-full justify-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                    onClick={handleSubmit}
                  >
                    {editMode ? 'Update' : 'Create'}
                  </button>
                  <button
                    type="button"
                    className="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    onClick={() => setOpen(false)}
                  >
                    Cancel
                  </button>
                </div>
              </Dialog.Panel>
            </Transition.Child>
          </div>
        </div>
      </Dialog>
    </Transition.Root>
  )
}
