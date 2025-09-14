/*
 * Bottle Recycling System - ESP8266 with IR Sensor
 * 
 * This code runs on ESP8266 and communicates with Laravel application
 * to automatically detect bottles and award points to users.
 * 
 * Hardware Requirements:
 * - ESP8266 (NodeMCU or similar)
 * - IR Sensor (IR Obstacle Sensor or IR Proximity Sensor)
 * - LED indicators (optional)
 * - Buzzer (optional)
 * 
 * Connections:
 * - IR Sensor VCC -> 3.3V or 5V
 * - IR Sensor GND -> GND
 * - IR Sensor OUT -> D2 (GPIO4)
 * - LED -> D4 (GPIO2) - Built-in LED
 * - Buzzer -> D5 (GPIO14) - Optional
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ArduinoJson.h>

// WiFi Configuration
const char* ssid = "YOUR_WIFI_SSID";           // Change this to your WiFi name
const char* password = "YOUR_WIFI_PASSWORD";   // Change this to your WiFi password

// Laravel API Configuration
const char* serverURL = "http://127.0.0.1:8000";  // Change this to your Laravel server IP
const char* deviceId = "ESP8266_001";             // Unique device ID
int userId = 1;                                    // Change this to the logged-in user's ID

// Pin Definitions
const int IR_SENSOR_PIN = 4;    // D2 - IR Sensor output
const int LED_PIN = 2;          // D4 - Built-in LED
const int BUZZER_PIN = 14;      // D5 - Buzzer (optional)

// Variables
bool lastSensorState = HIGH;
bool currentSensorState = HIGH;
unsigned long lastDetectionTime = 0;
unsigned long debounceDelay = 500;  // 500ms debounce
int bottleCount = 0;
bool isAuthenticated = false;
unsigned long lastHeartbeat = 0;
unsigned long heartbeatInterval = 30000; // 30 seconds

// HTTP Client
WiFiClient client;
HTTPClient http;

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  // Initialize pins
  pinMode(IR_SENSOR_PIN, INPUT_PULLUP);
  pinMode(LED_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  
  // Turn off LED initially
  digitalWrite(LED_PIN, LOW);
  digitalWrite(BUZZER_PIN, LOW);
  
  Serial.println("Bottle Recycling System Starting...");
  Serial.println("Device ID: " + String(deviceId));
  
  // Connect to WiFi
  connectToWiFi();
  
  // Authenticate with Laravel
  authenticateWithLaravel();
  
  Serial.println("System Ready!");
  blinkLED(3, 200); // 3 quick blinks to indicate ready
}

void loop() {
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi disconnected. Reconnecting...");
    connectToWiFi();
    return;
  }
  
  // Send heartbeat every 30 seconds
  if (millis() - lastHeartbeat > heartbeatInterval) {
    sendHeartbeat();
    lastHeartbeat = millis();
  }
  
  // Read IR sensor
  currentSensorState = digitalRead(IR_SENSOR_PIN);
  
  // Check for bottle detection (sensor goes LOW when bottle detected)
  if (currentSensorState == LOW && lastSensorState == HIGH) {
    // Debounce the detection
    if (millis() - lastDetectionTime > debounceDelay) {
      detectBottle();
      lastDetectionTime = millis();
    }
  }
  
  lastSensorState = currentSensorState;
  
  delay(50); // Small delay to prevent overwhelming the system
}

void connectToWiFi() {
  Serial.print("Connecting to WiFi: ");
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
    Serial.println("WiFi connected!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    digitalWrite(LED_PIN, HIGH); // Turn on LED to indicate WiFi connected
  } else {
    Serial.println();
    Serial.println("Failed to connect to WiFi");
    digitalWrite(LED_PIN, LOW);
  }
}

void authenticateWithLaravel() {
  Serial.println("Authenticating with Laravel...");
  
  String url = String(serverURL) + "/api/arduino/authenticate";
  
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");
  
  // Create JSON payload
  StaticJsonDocument<200> doc;
  doc["user_id"] = userId;
  doc["device_id"] = deviceId;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  int httpResponseCode = http.POST(jsonString);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Authentication Response: " + response);
    
    // Parse response
    StaticJsonDocument<200> responseDoc;
    deserializeJson(responseDoc, response);
    
    if (responseDoc["success"] == true) {
      isAuthenticated = true;
      Serial.println("Authentication successful!");
      Serial.println("User: " + String(responseDoc["user"]["name"].as<const char*>()));
      Serial.println("Current Points: " + String(responseDoc["user"]["current_points"].as<int>()));
      Serial.println("Points per Bottle: " + String(responseDoc["points_per_bottle"].as<int>()));
      
      // Success indication
      blinkLED(2, 300);
      beepBuzzer(2, 200);
    } else {
      isAuthenticated = false;
      Serial.println("Authentication failed: " + String(responseDoc["message"].as<const char*>()));
      blinkLED(5, 100); // Error indication
    }
  } else {
    Serial.println("HTTP Error: " + String(httpResponseCode));
    isAuthenticated = false;
    blinkLED(5, 100); // Error indication
  }
  
  http.end();
}

void detectBottle() {
  if (!isAuthenticated) {
    Serial.println("Not authenticated. Cannot detect bottles.");
    return;
  }
  
  bottleCount++;
  Serial.println("Bottle detected! Count: " + String(bottleCount));
  
  // Visual and audio feedback
  digitalWrite(LED_PIN, HIGH);
  beepBuzzer(1, 150);
  delay(100);
  digitalWrite(LED_PIN, LOW);
  
  // Send detection to Laravel
  sendBottleDetection();
}

void sendBottleDetection() {
  Serial.println("Sending bottle detection to Laravel...");
  
  String url = String(serverURL) + "/api/arduino/detect-bottle";
  
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");
  
  // Create JSON payload
  StaticJsonDocument<200> doc;
  doc["device_id"] = deviceId;
  doc["bottle_count"] = bottleCount;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  int httpResponseCode = http.POST(jsonString);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Detection Response: " + response);
    
    // Parse response
    StaticJsonDocument<300> responseDoc;
    deserializeJson(responseDoc, response);
    
    if (responseDoc["success"] == true) {
      Serial.println("Bottles recorded successfully!");
      Serial.println("Bottles detected: " + String(responseDoc["data"]["bottles_detected"].as<int>()));
      Serial.println("Points earned: " + String(responseDoc["data"]["points_earned"].as<int>()));
      Serial.println("Total points: " + String(responseDoc["data"]["user_total_points"].as<int>()));
      
      // Success indication
      blinkLED(3, 150);
      beepBuzzer(3, 100);
      
      // Reset bottle count
      bottleCount = 0;
    } else {
      Serial.println("Failed to record bottles: " + String(responseDoc["message"].as<const char*>()));
      blinkLED(5, 100); // Error indication
    }
  } else {
    Serial.println("HTTP Error: " + String(httpResponseCode));
    blinkLED(5, 100); // Error indication
  }
  
  http.end();
}

void sendHeartbeat() {
  if (!isAuthenticated) {
    return;
  }
  
  String url = String(serverURL) + "/api/arduino/session-status";
  
  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");
  
  // Create JSON payload
  StaticJsonDocument<200> doc;
  doc["device_id"] = deviceId;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  int httpResponseCode = http.POST(jsonString);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    
    // Parse response
    StaticJsonDocument<300> responseDoc;
    deserializeJson(responseDoc, response);
    
    if (responseDoc["authenticated"] == false) {
      Serial.println("Session expired. Re-authenticating...");
      isAuthenticated = false;
      authenticateWithLaravel();
    }
  }
  
  http.end();
}

void blinkLED(int times, int delayMs) {
  for (int i = 0; i < times; i++) {
    digitalWrite(LED_PIN, HIGH);
    delay(delayMs);
    digitalWrite(LED_PIN, LOW);
    delay(delayMs);
  }
}

void beepBuzzer(int times, int delayMs) {
  for (int i = 0; i < times; i++) {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(delayMs);
    digitalWrite(BUZZER_PIN, LOW);
    delay(delayMs);
  }
}

// Function to manually trigger bottle detection (for testing)
void manualBottleDetection() {
  if (Serial.available()) {
    String command = Serial.readStringUntil('\n');
    command.trim();
    
    if (command == "detect") {
      detectBottle();
    } else if (command == "auth") {
      authenticateWithLaravel();
    } else if (command == "status") {
      Serial.println("Device ID: " + String(deviceId));
      Serial.println("User ID: " + String(userId));
      Serial.println("Authenticated: " + String(isAuthenticated ? "Yes" : "No"));
      Serial.println("Bottle Count: " + String(bottleCount));
      Serial.println("WiFi Status: " + String(WiFi.status() == WL_CONNECTED ? "Connected" : "Disconnected"));
    }
  }
}



