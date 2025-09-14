import serial
import requests
import time
import json
import sys
from datetime import datetime

class ArduinoBottleListener:
    def __init__(self, arduino_port="COM3", baud_rate=9600, laravel_url="http://localhost:8000"):
        self.arduino_port = arduino_port
        self.baud_rate = baud_rate
        self.laravel_url = laravel_url.rstrip('/')
        self.user_id = None
        self.ser = None
        
    def connect_arduino(self):
        """Connect to Arduino via serial port"""
        try:
            self.ser = serial.Serial(self.arduino_port, self.baud_rate, timeout=1)
            time.sleep(2)  # wait for Arduino to reset
            print(f"✅ Connected to Arduino on {self.arduino_port}")
            return True
        except Exception as e:
            print(f"❌ Failed to connect to Arduino: {e}")
            return False
    
    def set_user_id(self, user_id):
        """Set the user ID for bottle detection"""
        self.user_id = user_id
        print(f"👤 User ID set to: {user_id}")
    
    def check_user_login(self):
        """Check if user is logged in via Laravel API"""
        if not self.user_id:
            print("⚠️ No user ID set")
            return False
            
        try:
            url = f"{self.laravel_url}/api/check-login/{self.user_id}"
            response = requests.get(url, timeout=5)
            
            if response.status_code == 200:
                data = response.json()
                if data.get("logged_in", False):
                    print(f"✅ User {data.get('user_name', 'Unknown')} is logged in")
                    return True
                else:
                    print("⚠️ User not logged in")
                    return False
            else:
                print(f"❌ Login check failed: HTTP {response.status_code}")
                return False
                
        except Exception as e:
            print(f"❌ Error checking login: {e}")
            return False
    
    def send_bottle_detection(self):
        """Send bottle detection to Laravel API"""
        if not self.user_id:
            print("⚠️ No user ID set")
            return False
            
        try:
            url = f"{self.laravel_url}/api/bottle-inserted"
            payload = {"user_id": self.user_id}
            
            response = requests.post(url, json=payload, timeout=5)
            
            if response.status_code == 200:
                data = response.json()
                if data.get("success", False):
                    points_awarded = data.get("data", {}).get("points_awarded", 0)
                    total_points = data.get("data", {}).get("user_total_points", 0)
                    print(f"✅ Bottle recorded! +{points_awarded} points (Total: {total_points})")
                    return True
                else:
                    print(f"❌ Bottle recording failed: {data.get('message', 'Unknown error')}")
                    return False
            else:
                print(f"❌ API request failed: HTTP {response.status_code}")
                return False
                
        except Exception as e:
            print(f"❌ Error sending bottle detection: {e}")
            return False
    
    def listen_for_bottles(self):
        """Main loop to listen for bottle detection from Arduino"""
        if not self.ser:
            print("❌ Arduino not connected")
            return
            
        print("🔄 Listening for bottle detection...")
        print("Press Ctrl+C to stop")
        
        try:
            while True:
                if self.ser.in_waiting > 0:
                    line = self.ser.readline().decode('utf-8').strip()
                    if line:
                        timestamp = datetime.now().strftime("%H:%M:%S")
                        print(f"[{timestamp}] Arduino: {line}")
                        
                        if line == "BOTTLE_DETECTED":
                            print("🍾 Bottle detected!")
                            
                            # Check if user is logged in
                            if self.check_user_login():
                                # Send bottle detection to Laravel
                                self.send_bottle_detection()
                            else:
                                print("⚠️ User not logged in - bottle ignored")
                        
                        elif line == "SENSOR_READY":
                            print("📡 Sensor ready")
                        
                        elif line.startswith("ERROR"):
                            print(f"⚠️ Arduino error: {line}")
                
                time.sleep(0.1)  # Small delay to prevent excessive CPU usage
                
        except KeyboardInterrupt:
            print("\n🛑 Stopping listener...")
        except Exception as e:
            print(f"❌ Error in main loop: {e}")
        finally:
            if self.ser:
                self.ser.close()
                print("🔌 Arduino connection closed")

def main():
    print("🍾 Arduino Bottle Detection Listener")
    print("=" * 40)
    
    # Configuration
    ARDUINO_PORT = "COM4"  # Change this to your Arduino COM port
    LARAVEL_URL = "http://localhost:8000"  # Change this to your Laravel URL
    USER_ID = 4  # Change this to your user ID
    
    # Create listener instance
    listener = ArduinoBottleListener(
        arduino_port=ARDUINO_PORT,
        laravel_url=LARAVEL_URL
    )
    
    # Set user ID
    listener.set_user_id(USER_ID)
    
    # Connect to Arduino
    if not listener.connect_arduino():
        print("❌ Cannot start without Arduino connection")
        sys.exit(1)
    
    # Test Laravel connection
    print("🔍 Testing Laravel connection...")
    if not listener.check_user_login():
        print("⚠️ Warning: User login check failed. Make sure Laravel is running and user exists.")
        response = input("Continue anyway? (y/n): ")
        if response.lower() != 'y':
            sys.exit(1)
    
    # Start listening
    listener.listen_for_bottles()

if __name__ == "__main__":
    main()

