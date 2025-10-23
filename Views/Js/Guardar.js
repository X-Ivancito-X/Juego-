document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('myForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const score = document.getElementById('numero').value;

            fetch('../Controller/Score.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'numero=' + encodeURIComponent(score)
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => {
                console.error('Error al guardar score:', error);
            });
        });
    }
});


