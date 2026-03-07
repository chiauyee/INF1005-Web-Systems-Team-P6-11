<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>

<!-- ugh --!>

<!DOCTYPE html>
<html>
<head>
  <title>Make Listing</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
  <h1>Make Listing</h1>
  <form id="form" method="POST" action="/api/create_listing.php">
    <label>Artist name: <input placeholder="Artist name..." type="text" id="searched-artist" required> </label><br><br>
    <label>Album title: <input placeholder="Album name..." type="text" id="searched-album" required> </label><br><br>
    <label>Price: <input name="price" placeholder="0.00" type="number" step=0.01 min=0 inputmode=decimal></label><br><br>

    <button type="button" onclick=search_for_metadata()>Verify metadata</button><br><br>

    <h1 id='metadata-header' hidden></h1>
    <div id='metadata' hidden>
      <input name='artist' type='text' readonly id='metadata-artist'>
      <input name='album' type='text' readonly id='metadata-album'>
      <input name='artist_mbid' type='text' readonly id='metadata-artist-mbid'>
      <input name='album_mbid' type='text' readonly id='metadata-album-mbid'>
      <input name='cached' type='checkbox' readonly id='metadata-cached'>
      <button id='metadata-submit' disabled type='submit'>Submit listing</button>
    </div>
     
  </form>
</body>

<script>

function handle_data(data) {

  console.log('handling data');
  
  var status = data.status;
  var data = data.data;

  switch (status) {
    case "not_found":
      return false;
    
    case "found_musicbrainz":
      var album = {};
      album.artist_name = data["artist-credit"][0].name.toUpperCase();
      album.artist_mbid = data["artist-credit"][0].artist.id;
      album.album_name = data.title.toUpperCase();
      album.album_mbid = data.id.toUpperCase();
      album.cached = false;
      return album;

    case "found_db":
      var album = data;
      album.cached = true;
      return album;
  }
}

function search_for_metadata() {

  fetch("api/search_metadata.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(
      {
        artist: $("#searched-artist").val(), 
        album: $("#searched-album").val()
      }
    )
  })
  .then(response => response.json())
  .then(data => {
    var album = handle_data(data);
    $("#metadata-header").prop("hidden", false)

    if (!data) {
        $("#metadata-header").text("couldn't find a match! :(");
    }

      else {
      console.log(album);
      $("#metadata-cached").prop("checked", album.cached);
      $("#metadata-album").val(album.album_name);
      $("#metadata-artist").val(album.artist_name);
      $("#metadata-artist-mbid").val(album.artist_mbid);
      $("#metadata-album-mbid").val(album.album_mbid);
      $("#metadata-header").text("found a match!");
      $("#metadata-submit").prop("disabled", false);
      $("#metadata").prop("hidden", false);
    }
  });
}

</script>

</html>

