<template>
<div class="container py-5">

    <!--Add Column-->
    <div class="">
        <form @submit.prevent="handleAddNewColumn">
          <div>
            <input class="" type="text" placeholder="Enter a title" v-model.trim="newColumn.title">
            <button class="btn btn-primary">
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

  <div class="row">
    <!-- Start lane -->
    <div v-for="column in columns" :key="column.slug" class="col-12 col-lg-4">
      <div class="card mb-3">
        <div class="card-header bg-light">
          <form @submit.prevent="handleDeleteColumn(column.id)">
            <div class="float-sm-right">            
                <button class="btn btn-danger">
                    X
                </button>            
            </div>
          </form>
          <h3 class="card-title h5 mb-1">
            {{ column.title }}
          </h3>
        </div>
        <div class="card-body">
          <div class="tasks">
            <!-- Start card -->
            <draggable
              class="flex-1 overflow-hidden"
              v-model="column.cards"
              v-bind="cardDragOptions"
              @end="handleCardMoved"
            >
            <div v-for="card in column.cards" :key="card.id" class="card mb-3 cursor-grab list-group-item">
              <div class="card-body">
                <p class="mb-0">{{ card.title }}</p>                
              </div>
            </div>
            </draggable>
            <!-- End card -->
          </div>
          <button class="btn btn-primary btn-block" @click="openAddCard(column.id)">Add Card</button>
            <AddCard
                v-if="newCardForColumn === column.id"
                :column-id="column.id"
                v-on:card-added="handleCardAdded"
                v-on:card-canceled="closeAddCard"
            />
        </div>
      </div>
    </div>
    <!-- End lane -->

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

      if(typeof this.columns[columnIndex].cards ==='undefined') {
        //For first card on column
        this.columns[columnIndex]=[].push(newCard);
        location.reload();
      }else{
 
      // Add newly created card to our column
      this.columns[columnIndex].cards.push(newCard);
      }
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
      //Reset input
      this.newColumn.title = "";
    },
    handleDeleteColumn(columnId) {
      axios.delete("/kanban-board/public/columns/del/" + columnId)
      .then(res => {
          if(res.data) {
            //To investigate if splice is in the vuejs limitations
            this.columns.splice((columnId -1), 1);         
             
             location.reload();
          }
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
/*.kanban-column {
  min-height: 300px;
}*/
.cursor-grab {
  cursor: -webkit-grab;
  cursor: grab;
}
</style>