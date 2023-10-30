import React from 'react'
import Board from 'react-trello'

export default function Kanban({ week, board }) {
    // export default class App extends React.Component {
    return (
        <div className="container mx-auto">
            <div className="flex justify-between">
                <h1 className="py-4 text-3xl text-sky-100">Week {week.id} Board</h1>
                
            </div>

            <Board data={board.lanes} editable />
        </div>
    )
}

