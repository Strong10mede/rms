<?php
require("session.php");
require "connect.php";

$name = $type = $location = $owner_id = $description = $town =  "";
$errors = ["name" => "", "type" => "", "location" => "", "owner_id" => "", "town" => "",];

if (isset($_POST['submit'])) {

  if (empty($_POST["name"])) {
    $errors["name"] = "The Apartment name is required. <br/>";
  } else {
    $name = htmlspecialchars($_POST["name"]);
  }
  if (empty($_POST["type"])) {
    $errors["type"] = "The Apartment type is required. <br/>";
  } else {
    $type = htmlspecialchars($_POST["type"]);
  }
  if (empty($_POST["location"])) {
    $errors["location"] = "The location is required. <br/>";
  } else {
    $location = htmlspecialchars($_POST["location"]);
  }
  if (empty($_POST["owner_id"])) {
    $errors["owner_id"] = "The Apartment Owner is required. <br/>";
  } else {
    $owner_id = htmlspecialchars($_POST["owner_id"]);
  }
  if (empty($_POST["town"])) {
    $errors["town"] = "The town is required. <br/>";
  } else {
    $town = htmlspecialchars($_POST["town"]);
  }

  $description = htmlspecialchars($_POST["description"]);

  if (!array_filter($errors)) {
    //there is no error in the form

    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $town = mysqli_real_escape_string($conn, $_POST["town"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $owner_id = mysqli_real_escape_string($conn, $_POST["owner_id"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    $sql = "INSERT INTO apartment(name,town,location,owner_id,type,description) VALUES('$name','$town','$location','$owner_id','$type','$description' ) ";
    if (mysqli_query($conn, $sql)) {
      header("Location: apartments.php");
      exit;
    } else {
      echo "quesry error: " . mysqli_error($conn);
    }
  }
}
$sql = "SELECT owner_id,name FROM owner;";
$result = mysqli_query($conn, $sql);
$owners = mysqli_fetch_all($result, MYSQLI_ASSOC);

$apartment_types = [
  ["name" => "Flat", "id" => "FLAT"],
  ["name" => "Bunglow", "id" => "BUNGLOW"],
  ["name" => "Maisonette", "id" => "MAISONETTE"],
  ["name" => "Other", "id" => "OTHER"]
]

?>


<?php include "header.php"; ?>

<div class="flex justify-center items-center">
  <div class="w-full   m-8  ">
    <form action="addApartment.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-700 my-2">
          <b>Add Apartment</b>
        </h1>
      </div>

      <div class="w-full   ">
        <label for="name" class="label required">
          Apartment Name
        </label>
        <input name="name" type="text" class="input" placeholder="Apartment name" maxlength="50" value="<?php echo $name; ?>" required>
        <p class="error_text"><?php echo $errors["name"] ? $errors["name"] : ""; ?> </p>
      </div>

      <div class="w-full flex gap-4 justify-center items-center">
        <div class="w-full  ">
          <label for="type" class=" label required">
            Apartment Type
          </label>
          <select name="type" class="input" required>
            <?php foreach ($apartment_types as $apartment_type) { ?>
              <option value="<?php echo $apartment_type['id']; ?>"><?php echo $apartment_type["name"] ?></option>
            <?php } ?>
          </select>
          <p class="error_text"><?php echo $errors["type"] ? $errors["type"] : ""; ?> </p>
        </div>

        <div class="w-full  ">
          <label for="owner_id" class="label required">
            Apartment Owner
          </label>
          <select name="owner_id" class="input" required>
            <?php foreach ($owners as $owner) { ?>
              <option value="<?php echo $owner['owner_id']; ?>"><?php echo $owner["name"] ?></option>
            <?php } ?>
          </select>
          <p class="error_text"><?php echo $errors["owner_id"] ? $errors["owner_id"] : ""; ?> </p>
        </div>
      </div>

      <div class="w-full  ">
        <label for="town" class="label required">
          Town
        </label>
        <input name="town" type="text" class="input" maxlength="50" placeholder="e.g. Rajkot" value="<?php echo $town; ?>" required>
        <p class="error_text"><?php echo $errors["town"] ? $errors["town"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="location" class=" label required">
          Location
        </label>
        <textarea name="location" class="input" maxlength="100" placeholder="Apartment address" autocomplete="address" required><?php echo $location; ?></textarea>
        <p class="error_text"><?php echo $errors["location"] ? $errors["location"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="description" class="label">
          Description
        </label>
        <textarea name="description" class="input" maxlength="100" placeholder="Apartment Description"><?php echo $description; ?></textarea>
      </div>



      <div class="w-full flex gap-4  ">
        <a href="/rms/apartments.php" class="btn secondary w-auto"> Cancel </a>
        <input name="submit" type="submit" class="btn primary " value="Add Apartment">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>