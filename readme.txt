FrostByte – Social Media Platform

FrostByte is a custom-built social media platform developed using PHP, MySQL, HTML5, CSS3, and JavaScript.

It allows users to register, log in, create posts with optional images, send messages, manage profiles, and interact with other users through likes, friend requests, and notifications.

This project was created for the Internet Programming 622 module assignment.

If you would like to see the system running live, you can also access it via the following URL: https://defiantlyduggan.co.za/FrostByte

Note:
 • The guide included, FrostByte User Guide PDF, explains how to use all features with relevant screenshots.
 • This README is aimed at technical notes, and test information.

Project Structure
The project must remain in its folder structure for proper execution

FrostByte/
  │── css/                     # Stylesheets    
  │── icons/                   # Icons (favicon, etc.)    
  │── js/                      # JavaScript files (interactivity & validation)    
  │── php/    
  │    ├── components/         # Reusable UI components (navigation, notifications bar, etc.)    
  │    ├── library/            # Database connection, installer, helper functions    
  │    ├── views/              # Page templates (login, signup, profile, timeline, messages, etc.)    
  │── uploads/                 # User-uploaded images (profile pictures, post images)    
  │── data.sql                 # Optional preloaded data for testing    
  │── index.php                # Main entry point    

Installation & Database Setup (Covered in detail in the FrostByte User Guide)

 1. Ensure your server (MAMP, XAMPP, WAMP, or cPanel hosting) is running.
 2. Copy the project files into your server’s root directory (e.g., htdocs for XAMPP).
 3. Open index.php in your browser (e.g., http://localhost/FrostByte/index.php).


Main Features
 • User Registration & Login with email verification and password hashing.
 • Password Reset via registered email.
 • Timeline showing posts in reverse chronological order with timestamps.
 • Posts with text (max 280 characters) and optional image (JPG/PNG, < 4MB).
 • Profile Page with editable name, bio (max 120 characters), and profile picture.
 • Search for Users by name or email.
 • Friend Requests (send, accept, decline).
 • Notifications for likes, messages, and requests.
 • Private Messaging system with real-time conversation threads.
 • Theme Preferences (light/dark mode) stored in the database.


