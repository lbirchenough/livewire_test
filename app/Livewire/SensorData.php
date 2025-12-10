<?php

namespace App\Livewire;

use App\Models\SensorData as SensorDataModel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SensorData extends Component
{
    public $sensorData;
    public array $sensorChart = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->sensorData = SensorDataModel::orderBy('time', 'asc')->get();
        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        // Separate data by sensor_name
        $sensor1Data = $this->sensorData->where('sensor_name', 1)->values();
        $sensor2Data = $this->sensorData->where('sensor_name', 2)->values();

        // Get all unique times and sort them
        $allTimes = $this->sensorData->pluck('time')->unique()->sort()->values();

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
                        'max' => 150,
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
