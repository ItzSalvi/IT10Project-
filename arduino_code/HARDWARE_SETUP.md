# Arduino Hardware Setup Guide

## Required Components

1. **ESP8266 Development Board** (NodeMCU, Wemos D1 Mini, or similar)
2. **IR Obstacle Sensor** (IR Proximity Sensor)
3. **LED** (Built-in LED on ESP8266 or external LED)
4. **Buzzer** (Optional - for audio feedback)
5. **Breadboard and Jumper Wires**
6. **Power Supply** (USB cable or external power)

## Wiring Diagram

```
ESP8266 Pinout:
┌─────────────────┐
│ 3.3V  GND  D1   │
│  D2   D3   D4   │
│  D5   D6   D7   │
│  D8   D9   D10  │
└─────────────────┘

Connections:
- IR Sensor VCC → 3.3V (or 5V if sensor supports it)
- IR Sensor GND → GND
- IR Sensor OUT → D2 (GPIO4)
- LED → D4 (GPIO2) - Built-in LED
- Buzzer → D5 (GPIO14) - Optional
```

## Step-by-Step Setup

### 1. Hardware Assembly
1. Place ESP8266 on breadboard
2. Connect IR sensor:
   - VCC to 3.3V
   - GND to GND
   - OUT to D2 (GPIO4)
3. Connect buzzer (optional):
   - Positive to D5 (GPIO14)
   - Negative to GND
4. Built-in LED on D4 (GPIO2) will be used automatically

### 2. IR Sensor Calibration
1. Power on the device
2. Check serial monitor for sensor readings
3. Adjust sensor sensitivity if needed
4. Test with a bottle to ensure proper detection

### 3. WiFi Configuration
1. Update WiFi credentials in Arduino code:
   ```cpp
   const char* ssid = "YOUR_WIFI_SSID";
   const char* password = "YOUR_WIFI_PASSWORD";
   ```

### 4. Server Configuration
1. Update server URL in Arduino code:
   ```cpp
   const char* serverURL = "http://YOUR_SERVER_IP:8000";
   ```

### 5. User Configuration
1. Set your user ID in Arduino code:
   ```cpp
   int userId = YOUR_USER_ID;
   ```

### 6. Device ID
1. Set unique device ID:
   ```cpp
   const char* deviceId = "ESP8266_001";
   ```

## Testing the Setup

### 1. Upload Code
1. Open Arduino IDE
2. Install ESP8266 board package
3. Select correct board and port
4. Upload the code

### 2. Monitor Serial Output
1. Open Serial Monitor (115200 baud)
2. Check for WiFi connection
3. Verify authentication with Laravel
4. Test bottle detection

### 3. Expected Serial Output
```
Bottle Recycling System Starting...
Device ID: ESP8266_001
Connecting to WiFi: YOUR_WIFI_SSID
WiFi connected!
IP address: 192.168.1.100
Authenticating with Laravel...
Authentication successful!
User: John Doe
Current Points: 150
Points per Bottle: 10
System Ready!
```

## Troubleshooting

### Common Issues

1. **WiFi Connection Failed**
   - Check WiFi credentials
   - Ensure WiFi network is 2.4GHz (ESP8266 doesn't support 5GHz)
   - Check signal strength

2. **Authentication Failed**
   - Verify server URL is correct
   - Check if Laravel server is running
   - Ensure user ID exists in database

3. **Bottle Detection Not Working**
   - Check IR sensor connections
   - Adjust sensor sensitivity
   - Verify sensor is working (test with multimeter)

4. **HTTP Errors**
   - Check network connectivity
   - Verify server is accessible
   - Check Laravel logs for errors

### Serial Commands for Testing

Send these commands via Serial Monitor:
- `detect` - Manually trigger bottle detection
- `auth` - Re-authenticate with server
- `status` - Show current device status

## Safety Notes

1. **Power Supply**: Use appropriate power supply (3.3V or 5V)
2. **Connections**: Double-check all connections before powering on
3. **Environment**: Keep electronics away from moisture
4. **Maintenance**: Regularly clean IR sensor for optimal performance

## Advanced Configuration

### Adjusting Detection Sensitivity
Modify the debounce delay in Arduino code:
```cpp
unsigned long debounceDelay = 500; // Increase for less sensitive detection
```

### Adding More Sensors
You can add multiple IR sensors for better detection:
```cpp
const int IR_SENSOR_2_PIN = 5; // Additional sensor
```

### Custom LED Patterns
Modify the LED feedback functions:
```cpp
void blinkLED(int times, int delayMs) {
    // Custom LED patterns
}
```

## Support

For technical support:
1. Check Arduino IDE serial monitor for error messages
2. Verify all connections match the wiring diagram
3. Test individual components separately
4. Check Laravel application logs for API errors



