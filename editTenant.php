<?php
require("session.php");
require "connect.php";

$name = $email = $phone = $address = $occupation =  "";
$errors = ["name" => "", "email" => ""];

if (isset($_POST['update_tenant']) && isset($_POST['tenant_id']) && !empty($_POST["tenant_id"])) {
  $tenant_id = mysqli_real_escape_string($conn, $_POST["tenant_id"]);

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
  $occupation = htmlspecialchars($_POST["occupation"]);

  if (!array_filter($errors)) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $occupation = mysqli_real_escape_string($conn, $_POST["occupation"]);

    $sql = "UPDATE tenant SET name='$name',email='$email',phone='$phone',address='$address',occupation='$occupation' WHERE tenant_id='$tenant_id'; ";
    if (mysqli_query($conn, $sql)) {
      header("Location: tenantDetails.php?id=" . $tenant_id);
    } else {
      echo "query error: " . mysqli_error($conn);
    }
  }
} else {

  $tenant_id = mysqli_real_escape_string($conn, $_POST["tenant_id"]);
  $sql = "SELECT * FROM tenant WHERE tenant_id='$tenant_id'";
  $result = mysqli_query($conn, $sql);
  $tenant = mysqli_fetch_assoc($result);
  $name = $tenant['name'];
  $email = $tenant['email'];
  $phone = $tenant['phone'];
  $occupation = $tenant['occupation'];
  $address = $tenant['address'];
}

?>


<?php include "header.php"; ?>

<div class="flex justify-center items-center">
  <div class="w-full m-8  ">
    <form action="editTenant.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-700 my-2">
          <b>Edit Tenant Details</b>
        </h1>
      </div>

      <input name="tenant_id" type="hidden" value="<?php echo $tenant_id; ?>">

      <div class="w-full   ">
        <label for="name" class="label required">
          Name
        </label>
        <input name="name" type="text" class="input" placeholder="Full name" autocomplete="name" maxlength="50" value="<?php echo $name; ?>" required>
        <p class="error_text"><?php echo $errors["name"] ? $errors["name"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="email" class="label required">
          Email
        </label>
        <input name="email" type="email" class="input" placeholder="Email address" autocomplete="email" maxlength="50" value="<?php echo $email; ?>" required>
        <p class="error_text"><?php echo $errors["email"] ? $errors["email"] : ""; ?> </p>
      </div>

      <div class="w-full flex gap-4 justify-center items-center">
        <div class="w-full  ">
          <label for="phone" class="label required">
            Phone
          </label>
          <input name="phone" type="tel" class="input" placeholder="Phone number" autocomplete="phone" maxlength="50" value="<?php echo $phone; ?>" required>
        </div>
        <div class="w-full  ">
          <label for="occupation" class="label">
            Occupation
          </label>
          <input name="occupation" type="tel" class="input" placeholder="Occupation" maxlength="50" value="<?php echo $occupation; ?>">
        </div>
      </div>

      <div class="w-full  ">
        <label for="address" class="label">
          Address
        </label>
        <textarea name="address" class="input" placeholder="Physical Address" autocomplete="address" maxlength="100"><?php echo $address; ?></textarea>
      </div>

      <div class="w-full flex gap-4  ">
        <a href="/rms/tenantDetails.php?id=<?php echo $tenant_id ?>" class="btn secondary w-auto">
          Cancel </a>
        <input name="update_tenant" type="submit" class="btn primary" value="Update Tenant Details">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>