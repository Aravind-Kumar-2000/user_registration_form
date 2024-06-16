<?php
require "./configuration.php";

//To select all the saved data from DB 
$sqlGet = "SELECT * FROM users ORDER BY ID ASC";
$getResult = mysqli_query($connection, $sqlGet);

?>

<html>

<head>
    <title>
        User Details
    </title>
    <link rel="stylesheet" href="./display.css" />
</head>

<body>
    <div class="displayPage">
        <table class="userDisplayTable">
            <div class="header">
                <h3 style="text-align: center; font-size:35px; margin-top:20px;margin-bottom:20px;">User Details</h3>
                <div><a href="./registrationPage.php">Back to Home</a></div>
            </div>
            <tr class="tableHeadRows">
                <td class="id" style="font-weight: 600;">ID</td>
                <td class="emailId" style="font-weight: 600;">Email ID</td>
                <td class="firstName" style="font-weight: 600;">First Name</td>
                <td class="lastName" style="font-weight: 600;">Last Name</td>
                <td class="contactNo" style="font-weight: 600;">Contact No</td>
                <td class="city" style="font-weight: 600;">City</td>
                <td class="gender" style="font-weight: 600;">Gender</td>
                <td class="image" style="font-weight: 600;">Image</td>
                <td class="editButton" style="font-weight: 600;">Edit</td>
                <td class="deleteButton" style="font-weight: 600;">Delete</td>
            </tr>
            <?php
            $displayID = 1;
            while ($row = mysqli_fetch_assoc($getResult)) {
            ?>
                <tr class="tableValueRows">
                    <td class="id"><?php echo $displayID; ?></td>
                    <td class="emailId"><?php echo $row["Email_ID"] ?></td>
                    <td class="firstName"><?php echo $row["First_Name"] ?></td>
                    <td class="lastName"><?php echo $row["Last_Name"] ?></td>
                    <td class="contactNo"><?php echo $row["Contact_Number"] ?></td>
                    <td class="city"><?php echo $row["City"] ?></td>
                    <td class="gender"><?php echo $row["Gender"] ?></td>
                    <td class="image"><img class="imageFile" src="./uploads/.<?php echo $row["Image"] ?>" alt="Image not found!" /></td>
                    <td class="editButton"><a href="./edit.php?editId=<?php echo $row["ID"]?>"><button>Edit</button></a></td>
                    <td class="deleteButton"><a href="./delete.php?deleteId=<?php echo $row["ID"]?>"><button>Delete</button></a></td>
                </tr>
            <?php
                $displayID++;
            }
            ?>
        </table>
    </div>
</body>

</html>