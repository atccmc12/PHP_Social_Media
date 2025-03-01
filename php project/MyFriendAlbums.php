<?php
include_once 'EntityClassLib.php';
include_once 'Functions.php';
session_start(); ?>

<?php include 'Header.php'; ?>

<?php
// Assuming you have access to the logged-in user's ID and the friend's ID from the URL parameter
$loggedInUserId = '3'; // Get the logged-in user's ID
$friendUserId = isset($_GET['friendId']) ? $_GET['friendId'] : null;
$loggedInUserName = getNameById($loggedInUserId);
// Verify that the friend ID is valid
if ($friendUserId && userExists($friendUserId)) {
    // Fetch shared albums for the specific friend
    $sharedAlbums = fetchSharedAlbums($friendUserId);
    // Display shared albums
    echo "<h2 class='text-center'>Shared Albums</h2>";
    echo "<p>Welcome <span style='font-weight: bold; color: black;'>$loggedInUserName</span>! (not you? change user <a href='LogOut.php'>here</a>)</p>";
    if (!empty($sharedAlbums)) {
        echo "<table class='table'>";
        echo "<thead><tr><th>Title</th><th>Number of Pictures</th><th>Accessibility</th></tr></thead>";
        echo "<tbody>";

        foreach ($sharedAlbums as $album) {
            echo "<tr>";
            echo "<td>{$album['Title']}</td>";
            echo "<td>{$album['PicCount']}</td>";
            echo "<td>{$album['Accessibility']}</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No shared albums with {$loggedInUserName}.</p>";
    }
} else {
    echo "<p>Invalid friend ID.</p>";
}
?>

<?php include 'Header.php'; ?>

<?php include 'Footer.php'; ?>
    
