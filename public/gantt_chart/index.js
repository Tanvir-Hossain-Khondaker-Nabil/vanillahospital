import VanillaGanttChart from "./VanillaGanttChart.js";


var chart = document.querySelector("#g1");

var inputElement = document.getElementById("tasks");
// Get the value from the input element
var tasks = JSON.parse(inputElement.value);
console.log(tasks);

var task_list = [];
var jobs = [];
tasks.forEach(task => {
    var wordsArray = (task.name).split(" ");
    if (wordsArray.length > 10) {
        wordsArray = wordsArray.slice(0, 10);
        wordsArray.push(".....");
    }
    let newObject = {
        name: wordsArray.join(" "),
        id: task.id
    };
    task_list.push(newObject);

    var date_today = new Date();

    var backgroundColor = "#999";
    if (task.status == 'review'){
        backgroundColor  = "#f39c12";
    }else if (task.status == 'to_do'){
        backgroundColor  = "#999";
    }else if (task.status == 'in_progress'){
        backgroundColor  = "#3c8dbc";
    }else if (task.status == 'done'){
        backgroundColor  = "#26c95e";
    }

    if(new Date(task.end_date)<date_today && task.status != 'done')
    {
        backgroundColor  = "#d93232";
    }

    let jobsObject = {
        id: "j"+task.id,
        start: new Date(formartDate(task.start_date)),
        end: new Date(formartDate(task.end_date)),
        backgroundSet: backgroundColor,
        resource: task.id
    };
    jobs.push(jobsObject);
});

// var jobs = [
//     {id: "j1", start: new Date("2021/6/1"), end: new Date("2021/6/4"), resource: 10},
//     {id: "j2", start: new Date("2021/6/4"), end: new Date("2021/6/13"), resource: 11},
//     // {id: "j3", start: new Date("2021/6/13"), end: new Date("2021/7/21"), resource: 3},
//     // {id: "j3", start: new Date("2021/6/13"), end: new Date("2021/6/21"), resource: 4},
// ];
var p_jobs = [];

// chart.resources = [{id:1, name: "Task 1"}, {id:2, name: "Task 2"}, {id:3, name: "Task 3"}, {id:4, name: "Task 4"}];
chart.resources = task_list;

jobs.forEach(job => {

    var validator = {
        set: function(obj, prop, value) {

            console.log("Job " + obj.id + ": " + prop + " was changed to " + value);
            console.log();
            obj[prop] = value;
            return true;
        },

        get: function(obj, prop){

            return obj[prop];
        }
    };

    var p_job = new Proxy(job, validator);
    p_jobs.push(p_job);

    // var ganttRowItems = document.querySelectorAll('.gantt-row-item');
    // var lastGanttRowItem = ganttRowItems[ganttRowItems.length - 1];
    // lastGanttRowItem.style.borderRight = '1px solid #e8e8e8';
    // lastGanttRowItem.style.setProperty('border-right', '1px solid #e8e8e8', 'important');
});

function formartDate(inputDate)
{

// Create a new Date object using the input date string
    const dateObject = new Date(inputDate);

// Extract year, month, and day from the date object
    const year = dateObject.getFullYear();
    const month = dateObject.getMonth() + 1; // Months are 0-based, so add 1
    const day = dateObject.getDate();

// Format the date in 'YYYY/M/D' format
    const endDate = `${year}/${month}/${day}`;

    return endDate;
}

chart.jobs = p_jobs;
