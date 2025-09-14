#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

// ===== CONFIGURATION =====
const char* ssid = "YOUR_WIFI_SSID";           // Change this to your WiFi name
const char* password = "YOUR_WIFI_PASSWORD";   // Change this to your WiFi password
const char* serverURL = "http://localhost:8000"; // Change this to your Laravel server URL
const char* deviceId = "ESP8266_001";          // Change this to your device ID
int userId = 1;                                // Change this to your user ID

// ===== HARDWARE PINS =====
const int IR_SENSOR_PIN = D2;  // IR sensor connected to D2
const int LED_PIN = D4;        // Built-in LED for status indication

// ===== VARIABLES =====
bool sensorReady = false;
bool lastSensorState = HIGH;
bool currentSensorState = HIGH;
unsigned long lastDetectionTime = 0;
const unsigned long DETECTION_COOLDOWN = 2000; // 2 seconds cooldown between detections

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  // Initialize pins
  pinMode(IR_SENSOR_PIN, INPUT_PULLUP);
  pinMode(LED_PIN, OUTPUT);
  
  // Turn on LED to indicate startup
  digitalWrite(LED_PIN, HIGH);
  
  Serial.println("🍾 Bottle Detection System Starting...");
  Serial.println("=" * 40);
  
  // Connect to WiFi
  connectToWiFi();
  
  // Authenticate with Laravel
  authenticateWithLaravel();
  
  // Initialize sensor
  lastSensorState = digitalRead(IR_SENSOR_PIN);
  sensorReady = true;
  
  Serial.println("SENSOR_READY");
  Serial.println("🔄 System ready - waiting for bottles...");
  
  // Turn off LED to indicate ready
  digitalWrite(LED_PIN, LOW);
}

void loop() {
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("ERROR_WIFI_DISCONNECTED");
    connectToWiFi();
    return;
  }
  
  // Read sensor state
  currentSensorState = digitalRead(IR_SENSOR_PIN);
  
  // Check for bottle detection (sensor goes LOW when bottle is detected)
  if (currentSensorState == LOW && lastSensorState == HIGH) {
    unsigned long currentTime = millis();
    
    // Check cooldown period
    if (currentTime - lastDetectionTime > DETECTION_COOLDOWN) {
      detectBottle();
      lastDetectionTime = currentTime;
    }
  }
  
  lastSensorState = currentSensorState;
  delay(50); // Small delay for stability
}

void connectToWiFi() {
  Serial.print("📡 Connecting to WiFi: ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println();
    Serial.println("✅ WiFi connected!");
    Serial.print("📡 IP address: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println();
    Serial.println("❌ WiFi connection failed");
    Serial.println("ERROR_WIFI_CONNECTION_FAILED");
  }
}

void authenticateWithLaravel() {
  Serial.println("🔐 Authenticating with Laravel...");
  
  WiFiClient client;
  HTTPClient http;
  
  String authURL = String(serverURL) + "/api/arduino/authenticate";
  
  http.begin(client, authURL);
  http.addHeader("Content-Type", "application/json");
  
  // Create authentication payload
  String payload = "{";
  payload += "\"device_id\":\"" + String(deviceId) + "\",";
  payload += "\"user_id\":" + String(userId);
  payload += "}";
  
  Serial.println("📤 Sending authentication request...");
  int httpResponseCode = http.POST(payload);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.print("📥 Response code: ");
    Serial.println(httpResponseCode);
    Serial.print("📥 Response: ");
    Serial.println(response);
    
    if (httpResponseCode == 200) {
      Serial.println("✅ Authentication successful!");
    } else {
      Serial.println("❌ Authentication failed");
      Serial.println("ERROR_AUTHENTICATION_FAILED");
    }
  } else {
    Serial.print("❌ HTTP request failed: ");
    Serial.println(httpResponseCode);
    Serial.println("ERROR_HTTP_REQUEST_FAILED");
  }
  
  http.end();
}

void detectBottle() {
  Serial.println("🍾 BOTTLE_DETECTED");
  
  // Flash LED to indicate detection
  digitalWrite(LED_PIN, HIGH);
  delay(100);
  digitalWrite(LED_PIN, LOW);
  
  // Send detection to Laravel
  sendBottleDetection();
}

void sendBottleDetection() {
  Serial.println("📤 Sending bottle detection to Laravel...");
  
  WiFiClient client;
  HTTPClient http;
  
  String bottleURL = String(serverURL) + "/api/arduino/detectBottle";
  
  http.begin(client, bottleURL);
  http.addHeader("Content-Type", "application/json");
  
  // Create bottle detection payload
  String payload = "{";
  payload += "\"device_id\":\"" + String(deviceId) + "\",";
  payload += "\"user_id\":" + String(userId);
  payload += "}";
  
  int httpResponseCode = http.POST(payload);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.print("📥 Response code: ");
    Serial.println(httpResponseCode);
    Serial.print("📥 Response: ");
    Serial.println(response);
    
    if (httpResponseCode == 200) {
      Serial.println("✅ Bottle detection sent successfully!");
    } else {
      Serial.println("❌ Bottle detection failed");
      Serial.println("ERROR_BOTTLE_DETECTION_FAILED");
    }
  } else {
    Serial.print("❌ HTTP request failed: ");
    Serial.println(httpResponseCode);
    Serial.println("ERROR_HTTP_REQUEST_FAILED");
  }
  
  http.end();
}

void checkSessionStatus() {
  Serial.println("🔍 Checking session status...");
  
  WiFiClient client;
  HTTPClient http;
  
  String statusURL = String(serverURL) + "/api/arduino/session-status";
  statusURL += "?device_id=" + String(deviceId) + "&user_id=" + String(userId);
  
  http.begin(client, statusURL);
  
  int httpResponseCode = http.GET();
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.print("📥 Session status: ");
    Serial.println(response);
  } else {
    Serial.print("❌ Session check failed: ");
    Serial.println(httpResponseCode);
  }
  
  http.end();
}


