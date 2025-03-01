<?php include 'Header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-offset-2">
            <h1>Upload Pictures</h1>
            <p>Accepted pictures types: JPG (JPEG), GIF and PNG</p>
            <p>You can upload multiple pictures at a time by pressing the shift key selecting pictures.</p>
            <p>When uploading multiple pictures, the title and descriptions fields will be applied to all pictures</p>
        </div>
    </div>

    <br>

    <form method="post" action="UploadPictures.php" id="UploadForm">
        <!-- Album selected -->
        <div class="form-group row">
            <label for="albumSelected" class="col-md-offset-1 col-md-2 col-form-label">Upload to Album:</label>
            <div class="col-md-6">
                <select name="albumSelected" class="form-control">
                    <option value="-1">Select an Album...</option>
                    <?php // create function to retrieve the albums from the DB using usersId?>
                </select>
            </div>
        </div>

        <!-- File(s) -->
        <div class="form-group row">
            <label for="picture" class="col-md-offset-1 col-md-2 col-form-label">File to Upload:</label>
            <div class="col-md-6">
                <input type="file" name="picture" class="form-control">
            </div>
        </div>

        <!-- Title -->
        <div class="form-group row">
            <label for="pictureTitle" class="col-md-offset-1 col-md-2 col-form-label">Title:</label>
            <div class="col-md-6">
                <input type="text" name="pictureTitle" class="form-control">
            </div>
        </div>

        <!-- Description -->
        <div class="form-group row">
            <label for="pcitureDescription" class="col-md-offset-1 col-md-2 col-form-label">Description:</label>
            <div class="col-md-6">
                <textarea type="text" class="form-control" name="pcitureDescription" rows="4" placeholder="Enter text"></textarea>
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
    var albumForm = document.getElementById("UploadForm");
    var clearButton = document.getElementById("clearForm");

    // Add an event listener to the clear button
    clearButton.addEventListener("click", function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Reset the form to its initial state
        albumForm.reset();
    });
</script>
    
