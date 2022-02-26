<?php
require("session.php");
require "connect.php";

$name = $type = $location = $owner_id = $description = $town = $apartment_id = "";
$errors = ["name" => "", "type" => "", "location" => "", "owner_id" => "", "town" => ""];

if (isset($_POST['update_apartment']) && isset($_POST['apartment_id']) && !empty($_POST["apartment_id"])) {

  $apartment_id = mysqli_real_escape_string($conn, $_POST["apartment_id"]);


  if (empty($_POST["name"])) {
    $errors["name"] = "Apartment name is required. <br/>";
  } else {
    $name = htmlspecialchars($_POST["name"]);
  }
  if (empty($_POST["type"])) {
    $errors["type"] = "Apartment type is required. <br/>";
  } else {
    $type = htmlspecialchars($_POST["type"]);
  }
  if (empty($_POST["location"])) {
    $errors["location"] = "Location is required. <br/>";
  } else {
    $location = htmlspecialchars($_POST["location"]);
  }
  if (empty($_POST["owner_id"])) {
    $errors["owner_id"] = "Apartment Owner is required. <br/>";
  } else {
    $owner_id = htmlspecialchars($_POST["owner_id"]);
  }
  if (empty($_POST["town"])) {
    $errors["town"] = "Town is required. <br/>";
  } else {
    $town = htmlspecialchars($_POST["town"]);
  }

  $description = htmlspecialchars($_POST["description"]);

  if (!array_filter($errors)) {

    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $town = mysqli_real_escape_string($conn, $_POST["town"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $owner_id = mysqli_real_escape_string($conn, $_POST["owner_id"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    $sql = "UPDATE apartment 
            SET name='$name',town='$town',location='$location',owner_id='$owner_id',type='$type',description='$description' 
            WHERE apartment_id='$apartment_id';";
    if (mysqli_query($conn, $sql)) {
      header("Location: apartmentDetails.php?id=" . $apartment_id);
      exit;
    } else {
      echo "query error: " . mysqli_error($conn);
    }
  }
} else {
  $apartment_id = mysqli_real_escape_string($conn, $_POST["apartment_id"]);
  $sql = "SELECT * FROM apartment WHERE apartment_id='$apartment_id'";
  $result = mysqli_query($conn, $sql);
  $apartment = mysqli_fetch_assoc($result);
  $name = $apartment['name'];
  $town = $apartment['town'];
  $location = $apartment['location'];
  $type = $apartment['type'];
  $owner_id = $apartment['owner_id'];
  $description = $apartment['description'];
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

<div class="flex ">
  <div class="w-full m-5">
    <form action="editApartment.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-700 my-2">
          <b>Edit Apartment Details</b>
        </h1>
      </div>

      <div class="w-full">
        <label for="name" class="label required">
          Apartment Name
        </label>
        <input name="name" type="text" class="input" maxlength="50" placeholder="Apartment name" value="<?php echo $name; ?>" required>
        <p class="error_text"><?php echo $errors["name"] ? $errors["name"] : ""; ?> </p>
      </div>
      <input name="apartment_id" type="hidden" value="<?php echo $apartment_id; ?>">


      <div class="w-full flex gap-4 justify-center items-center">
        <div class="w-full  ">
          <label for="type" class=" label required">
            Apartment Type
          </label>
          <select name="type" class="input" required>
            <option disabled <?php if (!isset($type)) {
                                echo "selected";
                              } ?>>
              ------ select type ------
            </option>
            <?php foreach ($apartment_types as $apartment_type) { ?>
              <option <?php if (isset($type) && $type == $apartment_type["id"]) {
                        echo "selected";
                      } ?> value="<?php echo $apartment_type['id']; ?>">
                <?php echo $apartment_type["name"] ?>
              </option>
            <?php } ?>
          </select>
          <p class="error_text"><?php echo $errors["type"] ? $errors["type"] : ""; ?> </p>
        </div>



        <div class="w-full  ">
          <label for="owner_id" class="label required">
            Apartment Owner
          </label>
          <select name="owner_id" class="input" required>
            <option disabled <?php if (!isset($owner_id)) {
                                echo "selected";
                              } ?>>
              ------ select owner ------
            </option>
            <?php foreach ($owners as $owner) { ?>
              <option <?php if (isset($owner_id) && $owner_id == $owner["owner_id"]) {
                        echo "selected";
                      } ?> value="<?php echo $owner['owner_id']; ?>">
                <?php echo $owner["name"]; ?>
              </option>
            <?php } ?>
          </select>
          <p class="error_text"><?php echo $errors["owner_id"] ? $errors["owner_id"] : ""; ?> </p>
        </div>
      </div>


      <div class="w-full  ">
        <label for="town" class="label required">
          Town
        </label>
        <input name="town" type="text" class=" input " maxlength="50" placeholder="e.g. Rajkot" value="<?php echo $town; ?>" required>
        <p class="error_text"><?php echo $errors["town"] ? $errors["town"] : ""; ?> </p>
      </div>


      <div class="w-full  ">
        <label for="location" class=" label required">
          Location
        </label>
        <textarea name="location" class=" input " placeholder="Apartment address" autocomplete="address" maxlength="100" required><?php echo $location; ?></textarea>
        <p class="error_text"><?php echo $errors["location"] ? $errors["location"] : ""; ?> </p>
      </div>

      <div class="w-full  ">
        <label for="description" class="label">
          Description
        </label>
        <textarea name="description" class=" input " maxlength="100" placeholder="Apartment Description"><?php echo $description; ?></textarea>
      </div>


      <div class="w-full flex gap-4  ">
        <a href="/rms/apartmentDetails.php?id=<?php echo $apartment_id; ?>" class="btn secondary w-auto"> Cancel </a>
        <input name="update_apartment" type="submit" class="btn primary" value="Update Apartment Details">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>