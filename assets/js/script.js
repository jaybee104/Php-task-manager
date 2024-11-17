document.querySelectorAll('.delete-task').forEach(function (button) {
    button.addEventListener('click', function (event) {
        if (!confirm('Are you sure you want to delete this task?')) {
            event.preventDefault();
        }
    });
});
