import {YearMonthRenderer} from './YearMonthRenderer.js';
import {DateTimeRenderer} from './DateTimeRenderer.js';

  const template = document.createElement('template');

  template.innerHTML =
   `<style>
      input, select{
      font-size: 10px;
    }

    fieldset{
      border:none;
    }

    #gantt-container{
        display: grid;
    }

    .gantt-row-resource{
      background-color: whitesmoke;
      color:rgba(0, 0, 0, 0.726);
      border: 1px solid #e8e8e8;
      text-align: left;
        padding: 10px 10px;
    }

    .gantt-row-period{
      background-color:whitesmoke;
      color:rgba(0, 0, 0, 0.726);
      border: 1px solid #e8e8e8;
      text-align: center;

      display:grid;
      grid-auto-flow: column;
      grid-auto-columns: minmax(5px, 1fr);
    }

    .gantt-row-item{
        border: 1px solid #e8e8e8;
          /*border-top: 1px solid #e8e8e8;*/
          /*border-bottom: 1px solid #e8e8e8;*/
        padding: 10px 0 10px 0;
        position: relative;
        background-color:white;
    }

    .job{
        position: absolute;
        height:10px;
		    top:40%;
        width: calc(2*100%);
        z-index: 100;
        background-color: #033b6a;
        border-radius: 5px;
		    cursor: pointer;
    }

    .drag-hide {
      transition: 0.01s;
      transform: translateX(-9999px);
    }

    #select-level{
      text-align: left;
      margin-top: 10px;
    }

    #gantt-settings{

      display: none;
      align-items: center;
      column-gap: 3px;
      font-size:10px;
      margin-bottom: 10px;
    }
  </style>

  <div id="gantt-settings">
    <select name="select-level" id="select-level">
      <option value="year-month">Month / Day</option>
      <option value="day">Day / Time</option>
    </select>

    <fieldset id="select-from">
      <legend>From</legend>
    </fieldset>

    <fieldset id="select-to">
      <legend>To</legend>
    </fieldset>

  </div>

  <div id="gantt-container">

  </div>

  `
  ;

  export default class VanillaGanttChart extends HTMLElement {

    constructor() {
      super();

      this.attachShadow({ mode: 'open' });
      this.shadowRoot.appendChild(template.content.cloneNode(true));

      this.levelSelect = this.shadowRoot.querySelector('#select-level');
    }

    _resources = [];
    _jobs = [];
    _renderer;

    set resources(list){
      this._resources = list;

      if(this.renderer){
        this.renderer.resources = this._resources;
        this.renderer.render();
      }
    }

    get resources(){
      return this._resources;
    }

    set jobs(list){
      this._jobs = list;

      if(this.renderer){
        this.renderer.jobs = this._jobs;
        this.renderer.render();
      }
    }

    get jobs(){
      return this._jobs;
    }

    get level() {
      return this.levelSelect.value;
    }

    set level(newValue) {
      this.levelSelect.value = newValue;
    }

    get renderer(){
      return this._renderer;
    }

    set renderer(r){
      this._renderer = r;
    }

    connectedCallback() {

      this.changeLevel = this.changeLevel.bind(this);

      this.levelSelect.addEventListener('change', this.changeLevel);
      this.level = "year-month";
      this.renderer = new YearMonthRenderer(this.shadowRoot);

    var project_start_date = document.getElementById("project_start_date").value;
    var project_end_date = document.getElementById("project_end_date").value;

      this.renderer.dateFrom = new Date( project_start_date);
      this.renderer.dateTo = new Date(project_end_date);
      this.renderer.render();
    }

    disconnectedCallback() {

      if(this.levelSelect)
        this.levelSelect.removeEventListener('change', this.changeLevel);

      if(this.renderer)
        this.renderer.clear();
    }

    changeLevel(){

      if(this.renderer){
        this.renderer.clear();
      }

      var r;

      if(this.level == "year-month"){

        r = new YearMonthRenderer(this.shadowRoot);

      }else{

        r = new DateTimeRenderer(this.shadowRoot);
      }

      r.dateFrom = new Date(2021,5,1);
      r.dateTo = new Date(2021,5,24);
      r.resources = this.resources;
      r.jobs = this.jobs;
      r.render();

      this.renderer = r;
    }
  }

  window.customElements.define('gantt-chart', VanillaGanttChart);

