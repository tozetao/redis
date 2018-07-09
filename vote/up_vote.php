<?php

/*
需求：
    假设某个网站一天发布1000篇文章，如果其中有一片文章获得200赞票，那么表示该文章很受欢迎，
    该网站每天会把50篇赞票最高的文章放到文章列表页前100一天。


文章的评分
    评分 = 当前时间戳 + 赞票，该评分会随着时间流失而不断减少(与其他文章对比)

数据结构
    1. 俩个有序集合zset
        一个以文件发布时间为分数，一个以评分为分数。

        time:article_id
        votes:article_id

    2. 一个存储文章已投票的散列(hash)
        key = article:article_id，存储每篇文章已投票的用户ID，确保一篇文章同一个用户只能投票一次。
        为了节约内存，我们定义一篇文章只能在7天内进行投票，超过该时间限制将无法投票，因此定义一个7天时间的过期时间。

        voted:article_id

    3. 一个存储文章少量信息的散列(hash)
        存储文章的ID、文章赞票、文章时间、文章作者等信息

        article:article_id



添加一篇文章

实现投票功能
*/

const ONE_WEEK_IN_SECONDS = 7 * 86400;
const VOTE_SCORE = 432;

function getRedis()
{
    return new Redis();
}

function upVote($articleId, $userId)
{
    $redis = getRedis();

    //1. 判断文章是否在可投票期间
    $cutoff = time() - ONE_WEEK_IN_SECONDS;
    $createdAt = $redis->zScore('time:', $articleId);
    if($createdAt < $cutoff)
        return false;

    //2. 判断用户是否已投票
    if($redis->sAdd('voted:' . $articleId, $userId))
    {
        //增加文章评分

        //增加文章赞票
    }

}