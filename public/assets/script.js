let MenuBtn = document.querySelector("#MenuBtn");
let Navbar = document.querySelector(".navbar");

MenuBtn.onclick = () => {
    MenuBtn.classList.toggle("fa-times");
    Navbar.classList.toggle("active");

};

window.onscroll = () => {
    MenuBtn.classList.remove("fa-times");
    Navbar.classList.remove("active");
    ThemeToggle.classList.remove("active");
};

// LOGIN FORM
document.querySelector("#LoginBtn").onclick = () => {
    document.querySelector(".loginFormContainer").classList.toggle("active");
};
document.querySelector("#CloseLoginForm").onclick = () => {
    document.querySelector(".loginFormContainer").classList.remove("active");
};
//homeParalexEffect
//homeParalex

document.querySelector(".home").onmousemove = (e) => {
    document.querySelectorAll(".homeParalexEffect").forEach((el) => {
        let Speed = el.getAttribute("data-speed")
        let X = (window.innerWidth - e.pageX * Speed) / 60;
        let Y = (window.innerHeight - e.pageY * Speed) / 60;

        el.style.transform = `translateX(${Y}px) translateY(${X}px)`;
    });
};
document.querySelector(".home").onmouseleave = (e) => {
    document.querySelectorAll(".homeParalexEffect").forEach((el) => {
        el.style.transform = `translateX(0px) translateY(0px)`;
    });
};

// script.js
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const ratingContainer = document.getElementById('rating-container');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            // Envoyer la valeur à votre backend via une requête AJAX par exemple
            console.log(`Vous avez noté ${value} étoiles.`);
            resetStars();
        });
    });

    function resetStars() {
        stars.forEach(star => star.classList.remove('active'));
    }
});

// ProductsColletion scrolling
document.addEventListener('DOMContentLoaded', function () {
    const scrollContainer = document.querySelector('.scroll-container');

    document.getElementById('scrollLeft').addEventListener('click', () => {
        // Défiler vers la gauche
        scrollContainer.scrollBy({ left: -330, behavior: 'smooth' });
    });

    document.getElementById('scrollRight').addEventListener('click', () => {
        // Défiler vers la droite
        scrollContainer.scrollBy({ left: 330, behavior: 'smooth' });
    });
});