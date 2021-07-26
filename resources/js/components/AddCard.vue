<template>
  <form @submit.prevent="handleAddNewCard">
  
    <div class="">
      <input
        class=""
        type="text"
        placeholder="Enter a title"
        v-model.trim="newCard.title"
      />

      <textarea
        class=""
        rows="2"
        placeholder="Add a description"
        v-model.trim="newCard.description"
      ></textarea>
      
      <div v-show="errorMessage">
        <span class="">
          {{ errorMessage }}
        </span>
      </div>
    </div>

    <div class="">
      <button
        @click="$emit('card-canceled')"
        type="reset"
        class=""
      >
        cancel
      </button>

      <button
        type="submit"
        class=""
      >
        Add
      </button>
    </div>

  </form>
</template>

<script>
export default {
  props: {
    columnId: Number
  },
  data() {
    return {
      newCard: {
        title: "",
        description: "",
        columns_id: null,
        state: 0
      },
      errorMessage: ""
    };
  },
  mounted() {
    this.newCard.columns_id = this.columnId;
  },
  methods: {
    handleAddNewCard() {
      // Basic validation so we don't send an empty card to the server
      if (!this.newCard.title) {
        this.errorMessage = "The title field is required";
        return;
      }
      // Send new card to server
      axios
        .post("/kanban-board/public/cards", this.newCard)
        .then(res => {
          // Tell the parent component we've added a new card and include it
          console.log(res.data);
          this.$emit("card-added", res.data);
        })
        .catch(err => {
          // Handle the error returned from our request
           console.log(err);
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
    }
  }
};
</script>