<?php
require("session.php");
require "connect.php";

$name = $email = $phone = $address = $owner_id =  "";
$errors = ["name" => "", "email" => ""];

if (isset($_POST['update_owner']) && isset($_POST['owner_id']) && !empty($_POST["owner_id"])) {

  $owner_id = mysqli_real_escape_string($conn, $_POST["owner_id"]);

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
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);

    $sql = "UPDATE owner 
            SET name='$name',email='$email',phone='$phone',address='$address' 
            WHERE owner_id='$owner_id';";

    if (mysqli_query($conn, $sql)) {
      header("Location: ownerDetails.php?id=" . $owner_id);
    } else {
      echo "query error: " . mysqli_error($conn);
    }
  }
} else {

  $owner_id = mysqli_real_escape_string($conn, $_POST["owner_id"]);
  $sql = "SELECT * FROM owner WHERE owner_id='$owner_id'";
  $result = mysqli_query($conn, $sql);
  $owner = mysqli_fetch_assoc($result);
  $name = $owner['name'];
  $email = $owner['email'];
  $phone = $owner['phone'];
  $address = $owner['address'];
}


?>


<?php include "header.php"; ?>

<div class="flex justify-center items-center">
  <div class="w-full m-8  ">
    <form action="editOwner.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-900 my-2">
          <b>Edit Owner Details</b>
        </h1>

      </div>
      <div class="w-full   ">
        <label for="name" class="label required">
          Name
        </label>
        <input name="name" type="text" class=" input " placeholder="Full name" autocomplete="name" maxlength="50" value="<?php echo $name; ?>" required>
        <p class="error_text"><?php echo $errors["name"] ? $errors["name"] : ""; ?> </p>
      </div>

      <input name="owner_id" type="hidden" value="<?php echo $owner_id; ?>">

      <div class="w-full  ">
        <label for="email" class="label required">
          Email
        </label>
        <input name="email" type="email" class=" input " maxlength="50" placeholder="Email address" autocomplete="email" value="<?php echo $email; ?>" required>
        <p class="error_text"><?php echo $errors["email"] ? $errors["email"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="phone" class="label required">
          Phone
        </label>
        <input name="phone" type="tel" class=" input " maxlength="50" placeholder="Phone number" autocomplete="phone" value="<?php echo $phone; ?>" required>
      </div>

      <div class="w-full  ">
        <label for="address" class="label">
          Address
        </label>
        <textarea name="address" class="input" placeholder="Address" maxlength="100" autocomplete="address"><?php echo $address; ?></textarea>
      </div>

      <div class="w-full flex gap-4  ">
        <a href="/rms/ownerDetails.php?id=<?php echo $owner_id; ?>" class="btn secondary w-auto"> Cancel </a>
        <input name="update_owner" type="submit" class=" btn primary" value="Update Owner">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>