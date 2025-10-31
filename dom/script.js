let today = new Date();
let currentYear = today.getFullYear();
let currentMonth = today.getMonth();

let yearSelect = document.getElementById("yearSelect");
let monthSelect = document.getElementById("monthSelect");
let showBtn = document.getElementById("showBtn");
let tbody = document.getElementById("calendar-body");

// Заполняем выпадающие списки
for (let y = 1900; y <= 2100; y++) {
    let option = document.createElement("option");
    option.value = y;
    option.textContent = y;
    if (y === currentYear) option.selected = true;
    yearSelect.appendChild(option);
}

const months = ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
months.forEach((m, i) => {
    let option = document.createElement("option");
    option.value = i;
    option.textContent = m;
    if (i === currentMonth) option.selected = true;
    monthSelect.appendChild(option);
});

function renderCalendar(year, month) {
    tbody.innerHTML = "";

    let firstDay = new Date(year, month, 1);
    let lastDay = new Date(year, month + 1, 0);
    let startDay = (firstDay.getDay() + 6) % 7;
    let daysInMonth = lastDay.getDate();

    let date = 1;
    for (let i = 0; i < 6; i++) {
        let row = document.createElement("tr");
        for (let j = 0; j < 7; j++) {
            let cell = document.createElement("td");
            if (i === 0 && j < startDay || date > daysInMonth) {
                cell.textContent = "";
            } else {
                cell.textContent = date;
                if (year === today.getFullYear() && month === today.getMonth() && date === today.getDate()) {
                    cell.classList.add("today");
                }
                if (j === 5 || j === 6) {
                    cell.classList.add("weekend");
                }
                date++;
            }
            row.appendChild(cell);
        }
        tbody.appendChild(row);
    }
}

showBtn.addEventListener("click", function() {
    let year = parseInt(yearSelect.value);
    let month = parseInt(monthSelect.value);
    renderCalendar(year, month);
});

renderCalendar(currentYear, currentMonth);
