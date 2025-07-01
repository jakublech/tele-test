import React, { useState, useContext } from 'react';
import { ScheduleContext } from '../context/ScheduleContext';

const QueueManagement = () => {
  const { queues, addQueue, demandData } = useContext(ScheduleContext);
  const [newQueue, setNewQueue] = useState('');

  return (
    <div className="queue-management">
      <h2>Zarządzanie Kolejkami</h2>
      <div className="add-queue">
        <input 
          placeholder="Nazwa kolejki" 
          value={newQueue}
          onChange={(e) => setNewQueue(e.target.value)}
        />
        <button onClick={() => addQueue(newQueue)}>Dodaj kolejkę</button>
      </div>
      
      <div className="queues-list">
        {queues.map(queue => (
          <div key={queue} className="queue-card">
            <h3>{queue}</h3>
            {/* Wykres zapotrzebowania */}
          </div>
        ))}
      </div>
    </div>
  );
};

export default QueueManagement;