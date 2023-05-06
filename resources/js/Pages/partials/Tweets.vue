<template>
    <div>
      <h3 class="text-3xl font-bold pt-6 pb-3">Latest Tweets</h3>

      <div class="flex flex-col gap-4">
        <Tweet
          v-for="tweet in tweets"
          :key="tweet.id"
          :id="tweet.id"
          :name="tweet.user.name"
          :joined="tweet.user.created_at"
          :posted="tweet.created_at"
          :body="tweet.body"
          :likes="tweet.likes"
          :editable="tweet.editable"
          :owned="tweet.editable"
          @edit-tweet="editTweet(tweet.id, $event.body)"
          @delete-tweet="deleteTweet(tweet.id)"
          @like-tweet="likeTweet(tweet.id, $event.body)"
          @unlike-tweet="unlikeTweet(tweet.id)"
        />
      </div>
    </div>
  </template>
<script>
import Tweet from "@/Components/Tweet.vue";
import { ref, watchEffect } from "vue";
import Echo from "laravel-echo";
// import { io } from "socket.io-client";
import axios from "axios";

export default {
  components: {
    Tweet,
  },
  props: {
    tweets: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const tweets = ref([]);
    watchEffect(() => {
      axios
        .get("/tweets")
        .then(response => {
          tweets.value = response.data;
        })
        .catch(error => {
          console.error("An error occurred while fetching tweets.");
        });

        window.Echo.channel('tweet')
        .listen('TweetCreated', data => {
            tweets.value.unshift(data.tweet);
            console.log(data.tweet)
        })
        .listen("TweetDeleted", data => {
            console.warn('TweetDeleted')
            const index = tweets.value.findIndex(tweet => tweet.id === data.tweet.id);
            console.warn(index)
                tweets.value.splice(index, 1);
        })
        .listen("TweetUpdated", data => {
            console.warn('TweetUpdated')
            const index = tweets.value.findIndex(tweet => tweet.id === data.tweet.id);
            console.warn(index)
                tweets.value.splice(index, 1, data.tweet);
        });
    });
    const editTweet = (id, body) => {
        console.log(`YAO: Editing tweet ${id} with body: ${body}`);

      axios
        .put(`/tweet/${id}`, { body })
        .then(response => {
            // window.Echo.channel("tweets").emit(".tweet.updated", { tweet: response.data });
        })
        .catch(error => {
          console.error(`An error occurred while updating tweet ${id}.`);
        });
    };
    const deleteTweet = id => {
        axios.delete(`/tweet/${id}`)
        .then(() => {
        })
        .catch(error => {
          console.error(error);
        });
    };
    const likeTweet = id => {
        axios
            .post(`/tweet/${id}/like`)
            .then(response => {
                const tweetIndex = tweets.value.findIndex(tweet => tweet.id === id);
                if (tweetIndex !== -1) {
                    tweets.value[tweetIndex].likes++;
                }
            })
            .catch(error => {
            console.error(`An error occurred while toggling like for tweet ${id}.`);
            });
    };
    const unlikeTweet = id => {
        axios
            .post(`/tweet/${id}/unlike`)
            .then(response => {
                const tweetIndex = tweets.value.findIndex(tweet => tweet.id === id);
                if (tweetIndex !== -1) {
                    tweets.value[tweetIndex].likes--;
                }
            })
            .catch(error => {
            console.error(`An error occurred while toggling like for tweet ${id}.`);
            });

    };
    return {
      tweets,
      editTweet,
      deleteTweet,
      likeTweet,
      unlikeTweet,
    };
  },
};
</script>
