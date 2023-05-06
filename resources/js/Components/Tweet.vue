<template>
    <div class="flex bg-white shadow-sm rounded-lg w-full">
      <div class="flex items-start px-4 py-6 w-full">
        <img
          class="w-12 h-12 rounded-full object-cover mr-4 shadow"
          :src="avatarUrl"
          :alt="name + ' profile picture'"
        />
        <div class="w-full">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 -mt-1">{{ name }}</h2>
            <small class="text-sm text-gray-700">{{ posted }}</small>
          </div>
          <p class="text-gray-700">Joined {{ joined }}</p>
          <div v-if="editing">
            <textarea
              v-model="editedBody"
              class="focus:outline-none focus:ring-0 resize-none shadow-none h-auto w-full  pt-2 p-0 mt-3 text-gray-700 text-sm"
            ></textarea>
            <div class="mt-4 flex items-center gap-3">
              <PrimaryButton @click="saveTweet">Save</PrimaryButton>
              <SecondaryButton @click="cancelEdit">Cancel</SecondaryButton>
            </div>
          </div>
          <p class="mt-3 text-gray-700 text-sm pt-2" v-else>{{ body }}</p>
          <div class="mt-4 flex items-center">
            <div
              class="flex items-center mr-2 text-gray-700 text-sm mr-3"
              @click="toggleLike"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                :fill="liked ? 'red' : 'none'"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-heart"
              >
                <path
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                />
              </svg>
              <span class="pl-3">{{ likes }}</span>
            </div>
            <div class="flex gap-4">
              <PrimaryButton
                v-if="canEditTweet && !editing"
                @click="editTweet"
              >Edit</PrimaryButton>
              <DangerButton
                v-if="canEditTweet && !editing"
                @click="deleteTweet"
              >Delete</DangerButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>
import PrimaryButton from "@/components/PrimaryButton.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import DangerButton from "@/components/DangerButton.vue";

export default {
  components: {
    PrimaryButton,
    SecondaryButton,
    DangerButton,
  },
  props: {
    id: {
      type: Number,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    body: {
      type: String,
      required: true,
    },
    joined: {
      type: String,
      required: true,
    },
    posted: {
      type: String,
      required: true,
    },
    likes: {
      type: Number,
      required: true,
    },
    editable: {
      type: Boolean,
      required: false,
      default: false,
    },
    owned: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      editing: false,
      editedBody: "",
      liked: false,
    };
  },
  computed: {
    avatarUrl() {
      return `https://i.pravatar.cc/150?u=${this.name}`;
    },
    canEditTweet() {
      return this.editable && this.owned;
    },
  },
  mounted() {
    // Check if the user has already liked this tweet
    if (localStorage.getItem(`liked_${this.id}`)) {
        this.liked = true;
    }
  },
  methods: {
    deleteTweet() {
      console.log('function deleteTweet() executed')
      this.$emit("delete-tweet", this.id);
    },
    editTweet() {
      console.log('function editTweet() executed')
        if (this.editable) {
            this.editing = true;
            this.editedBody = this.body;
        }
    },
    saveTweet() {
        console.log('function saveTweet() executed: ' + this.editedBody + " :::" + this.body);
        this.editing = false;
        this.$emit("edit-tweet", { id: this.id, body: this.editedBody });
        localStorage.setItem(`tweet_${this.id}`, this.editedBody);
    },
    cancelEdit() {
      console.log('function cancelEdit() executed')
      this.editing = false;
    },
    renderTweet(tweet) {
        const tweetElement = document.createElement('div');
        tweetElement.classList.add('tweet');

        const bodyElement = document.createElement('p');
        bodyElement.classList.add('body');
        bodyElement.textContent = text; // Set the tweet body from localStorage

        tweetElement.appendChild(bodyElement);
        return tweetElement;
    },
    toggleLike() {
        console.log('function toggleLike() executed')

        // Check if the user has already liked this tweet
        if (localStorage.getItem(`liked_${this.id}`)) {
            localStorage.removeItem(`liked_${this.id}`);
            this.$emit("unlike-tweet", this.id);
            this.liked = false;
            return;
        }

        // If the user hasn't already liked this tweet, toggle the "liked" state

        // Save the "liked" state in localStorage
        localStorage.setItem(`liked_${this.id}`, true);
        this.$emit("like-tweet", this.id);

        this.liked = true;
    }

  },
};

</script>
