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

    fetch('php/LikePost.php', {
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