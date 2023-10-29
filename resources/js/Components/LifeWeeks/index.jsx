import React, { useState } from 'react';
import { parseWeeks } from '@/Logic/lifeweeks';
import WeekModal from './WeekModal';

export default function LifeWeeks({ weeks }) {
    const [open, setOpen] = useState(false)
    const [modalWeek, setModelWeek] = useState(null)
    const lifeWeeks = parseWeeks(weeks);

    function handleWeekClick(week) {
        setModelWeek(week)
        setOpen(true)
    }

    return (
        <section className="w-full">
            <div className="life-calendar container mx-auto py-12">
                <div className="flex flex-wrap w-full">
                    {lifeWeeks.map((week, index) => {
                        return (
                            <div
                                onClick={() => handleWeekClick(week)}
                                className={`week-box relative w-4 h-4 m-[1px] rounded border border-slate-950 ${week.current ? 'current' : ''}`}
                                key={index}
                                data-hoverdetails={`Age: ${week.age} W:${index}`}
                                style={{ backgroundColor: week.color }}
                            ></div>
                        )
                    })}
                </div>
            </div>
            <div>
                <WeekModal open={open} setOpen={setOpen} week={modalWeek} />
            </div>
        </section>

    )
}

