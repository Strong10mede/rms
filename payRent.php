<?php
require("session.php");
require "connect.php";


$sql = "SELECT apartment.apartment_id, name
        FROM apartment
        JOIN house ON house.apartment_id = apartment.apartment_id
        WHERE house.house_id IN( SELECT house_id FROM assigned )
        GROUP BY apartment.apartment_id;";
$result = mysqli_query($conn, $sql);
$apartments = mysqli_fetch_all($result, MYSQLI_ASSOC);


$payment_types = [
  ["name" => "Rent", "id" => "RENT"]
];

$rent_types = [
  ["name" => "1 Month Rent", "id" => "1"],
  ["name" => "2 Month Rent", "id" => "2"],
  ["name" => "3 Month Rent", "id" => "3"],
  ["name" => "6 Month Rent", "id" => "6"],
  ["name" => "12 Month Rent", "id" => "12"],
];


if (isset($_GET['confirm_payment'])) {

  $errors = [];
  if (empty($_GET["tenant_id"])) {
    $errors["tenant_id"] = "The tenant_id is required. <br/>";
  }
  if (empty($_GET["house_id"])) {
    $errors["house_id"] = "The house_id is required. <br/>";
  }
  if (empty($_GET["amount"])) {
    $errors["amount"] = "The amount is required. <br/>";
  }
  if (empty($_GET["rent_type"])) {
    $errors["rent_type"] = "The rent_type is required. <br/>";
  }

  if (!array_filter($errors)) {
    $house_id = mysqli_real_escape_string($conn, $_GET["house_id"]);
    $tenant_id = mysqli_real_escape_string($conn, $_GET["tenant_id"]);
    $amount = mysqli_real_escape_string($conn, $_GET["amount"]);
    $description = "House Rent for " . mysqli_real_escape_string($conn, $_GET["rent_type"]) . " months";




    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    try {

      $sql = "SELECT owner_id FROM apartment 
              WHERE apartment_id =
                (SELECT apartment_id FROM house WHERE house_id ='$house_id' );";
      $result = mysqli_query($conn, $sql);
      $owner = mysqli_fetch_assoc($result);
      $owner_id = $owner["owner_id"];

      $sql = "INSERT INTO payment(house_id,tenant_id,owner_id, amount, payment_type, description) VALUES('$house_id','$tenant_id','$owner_id','$amount','RENT','$description') ;";
      mysqli_query($conn, $sql);


      /* If code reaches this point without errors then commit the data in the database */
      mysqli_commit($conn);
      header("Location: assignmentDetails.php?id=" . $house_id);
    } catch (mysqli_sql_exception $exception) {
      mysqli_rollback($conn);
      echo "query error: " . mysqli_error($conn);
      throw $exception;
    }
  } else {
    header("Location: payRent.php");
  }
} else {



  if (isset($_GET['apartment_id']) && !empty($_GET['apartment_id']) && isset($_GET['house_id']) && !empty($_GET['house_id'])) {
    $house_id = mysqli_real_escape_string($conn, $_GET["house_id"]);
    $sql = "SELECT
              tenant.tenant_id,
              tenant.name,
              tenant.email,
              tenant.phone,
              tenant.address,
              tenant.occupation,
              assigned.house_id,
              house.house_no,
              house.type,
              house.rent,
              house.description
          FROM tenant
          JOIN assigned ON assigned.tenant_id = tenant.tenant_id
          JOIN house ON house.house_id = assigned.house_id
          WHERE assigned.house_id = '$house_id';";
    $result = mysqli_query($conn, $sql);
    $details = mysqli_fetch_assoc($result);
  }


  if (isset($_GET['apartment_id']) && !empty($_GET['apartment_id'])) {
    $apartment_id = mysqli_real_escape_string($conn, $_GET["apartment_id"]);
    $sql = "SELECT house_id, house_no
            FROM house
            WHERE apartment_id = '$apartment_id' AND
              house_id IN( SELECT house_id FROM assigned );";
    $result = mysqli_query($conn, $sql);
    $houses = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  if (isset($_GET['rent_type']) && !empty($_GET['rent_type'])) {
    $rent_type = mysqli_real_escape_string($conn, $_GET["rent_type"]);
  }
}
?>


<?php include "header.php"; ?>

<div id="tobePrint" class="flex justify-center items-center">
  <div class="w-full m-4  ">
    <form action="payRent.php" method="get" class="max-w-5xl mx-auto w-full  bg-gray-50 rounded-xl px-6 py-6 shadow overflow-hidden">
      <div class=" text-center">
        <h1 class="text-3xl text-gray-900 my-2">
          <b>Pay House Rent</b>
        </h1>
      </div>

      <div class="flex gap-4">
        <div class="flex-1 flex flex-col justify-start items-center gap-4 p-4">

          <div class="w-full  ">
            <label for="apartment_id" class=" label required">
              Select Apartment:
            </label>
            <select onchange="this.form.submit()" name="apartment_id" class="input" required>
              <option <?php if (!isset($apartment_id)) {
                        echo "selected";
                      } ?> disabled>------ Select Apartment ------</option>
              <?php foreach ($apartments as $apartment) { ?>
                <option <?php if (isset($apartment_id) && $apartment["apartment_id"] == $apartment_id) {
                          echo "selected";
                        } ?> value="<?php echo $apartment['apartment_id']; ?>"><?php echo $apartment["name"] ?></option>
              <?php } ?>
            </select>
          </div>

          <?php if (isset($apartment_id)) {  ?>
            <div class="w-full  ">
              <label for="house_id" class=" label required">
                Select House:
              </label>
              <select onchange="this.form.submit()" name="house_id" class="input" required>
                <option <?php if (!isset($house_id)) {
                          echo "selected";
                        } ?> value="">------ Select House ------</option>
                <?php foreach ($houses as $house) { ?>
                  <option <?php if (isset($house_id) && $house["house_id"] == $house_id) {
                            echo "selected";
                          } ?> value="<?php echo $house['house_id']; ?>"><?php echo $house["house_no"] ?></option>
                <?php } ?>
              </select>
            </div>
          <?php }        ?>

          <?php if (isset($house_id) && $details) { ?>

            <input type="hidden" name="tenant_id" value="<?php echo $details['tenant_id']; ?>">

            <div class="w-full  ">
              <label for="rent_type" class=" label required">
                Select Rent Type:
              </label>
              <select onchange="this.form.submit()" name="rent_type" class="input" required>
                <option <?php if (!isset($rent_type)) {
                          echo "selected";
                        } ?> disabled>------ Select Type ------</option>
                <?php foreach ($rent_types as $rent_t) { ?>
                  <option <?php if (isset($rent_type) && $rent_t["id"] == $rent_type) {
                            echo "selected";
                          } ?> value="<?php echo $rent_t['id']; ?>"><?php echo $rent_t["name"] ?></option>
                <?php } ?>
              </select>
            </div>

            <?php if (isset($rent_type)) { ?>

              <div class="w-full   py-4 bg-indigo-100 rounded-2xl">

                <div class="px-4 w-full py-1.5 sm:grid sm:grid-cols-2 items-center sm:gap-2 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 ">
                    Rent Per Month(in Rs.)
                  </dt>
                  <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-1   max-w-xs break-words">
                    <?php echo number_format(htmlspecialchars($details["rent"])); ?> /month
                  </dd>
                </div>

                <div class="px-4 w-full  py-1.5 sm:grid sm:grid-cols-2 items-center sm:gap-2 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Total Months:
                  </dt>
                  <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-1   max-w-xs break-words">
                    <?php echo number_format(htmlspecialchars($rent_type)); ?> month
                  </dd>
                </div>

                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($details["rent"] * $rent_type); ?>">

                <div class="px-4 w-full  py-1.5 sm:grid sm:grid-cols-2 items-center sm:gap-2 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500 ">
                    Total Rent to Pay (in Rs.)
                  </dt>
                  <dd class="mt-1 text-xl font-medium text-gray-900 sm:mt-0 sm:col-span-1   max-w-xs break-words">
                    <p>
                      <span class="text-sm ">
                        <?php echo number_format(htmlspecialchars($details["rent"])); ?>
                        x
                        <?php echo number_format(htmlspecialchars($rent_type)); ?><br>
                      </span>
                      =
                      <?php echo number_format(htmlspecialchars($details["rent"] * $rent_type)); ?>
                    </p>
                  </dd>
                </div>
              </div>
            <?php } ?>

          <?php } ?>

        </div>

        <div class="flex-1 flex flex-col justify-start items-center gap-4 p-4">
          <?php if (isset($house_id) && $details) { ?>
            <div class="border-t border-gray-200 py-4 w-full bg-indigo-100 rounded-2xl">
              <h4 class=" font-medium text-base text-gray-900 uppercase   mb-2 ml-6 ">House Detail</h4>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 ">
                  House no.
                </dt>
                <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2   max-w-xs break-words">
                  <?php echo htmlspecialchars($details["house_no"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  House Type
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <?php echo htmlspecialchars($details["type"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2  max-w-xs break-words">
                  <?php echo htmlspecialchars($details["description"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Rent
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  Rs. <?php echo number_format(htmlspecialchars($details["rent"])); ?> / month
                </dd>
              </div>
            </div>

            <div class="border-t border-gray-200  py-4 bg-indigo-100 rounded-2xl">
              <h4 class=" font-medium text-base text-gray-900 uppercase mb-2 ml-6 ">Tenant Detail</h4>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Full name
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($details["name"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Email address
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($details["email"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Phone no.
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($details["phone"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Occupation
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2  max-w-xs break-words ">
                  <?php echo htmlspecialchars($details["occupation"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Address
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($details["address"]); ?>
                </dd>
              </div>
            </div>
          <?php } ?>
        </div>

      </div>

      <div class="w-full flex gap-4  ">
        <a href="/rms/index.php" class="btn secondary  w-auto ">
          Cancel </a>
        <input name="confirm_payment" onclick="return confirm('Please, confirm the payment.\nPress OK to confirm.\npress CANCEL to abort the asignment')" type="submit" class="btn primary" value="Confirm Payment">
      </div>

    </form>

  </div>
</div>

<?php include "footer.php"; ?>