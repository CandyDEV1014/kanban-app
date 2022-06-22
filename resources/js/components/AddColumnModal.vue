<template>
  <div class="modal-content">
    <p class="title">Create New Column</p>
    <form class="column-form" @submit.prevent="handleAddNewColumn">
      <div class="input-group">
        <input class="inputTitle" type="text" placeholder="Title" v-model.trim="newColumn.title" @change="handleChange">
        <div v-show="errorMessage">
          <span class="text-error">
            {{ errorMessage }}
          </span>
        </div>
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
    text: String,
  },
  data() {
    return {
      newColumn: {
        title: "",
      },
      errorMessage: ""
    };
  },
  mounted() {},
  methods: {
    handleAddNewColumn() {
      // Basic validation so we don't send an empty task to the server
      if (!this.newColumn.title) {
        this.errorMessage = "The title field is required";
        return;
      } else {
        this.errorMessage = "";
      }

      // Send new column to server
      axios
        .post("/columns", this.newColumn)
        .then(res => {
          // Tell the parent component we've added a new column and include it
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
      if (!this.newColumn.title) {
        this.errorMessage = "The title field is required";
        return;
      } else {
        this.errorMessage = "";
      }
    }
  }
};
</script>