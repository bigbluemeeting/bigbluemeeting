
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('upcoming-meetings', require('./components/Meetings/UpcomingMeetings.vue'));
Vue.component('past-meetings', require('./components/Meetings/PastMeetings.vue'));
Vue.component('delete-modal',require('./components/Meetings/deleteModal.vue'));
Vue.component('invite-participants',require('./components/Meetings/addParticipants.vue'));
Vue.component('meetings-files',require('./components/Meetings/Files/meetingFiles.vue'));
Vue.component('room-list',require('./components/Rooms/roomList.vue'));
Vue.component('invited-meetings-list',require('./components/invitedMeeting/invitedMeetingsList.vue'));
Vue.component('create-users',require('./components/users/create.vue'));
Vue.component('users-list',require('./components/users/usersList.vue'));

export const eventBus = new Vue({
    methods: {
        deleteID (data) {
            this.$emit('deleteId', data);

            },
        upcomingMeetings(data)
        {
            this.$emit('upcomingMeeting', data);

        },
        pastMeetings(data)
        {
            this.$emit('pastMeetings', data);

        },
        newUser(data)
        {
            this.$emit('userAdded',data);
        },
        editUser(data)
        {
            this.$emit('userEdit',data);

        }




    },
});

const app = new Vue({
    el: '#app'
});
