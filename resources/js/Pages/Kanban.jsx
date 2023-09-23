import React from 'react'
import Board from 'react-trello'

const data = {
    lanes: [
        {
            id: 'lane1',
            title: 'Planned Tasks',
            label: '2/2',
            cards: [
                { id: 'Card2', title: 'Pay Rent', description: 'Transfer via NEFT', label: '5 mins', metadata: { sha: 'be312a1' } },
                { id: 'Card3', title: 'Pay Rent', description: 'Transfer via NEFT', label: '5 mins', metadata: { sha: 'be312a1' } },
                { id: 'Card4', title: 'Pay Rent', description: 'Transfer via NEFT', label: '5 mins', metadata: { sha: 'be312a1' } },
                { id: 'Card5', title: 'Pay Rent', description: 'Transfer via NEFT', label: '5 mins', metadata: { sha: 'be312a1' } }
            ]
        },
        {
            id: 'lane2',
            title: 'In Progress',
            label: '0/0',
            cards: []
        },
        {
            id: 'lane3',
            title: 'On Hold',
            label: '0/0',
            cards: []
        },
        {
            id: 'lane4',
            title: 'Completed',
            label: '0/0',
            cards: []
        }
    ]
}

export default function Kanban() {
    // export default class App extends React.Component {
    return <Board data={data} editable canAddLanes/>
}