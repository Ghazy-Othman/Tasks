// 
// 
// 

import { addTask, deleteTask, getTasks, updateTask } from "./api/task.js";

//
document.addEventListener('DOMContentLoaded', function () {

  //
  const taskInput = document.getElementById('task-input');
  const taskDate = document.getElementById('task-date');
  const taskPriority = document.getElementById('task-priority');
  const taskDescription = document.getElementById('task-description');
  const addButton = document.querySelector(".addbutton");
  const updateButton = document.querySelector('.updateButton');

  // console.log("قيمة addButton هي:", addButton);
  const tasksList = document.getElementById('tasks-list');

  ///TODO : Get tasks from backend
  let tasks = [];

  addButton.addEventListener('click', addNewTask);

  // Add new task
  async function addNewTask() {
    //
    const taskTitle = taskInput.value.trim();
    const date = taskDate.value;
    const priority = taskPriority.value == 'عالية' ? 1 : (taskPriority.value == 'متوسطة' ? 2 : 3);
    const description = taskDescription.value;

    //
    if (taskTitle === '') {
      alert('الرجاء إدخال اسم المهمة.');
      return;
    }

    console.log(taskTitle);
    console.log(date);
    console.log(priority);
    console.log(description);

    //
    if (!date || !priority || !description) {
      alert('الرجاء إدخال كامل المعلومات.');
      return;
    }

    //
    const data = {
      "title": taskTitle,
      "date": date,
      "priority": priority,
      "content": description,
    };


    //
    const result = await addTask(data);
    console.log(result);
    if (!result?.data) {
      console.log(result);
      alert("Something went wrong, check console...");
      return;
    }

    //
    const newTask = { id: result.data.task.task_id, title: taskTitle, date: date, priority: priority, description: description };
    tasks.push(newTask);

    //
    renderTasks();

    taskInput.value = '';
    taskDate.value = '';
    taskPriority.value = '';
    taskDescription.value = '';
  }

  //
  async function renderTasks() {
    //
    tasksList.innerHTML = '';
    tasks = [];
    //
    const result = await getTasks();
    if (!result?.data) {
      console.log(result);
      alert("something went wrong, check console...");
      return;
    }


    // Save tasks count to local storage
    localStorage.setItem('tasks_count', result.data.tasks.length);

    //
    result.data.tasks.forEach(task => {

      tasks.push({
        id: task.task_id,
        title: task.title,
        description: task.content,
        priority: task.priority == 1 ? 'عالية' : (task.priority == 2 ? "متوسطة" : "منخفضة"),
        date: task.date,
      });
    });

    //
    tasks.forEach((task, index) => {

      const detailsDiv = document.createElement('div');
      detailsDiv.className = 'task-details';

      const title = document.createElement('h3');
      title.textContent = task.title;
      detailsDiv.appendChild(title);

      if (task.date) {
        const dateP = document.createElement('p');
        dateP.textContent = "التاريخ: " + task.date;
        detailsDiv.appendChild(dateP);
      }

      if (task.priority) {
        const priorityP = document.createElement('p');
        priorityP.textContent = "الأولوية: " + task.priority;
        detailsDiv.appendChild(priorityP);
      }

      if (task.description) {
        const descP = document.createElement('p');
        descP.textContent = "الوصف: " + task.description;
        detailsDiv.appendChild(descP);
      }

      const actionsDiv = document.createElement('div');
      actionsDiv.className = 'task-actions';

      const editBtn = document.createElement('button');
      editBtn.className = 'edit-button';
      editBtn.textContent = 'تعديل';

      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'delete-button';
      deleteBtn.textContent = 'حذف';

      actionsDiv.appendChild(editBtn);
      actionsDiv.appendChild(deleteBtn);

      const li = document.createElement('li');
      li.className = 'task-item';
      li.appendChild(detailsDiv);
      li.appendChild(actionsDiv);
      tasksList.appendChild(li);

      //
      deleteBtn.addEventListener('click', async () => {
        // Try to delete task
        const res = await deleteTask(task.id);
        //
        renderTasks();
      });

      //
      editBtn.addEventListener('click', async () => {

        // View update button and disappear add button
        addButton.style.display = 'none';
        updateButton.style.display = 'block';

        // Initialize input fields 
        taskInput.value = task.title;
        taskDate.value = task.date;
        taskPriority.value = task.priority;
        taskDescription.value = task.description;

        updateButton.addEventListener('click', async function () {

          // New task data
          const data = {
            title: taskInput.value.trim(),
            content: taskDescription.value.trim(),
            date: taskDate.value.trim(),
            priority: taskPriority.value == 'عالية' ? 1 : (taskPriority.value == 'منخفضة' ? 2 : 3),
          };

          // Try to update task
          const res = await updateTask(data, task.id);
          console.log(res);
          if (!res?.data) {
            console.log(result);
            alert("something went wrong, check console...");
            return;
          }

          //
          renderTasks();

          // Clear input
          taskInput.value = '';
          taskDate.value = '';
          taskPriority.value = '';
          taskDescription.value = '';
        });
      });
    });
  }

  renderTasks();

});

//
window.goToChatPage = function () {
  window.location.href = 'chat.html';
}

