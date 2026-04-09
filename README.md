# IoT Sensor Dashboard

A Laravel + Livewire web dashboard for visualising real-time IoT sensor data.

**Live demo:** https://iotproject-wamacq4e.on-forge.com/sensor-data
Login: `test@test.com` / `hiroluke123`

## What it does

Displays live charts for data sent by a Raspberry Pi, updating every 30 seconds:

- **Distance sensors** — two ultrasonic sensors plotted as a line chart (0–100 cm, UTC+8)
- **Switch sensors** — two binary switch inputs plotted as a step chart (0/1)

Data covers the last hour and is pushed to this backend via a REST API from the IoT device.

## IoT device

The MicroPython code and other supporting scripts live here: 
https://github.com/lbirchenough/iotproject

## Stack

- **Backend:** Laravel 11, Livewire 3, SQLite
- **Frontend:** Tailwind CSS, Chart.js
- **Hosting:** Laravel Forge
- **Auth:** Session-based login, API key authentication for sensor data ingestion

## Local setup

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=TestUser
```
