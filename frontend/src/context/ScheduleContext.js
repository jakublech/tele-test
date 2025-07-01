import React, { createContext, useState, useMemo } from 'react';
import { optimizeSchedule } from '../services/OptimizationService';

export const ScheduleContext = createContext();

export const ScheduleProvider = ({ children }) => {
  const [agents, setAgents] = useState([
    { id: 1, name: 'Jan Kowalski', skills: ['Sales', 'Tech Support'], efficiency: { 'Sales': 90, 'Tech Support': 80 } },
    { id: 2, name: 'Anna Nowak', skills: ['Tech Support', 'Complaints'], efficiency: { 'Tech Support': 85, 'Complaints': 75 } }
  ]);
  
  const [queues, setQueues] = useState(['Sales', 'Tech Support', 'Complaints', 'Billing', 'Activation']);
  const [demandData, setDemandData] = useState({});
  const [schedule, setSchedule] = useState({});
  const [isGenerating, setIsGenerating] = useState(false);

  const addAgent = (agent) => {
    setAgents([...agents, { ...agent, id: Date.now() }]);
  };

  const addQueue = (queue) => {
    if (queue && !queues.includes(queue)) {
      setQueues([...queues, queue]);
    }
  };

  const generateSchedule = async (timeRange) => {
    setIsGenerating(true);
    try {
      const optimized = await optimizeSchedule({
        agents,
        queues,
        timeRange,
        demandData
      });
      setSchedule(optimized);
    } catch (error) {
      console.error('Błąd generowania grafiku:', error);
    } finally {
      setIsGenerating(false);
    }
  };

  const value = useMemo(() => ({
    agents,
    queues,
    demandData,
    schedule,
    isGenerating,
    addAgent,
    addQueue,
    generateSchedule
  }), [agents, queues, demandData, schedule, isGenerating]);

  return (
    <ScheduleContext.Provider value={value}>
      {children}
    </ScheduleContext.Provider>
  );
};