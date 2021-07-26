<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col form-inline">
        <form @submit.prevent="handleAddNewColumn">
          <div>
            <input class="" type="text" placeholder="Enter a title" v-model.trim="newColumn.title">
            <button class="">
                Add Column
            </button>
            <div v-show="errorMessage">
              <span class="">
                {{ errorMessage }}
              </span>
            </div>            
          </div>
        </form>
      </div>
    </div>
    <div class="row mt-5">
      <div v-for="column in columns" :key="column.slug" class="col-3">
        <div class="p-2 alert alert-primary">
          <h3>{{ column.title }}</h3>

          <button class="" @click="openAddCard(column.id)">
            Add Card
          </button>

          <AddCard
              v-if="newCardForColumn === column.id"
              :column-id="column.id"
              v-on:card-added="handleCardAdded"
              v-on:card-canceled="closeAddCard"
          />
            <draggable
              class="flex-1 overflow-hidden"
              v-model="column.cards"
              v-bind="cardDragOptions"
              @end="handleCardMoved"
            >
            <div v-for="card in column.cards" :key="card.id" class="list-group-item">
              {{ card.title }}
            </div>
          </draggable>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
//import draggable
import draggable from "vuedraggable";
import AddCard from "./AddCard"; // import the AddCard component
export default {
  name: "kanban-test",
  components: {
    //import draggable as a component
    draggable,
    AddCard 
  },
  props: {
    initialData: []
  },
  data() {
    return {
        columns: [],
        newCardForColumn: 0, // track the ID of the column we want to add to
        newColumn: {
          title: "",
          slug: ""
        },
        errorMessage: ""
    };
  },
  mounted() {
    // 'clone' the columns so we don't alter the prop when making changes
    this.columns = JSON.parse(this.initialData);
  },
  computed: {
    cardDragOptions() {
      return {
        animation: 200,
        group: "card-list"
      };
    }
  },
  methods: {
    // set the columnsId and trigger the form to show 
    openAddCard(columnId) {
      this.newCardForColumn = columnId;
    },
    // reset the columnId and close form
    closeAddCard() {
      this.newCardForColumn = 0;
    },
    // add a card to the correct column in our list
    handleCardAdded(newCard) {
      // Find the index of the column where we should add the card
      const columnIndex = this.columns.findIndex(
        column => column.id === newCard.columns_id
      );
 
      // Add newly created card to our column
      this.columns[columnIndex].cards.push(newCard);

      // Reset and close the AddTaskForm
      this.closeAddCard();
    },
    handleCardMoved() {
      // Send the entire list of columns to the server
      axios.put("/kanban-board/public/cards/sync", {columns: this.columns}).catch(err => {
        console.log(err.response);
      });
    },
    handleAddNewColumn(){
      // Basic validation so we don't send an empty column to the server
      if (!this.newColumn.title) {
        this.errorMessage = "The title field is required";
        return;
      }
      this.newColumn.slug = this.newColumn.title.toLowerCase();
      axios.post("/kanban-board/public/columns", this.newColumn)
      .then(res => {
          // Tell the component we've added a new column and include it
          this.columns.push(res.data);
        })
      .catch(err => {
        console.log(err.response);
      });
    }
  }
};
</script>

<style>
/* light stylings for the kanban columns */
.kanban-column {
  min-height: 300px;
}
</style>