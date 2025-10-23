    // When page loads, go to the bottom of the messages so that the user
    // doesn't have to scroll all the way down.
    const chatArea = document.querySelector('.chat-area');
    chatArea.scrollTop = chatArea.scrollHeight;

    const inputBox = document.getElementById("textmessageinput");
    inputBox.value = "";
    
    // Putting the cursor into the input field
    inputBox.focus();