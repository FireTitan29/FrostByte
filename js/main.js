// -----------------------------------------------------------
// main.js
// Purpose: Handles client-side interactivity (theme toggle, 
// settings window, likes, and chat messaging).
// Functions:
// - changeTheme(): Switches between light/dark mode, updates DB
// - slideOutSettings(): Toggles settings panel visibility
// - likePost(): Updates like counter, toggles heart icon, notifies DB
// - sendMessage(): Sends chat message via fetch and reloads chat
// -----------------------------------------------------------

// Switches between light/dark mode and Updates the theme in user table
function changeTheme(displayMode) {
    // Get UI elements that need updating
    const settingsIcon = document.querySelector(".settings-icon");
    const logo = document.querySelector(".logo");
    
    if (displayMode === 'light') {
        // Apply light mode color variables
        document.documentElement.style.setProperty('--myWhite', 'rgb(240, 246, 246)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(8, 75, 131)');
        document.documentElement.style.setProperty('--lightBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--greyBlue', 'rgb(187, 230, 228)');
        document.documentElement.style.setProperty('--accentPink', 'rgb(255, 102, 179)');
        document.documentElement.style.setProperty('--lightWhite', 'rgb(255, 255, 255)');
        document.documentElement.style.setProperty('--background-image', 'url(../images/Background.svg)');

        // Update icons
        settingsIcon.src = "icons/Settings.svg";
        logo.src = "icons/logo.svg";
        
        
    } else if (displayMode === 'dark') {
        // Apply dark mode color variables
        document.documentElement.style.setProperty('--myWhite', 'rgb(16, 33, 64)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--lightBlue', 'rgb(33, 92, 210)');
        document.documentElement.style.setProperty('--lightWhite', 'rgb(20, 26, 37)');
        document.documentElement.style.setProperty('--greyBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--background-image', 'url(../images/Background-Dark.svg)');

        // Update dark mode icons
        const settingsIcon = document.querySelector(".settings-button .settings-icon");
        settingsIcon.src = "icons/Settings-Dark.svg";
        logo.src = "icons/logo-Dark.svg";
    }
    // Send updated User Theme preferences to Database
    fetch('php/controllers/UpdateTheme.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'theme=' + encodeURIComponent(displayMode)
    });
}

function slideOutSettings() {
    // Toggle settings panel visibility
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

    // Increase or decrease like count
    if (alreadyLiked === "true") {
        counterEl.innerHTML = counterNum - 1;
        heartEl.src = "icons/Like.svg";
        likeHolder.setAttribute("data-liked", "false");
    } else {
        counterEl.innerHTML = counterNum + 1;
        heartEl.src = "icons/Like-Active.svg";
        likeHolder.setAttribute("data-liked", "true");
    }

    // Send like/unlike update to DB
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

    // Prevent default form reload
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

    // Reload page to show updated chat box
    location.reload();});
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("searchBarForm");
  if (form) {
    form.addEventListener("submit", searchSubmitListener);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("searchBarMessageForm");
  if (form) {
    form.addEventListener("submit", searchMessageListener);
  }
});

async function searchSubmitListener(event) {
	// stop page reload
  event.preventDefault(); 

  let find = document.getElementById("searchInput").value;
  let data = await searchForUser(find);

  // render results
  showSearchResults(data, 'profileview', 'user');
}

async function searchMessageListener(event) {
	// stop page reload
  event.preventDefault(); 

  let find = document.getElementById("searchMessageInput").value;
  let data = await searchForUser(find);

  // render results
  showSearchResults(data, 'chat', 'sendto');
}

// send query to PHP and return results
async function searchForUser(find) {
  let response = await fetch("php/controllers/SearchUsers.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "find=" + encodeURIComponent(find)
  });

  let data = await response.json();
  return data;
}

function showSearchResults(data, view, variable) {
  let resultsDiv = document.getElementById("searchResults"+view);
  resultsDiv.innerHTML = "";
  
  if (data.length === 0) {
    resultsDiv.innerHTML = "<p class='search-placeholder'>No results found<br>try entering more letters</p>";
  } else {
    for (var i = 0; i < data.length; i++) {
      var user = data[i];
      resultsDiv.innerHTML +=
	  "<a class='anchor-search' href='index.php?view="+view+"&"+variable + "=" + user.id + "'>"+
        "<div class='user-result'>" +
        "<img class='picture-profile-Search' src='" + user.profile_pic + "' alt='profile-picture'>" +
        "<div class='userinfo-holder-search'>" +
			"<span class='Username-Search'>" + user.firstname + " " + user.surname + "</span><br>" +
			"<span class='Email-Search' >" + user.email + "</span>" +
    	" </div>" +
        "</div></a>";
    }
  }
}