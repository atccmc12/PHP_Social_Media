<?php
include_once 'EntityClassLib.php';
include_once 'Functions.php';
session_start();
// Assuming you have access to the user's ID and the friend's ID from the form submission
$userId = '3'; // Assuming you have a user session
$friendUserId = null;
$requestSent = null;
if (isset($_POST['friendUserId'])) {
    $friendUserId = $_POST['friendUserId'];
    if (userExists($friendUserId)) {
        if ($userId != $friendUserId) {
            if (areFriends($userId, $friendUserId)) {
                echo "You and this user are already friends.";
            } elseif (friendshipRequestExists($userId, $friendUserId)) {
                try {
                    automaticallyAcceptFriendRequests($userId, $friendUserId);
                    echo "Friendship created automatically!";
                } catch (PDOException $e) {
                    echo "Error creating friendship request: " . $e->getMessage();
                }
            } else {
                try {
                    createFriendshipRequest($userId, $friendUserId);
                    $requestSent = "Your request has been sent to Name(ID: ). " .
                            "Once Name accepts your request, you and Name " .
                            "will be friends and be able to view each others's shared albums.";
                } catch (PDOException $e) {
                    echo "Error creating friendship request: " . $e->getMessage();
                }
            }
        } else {
            echo "You can't send a friend request to yourself!";
        }
    } else {
        $errorMsg = "User with ID $friendUserId does not exist!";
    }
}
$loggedInUserName = getNameById($userId);
?>


<?php include 'Header.php'; ?>
        <h1 class="text-center">Add Friend</h1>
<?php
echo "<p>Welcome <span style='font-weight: bold; color: black;'>$loggedInUserName</span>! (not you? change user <a href='LogOut.php'>here</a>)</p>";
echo "<p>Enter the ID of the user you want to be friend with</p>";
if (!empty($errorMsg)) {
    echo "<p style='color: red;'>$errorMsg</p>";
}
if ($requestSent) {
echo "<p style='color: red;font-weight: bold;'>$requestSent</p>";
}
?>
        <form action='' method='post'>
            <table>
                <tr>
                    <th>ID:</th>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <input type="text" name="friendUserId" value="<?php echo isset($friendUserId) ? $friendUserId : ''; ?>"/>
                        </div>
                    </td>

                    <td colspan='2'>&nbsp;</td>

                    <td colspan='2'>&nbsp;</td>

                    <td>&nbsp;</td>
                    <td style='text-align: center'>
                        <button type="submit" name='btnFrRequest' class="btn btn-primary">Send Friend Request</button>
                    </td>
                </tr>
            </table>
        </form>
<?php include 'Footer.php'; ?>

