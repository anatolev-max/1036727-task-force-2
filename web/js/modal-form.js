const openModalButtons = document.querySelectorAll('.open-modal');
const closeModalButtons = document.querySelectorAll('.form-modal-close');
const closeRefuseModal = document.getElementById('close-modal');
const overlayElement = document.querySelector('.overlay');
const OVERLAY_TRANSITION_DURATION = 800;
const completeFormStar = document.querySelector('.completion-form-star');
const ratingInputElement = document.getElementById('rating');
const ratingStarsElements = document.querySelectorAll('.star-disabled');

const closeFormModal = () => {
    const modalElement = document.querySelector('.form-modal--active');
    modalElement.classList.remove('form-modal--active');
    overlayElement.classList.remove('overlay--active');

    setTimeout(() => {
        modalElement.style.display = 'none';
        overlayElement.style.display = 'none';
    }, OVERLAY_TRANSITION_DURATION);
};

openModalButtons.forEach(button => {
    button.addEventListener('click', evt => {
        evt.preventDefault();
        const modalElement = document.getElementById(evt.target.dataset.for);
        modalElement.style.display = 'block';
        overlayElement.style.display = 'block';

        setTimeout(() => {
            modalElement.classList.add('form-modal--active');
            overlayElement.classList.add('overlay--active');
        }, 0);
    });
});

overlayElement.addEventListener('click', closeFormModal);
closeRefuseModal.addEventListener('click', closeFormModal);
closeModalButtons.forEach(button => {
    button.addEventListener('click', closeFormModal);
});

completeFormStar.addEventListener('click', evt => {
    if (evt.target.classList.contains('star-disabled')) {
        const rating = evt.target.dataset.rating;
        ratingInputElement.value = rating;

        ratingStarsElements.forEach((star, i) => {
            if (i <= +rating - 1) {
                star.style.backgroundImage = 'url(/../img/star.png)';
            } else {
                star.style.backgroundImage = 'url(/../img/star-disabled.png)';
            }
        });
    }
});
