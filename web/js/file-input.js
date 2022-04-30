const input = document.querySelector('input[type=file]');
const label = document.querySelector('input[type=file] + label');

input.addEventListener('change', () => {
    if (input.files.length) {
        label.textContent = Array.from(input.files, file => file.name).join(', ');
    }
});
