import React from 'react'
import Board from 'react-trello'
import Navbar from "@/Components/Nav/NavBar";
import { Head } from '@inertiajs/react';
import dayjs from 'dayjs';

export default function Kanban({ week, board }) {
    const weekStart = dayjs(week.start).format('ddd MMM D, YYYY');
    const weekEnd = dayjs(week.end).format('ddd MMM D, YYYY');

    return (
        <>
            <Head title="Week" />
            <main>
                <Navbar route='/week' />
                <div className="container mx-auto">
                    <div className="flex justify-between">
                        <h1 className="py-4 text-3xl text-sky-100">Week {week.id} Board</h1>
                        <h2 className="py-4 text-3xl text-sky-100">{weekStart} - {weekEnd}</h2>
                    </div>

                    <div className="flex justify-center">
                        <Board data={board.lanes} editable />
                    </div>
                </div>
            </main>
        </>
    )
}
