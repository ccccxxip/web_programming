let tasks = [];
let editingId = null;

const taskList = document.getElementById('taskList');
const createBtn = document.getElementById('createBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');
const taskCard = document.getElementById('taskCard');

const taskTitleInput = document.getElementById('taskTitle');
const taskDescInput = document.getElementById('taskDesc');
const taskHighInput = document.getElementById('taskHigh');

function renderTasks() {
  taskList.innerHTML = '';

  tasks.forEach(function (task) {
    const li = document.createElement('li');
    li.className = 'task-item';
    if (task.done) {
      li.classList.add('done');
    }

    const check = document.createElement('input');
    check.type = 'checkbox';
    check.checked = task.done;
    check.addEventListener('change', function () {
      task.done = check.checked;
      renderTasks();
    });
    li.appendChild(check);

    if (task.high) {
        const priority = document.createElement('span');
        priority.textContent = '‼️';
        priority.className = 'icon-priority';
        li.appendChild(priority);
      }
      

    const spanTitle = document.createElement('span');
    spanTitle.className = 'task-title';
    spanTitle.textContent = task.title;
    li.appendChild(spanTitle);

    const actions = document.createElement('div');
    actions.className = 'task-actions';

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Редактировать';
    editBtn.addEventListener('click', function () {
      openCardForEdit(task.id);
    });
    actions.appendChild(editBtn);

    const delBtn = document.createElement('button');
    delBtn.textContent = 'Удалить';
    delBtn.addEventListener('click', function () {
      tasks = tasks.filter(function (t) { return t.id !== task.id; });
      renderTasks();
    });
    actions.appendChild(delBtn);

    li.appendChild(actions);
    taskList.appendChild(li);
  });
}

function clearForm() {
  taskTitleInput.value = '';
  taskDescInput.value = '';
  taskHighInput.checked = false;
}


function openCardCreate() {
  editingId = null;
  clearForm();
  taskCard.classList.add('active');
}

function openCardForEdit(id) {
  const task = tasks.find(function (t) { return t.id === id; });
  if (!task) return;

  editingId = id;
  taskTitleInput.value = task.title;
  taskDescInput.value = task.desc;
  taskHighInput.checked = task.high;
  taskCard.classList.add('active');
}


createBtn.addEventListener('click', openCardCreate);

cancelBtn.addEventListener('click', function () {
  clearForm();
  editingId = null;
  taskCard.classList.remove('active');
});

saveBtn.addEventListener('click', function () {
  const title = taskTitleInput.value.trim();
  const desc = taskDescInput.value.trim();
  const high = taskHighInput.checked;

  if (!title) {
    alert('Введите название задачи');
    return;
  }

  if (editingId === null) {
    const newTask = {
      id: Date.now(),
      title: title,
      desc: desc,
      high: high,
      done: false
    };
    tasks.push(newTask);
  } else {
    const task = tasks.find(function (t) { return t.id === editingId; });
    if (task) {
      task.title = title;
      task.desc = desc;
      task.high = high;
    }
  }

  clearForm();
  editingId = null;
  taskCard.classList.remove('active');
  renderTasks();
});

tasks = [
  { id: 1, title: 'Задача 1',  desc: 'Описание первой задачи',  high: true,  done: false },
];

renderTasks();
