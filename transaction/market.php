<?php

function getRedis()
{
    return new Redis();
}

/**
 * 将包裹里的商品放到市场上出售
 * @param $itemId
 * @param $sallerId
 * @param $price
 */
function listenItem($itemId, $sallerId, $price)
{
    $redis = getRedis();
    $redis->watch();

    //1. watch 用户包裹key
    //2. 判断商品是否在包裹中
    //3. 开启事务，将商品添加到市场key中
    //4. 在用户包括中移除商品
    //5. 执行事务

    //中途如果监视的包裹key发生数据变动，事务将会执行失败
}


/**
 * 从市场购买商品
 * @param $buyerId    买家id
 * @param $itemId     商品id
 * @param $sellerId   卖家id
 * @param $lprice     当前购买价格
 */
function purchaseItem($buyerId, $itemId, $sellerId, $lprice)
{
    //1. watch 监视市场key和买家信息key
    //2. 检查当前商品价格与市场价格是否一致，检查买家是否有足够的金额购买商品，如果任意条件不成立将unwatchkey，并返回false
    //3. 从市场中删除该商品，将购买商品放置在买家包裹中，同时减少买家金额，增加卖家金额

    //4. 事务过程中如果有发生变化，将进行重试

    //note：监视商品市场key是为了保证买家想要购买的商品仍然有售，监视买家的个人信息是为了验证买家是否有足够的金额来购买自己的商品
}

/*


需求1：将玩家包裹的商品放到市场出售
    添加一个市场key，有序集合：market_；市场key存储的成员为sellerId_itemId组成，分数是商品的价格。
    添加一个代表用户包裹的key，集合类型：invertory_sellerId

需求2：从市场购买一件商品
    添加一个用户信息key，散列：users_userId


*/