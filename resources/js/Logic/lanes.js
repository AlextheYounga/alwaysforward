export function generateLanes() {
}



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
            ],
            style: {
                backgroundColor: 'lightgrey'
            }
        },
        {
            id: 'lane2',
            title: 'In Progress',
            label: '0/0',
            cards: [],
            style: {
                backgroundColor: '#f87171',
                color: 'white'
            }
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