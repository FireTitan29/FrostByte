// JavaScript for viewing the image live on the page after upload
document.getElementById('image').addEventListener('change', function(event) {
const file = event.target.files[0];
if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
    }
        // read file as data URL
    reader.readAsDataURL(file);
}
});