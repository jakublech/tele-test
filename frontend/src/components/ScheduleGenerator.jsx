import React, { useState, useContext } from 'react';
import { ScheduleContext } from '../context/ScheduleContext';

const ScheduleGenerator = () => {
  const { generateSchedule, isGenerating } = useContext(ScheduleContext);
  const [timeRange, setTimeRange] = useState({
    start: '2023-11-01',
    end: '2023-11-07'
  });

  return (
    <div className="schedule-generator">
      <h2>Generator Grafiku</h2>
      <div className="controls">
        <label>
          Data początkowa:
          <input 
            type="date" 
            value={timeRange.start}
            onChange={(e) => setTimeRange({...timeRange, start: e.target.value})}
          />
        </label>
        <label>
          Data końcowa:
          <input 
            type="date" 
            value={timeRange.end}
            onChange={(e) => setTimeRange({...timeRange, end: e.target.value})}
          />
        </label>
        <button 
          onClick={() => generateSchedule(timeRange)}
          disabled={isGenerating}
        >
          {isGenerating ? 'Generowanie...' : 'Generuj grafik'}
        </button>
      </div>
    </div>
  );
};

export default ScheduleGenerator;