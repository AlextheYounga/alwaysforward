import { Fragment } from 'react'
import { Dialog, Transition } from '@headlessui/react'
import { XMarkIcon, ArrowTopRightOnSquareIcon, ViewColumnsIcon } from '@heroicons/react/24/outline'

export default function WeekModal({ open, setOpen, week }) {
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
              <Dialog.Panel className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
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
                <div>
                  <div className="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <Dialog.Title as="h2" className="text-base font-semibold leading-6 text-gray-900">
                      Week {week?.id} of Your Life
                    </Dialog.Title>

                    {/* Details */}
                    <div className="w-full mt-4">
                      <div>
                        <span className="text-base text-gray-600">Age: </span>
                        <span className="font-semibold tracking-tight text-gray-900">{week?.age}</span>
                      </div>
                      <div>
                        <span className="text-base text-gray-600">Start Date: </span>
                        <span className="font-semibold tracking-tight text-gray-900">{week?.start}</span>
                      </div>
                      <div>
                        <span className="text-base text-gray-600">End Date: </span>
                        <span className="font-semibold tracking-tight text-gray-900">{week?.end}</span>
                      </div>
                    </div>

                    {/* Events */}
                    <div className="mt-6 text-gray-500">
                      No Events
                    </div>


                    {/* Links */}
                    <div className="flex w-full mt-6">
                    <span className="flex pr-6">                      
                      <a
                        href={`/week/${week?.id}`}
                        className="tracking-tight text-indigo-500">
                        Kanban 
                      </a>
                        <ViewColumnsIcon className="w-5 h-5 ml-2 text-indigo-500" />
                      </span>

                      <span className="flex pr-6">                      
                      <a
                        href={`https://track.toggl.com/timer?start_date=${week?.start}&end_date=${week?.end}`}
                        className="tracking-tight text-indigo-500"
                        target='_blank'
                        >
                        Toggl 
                      </a>
                        <ArrowTopRightOnSquareIcon className="w-5 h-5 ml-2 text-indigo-500" />
                      </span>
                    </div>
                  </div>
                </div>
              </Dialog.Panel>
            </Transition.Child>
          </div>
        </div>
      </Dialog>
    </Transition.Root>
  )
}
