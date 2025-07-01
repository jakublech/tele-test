import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { ScheduleProvider } from '../../context/ScheduleContext';
import QueueManagement from '../QueueManagement';
import ScheduleGenerator from '../ScheduleGenerator';
import ScheduleView from '../ScheduleView';
import AgentList from '../AgentList';

function App() {
  const [message, setMessage] = useState('');
  const [data, setData] = useState([]);

  // useEffect(() => {
  //   axios.get('http://localhost/api/test')
  //     .then(response => {
  //       setMessage(response.data.message);
  //       setData(response.data.data);
  //     })
  //     .catch(error => {
  //       console.error('Error fetching data:', error);
  //     });
  // }, []);

  // return (
  //   <div className="App">
  //     <header className="App-header">
  //       <h1>msg: {message}</h1>
  //       <ul>
  //         {data.map((item, index) => (
  //           <li key={index}>{item}</li>
  //         ))}
  //       </ul>
  //     </header>
  //   </div>
  // );

  return (
    <ScheduleProvider>
      <div className="app">
        <header className="app-header">
          <h1>System Grafiku Call Center</h1>
        </header>
        <div className="app-content">
          <div className="left-panel">
            <AgentList />
            <QueueManagement />
          </div>
          <div className="right-panel">
            <ScheduleGenerator />
            <ScheduleView />
          </div>
        </div>
      </div>
    </ScheduleProvider>
  );
}

export default App;