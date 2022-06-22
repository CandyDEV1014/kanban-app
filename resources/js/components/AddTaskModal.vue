<template>
  <div class="modal-content">
    <p class="title">Create New Task</p>
    <form class="task-form" @submit.prevent="handleAddNewTask">
      <div class="input-group">
        <input class="inputTitle" type="text" placeholder="Title" v-model.trim="newTask.title" @change="handleChange">
        <div v-show="errorMessage">
          <span class="text-error">
            {{ errorMessage }}
          </span>
        </div>
      </div>
      <div class="input-group">
        <textarea class="inputDescription" placeholder="Description (optional)" rows="4"></textarea>
      </div>
      <div class="modal-button-group">
        <button type="reset" class="close_btn" @click="$emit('close')">
          Cancel
        </button>
        <button type="submit" class="">
          Create
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    columnId: Number
  },
  data() {
    return {
      newTask: {
        title: "",
        description: "",
        column_id: null
      },
      errorMessage: ""
    };
  },
  mounted() {
    this.newTask.column_id = this.columnId;
  },
  methods: {
    handleAddNewTask() {
      // Basic validation so we don't send an empty task to the server
      if (!this.newTask.title) {
        this.errorMessage = "The title field is required";
        return;
      } else {
        this.errorMessage = "";
      }

      // Send new task to server
      axios
        .post("/tasks", this.newTask)
        .then(res => {
          // Tell the parent component we've added a new task and include it
          this.$attrs.update(res.data);
          // close modal
          this.$emit("close");
        })
        .catch(err => {
          // Handle the error returned from our request
          this.handleErrors(err);
        });
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        // We have a validation error
        const errorBag = err.response.data.errors;
        if (errorBag.title) {
          this.errorMessage = errorBag.title[0];
        } else if (errorBag.description) {
          this.errorMessage = errorBag.description[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        // We have bigger problems
        console.log(err.response);
      }
    },
    handleChange() {
      if (!this.newTask.title) {
        this.errorMessage = "The title field is required";
        return;
      } else {
        this.errorMessage = "";
      }
    }
  }
};
</script>