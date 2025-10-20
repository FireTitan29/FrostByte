function changeTheme(displayMode) {
    const settingsIcon = document.querySelector(".settings-icon");
    const logo = document.querySelector(".logo");
    
    if (displayMode === 'light') {
        document.documentElement.style.setProperty('--myWhite', 'rgb(240, 246, 246)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(8, 75, 131)');
        document.documentElement.style.setProperty('--lightBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--greyBlue', 'rgb(187, 230, 228)');
        document.documentElement.style.setProperty('--accentPink', 'rgb(255, 102, 179)');
        document.documentElement.style.setProperty('--lightWhite', 'rgb(255, 255, 255)');

        document.documentElement.style.setProperty('--background-image', 'url(../images/Background.svg)');
        settingsIcon.src = "icons/Settings.svg";
        logo.src = "icons/logo.svg";
        
        
    } else if (displayMode === 'dark') {
        document.documentElement.style.setProperty('--myWhite', 'rgb(16, 33, 64)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--lightBlue', 'rgb(33, 92, 210)');
        document.documentElement.style.setProperty('--lightWhite', 'rgb(20, 26, 37)');
        document.documentElement.style.setProperty('--greyBlue', 'rgb(66, 191, 221)');
        
        document.documentElement.style.setProperty('--background-image', 'url(../images/Background-Dark.svg)');
        const settingsIcon = document.querySelector(".settings-button .settings-icon");
        settingsIcon.src = "icons/Settings-Dark.svg";
        logo.src = "icons/logo-Dark.svg";
    }
    // Send update to PHP DB
    fetch('php/controllers/UpdateTheme.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'theme=' + encodeURIComponent(displayMode)
    });
}

function slideOutSettings() {
    const settings = document.querySelector('.settings-window');
    settings.classList.toggle('active');
}

// This function activates when the like button is clicked
function likePost(postID) {
    let likeHolder = document.querySelector(`#post-${postID}-holder`);
    let alreadyLiked = likeHolder.getAttribute("data-liked");
    let counterEl = document.getElementById(`post-${postID}-counter`);
    let heartEl = document.getElementById(`like-heart-${postID}`);

    let counterNum = Number(counterEl.innerHTML);

    if (alreadyLiked === "true") {
        counterEl.innerHTML = counterNum - 1;
        heartEl.src = "icons/Like.svg";
        likeHolder.setAttribute("data-liked", "false");
    } else {
        counterEl.innerHTML = counterNum + 1;
        heartEl.src = "icons/Like-Active.svg";
        likeHolder.setAttribute("data-liked", "true");
    }

    fetch('php/controllers/LikePost.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'like-button-id=' + encodeURIComponent(postID)
    });

    // Activating the animation and then after it plays once, stop it by removing active
    heartEl.classList.add("active");
    heartEl.addEventListener("animationend", () => {
        heartEl.classList.remove("active");
    }, { once: true });
}

// Sends a message to the chat and updates the DB
function sendMessage(event) {
    event.preventDefault();
    
    let message = document.getElementById("textmessageinput").value;
    let userId = document.getElementById("sender").value;
    let receiverId = document.getElementById("reciever").value;
    
    fetch('php/controllers/sendMessage.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 
        'user_id=' + encodeURIComponent(userId) +
        '&receiver_id=' + encodeURIComponent(receiverId) +
        '&textmessage=' + encodeURIComponent(message)
    }).then(() => {
    location.reload();});
}