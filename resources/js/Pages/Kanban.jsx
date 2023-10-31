import React from 'react'
import Board from 'react-trello'
import Navbar from "@/Components/Nav/NavBar";

export default function Kanban({ route, week, board }) {
    console.log(board)
    return (
        <main>
            <Navbar route={route} />
            <div className="container mx-auto">
                <div className="flex justify-between">
                    <h1 className="py-4 text-3xl text-sky-100">Week {week.id} Board</h1>
                </div>

                <div className="flex justify-center">
                    <Board data={board.lanes} editable />
                </div>
            </div>
        </main>
    )
}

