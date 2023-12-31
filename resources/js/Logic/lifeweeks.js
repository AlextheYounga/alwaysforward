import dayjs from 'dayjs';
import isBetween from 'dayjs/plugin/isBetween';
dayjs.extend(isBetween);

export function generateWeeksTimeline(birthday, deathAge) {
    birthday = dayjs(birthday || '1995-11-13')
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

export function parseWeeks(weeks) {
    if (!weeks || weeks.length === 0) return [];
    const today = dayjs();

    const parsedWeeks = [];

    for (const week of weeks) {
        const isCurrentWeek = today.isBetween(week.start, week.end);
        const isPastWeek = today.isAfter(week.end);

        const color = () => {
            if (isCurrentWeek) return '#67e8f9';
            if (isPastWeek) return '#3b82f6';
            return '#172554'; // future weeks
        }

        const status = () => {
            if (isCurrentWeek) return 'current';
            if (isPastWeek) return 'past';
            return 'future';
        }

        const parsedWeek = {
            ...week,
            color: color(),
            status: status(),
        }

        parsedWeeks.push(parsedWeek)
    }

    return parsedWeeks;
}

