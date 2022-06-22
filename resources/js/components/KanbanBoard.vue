<template>
  <div class="kanban-board">
    <div
      v-for="column in columns"
      :key="column.id"
      class="card-wrapper"
    >
      <div class="card-content">
        <div class="card-header">
          <h4 class="card-title">
            {{ column.title }}
          </h4>
          <span class="close" @click="deleteColumn(column)"></span>
        </div>
        <div class="card-body">
          <draggable
            class="task-drag"
            v-model="column.tasks"
            v-bind="taskDragOptions"
            @end="handleTaskMoved"
          >
            <transition-group
              class="task-drag-transition"
              tag="div"
            >
              <div
                v-for="task in column.tasks"
                :key="task.id"
                class="task-item"
                @click="openEditTaskModal(task.id)"
              >
                <span class="block mb-2 text-xl text-gray-900">
                  {{ task.title }}
                </span>
              </div>
              <!-- ./Tasks -->
            </transition-group>
          </draggable>
          
        </div>
        <div class="card-footer">
          <button
            class="create-task-btn"
            @click="openAddTaskModal(column.id)"
          >
            Create Task
          </button>
        </div>
      </div>
      
    </div>
    <div class="create_button_wrapper">
      <button class="create-column-btn" v-on:click="openAddColumnModal();">Create Column</button>
    </div>
    
  </div>
  
</template>

<script>
import draggable from "vuedraggable";
import AddColumnModal from "./AddColumnModal";
import AddTaskModal from "./AddTaskModal";
import EditTaskModal from "./EditTaskModal";

export default {
  components: { draggable, AddColumnModal, AddTaskModal, EditTaskModal },
  props: {
    initialData: Array
  },
  data() {
    return {
      columns: [],
      newTaskForColumn: 0,
    };
  },
  computed: {
    taskDragOptions() {
      return {
        animation: 200,
        group: "task-list",
        dragClass: "column-drag"
      };
    }
  },
  mounted() {
    this.columns = JSON.parse(JSON.stringify(this.initialData));
  },
  methods: {
    openAddColumnModal() {
      this.$modal.show(
        AddColumnModal,
        {
          update: this.handleColumnAdded
        },
        {
          name: "addColumnModal",
          draggable: true,
          width: 400,
          height: 'auto',
          shiftY: 0.1
        },
      );
    },
    handleColumnAdded(newColumn) {
      Vue.set(newColumn, "tasks", [])
      this.columns.push(newColumn);
    },
    deleteColumn(column) {
      if(confirm("Do you really want to delete?")){
        axios
          .delete("/columns/" + column.id)
          .then(res => {
            this.columns.splice(this.columns.indexOf(column), 1);
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    openAddTaskModal(columnId) {
      this.newTaskForColumn = columnId;
      this.$modal.show(
        AddTaskModal,
        {
          columnId: this.newTaskForColumn,
          update: this.handleTaskAdded
        },
        {
          draggable: true,
          width: 400,
          height: 'auto',
          shiftY: 0.1
        },
      );
    },
    handleTaskAdded(newTask) {
      const columnIndex = this.columns.findIndex(
        column => column.id === newTask.column_id
      );
      this.columns[columnIndex].tasks.push(newTask);
    },
    openEditTaskModal(taskId) {
      axios
        .post("/task", {taskId: taskId})
        .then(res => {
          var task = res.data;
          this.$modal.show(
            EditTaskModal,
            {
              task: task,
              update: this.handleTaskEdit
            },
            {
              draggable: true,
              width: 400,
              height: 'auto',
              shiftY: 0.1
            },
          );
        })
        .catch(err => {
          console.log(err);
        });
      
    },
    handleTaskEdit(newTask) {
      const columnIndex = this.columns.findIndex(
        column => column.id === newTask.column_id
      );

      const taskIndex = this.columns[columnIndex].tasks.findIndex(
        task => task.id === newTask.id
      );

      this.columns[columnIndex].tasks[taskIndex] = newTask;
      this.$forceUpdate();
    },    
    handleTaskMoved(evt) {
      axios.put("/tasks/sync", { columns: this.columns }).catch(err => {
        console.log(err.response);
      });
    },
  }
};
</script>

<style scoped>
.column-drag {
  transition: transform 0.5s;
  transition-property: all;
}
</style>
