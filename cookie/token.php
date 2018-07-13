<?php

const LIMIT = 10000000;

function getRedis()
{
    return new Redis();
}

//检验令牌，返回用户数据
function check_token($token)
{
    $redis = getRedis();
    return $redis->hget('login:', $token);
}

//添加购物车
function add_to_cart()
{

}

//更新token，用户在浏览商品的时候会调用该方法
function update_token($token, $user, $item = null)
{
    $redis = getRedis();

    //1. 维持令牌与登录用户的映射
    $redis->hSet('login_', $token);

    //2. 记录令牌的使用时间
    $redis->zAdd('recent_', $user);

    if($item)
    {
        //3. 添加用户访问商品的记录
        $redis->zAdd('viewed_' . $token, time(), $item);

        //移除0-倒数第26个索引之间的元素，实际运行中如果有序集合满足26个元素后，0下标的元素会被移除，
        //所以最多保存25个元素。
        $redis->zRemRangeByRank('viewed_' . $token, 0, -26);
    }
}

function clean_full_sessions()
{

}


/*
需求：
    假设有某个电商网站用户量非常多，每天打开有500w用户量，带来一亿多访问量。

    现在需要记录用户登录信息，用户浏览商品的记录，用户的购物车数据。
    大多数关系型数据库每秒的插入、更新和删除在200-2000个数据行，虽然批量操作可以提升性能，但是用户每次浏览网页只是更新少许行数据，所以批量更新用不上。
    大约每秒1200次写入，高峰6000次写入。


键的设计
    login_，一个散列，用于存储登录令牌与登录用户的映射，即存储会话cookie
    recent_，一个有序列表，记录每个登录令牌最后一次使用的时间
    viewed_token，一个有序列表，token是登录令牌。每个键都存储对应用户浏览的页面，只存储25个页面数据，超过该页面数据会进行修剪。

    cart_session，一个散列，session是用户的cookie id，具体自己设置，每个散列都相当于一个独立的购物车，存储用户的数据。 

清理
    由于回话数据会一直存在，因此需要定期清理旧的回话数据。

    检查最近登录令牌的有序集合，如果集合中的大小超过限制，那么从有序集合中移除最多100个旧的令牌。
    因此最近登录令牌的有序集合，记录了用户的最后一次使用的时间，也就是说活跃的用户分值始终是排在前面的，因此清除掉不活跃的登录令牌不会有很大影响，一般这时候用户会话过期了。




*/

/*
需求扩展：关注浏览量最高的页面


*/