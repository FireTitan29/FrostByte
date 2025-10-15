// JavaScript for viewing the image live on the page after upload
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const toggle = document.getElementById("edit-profile-picture-toggle");

    if (input) {
        // Listining for any change on the page
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            // If there has been a file that has been uploaded
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    // Show new preview
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    //  Only hide old pic after new one has been uploaded
                    if (toggle) {
                        toggle.style.display = "none";
                    }
                    
                    if (toggle) {
                        document.getElementById("edit-pic-overlay-preview").style.display = "block";
                    }
                };

                reader.readAsDataURL(file);
            } else {

                // If user cancels then we have to keep old pic
                if (toggle) toggle.style.display = "block";
                preview.src = "";
                preview.style.display = "none";
            }
        });
    }
});