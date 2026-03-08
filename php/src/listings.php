<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Listings</title>
</head>
<body>

  <?php if (isset($_GET['created'])): ?>
    <p><strong>Listing created successfully!</strong></p>
  <?php endif; ?>

  <h1>Listings</h1>
  <a href="make_listing.php">+ New listing</a>

  <div id="listings-container">
    <p>Loading...</p>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
  <script>
    fetch('/api/get_listings.php')
      .then(response => response.json())
      .then(json => {
        const container = document.getElementById('listings-container');

        if (json.error) {
          container.innerHTML = '<p>Error: ' + json.error + '</p>';
          return;
        }

        const listings = json.data;

        if (listings.length === 0) {
          container.innerHTML = '<p>No listings yet.</p>';
          return;
        }

        let html = `
          <table border="1" cellpadding="6" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Seller</th>
                <th>Listed at</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody>
        `;

        listings.forEach(row => {
          html += `
            <tr>
              <td>${DOMPurify.sanitize(String(row.listing_id))}</td>
              <td>${DOMPurify.sanitize(row.artist_name)}</td>
              <td>${DOMPurify.sanitize(row.album_name)}</td>
              <td>${DOMPurify.sanitize(row.seller)}</td>
              <td>${DOMPurify.sanitize(row.created_at)}</td>
              <td>${DOMPurify.sanitize(row.price)}</td>
            </tr>
          `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
      })
      .catch(err => {
        document.getElementById('listings-container').innerHTML = '<p>Failed to load listings.</p>';
        console.error(err);
      });

  </script>

</body>
</html>
