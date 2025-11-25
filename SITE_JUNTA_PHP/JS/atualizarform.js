function updateMoveForm() {
    const anoVal = document.querySelector('#customSelectAno input').value;
    const mesVal = document.querySelector('#customSelectMes input').value;

    const form = document.getElementById('filtrosForm');

    if ((anoVal && anoVal !== 'all') || (mesVal && mesVal !== 'all')) {
        form.classList.add('move-form');
    } else {
        form.classList.remove('move-form');
    }
}

document.querySelectorAll('.custom-select .options li').forEach(li => {
    li.addEventListener('click', () => {
        setTimeout(updateMoveForm, 10);
    });
});

window.addEventListener('DOMContentLoaded', () => {
    updateMoveForm();
});