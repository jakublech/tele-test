import React, { useContext } from 'react';
import { ScheduleContext } from '../context/ScheduleContext';

const ScheduleView = () => {
  const { schedule } = useContext(ScheduleContext);

  return (
    <div className="schedule-view">
      <h2>Podgląd Grafiku</h2>
      <div className="schedule-grid">
        <div className="header-row">
          <div className="header-cell">Godzina</div>
          {schedule.queues?.map(queue => (
            <div key={queue} className="header-cell">{queue}</div>
          ))}
        </div>
        
        {schedule.timeSlots?.map(timeSlot => (
          <div key={timeSlot.time} className="time-row">
            <div className="time-cell">{timeSlot.time}</div>
            {schedule.queues?.map(queue => (
              <div key={queue} className="queue-cell">
                {timeSlot.queues[queue]?.map(agent => (
                  <span key={agent.id}>{agent.name}</span>
                ))}
              </div>
            ))}
          </div>
        ))}
      </div>
    </div>
  );
};

export default ScheduleView;