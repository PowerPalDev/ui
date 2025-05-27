<?php
require_once 'function.php';
$backendAddress = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/";

$sql = "SELECT * FROM channel";
$result = DB()->query($sql);
$channels = $result->fetch_all(MYSQLI_ASSOC);

//now compose the table according the channel table
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ Renew</title>
    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css.css?<?php echo time(); ?>">
    <script>
    const backendAddress = "<?php echo $backendAddress; ?>";
    </script>
</head>

<body>
    <div class="background-image"></div>
    <!-- Add backdrop div -->
    <div class="panel-backdrop"></div>

    <div class="container-fluid">
        <div class="row">
            <div class="logo-container">
                <img src="https://seisho.us/ui/renewLogo.png" alt="PowerPal Logo" height="75">
            </div>
            <!-- Sidebar for desktop -->
            <div id="sidebar" class="no-gutter nav-container" style="max-width: 120px;">
                <a class="nav-link active" href="#"> <i class="p25 fa-solid fa-tachometer-alt-fast"></i><br>Devices</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-bolt"></i><br>Energy</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-gear"></i><br>Settings</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-circle-question"></i><br>Support</a>
            </div>

            <!-- Main content area -->
            <main class="col p-2">
                <h1 class="sectionTitle mb-4"></h1>
                <div class="row g-2 cards-grid">
                    <?php foreach ($channels as $device): 
                        $chanDevCode = "device='" . htmlspecialchars($device['deviceId']) . "' channel='" . htmlspecialchars($device['channel']) . "'";
                    ?>
                    <div class="col-12 col-md card-container">
                        <div class="card">
                            <div class="card-body2 device-card"
                                device="<?php echo htmlspecialchars($device['deviceId']); ?>"
                                channel="<?php echo htmlspecialchars($device['channel']); ?>">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title"><?php echo htmlspecialchars($device['name']); ?></span>
                                    <div class="metric">
                                        <?php if ($device['type'] == "thermostat"): ?>
                                        <!-- Thermostat -->
                                        <table class="metric-table">
                                            <tr>
                                                <td class="powerReading metric-cell"><i class="fa-solid fa-bolt"></i>
                                                    <?php echo htmlspecialchars($device['power']); ?> W</td>
                                                <td class="temperatureReading metric-cell"><i
                                                        class="fa-solid fa-temperature-half"></i>
                                                    <?php echo htmlspecialchars($device['currentTemp']); ?>°C</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="temperature-controls">
                                                        <button class="temp-button"
                                                            onclick="adjustTemperature(event, -0.5)"
                                                            <?php echo $chanDevCode; ?>>
                                                            <i class="fas fa-minus" <?php echo $chanDevCode; ?>></i>
                                                        </button>
                                                        <span class="targetTemp"
                                                            <?php echo $chanDevCode; ?>><?php echo htmlspecialchars($device['targetTemp']); ?>°C</span>
                                                        <button class="temp-button"
                                                            onclick="adjustTemperature(event, 0.5)"
                                                            <?php echo $chanDevCode; ?>>
                                                            <i class="fas fa-plus" <?php echo $chanDevCode; ?>></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Thermostat -->


                                        <?php elseif ($device['type'] == "light"): ?>
                                        <!-- Dimmable -->
                                        <table class="metric-table">
                                            <tr class="metric-row">
                                                <td class="powerReading"></td>
                                                <td class="dutyReading"></td>
                                            </tr>
                                            <tr class="desktop-slider">
                                                <td colspan="2">
                                                    <div class="slider-container">
                                                        <input type="range" class="custom-slider" min="0" max="100"
                                                            value="<?php echo htmlspecialchars($device['duty']); ?>"
                                                            <?php echo $chanDevCode; ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Dimmable -->

                                        <?php else: ?>
                                        <!-- Power -->
                                        <table class="metric-table">
                                            <tr class="metric-row">
                                                <td class="powerReading"><i class="fa-solid fa-bolt"></i>
                                                    <?php echo htmlspecialchars($device['power']); ?> W</td>
                                            </tr>
                                        </table>
                                        <!-- End Power -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="right">
                                    <?php 
                                    $buttonColor = $device['state'] ? $device['color'] : 'grey';
                                    ?>
                                    <button class="power-button <?php echo $buttonColor; ?>-border"
                                        <?php echo $chanDevCode; ?>>
                                        <i class="fa fa-power-off <?php echo $buttonColor; ?>-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <?php if ($device['type'] == "light"): ?>
                            <div class="mobile-slider">
                                <div class="slider-container">
                                    <input type="range" class="custom-slider" min="0" max="100"
                                        value="<?php echo htmlspecialchars($device['duty']); ?>"
                                        device="<?php echo htmlspecialchars($device['deviceId']); ?>"
                                        channel="<?php echo htmlspecialchars($device['channel']); ?>">
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Add your main content here -->
            </main>
        </div>
    </div>

    <!-- Bottom navigation for mobile -->
    <nav id="bottom-nav" class="navbar navbar-expand navbar-light"
        style="margin-bottom: 0; padding-bottom: 0; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 95%;">
        <div class="container-fluid justify-content-around nav-container"
            style="background-color: var(--background-image-overlay-blur); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border: 0.7px solid var(--color-green-light); margin-bottom: 0; border-top-right-radius: 10px; border-top-left-radius: 10px;">
            <a class="nav-link active" style="width: 25%;"><i
                    class="p25 fa-solid fa-tachometer-alt-fast"></i><br>Devices</a>
            <a class="nav-link" style="width: 25%;"><i class="p25 fa-solid fa-bolt"></i><br>Energy</a>
            <a class="nav-link" style="width: 25%;"><i class="p25 fa-solid fa-gear"></i><br>Settings</a>
            <a class="nav-link" style="width: 25%;"><i class="p25 fa-solid fa-circle-question"></i><br>Support</a>
        </div>
    </nav>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        //const rightPanel = document.getElementById('right-panel');
        //const mainContent = document.querySelector('main');
        //const closeButton = document.querySelector('.panel-close');

        //closeButton.addEventListener('click', togglePanel);

        // function togglePanel(deviceId, channel) {
        //     rightPanel.classList.toggle('show');
        //     if (window.innerWidth >= 768) {
        //         mainContent.classList.toggle('panel-visible');
        //     }
        //     updateRightPanelInfo(deviceId, channel);
        // }

        // function updateRightPanelInfo(deviceId, channel) {
        //     // Check if deviceId and channel are provided
        //     if (deviceId && channel) {
        //         // Make an AJAX GET request to fetch device information
        //         fetch(`${backendAddress}ui/info.php?deviceId=${deviceId}&channel=${channel}`)
        //             .then(response => {
        //                 if (!response.ok) {
        //                     throw new Error('Network response was not ok');
        //                 }
        //                 return response.json();
        //             })
        //             .then(data => {
        //                 console.log('Device info received:', data);
        //                 // Here you can update the right panel with the received data
        //                 // For example, update device name, status, and other information
        //             })
        //             .catch(error => {
        //                 console.error('Error fetching device info:', error);
        //             });
        //     } else {
        //         console.log('No device ID or channel provided for info panel');
        //     }
        // }
        // Get all device cards
        const deviceCards = document.querySelectorAll('.device-card');
        /*
        // Add click event listener to each device card
        deviceCards.forEach(card => {
            card.addEventListener('click', function() {
                // Get the parent card element to access device and channel attributes
                const parentCard = this.closest('.card');
                const deviceId = parentCard.getAttribute('device');
                const channel = parentCard.getAttribute('channel');
                
                // Log the device info when clicked
                console.log('Device card clicked:', deviceId, channel);
                
                // Toggle the panel
                togglePanel(deviceId, channel);
                
                // You can use deviceId and channel to update the right panel content if needed
                // For example: updateRightPanelInfo(deviceId, channel);
            });
        });
        */

        function turnOnOff(deviceId, channel, state) {
            // Check if deviceId and channel are provided
            if (!deviceId || !channel) {
                console.error('Device ID or channel missing for power control');
                return;
            }

            // Make an AJAX request to control the device
            fetch(`${backendAddress}ui/update.php?deviceId=${deviceId}&channel=${channel}&state=${state}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`Device ${deviceId} channel ${channel} turned ${state}:`, data);
                    // You can add additional handling for the response here if needed
                })
                .catch(error => {
                    console.error(`Error turning ${state} device:`, error);
                });
        }

        function turnOn(deviceId, channel) {
            turnOnOff(deviceId, channel, 'on');
        }

        function turnOff(deviceId, channel) {
            turnOnOff(deviceId, channel, 'off');
        }


        // Close panel when clicking outside (optional)
        document.addEventListener('click', function(event) {
            const isClickInside = rightPanel.contains(event.target) || mainContent.contains(event
                .target);
            if (!isClickInside && rightPanel.classList.contains('show')) {
                togglePanel();
            }
        });

        // Add power button functionality
        const powerButtons = document.querySelectorAll('.power-button');

        powerButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent card click event

                const icon = button.querySelector('i');
                const deviceId = button.getAttribute('device');
                const channel = button.getAttribute('channel');
                console.log('Power button clicked:', deviceId, channel);

                if (button.classList.contains('green-border')) {
                    // Change from green to grey
                    button.classList.remove('green-border');
                    button.classList.add('grey-border');
                    icon.classList.remove('green-icon');
                    icon.classList.add('grey-icon');
                    turnOff(deviceId, channel);
                } else if (button.classList.contains('blue-border')) {
                    button.classList.remove('blue-border');
                    button.classList.add('grey-border');
                    icon.classList.remove('blue-icon');
                    icon.classList.add('grey-icon');
                    turnOff(deviceId, channel);
                } else if (button.classList.contains('violet-border')) {
                    button.classList.remove('violet-border');
                    button.classList.add('grey-border');
                    icon.classList.remove('violet-icon');
                    icon.classList.add('grey-icon');
                    turnOff(deviceId, channel);
                } else if (button.classList.contains('grey-border')) {
                    button.classList.remove('grey-border');
                    icon.classList.remove('grey-icon');
                    button.classList.add('blue-border');
                    icon.classList.add('blue-icon');
                    turnOn(deviceId, channel);
                } else if (button.classList.contains('orange-border')) {
                    button.classList.remove('orange-border');
                    button.classList.add('grey-border');
                    icon.classList.remove('orange-icon');
                    icon.classList.add('grey-icon');
                    turnOff(deviceId, channel);
                } else {
                    console.log('Unknown button class:', button.classList);
                }
            });
        });

        window.pausePolling = false;

        // Add polling function
        function pollDeviceStatus() {
            if (window.pausePolling) {
                return;
            }
            //Disable polling here
            if (window.forcePausePolling) {
                return;
            }
            // return;

            fetch(backendAddress + 'ui/status.php')
                .then(response => response.json())
                .then(data => {
                    // Iterate through each device card
                    document.querySelectorAll('.device-card').forEach(card => {
                        const deviceId = card.getAttribute('device');
                        const channel = card.getAttribute('channel');
                        const metricDiv = card.querySelector('.metric');
                        const powerButton = card.querySelector('.power-button');

                        // Check if we have data for this device and channel
                        if (data[deviceId] && data[deviceId][channel]) {
                            const deviceData = data[deviceId][channel];

                            // Update metric information
                            if (metricDiv) {
                                if (deviceData.offline) {
                                    // Find and update power reading if it exists
                                    const powerReadingElement = card.querySelector('.powerReading');
                                    powerReadingElement.innerHTML =
                                        `<i class="fa-solid fa-link-slash red-icon"></i> offline ${deviceData.offline}`;

                                } else {
                                    // Update power button color
                                    if (powerButton) {
                                        // Remove all existing color classes
                                        powerButton.classList.remove('blue-border', 'green-border',
                                            'grey-border', 'orange-border', 'violet-border');
                                        powerButton.querySelector('i').classList.remove('blue-icon',
                                            'green-icon', 'grey-icon', 'orange-icon',
                                            'violet-icon');

                                        // Add new color class
                                        powerButton.classList.add(`${deviceData.color}-border`);
                                        powerButton.querySelector('i').classList.add(
                                            `${deviceData.color}-icon`);
                                    }

                                    if (deviceData.isThermostat > 0) {
                                        const tempReadingElement = card.querySelector(
                                            '.temperatureReading');
                                        tempReadingElement.innerHTML =
                                            `<i class="fa-solid fa-temperature-half"></i> ${deviceData.currentTemp}°C`;
                                        const targetTempElement = card.querySelector('.targetTemp');
                                        targetTempElement.innerHTML = `${deviceData.targetTemp}°C`;
                                    }

                                    // Handle dimmable devices
                                    if (deviceData.isDimmable > 0) {
                                        const dutyReadingElement = card.querySelector(
                                            '.dutyReading');
                                        dutyReadingElement.innerHTML =
                                            `<i class="fa-regular fa-lightbulb"></i> ${deviceData.duty}%`;

                                        // Update all slider value displays for this device/channel
                                        const sliderValueDisplays = document.getElementsByClassName(
                                            `slider-value-${deviceId}-${channel}`);
                                        for (let display of sliderValueDisplays) {
                                            display.textContent = `${deviceData.duty}%`;
                                        }

                                        // Update all sliders for this device/channel
                                        const sliders = document.querySelectorAll(
                                            `.custom-slider[device="${deviceId}"][channel="${channel}"]`
                                        );
                                        sliders.forEach(slider => {
                                            slider.value = deviceData.duty;
                                        });
                                    }

                                    const powerReadingElement = card.querySelector('.powerReading');
                                    powerReadingElement.innerHTML =
                                        `<i class="fa-solid fa-bolt"></i> ${deviceData.power}W `;

                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error polling device status:', error));
        }


        window.pollInterval = setInterval(pollDeviceStatus, 500);
        window.isDragging = false;

        // Then in the slider event listeners
        const sliders = document.querySelectorAll('.custom-slider');

        sliders.forEach(slider => {
            // Track when user starts dragging
            slider.addEventListener('mousedown', function(e) {
                this.isDragging = true;
                window.isDragging = true;
                // Clear existing interval
                if (window.pollInterval) {
                    clearInterval(window.pollInterval);
                    window.pollInterval = null; // Clear the reference
                    console.log('Polling paused during slider drag');
                }
            });


            slider.addEventListener('touchstart', function(e) {
                this.isDragging = true;
                window.isDragging = true;
                if (window.pollInterval) {
                    clearInterval(window.pollInterval);
                    window.pollInterval = null;
                    console.log('Polling paused during touch drag');
                }
            });

            // Track when user stops dragging
            document.addEventListener('mouseup', function() {
                slider.isDragging = false;
                window.isDragging = false;
                // Only start a new interval if one isn't already running
                if (!window.pollInterval) {
                    window.pollInterval = setInterval(pollDeviceStatus, 1000);
                    console.log('Polling resumed after slider drag');
                }
            });

            document.addEventListener('touchend', function() {
                slider.isDragging = false;
                window.isDragging = false;
                if (!window.pollInterval) {
                    window.pollInterval = setInterval(pollDeviceStatus, 500);
                    console.log('Polling resumed after touch drag');
                }
            });

            // Keep your existing input event listener
            slider.addEventListener('input', function(e) {
                // Debounce the slider input to prevent too many requests
                if (this.debounceTimeout) {
                    clearTimeout(this.debounceTimeout);
                }

                // Update the display immediately for responsive UI
                const currentValue = e.target.value;
                const deviceId = slider.getAttribute('device');
                const channel = slider.getAttribute('channel');

                const valueDisplay = document.getElementsByClassName(
                    `slider-value-${deviceId}-${channel}`);
                for (let i = 0; i < valueDisplay.length; i++) {
                    valueDisplay[i].textContent = currentValue + '%';
                }

                // Set a timeout to actually send the request after user stops sliding
                this.debounceTimeout = setTimeout(() => {
                    // Send the update to the server
                    fetch(
                            `${backendAddress}ui/update.php?deviceId=${deviceId}&channel=${channel}&duty=${currentValue}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            console.log('Duty updated:', data);
                            // Restart polling after update is complete
                            if (!window.pollInterval && !window.isDragging) {
                                window.pollInterval = setInterval(pollDeviceStatus,
                                    500);
                                console.log('Polling resumed after duty update');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating duty:', error);
                            // Restart polling also in case of error
                            if (!window.pollInterval && !window.isDragging) {
                                window.pollInterval = setInterval(pollDeviceStatus,
                                    500);
                                console.log('Polling resumed after duty update');
                            }
                        });
                }, 300); // 300ms debounce delay
            });


            // Prevent the card click event when interacting with the slider
            slider.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Move adjustTemperature function inside DOMContentLoaded
        window.adjustTemperature = function(e, delta) {
            e.stopPropagation(); // Prevent event bubbling

            let deviceId = e.target.getAttribute('device');
            let channel = e.target.getAttribute('channel');


            const tempSpan = document.querySelector('.targetTemp[device="' + deviceId + '"][channel="' +
                channel + '"]');
            let targetTemp = parseFloat(tempSpan.textContent);
            targetTemp += delta;
            // Round to nearest 0.5
            targetTemp = Math.round(targetTemp * 2) / 2;
            tempSpan.textContent = targetTemp.toFixed(1) + '°C';


            window.pausePolling = true;
            if (window.pollInterval) {
                clearInterval(window.pollInterval);
                window.pollInterval = null;
            }
            fetch(
                    `${backendAddress}ui/update.php?deviceId=${deviceId}&channel=${channel}&temperature=${targetTemp}`
                )
                .then(response => response.json())
                .then(data => {
                    console.log('Temperature updated:', data);
                    window.pausePolling = false;
                    // Restart polling after update is complete
                    if (!window.pollInterval && !window.isDragging) {
                        window.pollInterval = setInterval(pollDeviceStatus, 500);
                        console.log('Polling resumed after duty update');
                    }
                })
                .catch(error => {
                    console.error('Error updating temperature:', error);
                    window.pausePolling = false;
                    // Restart polling also in case of error
                    if (!window.pollInterval && !window.isDragging) {
                        window.pollInterval = setInterval(pollDeviceStatus, 500);
                        console.log('Polling resumed after duty update');
                    }
                });
        };
    });
    </script>
</body>

</html>