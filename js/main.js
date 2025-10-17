function changeTheme(displayMode) {
    if (displayMode === 'light') {
        document.documentElement.style.setProperty('--myWhite', 'rgb(240, 246, 246)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(8, 75, 131)');
        document.documentElement.style.setProperty('--lightBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--accentPink', 'rgb(255, 102, 179)');
        document.documentElement.style.setProperty('--background-image', 'url(../images/Background.svg)');
    } else if (displayMode === 'dark') {
        document.documentElement.style.setProperty('--myWhite', 'rgb(20, 26, 37)');
        document.documentElement.style.setProperty('--darkBlue', 'rgb(66, 191, 221)');
        document.documentElement.style.setProperty('--lightBlue', 'rgba(33, 92, 210, 1)');
        document.documentElement.style.setProperty('--background-image', 'url(../images/Background-Dark.svg)');
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