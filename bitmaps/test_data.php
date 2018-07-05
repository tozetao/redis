<?php
$handler = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

echo '<pre/>';
/*
for($i=0; $i<1000000; $i++)
{
    $insertSql = "insert into user(name) value(:name)";
    $query = $handler->prepare($insertSql);
    $name = 'name' . $i;
    $query->bindParam(':name', $name);
    $result = $query->execute();
    var_dump($result);
}
*/

for($i=0; $i<10000; $i++)
{
    $insertSql = "insert into user_tag(user_id, tag_id) value(:user_id, :tag_id)";
    $query = $handler->prepare($insertSql);

    $user_id = $i + 1;
    $tag_id  = mt_rand(1, 5);

    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':tag_id',  $tag_id);
    $result = $query->execute();
    var_dump($result);
}



/*
create table user(
 id int primary key auto_increment,
 name varchar(20)
);

create table tag(
 id tinyint primary key,
 name varchar(20)
);

create table user_tag(
 user_id int not null,
 tag_id tinyint not null
);

insert into tags value(1, 'a1');
insert into tags value(2, 'b2');
insert into tags value(3, 'c3');
insert into tags value(4, 'd4');
insert into tags value(5, 'e5');
 */