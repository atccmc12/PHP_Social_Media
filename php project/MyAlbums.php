<?php
    include_once 'Functions.php';
    session_start();
    
    $userId = '3';//$_SESSION['userId'];
    $loggedInUserName = getNameById($userId);
?>
<?php include 'Header.php'; ?>



<div class="container">
    <div class="row">
    
        <div class="col-md-offset-2">
            <h1>Create New Album</h1>
            
        </div>
        <div class="col-md-offset-1">
        <?php
            echo "<p>Welcome <span style='font-weight: bold; color: black;'>$loggedInUserName</span>! (not you? change user <a href='LogOut.php'>here</a>)</p>";
            ?>
        </div>
        
    </div>

    <br>

    <form method="post" action="UploadPictures.php" id="AlbumForm">

        <!-- Title -->
        <div class="form-group row">
            <label for="albumTitle" class="col-md-offset-1 col-md-2 col-form-label">Title:</label>
            <div class="col-md-6">
                <input type="text" name="albumTitle" class="form-control">
            </div>
        </div>

        <!-- Album selected -->
        <div class="form-group row">
            <label for="albumSelected" class="col-md-offset-1 col-md-2 col-form-label">Accessibility:</label>
            <div class="col-md-6">
                <select name="albumSelected" class="form-control">
                    <option value="-1">Select an option...</option>
                    <option value="private">Accessible only by owner</option>
                    <option value="public">Accessible only by owner and friends</option>
                    <?php // create function to retrieve the albums from the DB using usersId?>
                </select>
            </div>
        </div>

        <!-- Description -->
        <div class="form-group row">
            <label for="albumDescription" class="col-md-offset-1 col-md-2 col-form-label">Description:</label>
            <div class="col-md-6">
                <textarea type="text" class="form-control" name="albumDescription" rows="4" placeholder="Enter text"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-offset-3 col-md-6">
                <input type="submit" class="btn btn-primary" value="Submit">
                <button id="clearForm" class="btn btn-primary">Clear</button>
            </div>
        </div>
    </form>
</div>

<?php include 'Footer.php'; ?>

<script>
    var albumForm = document.getElementById("AlbumForm");
    var clearButton = document.getElementById("clearForm");

    // Add an event listener to the clear button
    clearButton.addEventListener("click", function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Reset the form to its initial state
        albumForm.reset();
    });
</script>
    