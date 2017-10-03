<?php

// Функции обратного вызова комментариев

// Функция вызывается при добавлении комментария
function instruction_com_update($pageid, $total_num)
{
    $db  = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE ' . $db->prefix('instruction_page') . ' SET comments = ' . $total_num . ' WHERE pageid  = ' . $pageid;
    $db->query($sql);
}

function instruction_com_approve(&$comment)
{
    // notification mail here
}
