<?php
$id = $_GET['id'];
$data = file_get_contents('https://www.jiosaavn.com/api.php?__call=content.getAlbumDetails&_format=json&cc=in&_marker=0%3F_marker=0&albumid=' . $id . '');
$data = json_decode($data, true);
$song_id = $data['songs']['0']['id'];
echo "$song_id";
header("Location: song?id=" . $song_id . "");
