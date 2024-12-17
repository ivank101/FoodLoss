const title = document.querySelectorAll('#title');

window.addEventListener('scroll', checkBoxes);

function checkBoxes() {
    const triggerBottom = window.innerHeight / 5 * 4;

    title.forEach(box => {
        const boxTop = box.getBoundingClientRect().top;
        
        if (boxTop < triggerBottom) {
            box.classList.add('show');
        } else {
            box.classList.remove('show');
        }
    });
}