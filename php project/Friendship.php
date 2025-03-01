<?php
include_once 'EntityClassLib.php';
include_once 'Functions.php';
session_start();
$userId = '3'; //$_SESSION['userId']; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept'])) {
    // Assuming you have a function to accept selected requests
    acceptFriendRequests($userId, $_POST['selectedRequests']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['defriend'])) {
    // Defriend selected friends
    defriendSelected($userId, $_POST['selectedFriends']);
}
$friends = fetchAcceptedFriends($userId);
$loggedInUserName = getNameById($userId);
?>

        <?php include 'Header.php'; ?>
        <div class="container">
            <h2 class="text-center">My Friends</h2>

            <?php
            echo "<p>Welcome <span style='font-weight: bold; color: black;'>$loggedInUserName</span>! (not you? change user <a href='LogOut.php'>here</a>)</p>";
            ?>

            <form action="" method="post" id="">
                <?php
                

                echo "<table class='table'>";
                echo "<thead><tr><th>Friends:</th><th>     </th><th class='text-center'><a href='AddFriend.php'>Add Friends</a></th></tr></thead>";
                echo "<thead><tr><th>Name</th><th>Shared Albums</th><th>Defriend</th></tr></thead>";
                echo "<tbody>";

                foreach ($friends as $friend) {
                    echo "<tr>";
                        echo "<td><a href='MyFriendAlbums.php?friendId={$friend['FriendId']}'>{$friend['Name']}</a></td>";

                    echo "<td>{$friend['SharedAlbums']}</td>";
                    echo "<td><input type='checkbox' name='selectedFriends[]' value='{$friend['FriendId']}' id='{$friend['FriendId']}'></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
                //}
                echo "<div class='text-right' style='margin-bottom: 20px;'>";
                echo "<button type='submit' class='btn btn-primary' name='defriend' >Defriend Selected</button>";
                echo "</div>";
                ?>

            </form>
            <form action="" method="post" id="">
                <?php
                // Fetch friend requests from the database
                $friendRequests = fetchFriendRequests($userId);

                if (empty($friendRequests)) {
                    echo "<p class=\"text-right\">No friend requests.</p>";
                } else {
                    echo "<table class='table table-bordered'>";
                    echo "<thead><tr><th>Name</th><th>Accept or Deny</th></tr></thead>";
                    echo "<tbody>";

                    foreach ($friendRequests as $request) {
                        echo "<tr>";
                        echo "<td>{$request['Name']}</td>";
                        echo "<td><input type='checkbox' name='selectedRequests[]' value='{$request['UserId']}' id='{$request['UserId']}'></td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                    echo "<div class='text-right' style='margin-bottom: 20px;'>";
                    echo "<button type='submit' class='btn btn-primary' name='accept' >Accept Selected</button>";
                    echo "<button type='reset' class='btn btn-primary'>Deny Selected</button>";
                    echo "</div>";
                }
                ?>
            </form>
        </div>

<?php include 'Footer.php'; ?>
