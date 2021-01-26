const post_notification_prefix = 'notifyPost';
const comment_notification_prefix = 'notifyComment';



const AttributeBinding = {
    data() {
        return {
            // @todo name it registerForm,
            register: {},
            // @todo change it to loginForm,
            login: {},
            user: null,
            pushNotification: {
                sendTo: 'tokens',
                receiverInfo: 'eiV0BhcxEMJ2iUa-BC5tb2:APA91bGL6PeuIgri5xDQ7amb6SSEu3lCS_4cTumfpP6SbTRayntsHhq0Ay-4yY0qNwoXc8QzSkJ8VfCERC4UnUTHNWAQVX36cc9I2FeIWusAKUOiqu6U2RTCrMhX7DnO60lc0CWCnjod',
                title: 'abcd',
                body: 'efgh',
                click_action: '/'
            },
            modal: {
                active: false,
                eventName: '',
                title: '',
                content: '',
            },
            /// translation
            newLanguage: '',
            languages: [],
            translations: [],
        }
    },
    created() {
        console.log('created!');
        this.getUser();
    },
    mounted() {
        console.log('mounted!');
    },
    methods: {
        isAdmin() {
            return this.$data.user && this.$data.user.admin;
        },
        loggedIn() {
            return this.$data.user !== null && this.$data.user.session_id;
        },
        sessionId() {
            return this.$data.user && this.$data.user.session_id;
        },
        notLoggedIn() {
            return ! this.loggedIn();
        },
        onRegisterFormSubmit() {
            console.log('register form submitted');
            console.log(this.$data.register);
            request('user.register', this.$data.register, this.setUser, this.error);
        },
        onLoginFormSubmit() {
            request('user.login', this.$data.login, function(profile) {
                app.setUser(profile);
                move('/');
            }, this.error);
        },
        onProfileUpdateSubmit(data) {
            request('user.profileUpdate', data, this.setUser, this.error);
        },
        logout() {
            localStorage.removeItem('user');
            this.$data.user = null;
        },
        error(e) {
            console.log('alert(e)', e);
            alert(e);
        },
        setUser(profile) {
            this.set('user', profile);
            this.$data.user = profile;
        },
        getUser() {
            this.$data.user = this.get('user');
            return this.$data.user;
        },
        set(name, value) {
            value = JSON.stringify(value);
            localStorage.setItem(name, value);
        },
        get(name) {
            const val = localStorage.getItem(name);
            if ( val ) {
                return JSON.parse(val);
            } else {
                return val;
            }
        },
        alert(title, body) {
            alert(title + "\n" + body);
        },
        saveToken(token, topic = '') {
            request('notification.updateToken', { token: token, topic: topic }, function (re) {
                // console.log(re);
            }, this.error);
        },
        sendPushNotification() {
            // console.log(this.$data.pushNotification.title);
            // if (this.$data.pushNotification.title === void 0 && this.$data.pushNotification.title === void 0) return alert('Title or Body is missing');
            console.log("sendPushNotification::", this.$data.pushNotification);

            let route = '';
            const data = {
                title: this.$data.pushNotification.title,
                body: this.$data.pushNotification.body,
                click_action: this.$data.pushNotification.click_action
            };
            if (this.$data.pushNotification.sendTo === 'topic' ) {
                route = 'notification.sendMessageToTopic';
                data['topic'] = this.$data.pushNotification.receiverInfo;
            } else if (this.$data.pushNotification.sendTo === 'tokens' ) {
                route = 'notification.sendMessageToTokens';
                data['tokens'] = this.$data.pushNotification.receiverInfo;
            } else if (this.$data.pushNotification.sendTo === 'users' ) {
                route = 'notification.sendMessageToUsers';
                data['users'] = this.$data.pushNotification.receiverInfo;
            }
            request(route, data, function(res) {
                // console.log(res);
            }, this.error);
        },
        showModal(eventName) {
            this.$data.modal.active = true;
            this.$data.modal.eventName = eventName;
        },
        hideModal(trigger = false) {
            this.$data.modal.active = false;
            if (trigger) {
                this.$emit(this.$data.modal.eventName)
                console.log(this.$data.modal.eventName);
            }
        },
    }
};
const _app = Vue.createApp(AttributeBinding);
_app.mixin(forumMixin);
if ( typeof mixin !== 'undefined' ) {
    _app.mixin(mixin);
}
const app = _app.mount('#app');


