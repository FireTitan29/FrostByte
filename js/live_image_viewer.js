// -----------------------------------------------------------
// live_image_viewer.js
// Purpose: Provides live preview functionality for profile picture uploads
// - Watches for file input changes (when user selects or cancels an image)
// - Shows a preview of the uploaded image immediately
// - Hides the old image toggle once a new image is chosen
// - Restores the old image if upload is canceled
// -----------------------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
    // File input element
    const input = document.getElementById('image');

    // Image element to show preview
    const preview = document.getElementById('preview');

    // Toggle for old profile pic
    const toggle = document.getElementById("edit-profile-picture-toggle");

    if (input) {
        // Listen for changes to the file input (when user picks or cancels a file)
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            // If User selected a file
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    // Show new image in preview area
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    // Hide old image toggle since a new picture is selected
                    if (toggle) {
                        toggle.style.display = "none";
                    }

                    // Show overlay to confirm picture change
                    if (toggle) {
                        document.getElementById("edit-pic-overlay-preview").style.display = "block";
                    }
                };

                // Convert uploaded file into a DataURL for preview
                reader.readAsDataURL(file);

            // If User canceled file selection
            } else {
                if (toggle) toggle.style.display = "block";
                // Keep old picture visible
                preview.src = "";
                // Hide preview image
                preview.style.display = "none";
            }
        });
    }
});