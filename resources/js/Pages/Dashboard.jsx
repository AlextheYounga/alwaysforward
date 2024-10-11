import { Head } from '@inertiajs/react';
import AlwaysForwardLogo from '../../images/always_forward.jpeg.webp';
import {
    ViewColumnsIcon,
    CalendarDaysIcon,
    ArrowRightIcon,
    FlagIcon,
    RectangleStackIcon,
} from '@heroicons/react/24/outline'

export default function Dashboard({ auth, laravelVersion, phpVersion }) {

    const directory = [
        { name: 'Life', href: '/life', icon: CalendarDaysIcon, description: "Your entire life in weeks, from a bird's eye view." },
        { name: 'Board', href: '/board', icon: ViewColumnsIcon, description: "This week of your life." },
        { name: 'Goals', href: '/goals', icon: FlagIcon, description: "The road you are paving ahead." },
        { name: 'Tasks', href: '/tasks', icon: RectangleStackIcon, description: "The little things you have to do to get there." },
    ];

    return (
        <>
            <Head title="Dashboard" />
            <div className="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-cyan-500 selection:text-white">
                <div className="max-w-7xl mx-auto p-6 lg:p-8">
                    <div className="flex flex-wrap justify-center">
                    <img src={AlwaysForwardLogo} className="w-36 h-36 rounded-full shadow-2xl shadow-gray-500/20 dark:shadow-none" />
                        <h1 className="text-center text-3xl w-full block text-cyan-300">Always Forward</h1>
                    </div>
                    <div className="mt-12">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                            {directory.map((item) => (
                                <a
                                    key={`item-${item.name}`}
                                    href={item.href}
                                    className="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-cyan-500"
                                >
                                    <div className="w-full">
                                        <div className="h-16 w-16 bg-cyan-700 flex items-center justify-center rounded-full">
                                            <item.icon className="w-10 h-10 text-cyan-300" aria-hidden="true" />
                                        </div>

                                        <h2 className="mt-6 text-xl font-semibold text-gray-900 dark:text-white">
                                            {item.name}
                                        </h2>

                                        <p className="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                            {item.description}
                                        </p>
                                    </div>

                                    <ArrowRightIcon className="self-center shrink-0 text-cyan-300 w-6 h-6 mx-6" />
                                </a>
                            ))
                            }
                        </div>
                    </div>
                </div>
            </div>

            <style>{`
                .bg-dots-darker {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
                }
                @media (prefers-color-scheme: dark) {
                    .dark\\:bg-dots-lighter {
                        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
                    }
                }
            `}</style>
        </>
    );
}
