//All queries to the user, album and picture tables are simple SQL statements. 
//The followings are queries which are more complicated.

//Get comments and their authors for a picture

	$sql = "SELECT Comment_Id, Comment_Text, UserId, Name FROM Comment "
                . "INNER JOIN User ON Comment.Author_Id = User.UserId WHERE Picture_Id = :pictureId";
				        
//Delete a picture and all its comments. A picture's comments must be deleted before delete the picture.

	$sql = "DELETE FROM Comment WHERE Picture_Id = :pictureId";
	$sql1 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
	
	
//Accept a friend request

	$sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";

//Delete a friend of a user:

	$sql = "DELETE FROM Friendship "
                . "WHERE ((Friend_RequesterId = :userId AND Friend_RequesteeId= :friendId) "
                . "  OR (Friend_RequesterId = :friendId AND Friend_RequesteeId= :userId)) "
                . "    AND Status='accepted'";	
				
//Deny a friend request

	$sql = "DELETE FROME Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId AND Status='request'";
	
//Get friends for a user. The first query returns all friends to whom the user initiated the requests.
//The second query returns all friends whose requests the user accepted. Add the results of the following two queries

	$sql = "SELECT Friend_RequesteeId FROM Friendship "
                . "WHERE Friend_RequesterId = :userId AND Status = 'accepted'";
				
	$sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'accepted'";
				
//Get friend requesters to a user

	$sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'request'";
				
