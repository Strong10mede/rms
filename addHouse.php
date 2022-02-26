<?php
require("session.php");

require "connect.php";

$type = "";
$house_no = "";
// $apartment_id = "";
$rent = "";
$deposit_amt = "";
$placement_fee = "";
$description =  "";
$errors = ["house_no" => "", "type" => "", "apartment_id" => "", "rent" => "", "deposit_amt" => "", "placement_fee" => ""];

if (isset($_POST['submit'])) {

  if (empty($_POST["house_no"])) {
    $errors["house_no"] = "House number is required. <br/>";
  } else {
    $house_no = htmlspecialchars($_POST["house_no"]);
  }
  if (empty($_POST["type"])) {
    $errors["type"] = "House type is required. <br/>";
  } else {
    $type = htmlspecialchars($_POST["type"]);
  }
  if (empty($_POST["apartment_id"])) {
    $errors["apartment_id"] = "Apartment name is required. <br/>";
  } else {
    $apartment_id = htmlspecialchars($_POST["apartment_id"]);
  }
  if (empty($_POST["rent"])) {
    $errors["rent"] = "Rent amount is required. <br/>";
  } else {
    $rent = htmlspecialchars($_POST["rent"]);
  }
  if (empty($_POST["deposit_amt"])) {
    $errors["deposit_amt"] = "Deposit amount is required. <br/>";
  } else {
    $deposit_amt = htmlspecialchars($_POST["deposit_amt"]);
  }
  if (empty($_POST["placement_fee"])) {
    $errors["placement_fee"] = "Placement fee is required. <br/>";
  } else {
    $placement_fee = htmlspecialchars($_POST["placement_fee"]);
  }
  $description = htmlspecialchars($_POST["description"]);

  if (!array_filter($errors)) {
    //there is no error in the form

    $house_no = mysqli_real_escape_string($conn, $_POST["house_no"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $apartment_id = mysqli_real_escape_string($conn, $_POST["apartment_id"]);
    $rent = mysqli_real_escape_string($conn, $_POST["rent"]);
    $deposit_amt = mysqli_real_escape_string($conn, $_POST["deposit_amt"]);
    $placement_fee = mysqli_real_escape_string($conn, $_POST["placement_fee"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    $sql = "INSERT INTO house(house_no,type,apartment_id,rent,deposit_amt,placement_fee,description) VALUES('$house_no','$type','$apartment_id','$rent','$deposit_amt','$placement_fee','$description' ) ";
    if (mysqli_query($conn, $sql)) {
      header("Location: apartmentDetails.php?id=" . $apartment_id);
    } else {
      echo "quesry error: " . mysqli_error($conn);
    }
  }
} else if (isset($_GET['apartment_id']) && !empty($_GET["apartment_id"])) {
  $apartment_id = mysqli_real_escape_string($conn, $_GET["apartment_id"]);
}

$sql = "SELECT apartment_id,name FROM apartment;";
$result = mysqli_query($conn, $sql);
$apartments = mysqli_fetch_all($result, MYSQLI_ASSOC);


$house_types = [
  ["name" => "1 B.H.K.", "id" => "1BHK"],
  ["name" => "2 B.H.K.", "id" => "2BHK"],
  ["name" => "3 B.H.K.", "id" => "3BHK"],
  ["name" => "Maisonette", "id" => "MAISONETTE"],
  ["name" => "Shop", "id" => "SHOP"]
]

?>


<?php include "header.php"; ?>

<div class="flex justify-center items-center">
  <div class="w-full m-8  ">
    <form action="addHouse.php" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
      <div class="p-3 text-center">
        <h1 class="text-3xl text-gray-700 my-2">
          <b>Add house</b>
        </h1>
      </div>

      <div class="w-full  ">
        <label for="apartment_id" class="label required">
          Select Apartment:
        </label>
        <select name="apartment_id" class="input" required>
          <option disabled <?php if (!isset($apartment_id)) {
                              echo "selected";
                            } ?>>
            ------ select apartment ------
          </option>
          <?php foreach ($apartments as $apartment) { ?>
            <option <?php if (isset($apartment_id) && $apartment_id == $apartment["apartment_id"]) {
                      echo "selected";
                    } ?> value="<?php echo $apartment['apartment_id']; ?>">
              <?php echo $apartment["name"]; ?>
            </option>
          <?php } ?>
        </select>
        <p class="error_text"><?php echo $errors["apartment_id"] ? $errors["apartment_id"] : ""; ?> </p>
      </div>

      <div class="w-full flex gap-4 justify-center ">
        <div class="w-full   ">
          <label for="house_no" class="label required">
            House No.
          </label>
          <input name="house_no" type="text" class="input" placeholder="e.g. A/403" maxlength="50" value="<?php echo $house_no; ?>" required>
          <p class="error_text"><?php echo $errors["house_no"] ? $errors["house_no"] : ""; ?> </p>
        </div>

        <div class="w-full  ">
          <label for="type" class="label required">
            House Type
          </label>
          <select name="type" class="input" required>
            <?php foreach ($house_types as $house_type) { ?>
              <option value="<?php echo $house_type['id']; ?>"><?php echo $house_type["name"] ?></option>
            <?php } ?>
          </select>
          <p class="error_text"><?php echo $errors["type"] ? $errors["type"] : ""; ?> </p>
        </div>
      </div>

      <div class="w-full  ">
        <label for="rent" class="label required">
          Rent (in Rs.)
        </label>
        <input name="rent" type="number" class="input" placeholder="e.g. 12000" max="2000000000" value="<?php echo $rent; ?>" required>
        <p class="error_text"><?php echo $errors["rent"] ? $errors["rent"] : ""; ?> </p>
      </div>

      <div class="w-full flex gap-4 justify-center ">
        <div class="w-full  ">
          <label for="deposit_amt" class="label required">
            Deposit Amount (in Rs.)
          </label>
          <input name="deposit_amt" type="number" class="input" placeholder="e.g. 50000" max="2000000000" value="<?php echo $deposit_amt; ?>" required>
          <p class="error_text"><?php echo $errors["deposit_amt"] ? $errors["deposit_amt"] : ""; ?> </p>
        </div>

        <div class="w-full  ">
          <label for="placement_fee" class="label required">
            Placement Fee (in Rs.)
          </label>
          <input name="placement_fee" type="number" class="input" placeholder="e.g. 2000" max="2000000000" value="<?php echo $placement_fee; ?>" required>
          <p class="error_text"><?php echo $errors["placement_fee"] ? $errors["placement_fee"] : ""; ?> </p>
        </div>
      </div>

      <div class="w-full  ">
        <label for="description" class="label">
          Description
        </label>
        <textarea name="description" class="input" maxlength="100" placeholder="House Description"><?php echo $description; ?></textarea>
      </div>



      <div class="w-full flex gap-4  ">
        <a href="/rms/houses.php" class="btn secondary w-auto"> Cancel </a>
        <input name="submit" type="submit" class="btn primary " value="Add New House">
      </div>

    </form>

  </div>
</div>
<?php include "footer.php"; ?>