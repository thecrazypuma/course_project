function toggleNav() {
    var sidebar = document.getElementById("menuSidebar");
    if (sidebar.style.width === "0px" || sidebar.style.width === "") {
        sidebar.style.width = "15%"; // Открываем меню
    } else {
        sidebar.style.width = "0"; // Закрываем меню
    }
}

function deleteEmployee(id) {
    if (confirm('Вы уверены, что хотите удалить этого сотрудника?')) {
        window.location.href = 'backend/delete_employee.php?id=' + id;
    }
}

function clearEmployees() {
    if (confirm('Вы уверены, что хотите ОЧИСТИТЬ таблицу сотрудников? Так же удалятся данные об образовательных программах! ЭТО ДЕЙСТВИЕ НЕВОЗМОЖНО БУДЕТ ОТМЕНИТЬ!')) {
        window.location.href = 'backend/clear_employees.php';
    }
};

function deleteDisciplines(id) {
    if (confirm('Вы уверены, что хотите удалить эту диспиплину?')) {
        window.location.href = 'backend/delete_discipline.php?id=' + id;
    }
}

function deletePositions(id) {
    if (confirm('Вы уверены, что хотите удалить эту должность?')) {
        window.location.href = 'backend/delete_position.php?id=' + id;
    }
}

function deleteProgram(id) {
    if (confirm('Вы уверены, что хотите удалить эту должность?')) {
        window.location.href = 'backend/delete_program.php?id=' + id;
    }
}

//---- Очистка БД -----
function showConfirmation() {
    document.getElementById('confirmationModal').style.display = 'block';
}

function hideConfirmation() {
    document.getElementById('confirmationModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    // "Показать все"
    document.querySelectorAll('.show-more').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const container = this.closest('.program-info-container');
            container.querySelector('.short-text').style.display = 'none';
            container.querySelector('.full-text').style.display = 'inline';
            this.style.display = 'none';
            container.querySelector('.show-less').style.display = 'inline';
        });
    });

    // "Скрыть"
    document.querySelectorAll('.show-less').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const container = this.closest('.program-info-container');
            container.querySelector('.short-text').style.display = 'inline';
            container.querySelector('.full-text').style.display = 'none';
            this.style.display = 'none';
            container.querySelector('.show-more').style.display = 'inline';
        });
    });
});