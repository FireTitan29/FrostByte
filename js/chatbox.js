    // When page loads, go to the bottom of the messages so that the user
    // doesn't have to scroll all the way down.
    const chatArea = document.querySelector('.chat-area');
    chatArea.scrollTop = chatArea.scrollHeight;

    const inputBox = document.getElementById("textmessageinput");
    inputBox.value = "";
    
    // Putting the cursor into the input field
    inputBox.focus();

// Function to refresh the page every 10 seconds
function autoRefresh() {
    setInterval(function () {
        location.reload();
    }, 10000);
}

// Start auto refresh when the page loads
autoRefresh();