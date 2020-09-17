<?php
/**
 * Module Message translations (this file must be saved in UTF-8 encoding).
 * It merges messages from below sources in sequence:
 * [1] application level messages 
 * [2] common module level messages (inclusive of kernel common messages)
 * [3] local module level messages
 */
$appMessages = require(Yii::app()->basePath.DIRECTORY_SEPARATOR.'messages/zh_cn/sii.php');//already inclusive kernel messages
$moduleName = basename(dirname(__DIR__, 2));//move two levels up and grep module name
$moduleMessages = Sii::findMessages(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.KERNEL_NAME,$moduleName,'zh_cn');
return array_merge($appMessages,$moduleMessages,[
    'Ask a Question'=>'提出问题',
    'Ask Questions'=>'参与发问',
    'Ask questions and get reply from our active and friendly community, or help others by answering their questions.'=>'您可提出问题并从我们活跃且友善的社区得到答复，或者帮助回答他人的问题。',
    'A wide range of topics'=>'广泛的课题',
    'Choose any specific topics that you are interested in and learn more.'=>'选择您所感兴趣的课题并了解更多。',
    'Community'=> '社区',
    'Be The First One To Answer This Question'=>'做第一个答复这问题的人',
    'Be The First One To Make A Comment'=>'做第一个评论这教程的人',
    'Filtered by topic "{topic}"'=>'课题筛选 "{topic}"',
    'Help each other by Q&A'=>'通过问与答互相帮助',
    'Learn from curated tutorials'=>'精心编制的教程帮助您学习',
    'Read Tutorials'=>'阅读教程',
    'Master Tutorial Series'=>'进修教程系列',
    'Fast track learning'=>'系列集结相关教程让您更快速上手',
    'Search community'=>'搜索社区',
    'Search {object}'=>'搜索{object}',
    'Search Topics'=>'搜索课题',
    'End'=> '本文结束',
    'Tagged in'=> '标签',
    'tagged in'=> '标签为',
    'Collected in series'=>'收编於系列',
    'Topics'=>'课题',
    'Tutorial Series'=>'教程系列',
    'Series'=>'教程系列',
    'We make short courses to help you get started even easier and faster.'=>'我们制作短期课程让您更快更易学习。',
    'We have many tutorials to help you get started, learn, and become experts. You can also make contributions to the community by writing your own.'=>'我们这里有很多教程能帮助您开始，学习，并成为专家。您也可以通过编写自己的教程为社区作出贡献。',
    'Write a Tutorial'=> '编写教程',
    'Write your answer for this question'=>'我想为这问题提供答复',
    'View previous answers'=>'查看之前的答复',
    'View previous comments'=>'查看之前的评论',
    'Write a comment for this {target}'=>'我想要给这{target}发表评论',
    'You must be logged in to post comment.'=>'您必须登入才能发表评论。',
    'You must be logged in to answer question.'=>'您必须登入才能答复问题。',
    '1#{n} Comment|n>1#{n} Comments'=>'{n}条评论',
    '1#{n} Answer|n>1#{n} Answers'=>'{n}条答复',
    '{app} Community'=>'{app}社区', 
    'n<=1#{n} Like|n>1#{n} Likes'=>'{n}喜欢',    
    'Related to {series}, see also:'=>'请参阅教程系列{series}其他相关内容:',
]);
