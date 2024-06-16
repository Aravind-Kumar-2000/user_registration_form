<?php
require "./configuration.php";

$editId = $_GET["editId"];
$sql = "SELECT * FROM users WHERE ID = $editId";
$result = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $emailId = $row["Email_ID"];
    $password = $row["Password"];
    $firstName = $row["First_Name"];
    $lastName = $row["Last_Name"];
    $contactNo = $row["Contact_Number"];
    $city = $row["City"];
    $gender = $row["Gender"];
    $fileUploads = $row["Image"];
}
?>

<html>

<head>
    <title>
        Updating User Details
    </title>
    <link rel="stylesheet" href="./registrationPage.css">
</head>

<body>
    <?php
    $error = [];

    if (isset($_POST["update"])) {

        $editId = $_GET["editId"];

        //First Name Validation
        if (empty($_POST["firstName"])) {
            $error["firstNameError"] = "First Name is required!";
        } else {
            if (!preg_match("/^[A-Za-z ]*$/", $_POST["firstName"])) {
                $error["firstNameError"] = "First Name must contains letters and empty spaces!";
            } elseif (strlen($_POST["firstName"]) >= 3 && strlen($_POST["firstName"]) <= 15) {
                $firstName = test_data($_POST["firstName"]);
            } else {
                $error["firstNameError"] = "First Name must be between 3 to 15 characters!";
            }
        }

        //Last Name Validation
        if (empty($_POST["lastName"])) {
            $error["lastNameError"] = "Last Name is required";
        } else {
            if (!preg_match("/^[A-Za-z ]*$/", $_POST["lastName"])) {
                $error["lastNameError"] = "Last Name must contains letters and empty spaces!";
            } elseif (strlen($_POST["lastName"]) >= 3 && strlen($_POST["lastName"]) <= 15) {
                $lastName = test_data($_POST["lastName"]);
            } else {
                $error["lastNameError"] = "Last Name must be between 3 to 15 characters!";
            }
        }

        //Contact Number Validation
        if (empty($_POST["contactNo"])) {
            $error["contactNumberError"] = "Contact Number is required!";
        } else if (!preg_match("/^[0-9]{10}$/", $_POST["contactNo"])) {
            $error["contactNumberError"] = "Contact Number is Invalid!";
        } else {
            $contactNo = test_data($_POST["contactNo"]);
        }


        //City Validation
        if (empty($_POST["city"])) {
            $error["cityError"] = "City is required!";
        } else {
            $city = test_data($_POST["city"]);
        }

        //Gender Validation
        if (empty($_POST["gender"])) {
            $error["genderError"] = "Gender is required!";
        } else {
            $gender = test_data($_POST["gender"]);
        }

        //File Uploads Validation
        if (!empty($_FILES["fileUploads"]["name"])) {
            $fileName = $_FILES["fileUploads"]["name"];
            $fileTmpName = $_FILES["fileUploads"]["tmp_name"];
            $fileSize = $_FILES["fileUploads"]["size"];
            $fileError = $_FILES["fileUploads"]["error"];

            $allowedExtensions = array("jpeg", "jpg", "png");
            $fileExtensions = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtensions, $allowedExtensions)) {
                $error["fileError"] = "Invalid file format. Allowed formats: jpeg, jpg, gif, png!";
            } else if ($fileSize > 5242880) {
                $error["fileError"] = "File size exceeds 5MB limit!";
            } else {
                $fileUploads = "./uploads/" . uniqid('', true) . "." . $fileExtensions;
                move_uploaded_file($fileTmpName, $fileUploads);

                $sqlEdit  = "UPDATE users SET  Image = '$fileUploads' WHERE ID = '$editId' ";
                $editResult = mysqli_query($connection, $sqlEdit);
            }
        }

        //Inserting new data into DB
        if (empty($error)) {
            $sqlEdit  = "UPDATE users SET First_Name = '$firstName', Last_Name = '$lastName', Contact_Number='$contactNo', City = '$city', Gender = '$gender' WHERE ID = '$editId' ";
            $editResult = mysqli_query($connection, $sqlEdit);
            if ($editResult) {
                echo '<script>
                    window.location.href="./updatedResponse.php";
                 </script>';
                exit();
            }
        }
    }

    //Function to remove the empty spaces and slashes from the inputs
    function test_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ?>
    <div id="registrationFormMain">
        <form id="registrationForm" method="post" enctype="multipart/form-data">
            <div id="headingField">
                <h3>
                    Update Form
                </h3>
            </div>

            <div id="emailIdField">
                <label>
                    Email ID: <span style="color: red;">*</span>
                </label>
                <input type="text" name="emailId" value="<?php echo $emailId ?>" disabled />
                <span id="errMsg">
                    <?php
                    if (!empty($error["emailIdError"])) {
                        echo $error["emailIdError"];
                    }
                    ?>
                </span>
            </div>
            <div id="passwordField">
                <label>
                    Password: <span style="color: red;">*</span>
                </label>
                <input type="password" name="password" value="<?php echo $password ?>" disabled />
                <span id="errMsg"><?php if (!empty($error["passwordError"])) {
                                        echo $error["passwordError"];
                                    } ?></span>
            </div>
            <div id="firstNameField">
                <label>
                    First Name: <span style="color: red;">*</span>
                </label>
                <input type="text" name="firstName" value="<?php echo $firstName ?>" />
                <span id="errMsg">
                    <?php
                    if (!empty($error["firstNameError"])) {
                        echo $error["firstNameError"];
                    }
                    ?>
                </span>
            </div>
            <div id="lastNameField">
                <label>
                    Last Name: <span style="color: red;">*</span>
                </label>
                <input type="text" name="lastName" value="<?php echo $lastName  ?>" />
                <span id="errMsg">
                    <?php
                    if (!empty($error["lastNameError"])) {
                        echo $error["lastNameError"];
                    }
                    ?>
                </span>
            </div>
            <div id="contactNumberField">
                <label>
                    Contact Number: <span style="color: red;">* </span>
                </label>
                <input type="text" name="contactNo" value="<?php echo $contactNo ?>" />
                <span id="errMsg">
                    <?php
                    if (!empty($error["contactNumberError"])) {
                        echo $error["contactNumberError"];
                    }
                    ?>
                </span>
            </div>
            <div id="cityField">
                <label for="city">
                    City: <span style="color: red;">*</span>
                </label>
                <select name="city">
                    <option selected disabled>--Select a City--</option>
                    <option value="chennai" <?php if ($city == "chennai") echo "selected"; ?>>Chennai</option>
                    <option value="madurai" <?php if ($city == "madurai") echo "selected"; ?>>Madurai</option>
                    <option value="coimbatore" <?php if ($city == "coimbatore") echo "selected"; ?>>Coimbatore</option>
                    <option value="salem" <?php if ($city == "salem") echo "selected"; ?>>Salem</option>
                    <option value="vellore" <?php if ($city == "vellore") echo "selected"; ?>>Vellore</option>
                    <option value="virudhunagar" <?php if ($city == "virudhunagar") echo "selected"; ?>>Virudhunagar</option>
                    <option value="trichy" <?php if ($city == "trichy") echo "selected"; ?>>Trichy</option>
                    <option value="sivakasi" <?php if ($city == "sivakasi") echo "selected"; ?>>Sivakasi</option>
                    <option value="Erode" <?php if ($city == "Erode") echo "selected"; ?>>Erode</option>
                    <option value="Namakkal" <?php if ($city == "Namakkal") echo "selected"; ?>>Namakkal</option>
                    <option value="Tenkasi" <?php if ($city == "Tenkasi") echo "selected"; ?>>Tenkasi</option>
                    <option value="Tirunelveli" <?php if ($city == "Tirunelveli") echo "selected"; ?>>Tirunelveli</option>
                    <option value="Thoothukudi" <?php if ($city == "Thoothukudi") echo "selected"; ?>>Thoothukudi</option>
                    <option value="Thanjavur" <?php if ($city == "Thanjavur") echo "selected"; ?>>Thanjavur</option>
                    <option value="Tiruppur" <?php if ($city == "Tiruppur") echo "selected"; ?>>Tiruppur</option>
                    <option value="Kancheepuram" <?php if ($city == "Kancheepuram") echo "selected"; ?>>Kancheepuram</option>
                    <option value="Cuddalore" <?php if ($city == "Cuddalore") echo "selected"; ?>>Cuddalore</option>
                    <option value="Dindigul" <?php if ($city == "Dindigul") echo "selected"; ?>>Dindigul</option>
                    <option value="Karaikudi" <?php if ($city == "Karaikudi") echo "selected"; ?>>Karaikudi</option>
                </select>
                <span id="errMsg">
                    <?php
                    if (!empty($error["cityError"])) {
                        echo $error["cityError"];
                    }
                    ?>
                </span>
            </div>
            <div id="genderField">
                <label for="gender">
                    Gender: <span style="color: red;">*</span>
                </label>
                <span id="genderInput">
                    <span id="gender">
                        <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked" ?> />
                        <label>Male</label>
                    </span>
                    <span id="gender">
                        <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked" ?> />
                        <label>Female</label>
                    </span>
                    <span id="gender">
                        <input type="radio" name="gender" value="Others" <?php if ($gender == "Others") echo "checked" ?> />
                        <label>Others</label>
                    </span>
                </span>
                <span id="errMsg">
                    <?php
                    if (!empty($error["genderError"])) {
                        echo $error["genderError"];
                    }
                    ?>
                </span>
            </div>
            <div id="fileUploadField">
                <label>
                    Image:<span style="color: red;">*</span>
                </label>
                <input type="file" style="width: 230px;" name="fileUploads" value="<?php echo $fileUploads ?>" />
                <span id="errMsg" style="margin-left: -105px;">
                    <?php
                    if (!empty($error["fileUploadsError"])) {
                        echo $error["fileUploadError"];
                    }
                    ?>
                </span>
                <span class="preview">
                    <img class="imagePreview" src="./uploads/.<?php echo $fileUploads ?>" alt="Image not found!" />
                </span>
            </div>
            <div id="submitButton">
                <input type="submit" name="update" value="Update" />
            </div>
        </form>
    </div>

</body>

</html>