import sys
import dlib
from skimage import io

def detect_disease(image_path):
    # Load the image
    image = io.imread(image_path)
    
    # Debugging statement to check if image is loaded
    print("Image loaded successfully.")
    
    # Load a pre-trained model (replace with actual model and processing code)
    detector = dlib.simple_object_detector("disease_detector.svm")
    
    # Detect disease
    detections = detector(image)
    
    # Process detections and return result
    if len(detections) > 0:
        return "Disease detected"
    else:
        return "No disease detected"

if __name__ == "__main__":
    image_path = sys.argv[1]
    result = detect_disease(image_path)
    print(result)
