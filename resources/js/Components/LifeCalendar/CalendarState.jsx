import { atom } from 'recoil';

export const calendarState = atom({
  key: 'calendarState',
  default: {
    options: {
      highlightYears: false,
      showEveryYears: 5,
      oneRowOneYear: false
    },
    events: []
  }
});
