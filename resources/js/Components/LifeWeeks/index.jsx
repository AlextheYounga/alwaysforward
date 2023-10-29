import React, { useState } from 'react';
import { parseWeeks } from '@/Logic/lifeweeks';
import WeekModal from './WeekModal';

export default function LifeWeeks({ weeks }) {
    const [open, setOpen] = useState(false)
    const [selectedWeek, setSelectedWeek] = useState(null)

    // Parse the weeks
    const lifeWeeks = parseWeeks(weeks);

    function handleSelectedWeek(week) {
        setOpen(true)
        setSelectedWeek(week)
    }

    return (
        <section className="w-full">
            <div className="life-calendar container mx-auto py-12">
                <div className="flex flex-wrap w-full">
                    {lifeWeeks.map((week, index) => {
                        return (
                            <div
                                onClick={() => handleSelectedWeek(week)}
                                className={`week-box relative w-4 h-4 m-[1px] rounded border border-slate-950 ${week.status}`}
                                key={index}
                                data-hoverdetails={`Age: ${week.age} W:${index}`}
                                style={{ backgroundColor: week.color }}
                            ></div>
                        )
                    })}
                </div>
            </div>
            <div>
                <WeekModal open={open} setOpen={setOpen} week={selectedWeek} />
            </div>
        </section>

    )
}

