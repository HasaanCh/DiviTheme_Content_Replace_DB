<?php


function dbdata($pageid)
{
    global $wpdb;
    $data= $wpdb->get_results("Select post_content from $wpdb->posts where ID=$pageid");

    return $data;
}

function insertdata($pageid,$content)
{
    global $wpdb;
    $wpdb->update($wpdb->posts,array('post_content'=>$content),array('ID'=>$pageid),array('%s'),array('%d'));
}


?>