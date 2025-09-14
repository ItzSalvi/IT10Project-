import serial.tools.list_ports

def find_arduino_port():
    """Find Arduino port automatically"""
    print("🔍 Scanning for Arduino devices...")
    
    ports = list(serial.tools.list_ports.comports())
    
    if not ports:
        print("❌ No COM ports found")
        return None
    
    print("📋 Available COM ports:")
    arduino_ports = []
    
    for port in ports:
        print(f"  {port.device} - {port.description}")
        
        # Check for common Arduino identifiers
        description = port.description.lower()
        if any(keyword in description for keyword in ['arduino', 'ch340', 'cp210', 'ftdi', 'usb serial']):
            arduino_ports.append(port.device)
            print(f"    ✅ Likely Arduino device!")
    
    if arduino_ports:
        print(f"\n🎯 Recommended Arduino port: {arduino_ports[0]}")
        return arduino_ports[0]
    else:
        print("\n⚠️ No Arduino device detected automatically")
        print("💡 Try connecting your Arduino and running this script again")
        return None

if __name__ == "__main__":
    find_arduino_port()

