<?php

$category = '';
$post = null;

if (isset($_REQUEST['category'])) {
    $category = $_REQUEST['category'];
} else if(isset($_REQUEST['ID'])){
    $post = get_post($_REQUEST['ID'], ARRAY_A);
    $category = get_the_category($post['ID'])[0]->slug;
}

//print_r($post);

?>

<h1>Admin send push notification</h1>

@TODO
*when reply send notification

*send notification on forum if admin and selected a forum/post
***forum subscription/ comment subscription (topic)<br>

post only
post and comment

***comment is created ancestors

*add click/route so when notification is click,
move the notification to the specific page.

user settings page
push notification under his comment/post


<form @submit.prevent="sendPushNotification">
    <div class="form-group">
        <select class="form-control mb-2 col-12 col-sm-6 col-md-3" v-model="pushNotification.sendTo">
            <option value="topic">Topic</option>
            <option value="tokens">Tokens</option>
            <option value="users">Users</option>
        </select>
        <input type="text" class="form-control" id="sendTo" name="sendTo" :placeholder="pushNotification.sendTo" v-model="pushNotification.receiverInfo">
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="title" v-model="pushNotification.title">
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <input type="text" class="form-control" id="body" name="body" aria-describedby="body" v-model="pushNotification.body">
    </div>
    <div class="form-group">
        <label for="click_action">Click Action</label>
        <input type="text" class="form-control" id="click_action" name="click_action" aria-describedby="click_action" v-model="pushNotification.click_action">
    </div>
    <button type="submit" class="btn btn-primary px-5" type="submit">Send</button>
</form>



<script>
    const ID = "<?=$post['ID'] ?? '';?>";
    const title = "<?=$post['post_title'] ?? '';?>";
    const body = "<?=$post['post_content'] ?? '';?>";
    const click_action = "<?=$post['guid'] ?? ''?>";
    const topic = "<?=$category ?? ''?>";

    const mixin = {
        created() {
            console.log('send push notification.created!');
        },
        mounted() {
            console.log('send push notification.mounted!');
            this.$data.pushNotification = {
                sendTo: ID ? 'topic' : 'tokens',
                receiverInfo: topic ? config.post_notification_prefix + topic : '',
                title: title,
                body: body,
                click_action: click_action ?? "/"
            }
        },
        data() {
            return {
                pushNotification: {
                    sendTo: 'topic',
                    receiverInfo: config.allTopic,
                    title: '',
                    body: '',
                    click_action: "/"
                },
            }
        },
        methods: {
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
                    console.log(res);
                    alert('Send Push Success');
                }, this.error);
            },
        }
    }
</script>

