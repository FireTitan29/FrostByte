// -----------------------------------------------------------
// change_theme_dark.js
// Purpose: Applies the dark mode theme by overriding CSS variables
// - Updates color palette variables (--myWhite, --darkBlue, etc.)
// - Changes the background image to the dark theme version
// -----------------------------------------------------------

// Update CSS variables for dark theme colors
document.documentElement.style.setProperty('--myWhite', 'rgb(16, 33, 64)');
document.documentElement.style.setProperty('--darkBlue', 'rgb(66, 191, 221)');
document.documentElement.style.setProperty('--lightBlue', 'rgb(33, 92, 210)');
document.documentElement.style.setProperty('--lightWhite', 'rgb(20, 26, 37)');
document.documentElement.style.setProperty('--greyBlue', 'rgb(66, 191, 221)');

// Swap background to dark mode image
document.documentElement.style.setProperty('--background-image', 'url(../images/Background-Dark.svg)');