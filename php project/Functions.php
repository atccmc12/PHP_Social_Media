<?php

include_once 'EntityClassLib.php';

function getPDO() {
    $dbConnection = parse_ini_file("DBConnection.ini");
    extract($dbConnection);
    return new PDO($dsn, $scriptUser, $scriptPassword);
}

function getUserByIdAndPassword($studentId, $password) {
    $pdo = getPDO();
    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = :userId AND Password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $studentId, 'password' => $password]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return new User($row['StudentId'], $row['Name'], $row['Phone']);
    } else {
        return null;
    }
}

function getUserById($userId) {
    $pdo = getPDO();

    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = :userId";
    $pStmt = $pdo->prepare($sql);
    $pStmt->execute(['userId' => $userId]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return new User($row['UserId'], $row['Name'], $row['Phone']);
    } else {
        return null;
    }
}

function getNameById($userId) {
    $pdo = getPDO();

    $sql = "SELECT Name FROM user WHERE UserId = :userId";
    $pStmt = $pdo->prepare($sql);
    $pStmt->execute(['userId' => $userId]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['Name'];
    } else {
        return null;
    }
}

function userExists($userId) {
    $pdo = getPDO();

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE UserId = :userId");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Returns true if user exists, false otherwise
    } catch (PDOException $e) {
        // Handle the exception, e.g., log or display an error message
        die("Error checking user existence: " . $e->getMessage());
    }
}

function friendshipExists($userId, $friendUserId) {
    $pdo = getPDO();
    $sql = "SELECT COUNT(*) as count FROM friendship WHERE (Friend_RequesterId = :userId AND Friend_RequesteeId = :friendUserId) OR (Friend_RequesterId = :friendUserId AND Friend_RequesteeId = :userId)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':friendUserId', $friendUserId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['count'] > 0);
}

function areFriends($userId, $friendUserId) {
    $pdo = getPDO();

    try {
        $sql = "SELECT COUNT(*) as count, Name FROM friendship WHERE " .
                "((Friend_RequesterId = :userId AND Friend_RequesteeId = :friendUserId) " .
                "OR (Friend_RequesterId = :friendUserId AND Friend_RequesteeId = :userId)) " .
                "AND Status = 'accepted'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':friendUserId', $friendUserId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false && isset($result['count'])) {
            return ($result['count'] > 0);
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Handle the exception, e.g., log or display an error message
        return false;
    }
}

function friendshipRequestExists($userId, $friendUserId) {
    try {
        $pdo = getPDO();
        $sql = "SELECT COUNT(*) as count FROM friendship WHERE (Friend_RequesteeId = :userId AND Friend_RequesterId = :friendUserId) AND Status='request'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':friendUserId', $friendUserId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['count'] > 0);
    } catch (PDOException $e) {
        die("Error checking friendship request: " . $e->getMessage());
    }
}

function createFriendshipRequest($userId, $friendUserId) {
    try {
        $pdo = getPDO();
        $sql = "INSERT INTO friendship (Friend_RequesterId, Friend_RequesteeId, Status) VALUES (:userId, :friendUserId, 'request')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'friendUserId' => $friendUserId]);
        return true;
    } catch (PDOException $e) {
        throw $e;
    }
}

function fetchFriendRequests($userId) {
    $pdo = getPDO();

    $sql = "SELECT u.UserId, u.Name " .
            "FROM friendship f " .
            "JOIN user u ON f.Friend_RequesterId = u.UserId " .
            "WHERE f.Friend_RequesteeId = :userId " .
            "AND f.Status = 'request'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function acceptFriendRequests($userId, $selectedRequests) {
    $pdo = getPDO();
    try {
        $sql = "UPDATE friendship SET Status = 'accepted' WHERE Friend_RequesteeId = :userId " .
                "AND Friend_RequesterId IN (" . implode(',', $selectedRequests) . ")";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
    } catch (PDOException $e) {
        return "Error updating data: " . $e->getMessage();
    }
}

function automaticallyAcceptFriendRequests($userId, $friendUserId) {
    $pdo = getPDO();

    try {
        // Update the status to 'accepted' for reciprocal friend request
        $sql = "UPDATE friendship SET Status = 'accepted' WHERE (Friend_RequesterId = :userId AND Friend_RequesteeId = :friendUserId) OR (Friend_RequesterId = :friendUserId AND Friend_RequesteeId = :userId)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':friendUserId', $friendUserId);

        $stmt->execute();
    } catch (PDOException $e) {
        // Handle the exception, e.g., log or display an error message
        return "Error updating data: " . $e->getMessage();
    }
}

function fetchAcceptedFriends($userId) {
    $pdo = getPDO();

    try {
        $sql = "SELECT u.Name,COUNT(DISTINCT a.Album_Id) AS SharedAlbums, f.Friend_RequesterId AS FriendId " .
                "FROM Friendship AS f " .
                "JOIN User AS u ON (f.Friend_RequesterId = u.UserId ) " .
                "LEFT JOIN Album AS a ON (f.Friend_RequesterId = a.Owner_Id AND f.Status = 'accepted') " .
                "LEFT JOIN Accessibility AS ac ON a.Accessibility_Code = ac.Accessibility_Code " .
                "WHERE (f.Friend_RequesterId = :userId OR f.Friend_RequesteeId = :userId)AND f.Status = 'accepted' " .
                "GROUP BY u.UserId";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);

        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $friends;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

function defriendSelected($userId, $selectedFriends) {
    $pdo = getPDO();

    try {
        $placeholders = implode(', ', array_map(function ($friend) {
                    return ':' . $friend;
                }, $selectedFriends));

        $sql = "DELETE FROM friendship WHERE (Friend_RequesterId = :userId OR Friend_RequesteeId = :userId) AND (Friend_RequesterId IN ($placeholders) OR Friend_RequesteeId IN ($placeholders))";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':userId', $userId);

        foreach ($selectedFriends as $friend) {
            $stmt->bindValue(':' . $friend, $friend);
        }

        $stmt->execute();
    } catch (PDOException $e) {
        return "Error updating data: " . $e->getMessage();
    }
}
function fetchSharedAlbums($friendUserId)
{
    $pdo = getPDO();

    try {
        $sql = "SELECT a.Title, COUNT(p.Picture_Id) AS PicCount, ac.Description AS Accessibility  FROM album a " .
                "INNER JOIN picture p ON a.Album_Id = p.Album_Id " .
                "INNER JOIN accessibility ac ON a.Accessibility_Code = ac.Accessibility_Code " .
                "WHERE a.Owner_Id = :friendUserId AND a.Accessibility_Code = 'shared' " .
                "GROUP BY a.Title, ac.Description";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':friendUserId', $friendUserId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle the exception, e.g., log or display an error message
        return [];
    }
}