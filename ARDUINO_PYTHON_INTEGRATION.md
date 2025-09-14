# Arduino-Python-Laravel Integration Setup

This guide explains how to set up the Arduino bottle detection system with Python listener and Laravel backend.

## 🏗️ System Architecture

```
Arduino (ESP8266 + IR Sensor) → Python Listener → Laravel API → Database
```

## 📋 Prerequisites

1. **Hardware:**
   - ESP8266 development board
   - IR sensor (for bottle detection)
   - Jumper wires
   - Breadboard (optional)

2. **Software:**
   - Arduino IDE
   - Python 3.7+
   - Laravel application running
   - Serial port access

## 🔧 Hardware Setup

### ESP8266 Pin Connections:
- **IR Sensor VCC** → 3.3V or 5V
- **IR Sensor GND** → GND
- **IR Sensor OUT** → D2 (with pull-up resistor)

### IR Sensor Configuration:
- The sensor should output HIGH when no bottle is present
- The sensor should output LOW when a bottle is detected
- Adjust sensor sensitivity as needed

## 💻 Software Setup

### 1. Arduino Code Setup

1. Open `arduino_code/esp8266_bottle_detector.ino` in Arduino IDE
2. Install ESP8266 board support if not already installed
3. Update configuration variables:
   ```cpp
   const char* ssid = "YOUR_WIFI_SSID";
   const char* password = "YOUR_WIFI_PASSWORD";
   const char* serverURL = "http://YOUR_LARAVEL_SERVER:8000";
   const char* deviceId = "ESP8266_001";
   int userId = YOUR_USER_ID;
   ```
4. Upload code to ESP8266

### 2. Python Listener Setup

1. Install Python dependencies:
   ```bash
   pip install -r requirements.txt
   ```

2. Update configuration in `python_arduino_listener.py`:
   ```python
   ARDUINO_PORT = "COM3"  # Windows: COM3, Linux/Mac: /dev/ttyUSB0
   LARAVEL_URL = "http://localhost:8000"
   USER_ID = 1  # Your user ID
   ```

3. Run the Python listener:
   ```bash
   python python_arduino_listener.py
   ```

### 3. Laravel API Setup

The Laravel API endpoints are already configured:

- `GET /api/check-login/{userId}` - Check if user is logged in
- `POST /api/bottle-inserted` - Record bottle insertion
- `GET /api/session-status` - Get session status

## 🚀 Usage

### Starting the System:

1. **Start Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Run Python listener:**
   ```bash
   python python_arduino_listener.py
   ```

3. **Power on Arduino** - it will automatically connect to WiFi and authenticate

### Testing:

1. Insert a bottle into the sensor area
2. Check Python console for detection messages
3. Check Laravel application for updated points
4. View transactions in the web interface

## 🔍 Troubleshooting

### Arduino Issues:
- **WiFi Connection Failed:** Check SSID and password
- **Authentication Failed:** Verify server URL and user ID
- **No Bottle Detection:** Check sensor wiring and sensitivity

### Python Issues:
- **Serial Port Error:** Check COM port and permissions
- **API Connection Failed:** Verify Laravel server is running
- **User Not Found:** Check user ID exists in database

### Laravel Issues:
- **API Routes Not Working:** Run `php artisan route:clear`
- **Database Errors:** Run `php artisan migrate`
- **CORS Issues:** Check API middleware configuration

## 📊 API Endpoints

### Check User Login
```
GET /api/check-login/{userId}
Response: {"logged_in": true, "user_id": 1, "user_name": "John Doe"}
```

### Record Bottle Insertion
```
POST /api/bottle-inserted
Body: {"user_id": 1}
Response: {"success": true, "data": {"points_awarded": 10}}
```

### Get Session Status
```
GET /api/session-status?user_id=1
Response: {"logged_in": true, "total_points": 50}
```

## 🔧 Configuration

### Environment Variables (.env):
```env
POINTS_PER_BOTTLE=10
APP_URL=http://localhost:8000
```

### Arduino Configuration:
- Adjust `DETECTION_COOLDOWN` for sensor sensitivity
- Modify `IR_SENSOR_PIN` if using different pin
- Update `deviceId` for multiple devices

### Python Configuration:
- Change `ARDUINO_PORT` for different serial ports
- Adjust `timeout` values for network requests
- Modify logging levels as needed

## 📈 Monitoring

### Python Console Output:
```
🍾 Arduino Bottle Detection Listener
========================================
👤 User ID set to: 1
✅ Connected to Arduino on COM3
✅ User John Doe is logged in
🔄 Listening for bottle detection...
[14:30:15] Arduino: SENSOR_READY
[14:30:20] Arduino: BOTTLE_DETECTED
🍾 Bottle detected!
✅ User John Doe is logged in
✅ Bottle recorded! +10 points (Total: 60)
```

### Laravel Logs:
Check `storage/logs/laravel.log` for API requests and errors.

## 🔒 Security Notes

- Change default device IDs for production
- Use HTTPS in production environment
- Implement proper authentication tokens
- Validate all API inputs
- Use environment variables for sensitive data

## 📝 Next Steps

1. **Multiple Devices:** Support multiple Arduino devices
2. **Real-time Updates:** WebSocket integration for live updates
3. **Device Management:** Web interface for device configuration
4. **Analytics:** Bottle detection statistics and reporting
5. **Notifications:** Email/SMS alerts for system events

