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
    fetch('php/UpdateTheme.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'theme=' + encodeURIComponent(displayMode)
    });
}

function slideOutSettings() {
    const settings = document.querySelector('.settings-window');
    settings.classList.toggle('active');
}