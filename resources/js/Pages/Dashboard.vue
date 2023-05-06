<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Home</h2>
      </template>

      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg relative px-2 pt-4 pb-14">
            <form @submit.prevent="submitTweet">
              <textarea
                v-model="tweet.body"
                name="body"
                class="focus:outline-none focus:ring-0 resize-none shadow-none border-none h-60 w-full"
                placeholder="What's on your mind?"
                autocomplete="off"
              ></textarea>
              <PrimaryButton type="submit" class="ml-4 absolute right-6 bottom-6">
                Post
              </PrimaryButton>
            </form>
          </div>
          <Tweets :tweets="tweets" />
        </div>
      </div>
    </AuthenticatedLayout>
  </template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Tweets from './partials/Tweets.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watchEffect } from 'vue';
import Echo from 'laravel-echo';
// import { io } from 'socket.io-client';

import Pusher from 'pusher-js';
window.Pusher = Pusher;


export default {
  components: {
    AuthenticatedLayout,
    PrimaryButton,
    Tweets,
  },

  setup() {
    const tweet = ref({
      body: '',
    });


    const tweets = ref([]);

    const submitTweet = () => {
      axios
        .post('/tweet', tweet.value)
        .then(response => {
          tweet.value.body = '';
        })
        .catch(error => {
          console.error('An error occurred while posting the tweet.');
        });
    };

    const likeTweet = (tweetId) => {
      axios.post(`/tweets/${tweetId}/like`)
        .then(response => {
          const updatedTweetIndex = tweets.value.findIndex(tweet => tweet.id === tweetId);
          if (updatedTweetIndex !== -1) {
            tweets.value.splice(updatedTweetIndex, 1, response.data.tweet);
          }
        })
        .catch(error => {
          console.error('An error occurred while liking the tweet.');
        });
    };

    const unlikeTweet = (tweetId) => {
      axios.delete(`/tweets/${tweetId}/like`)
        .then(response => {
          const updatedTweetIndex = tweets.value.findIndex(tweet => tweet.id === tweetId);
          if (updatedTweetIndex !== -1) {
            tweets.value.splice(updatedTweetIndex, 1, response.data.tweet);
          }
        })
        .catch(error => {
          console.error('An error occurred while unliking the tweet.');
        });
    };

    const deleteTweet = (tweetId) => {
      axios.delete(`/tweets/${tweetId}`)
        .then(response => {
          const deletedTweetIndex = tweets.value.findIndex(tweet => tweet.id === tweetId);
          if (deletedTweetIndex !== -1) {
            tweets.value.splice(deletedTweetIndex, 1);
          }
        })
        .catch(error => {
          console.error('An error occurred while deleting the tweet.');
        });
    };

    return {
      tweet,
      tweets,
      submitTweet,
      likeTweet,
      unlikeTweet,
      deleteTweet,
    };
  },
};
</script>
