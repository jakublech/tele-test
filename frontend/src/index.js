import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/App/App';
import './components/App/App.css';

const container = document.getElementById('root');
const root = createRoot(container);
root.render(<App/>);
