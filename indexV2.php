<?php
$backendAddress = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Layout</title>
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
                <img src="https://ds.seisho.us/svg/powerPal_header.svg" alt="PowerPal Logo" height="40">
            </div>
            <!-- Sidebar for desktop -->
            <div id="sidebar" class="no-gutter nav-container" style="max-width: 120px;">
                <a class="nav-link active" href="#"> <i class="p25 fa-solid fa-tachometer-alt-fast"></i><br>Devices</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-bolt"></i><br>Energy</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-gear"></i><br>Settings</a>

                <a class="nav-link" href="#"> <i class="p25 fa-solid fa-circle-question"></i><br>Support</a>
            </div>

            <!-- Main content area -->
            <main class="col p-4">
                <h1 class="sectionTitle mb-4">Devices</h1>
                <div class="row g-2 cards-grid">
                    <div class="col-12 col-md card-container">
                        <div class="card">
                            <div class="card-body2 device-card" device="AC1518D6640C" channel="33">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Street Illumination</span>
                                    <div class="metric">
                                        <table class="metric-table">
                                            <tr>
                                                <td><i class="fa-solid fa-bolt"></i> 3.1 W</td>
                                                <td><i class="fa-solid fa-lightbulb"></i> <span class="slider-value slider-value-AC1518D6640C-33">50%</span></td>
                                            </tr>
                                            <tr class="desktop-slider">
                                            <td colspan="2">
                                                    <div class="slider-container">
                                                        <input type="range" class="custom-slider" min="0" max="100" value="50"  device="AC1518D6640C" channel="33">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="right">
                                    <button class="power-button blue-border" device="AC1518D6640C" channel="33">
                                        <i class="fa fa-power-off blue-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mobile-slider">
                                    <div class="slider-container">
                                        <input type="range" class="custom-slider" min="0" max="100" value="50"  device="AC1518D6640C" channel="33">
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="col-12 col-md card-container">
                        <div class="card">
                            <div class="card-body2 device-card" device="AC1518D6640C" channel="25" >
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Heat Pump</span>
                                    
                                    <div class="metric">
                                        <table class="metric-table">
                                            <tr>
                                                <td class="metric-cell"><i class="fa-solid fa-bolt"></i> 3.1 W</td>
                                                <td class="metric-cell"><i class="fa-solid fa-temperature-half"></i> 22°C</td>
                                            </tr>
                                            <tr>
                                            <td colspan="2">
                                                    <div class="temperature-controls">
                                                        <button class="temp-button" onclick="adjustTemperature(event, -0.5)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <span class="current-temp">21.5°C</span>
                                                        <button class="temp-button" onclick="adjustTemperature(event, 0.5)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="right">
                                    <button class="power-button green-border" device="AC1518D6640C" channel="25">
                                        <i class="fa fa-power-off green-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md card-container">
                        <div class="card">
                            <div class="card-body2 device-card" device="AC1518D6640C" channel="00">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Device Name</span>
                                    <div class="metric">
                                        <i class="fa-solid fa-link-slash red-icon"></i> offline 1:45
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md card-container">
                        <div class="card">
                            <div class="card-body2 device-card" device="AC1518D6640C" channel="26">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Device Name</span>
                                    <div class="metric">
                                        3.1 W <i class="fa-solid fa-bolt"></i> 231.5 V
                                    </div>
                                </div>
                                <div class="right">
                                    <button class="power-button orange-border" device="AC1518D6640C" channel="26">
                                        <i class="fa fa-power-off orange-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-md card-container">
                        <div class="card" id="device-5">
                            <div class="card-body2 device-card">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Device Name</span>
                                    <div class="metric">
                                        2.4 kW <i class="fa-solid fa-bolt"></i> 231.5 V
                                    </div>
                                </div>
                                <div class="right">
                                    <button class="power-button violet-border">
                                        <i class="fa fa-power-off violet-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md card-container">
                        <div class="card" id="device-4">
                            <div class="card-body2 device-card">
                                <div class="device-image">
                                    <img src="https://ds.seisho.us/img/smartPlug.png" alt="PowerPal Logo">
                                </div>
                                <div class="device-info">
                                    <span class="card-title">Device Name</span>
                                    <div class="metric">
                                        3.1 W <i class="fa-solid fa-bolt"></i> 231.5 V
                                    </div>
                                </div>
                                <div class="right">
                                    <button class="power-button grey-border">
                                        <i class="fa fa-power-off grey-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- Add your main content here -->
            </main>

            <!-- Right info panel -->
            <div id="right-panel" class="col-md-3 col-lg-2 p-0">
                <div style="height: 32px; background-color: #242527; position: relative; border-top-left-radius: 18px;">
                    <button type="button" class="panel-close" aria-label="Close"
                        style="font-size: 26px; position: absolute; right: 10px; top: 5px; background: none; border: none; color: silver;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="p-3">
                    <h3>Info Panel</h3>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Information</h5>
                            <p class="card-text">Additional information can be displayed here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom navigation for mobile -->
    <nav id="bottom-nav" class="navbar navbar-expand navbar-light" style="margin-bottom: 0; padding-bottom: 0; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 95%;">
        <div class="container-fluid justify-content-around nav-container" style="background-color: var(--background-image-overlay-blur); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); border: 0.7px solid var(--color-green-light); margin-bottom: 0; border-top-right-radius: 10px; border-top-left-radius: 10px;">
            <a class="nav-link active" style="width: 25%;"><i class="p25 fa-solid fa-tachometer-alt-fast"></i><br>Devices</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            
            const rightPanel = document.getElementById('right-panel');
            const mainContent = document.querySelector('main');
            const closeButton = document.querySelector('.panel-close');
            closeButton.addEventListener('click', togglePanel);

            function togglePanel(deviceId, channel) {
                rightPanel.classList.toggle('show');
                if (window.innerWidth >= 768) {
                    mainContent.classList.toggle('panel-visible');
                }
                updateRightPanelInfo(deviceId, channel);
            }

            function updateRightPanelInfo(deviceId, channel) {
                // Check if deviceId and channel are provided
                if (deviceId && channel) {
                    // Make an AJAX GET request to fetch device information
                    fetch(`${backendAddress}ui/info.php?deviceId=${deviceId}&channel=${channel}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Device info received:', data);
                            // Here you can update the right panel with the received data
                            // For example, update device name, status, and other information
                        })
                        .catch(error => {
                            console.error('Error fetching device info:', error);
                        });
                } else {
                    console.log('No device ID or channel provided for info panel');
                }
            }
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
            document.addEventListener('click', function (event) {
                const isClickInside = rightPanel.contains(event.target) || mainContent.contains(event.target);
                if (!isClickInside && rightPanel.classList.contains('show')) {
                    togglePanel();
                }
            });

            // Add power button functionality
            const powerButtons = document.querySelectorAll('.power-button');

            powerButtons.forEach(button => {
                button.addEventListener('click', function (e) {
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

            // Add polling function
            function pollDeviceStatus() {
                fetch('//127.0.0.1/ui/status.php')
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

                                // Update power button color
                                if (powerButton) {
                                    // Remove all existing color classes
                                    powerButton.classList.remove('blue-border', 'green-border', 'grey-border', 'orange-border', 'violet-border');
                                    powerButton.querySelector('i').classList.remove('blue-icon', 'green-icon', 'grey-icon', 'orange-icon', 'violet-icon');
                                    
                                    // Add new color class
                                    powerButton.classList.add(`${deviceData.color}-border`);
                                    powerButton.querySelector('i').classList.add(`${deviceData.color}-icon`);
                                }

                                // Handle dimmable devices
                                if (deviceData.isDimmable) {
                                    // Update all slider value displays for this device/channel
                                    const sliderValueDisplays = document.getElementsByClassName(`slider-value-${deviceId}-${channel}`);
                                    for (let display of sliderValueDisplays) {
                                        display.textContent = `${deviceData.duty}%`;
                                    }

                                    // Update all sliders for this device/channel
                                    const sliders = document.querySelectorAll(`.custom-slider[device="${deviceId}"][channel="${channel}"]`);
                                    sliders.forEach(slider => {
                                        slider.value = deviceData.duty;
                                    });
                                }

                                // Update metric information
                                if (metricDiv) {
                                    if (deviceData.offline) {
                                        // Show offline status if it's offline
                                        metricDiv.innerHTML = `<i class="fa-solid fa-link-slash red-icon"></i> offline ${deviceData.offline}`;
                                    } else {
                                        // Construct metric content
                                        let metricContent = `${deviceData.power}W <i class="fa-solid fa-bolt"></i>`;
                                        
                                        // Add duty information if present and greater than 0
                                        if (deviceData.duty && deviceData.duty > 0) {
                                            metricContent += ` <i class="fa-regular fa-lightbulb"></i> ${deviceData.duty}%`;
                                        }
                                        
                                        metricDiv.innerHTML = metricContent;
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error polling device status:', error));
            }

            // Poll every 2 seconds
            setInterval(pollDeviceStatus, 2000);
            
            // Initial poll
            pollDeviceStatus();

            // Add this to your existing DOMContentLoaded event listener
            const sliders = document.querySelectorAll('.custom-slider');
            sliders.forEach(slider => {
                slider.addEventListener('input', function(e) {
                    // Debounce the slider input to prevent too many requests
                    if (this.debounceTimeout) {
                        clearTimeout(this.debounceTimeout);
                    }
                    
                    // Update the display immediately for responsive UI
                    const currentValue = e.target.value;
                    const deviceId = slider.getAttribute('device');
                    const channel = slider.getAttribute('channel');

                    const valueDisplay = document.getElementsByClassName(`slider-value-${deviceId}-${channel}`);
                    for (let i = 0; i < valueDisplay.length; i++) {
                        valueDisplay[i].textContent = currentValue + '%';
                    }
                    
                    // Set a timeout to actually send the request after user stops sliding
                    this.debounceTimeout = setTimeout(() => {

                        
                        // Send the update to the server
                        fetch(`${backendAddress}ui/update.php?deviceId=${deviceId}&channel=${channel}&duty=${currentValue}`)
                            .then(response => response.json())
                            .then(data => console.log('Duty updated:', data))
                            .catch(error => console.error('Error updating duty:', error));
                    }, 300); // 300ms debounce delay
                });

                
                // Prevent the card click event when interacting with the slider
                slider.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });

    function adjustTemperature(e, delta) {
        e.stopPropagation(); // Prevent event bubbling
        
        const tempSpan = document.querySelector('.current-temp');
        let currentTemp = parseFloat(tempSpan.textContent);
        currentTemp += delta;
        // Round to nearest 0.5
        currentTemp = Math.round(currentTemp * 2) / 2;
        tempSpan.textContent = currentTemp.toFixed(1) + '°C';
        
        // Here you would also want to send this new temperature to your backend
        const deviceId = 'AC1518D6640C';
        const channel = '25';
        fetch(`//127.0.0.1/ui/update.php?deviceId=${deviceId}&channel=${channel}&temperature=${currentTemp}`)
            .then(response => response.json())
            .then(data => console.log('Temperature updated:', data))
            .catch(error => console.error('Error updating temperature:', error));
    }   
    </script>
</body>

</html>