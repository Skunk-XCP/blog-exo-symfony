const body = document.querySelector(".js-body");
if (localStorage.getItem('dark') === 'true') {
    body.classList.add("dark-activated");
}

const darkBtn = document.querySelector(".js-dark");


darkBtn.addEventListener('click', function () {

    console.log('click');
    if (body.classList.contains("dark-activated")) {
        body.classList.remove("dark-activated")
        localStorage.removeItem('dark')
    } else {

        body.classList.add("dark-activated")
        localStorage.setItem('dark', 'true');
    }
});
