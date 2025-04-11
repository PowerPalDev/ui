<?php
header('Content-Type: application/json');

// Function to generate random power value between 1 and 3000
function getRandomPower() {
    return round(rand(100, 3000) / 10, 1);
}

// Function to generate random voltage around 230V
function getRandomVoltage() {
    return round(rand(2290, 2330) / 10, 1);
}

// Function to get random duty cycle (0-100)
function getRandomDuty() {
    return rand(0, 100);
}

// Function to get random color (blue, green, orange, grey)
function getRandomColor() {
    $colors = ['blue', 'green', 'orange', 'grey'];
    return $colors[array_rand($colors)];
}

// Simulate occasional offline status
function getOfflineStatus() {
    if (rand(1, 10) === 1) { // 10% chance to be offline
        $minutes = rand(1, 60);
        return "$minutes:00";
    }
    return false;
}

// Create response data structure
$response = [
    'AC1518D6640C' => [
        '33' => [
            'offline' => getOfflineStatus(),
            'color' => getRandomColor(),
            'power' => getRandomPower(),
            'tension' => getRandomVoltage(),
            'state' => rand(0, 1) ? 'on' : 'off',
            'duty' => getRandomDuty()
        ],
        '25' => [
            'offline' => getOfflineStatus(),
            'color' => getRandomColor(),
            'power' => getRandomPower(),
            'tension' => getRandomVoltage(),
            'state' => rand(0, 1) ? 'on' : 'off',
            'duty' => getRandomDuty()
        ],
        '26' => [
            'offline' => getOfflineStatus(),
            'color' => getRandomColor(),
            'power' => getRandomPower(),
            'tension' => getRandomVoltage(),
            'state' => rand(0, 1) ? 'on' : 'off',
            'duty' => getRandomDuty()
        ]
    ]
];

// Add random delay to simulate network latency (50-200ms)
usleep(rand(50000, 200000));

// Return JSON response
echo json_encode($response);
?>