# Telemedi Test Project

This project is a call center scheduling system built with Symfony (PHP) for the backend and React for the frontend.
This is only demo concept without tests, and good frontend.
This code may not work. It can be treated as an introduction for further discussion.

video with presentation: [https://www.loom.com/share/1e14f34d7c0b4d3392a8152f1eaac107](https://www.loom.com/share/1e14f34d7c0b4d3392a8152f1eaac107)
miro diagram roboczy/podstawowy koncept: [https://miro.com/app/board/uXjVIi3DffE=/](https://miro.com/app/board/uXjVIi3DffE=/)
## Features
- Agent management with availability and queue support
- Queue management
- Schedule generation and preview
- React frontend
- Symfony backend API

## Project Structure
- `src/` - Symfony backend source code
- `frontend/` - React frontend source code
- `public/` - Symfony public directory (serves backend and, optionally, frontend build)
- `docker/` - Docker configuration for local development

## Prerequisites
- Docker & Docker Compose (recommended for local development)
- Node.js (for frontend development)
- PHP 8.1+ and Composer (for backend development)

## Quick Start (with Docker)

1. **Clone the repository:**
   ```bash
   git clone <repo-url>
   ```

2. **Start all services:**
   ```bash
   make start
   make init
   ```
   This will start PHP, Nginx, and Node containers.

3. **Access the app:**
   - Frontend: [http://localhost:3000](http://localhost:3000) 
   - Backend/API: [http://localhost](http://localhost)

## Manual Start (without Docker)

### Backend (Symfony)
1. Install dependencies:
   ```bash
   make init
   ```
2. The backend will be available at [http://localhost:8000](http://localhost:8000)

### Frontend (React)
1. Install dependencies:
   ```bash
   make npm-build
   ```
2. The frontend will be available at [http://localhost:3000](http://localhost:3000)
