'use strict'
const sideNav = document.getElementById("sideNav");
const closeBtn = document.querySelector(".closeBtn");
let dropdownContent = document.querySelector(".dropdown-content");
let drop1= document.querySelector(".dropdown-content");
hamburger.addEventListener("click", () => {
    sideNav.style.width = "250px";
    content.style.transition = "margin-left 0.5s";
    content.style.marginLeft = '16%';
    content.style.width = '84%';
});

closeBtn.addEventListener("click", () => {
    sideNav.style.width = "0";
    content.style.transition = "margin-left 0.5s";
    content.style.marginLeft = '0%';
    content.style.width = '100%';

});

// logout the page

function logout() {
    localStorage.clear();
    window.location.href = "/";
}

// document.getElementById('imageClick').addEventListener('click', logout);


document.getElementById('imageClick').addEventListener('click', function(event) {
    var dropdownContent = document.getElementById('dropdownContent');
    if (dropdownContent.style.display === "block") {
        dropdownContent.style.display = "none";
    } else {
        dropdownContent.style.display = "block";
    }
    // Prevent event propagation to avoid unwanted behavior
    event.stopPropagation();
});

// Close the dropdown if the user clicks outside of it
// window.addEventListener('click', function(event) {
//     var dropdownContent = document.getElementById('dropdownContent');
//     if (!event.target.matches('#imageClick') && !event.target.matches('#imageClick *')) {
//         dropdownContent.style.display = "none";
//     }
// });


