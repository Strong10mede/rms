<?php
require("session.php");

require "connect.php";

$name = $email = $phone = $address =  "";
$errors = ["name" => "", "email" => ""];

if (isset($_POST['submit'])) {

  if (empty($_POST["email"])) {
    $errors["email"] = "An email is required. <br/>";
  } else {
    $email = htmlspecialchars($_POST["email"]);
  }
  if (empty($_POST["name"])) {
    $errors["name"] = "An name is required. <br/>";
  } else {
    $name = htmlspecialchars($_POST["name"]);
  }


  $address = htmlspecialchars($_POST["address"]);
  $phone = htmlspecialchars($_POST["phone"]);

  if (!array_filter($errors)) {
    //there is no error in the form

    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);

    $sql = "INSERT INTO owner(name,email,phone,address) VALUES('$name','$email','$phone','$address' ) ";
    if (mysqli_query($conn, $sql)) {
      header("Location: owners.php");
    } else {
      echo "quesry error: " . mysqli_error($conn);
    }
  }
}


?>


<?php include "header.php"; ?>

<div class="flex justify-center items-center">
  <div class="w-full m-8  ">
    <form action="addOwner.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-700 my-2">
          <b>Add Owner</b>
        </h1>

      </div>
      <div class="w-full   ">
        <label for="name" class="label required">
          Name
        </label>
        <input name="name" type="text" class=" input " placeholder="Full name" autocomplete="name" maxlength="50" value="<?php echo $name; ?>" required>
        <p class="error_text"><?php echo $errors["name"] ? $errors["name"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="email" class="label required">
          Email
        </label>
        <input name="email" type="email" class=" input " placeholder="Email address" autocomplete="email" maxlength="50" value="<?php echo $email; ?>" required>
        <p class="error_text"><?php echo $errors["email"] ? $errors["email"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="phone" class="label required">
          Phone
        </label>
        <input name="phone" type="tel" class=" input " placeholder="Phone number" autocomplete="phone" maxlength="50" value="<?php echo $phone; ?>" required>
      </div>

      <div class="w-full  ">
        <label for="address" class="label">
          Address
        </label>
        <textarea name="address" class=" input " maxlength="100" placeholder="Address" autocomplete="address"><?php echo $address; ?></textarea>
      </div>

      <div class="w-full flex gap-4  ">
        <a href="/rms/owners.php" class="btn secondary w-auto">
          Cancel </a>
        <input name="submit" type="submit" class=" btn primary " value="Add Owner">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>