<?php

namespace App\Livewire;

use App\Models\SensorData as SensorDataModel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SensorData extends Component
{
    public array $sensorChart = [];
    public array $switchChart = [];
    public int $pollInterval = 30;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $sensorData = SensorDataModel::where('time', '>=', now()->subHour())
            ->where('type', 'distance')
            ->orderBy('time', 'asc')
            ->get();
        $this->prepareChartData($sensorData);

        $switchData = SensorDataModel::where('time', '>=', now()->subHour())
            ->where('type', 'switch')
            ->orderBy('time', 'asc')
            ->get();
        $this->prepareSwitchChartData($switchData);
    }

    public function prepareChartData($sensorData)
    {
        // Separate data by sensor_name
        $sensor1Data = $sensorData->where('sensor_name', 1)->values();
        $sensor2Data = $sensorData->where('sensor_name', 2)->values();

        // Get all unique times and sort them
        $allTimes = $sensorData->pluck('time')->unique()->sort()->values();

        // Prepare data arrays for each sensor (matching the time labels)
        $sensor1Values = [];
        $sensor2Values = [];

        foreach ($allTimes as $time) {
            $sensor1Value = $sensor1Data->firstWhere('time', $time);
            $sensor2Value = $sensor2Data->firstWhere('time', $time);

            $sensor1Values[] = $sensor1Value ? $sensor1Value->value : null;
            $sensor2Values[] = $sensor2Value ? $sensor2Value->value : null;
        }

        $this->sensorChart = [
            'type' => 'line',
            'data' => [
                'labels' => $allTimes->map(fn($time) => date('Y-m-d H:i', strtotime($time)))->toArray(),
                'datasets' => [
                    [
                        'label' => 'Sensor 1',
                        'data' => $sensor1Values,
                        'borderColor' => 'rgb(239, 68, 68)', // red
                        'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                        'fill' => false,
                        'tension' => 0.1, // Smooth lines between points
                        'borderWidth' => 2,
                        'showLine' => true,
                        'spanGaps' => true,
                    ],
                    [
                        'label' => 'Sensor 2',
                        'data' => $sensor2Values,
                        'borderColor' => 'rgb(59, 130, 246)', // blue
                        'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                        'fill' => false,
                        'tension' => 0.1, // Smooth lines between points
                        'borderWidth' => 2,
                        'showLine' => true,
                        'spanGaps' => true,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'elements' => [
                    'line' => [
                        'tension' => 0.1,
                    ],
                    'point' => [
                        'radius' => 3,
                        'hoverRadius' => 5,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'min' => 0,
                        'max' => 100,
                        'title' => [
                            'display' => true,
                            'text' => 'cm',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function prepareSwitchChartData($switchData)
    {
        $sensor1Data = $switchData->where('sensor_name', 1)->values();
        $sensor2Data = $switchData->where('sensor_name', 2)->values();

        $allTimes = $switchData->pluck('time')->unique()->sort()->values();

        $sensor1Values = [];
        $sensor2Values = [];

        foreach ($allTimes as $time) {
            $s1 = $sensor1Data->firstWhere('time', $time);
            $s2 = $sensor2Data->firstWhere('time', $time);

            $sensor1Values[] = $s1 ? (int) $s1->value : null;
            $sensor2Values[] = $s2 ? (int) $s2->value : null;
        }

        $this->switchChart = [
            'type' => 'line',
            'data' => [
                'labels' => $allTimes->map(fn($time) => date('Y-m-d H:i', strtotime($time)))->toArray(),
                'datasets' => [
                    [
                        'label' => 'Sensor 1',
                        'data' => $sensor1Values,
                        'borderColor' => 'rgb(239, 68, 68)',
                        'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                        'fill' => true,
                        'stepped' => 'after',
                        'borderWidth' => 2,
                        'spanGaps' => true,
                    ],
                    [
                        'label' => 'Sensor 2',
                        'data' => $sensor2Values,
                        'borderColor' => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'fill' => true,
                        'stepped' => 'after',
                        'borderWidth' => 2,
                        'spanGaps' => true,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'elements' => [
                    'point' => [
                        'radius' => 3,
                        'hoverRadius' => 5,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'min' => 0,
                        'max' => 1,
                        'ticks' => [
                            'stepSize' => 1,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.sensor-data');
    }
}
