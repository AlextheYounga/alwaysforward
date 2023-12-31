import React from 'react'
import Board from 'react-trello'
import Navbar from "@/Components/Nav/NavBar";
import { Head } from '@inertiajs/react';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/20/solid'
import dayjs from 'dayjs';
import axios from 'axios';

export default function Kanban({ week, board }) {
    const weekStart = dayjs(week.start).format('ddd MMM D, YYYY');
    const weekEnd = dayjs(week.end).format('ddd MMM D, YYYY');
    const nextWeek = week.id + 1;
    const prevWeek = week.id - 1;

    function handleBoardChange(data) {
        const url = '/board/update'
        const lanes = data.lanes;

        axios.post(url, { lanes })
            .then(response => {
                console.log(response);
            })
            .catch(error => {
                console.error('Error posting data:', error);
            });
    }

    return (
        <>
            <Head title="Week" />
            <main>
                <Navbar route='/board' />
                <div className="container mx-auto">
                    <div className="flex justify-between text-sky-300 my-4">
                        <a href={`/board/${prevWeek}`} className="flex items-center"><ChevronLeftIcon className="h-5 w-5" aria-hidden="true" /> Last Week</a>
                        <a href={`/board/${nextWeek}`} className="flex items-center">Next Week <ChevronRightIcon className="h-5 w-5" aria-hidden="true" /></a>
                    </div>

                    <div className="flex justify-between text-sky-100">
                        <h1 className="py-4 text-3xl">Week {week.id} Board</h1>
                        <h2 className="py-4 text-3xl">{weekStart} - {weekEnd}</h2>
                    </div>

                    <div className="flex justify-center">
                        <Board
                            data={board.lanes}
                            onDataChange={handleBoardChange}
                            editable
                        />
                    </div>
                </div>
            </main>
        </>
    )
}

