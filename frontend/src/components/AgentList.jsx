import React, { useState, useContext } from 'react';
import { ScheduleContext } from '../context/ScheduleContext';

const AgentList = () => {
  const { agents, addAgent, updateAgent, queues } = useContext(ScheduleContext);
  const [newAgent, setNewAgent] = useState({
    name: '',
    skills: [],
    efficiency: {},
    availability: {},
    supportedQueues: []
  });

  const handleAddSkill = (queue, efficiency) => {
    setNewAgent(prev => ({
      ...prev,
      skills: [...prev.skills, queue],
      efficiency: { ...prev.efficiency, [queue]: efficiency }
    }));
  };

  return (
    <div className="agent-management">
      <h2>Zarządzanie Agentami</h2>
      <div className="add-agent">
        <div>
        <input 
          placeholder="Imię i nazwisko" 
          value={newAgent.name}
          onChange={(e) => setNewAgent({...newAgent, name: e.target.value})}
        />
        </div>
        <div>
        <input
          type="number"
          placeholder="Max godzin dziennie"
          value={newAgent.maxHoursDaily || '10'}
          onChange={e => setNewAgent({ ...newAgent, maxHoursDaily: parseInt(e.target.value, 10) })}
        />
        </div>
        <div>
        <input
          type="number"
          placeholder="Max godzin tygodniowo"
          value={newAgent.maxHoursWeekly || '50'}
          onChange={e => setNewAgent({ ...newAgent, maxHoursWeekly: parseInt(e.target.value, 10) })}
        />
        </div>
        <div>
        <input
          type="number"
          placeholder="Max godzin miesięcznie"
          value={newAgent.maxHoursMonthly || '160'}
          onChange={e => setNewAgent({ ...newAgent, maxHoursMonthly: parseInt(e.target.value, 10) })}
        />
        </div>
        <div>
          <label>Domyślna dostępność (dni robocze):</label>
          <input
            type="time"
            value={newAgent.workdayFrom || '08:00'}
            onChange={e => setNewAgent({ ...newAgent, workdayFrom: e.target.value })}
          />
          <input
            type="time"
            value={newAgent.workdayTo || '16:00'}
            onChange={e => setNewAgent({ ...newAgent, workdayTo: e.target.value })}
          />
        </div>
        <div>
          <label>Dostępność (soboty):</label>
          <input
            type="time"
            value={newAgent.saturdayFrom || '08:00'}
            onChange={e => setNewAgent({ ...newAgent, saturdayFrom: e.target.value })}
          />
          <input
            type="time"
            value={newAgent.saturdayTo || '16:00'}
            onChange={e => setNewAgent({ ...newAgent, saturdayTo: e.target.value })}
          />
        </div>
        <div>
          <label>Dostępność (niedziele):</label>
          <input
            type="time"
            value={newAgent.sundayFrom || '00:00'}
            onChange={e => setNewAgent({ ...newAgent, sundayFrom: e.target.value })}
          />
          <input
            type="time"
            value={newAgent.saturdayTo || '00:00'}
            onChange={e => setNewAgent({ ...newAgent, sundayTo: e.target.value })}
          />
        </div>
        <div>
          <label>Obsługiwane kolejki:</label>
          <select
            multiple
            value={newAgent.skills}
            onChange={e => {
              const selected = Array.from(e.target.selectedOptions, option => option.value);
              setNewAgent({ ...newAgent, skills: selected });
            }}
          >
            {queues.map(queue => (
              <option key={queue} value={queue}>{queue}</option>
            ))}
          </select>
        </div>
        <button onClick={() => addAgent(newAgent)}>Dodaj agenta</button>
      </div>
      
      <div className="agent-list">
        {agents.map(agent => (
          <div key={agent.id} className="agent-card">
            <h3>{agent.name}</h3>
            <p>Umiejętności: {agent.skills.join(', ')}</p>
            {/* Więcej szczegółów i opcja edycji */}
          </div>
        ))}
      </div>
    </div>
  );
};

export default AgentList;