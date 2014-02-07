# Fusion Framework Social module

Contains the following features:

 - forums
 - private chat
 - news

## Tasks

## Cronjobs

## Notifications

**mail.message**
When creating a new message

Variables: :username(sender), :other_username(receiver), :message_id

**mail.reply**
When relpying to a message

Variables: :username(sender), :other_username(receiver), :message_id

## Events

**forum.parse**
After creating a new topic/reply.

The argument $model will either be Model_Quill_Topic or Model_Quill_Topic

$type will be either 'topic' or 'reply'

*Arguments: $model, $type*

**forum.render**
Before showing a topic/reply's content.

a chance to change $model->content before it's sent to the template.

*Arguments: $model*

**forum.parse**
After creating/replying to a topic.

*Arguments: Model_Quill_Reply|Model_Quill_Topic $model*

**msg.render**
Before showing a message's content.

a chance to change $model->content before it's sent to the template.

*Arguments: $model*

**news.render**
Before showing a news post/reply's content.

a chance to change $model->content before it's sent to the template.

*Arguments: Model_Quill_Topic|Model_Quill_Reply $model*

**news.parse**
After replying to a news post.

*Arguments: Model_Quill_Reply $model*