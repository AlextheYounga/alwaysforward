import dayjs from 'dayjs';

export function generateWeeksTimeline(birthday, deathAge) {
    const birthday = dayjs(birthday || '1995-11-13')
    const death = birthday.add((deathAge || 90), 'years')

    const weeks = [
        {
            week: 0,
            start: birthday,
            end: birthday.add(1, 'week'),
            events: [
                {
                    date: birthday,
                    title: "👶 I was born"
                }
            ]
        }
    ]

    while (true) {
        const weekNumber = weeks.length
        const thisWeekStart = birthday.add(weekNumber, 'weeks')
        let thisWeekEnd = birthday.add((weekNumber + 1), 'weeks')
        const afterDeath = (thisWeekEnd - death) > 0


        if (afterDeath) thisWeekEnd = death

        weeks.push({
            week: weekNumber,
            start: thisWeekStart,
            end: thisWeekEnd,
            events: []
        })

        if (afterDeath) break;
    }
    return weeks;
}

