<?php
require("session.php");
require "connect.php";


$sql = "SELECT apartment.apartment_id, name
        FROM apartment
        JOIN house ON house.apartment_id = apartment.apartment_id
        WHERE house.house_id NOT IN( SELECT house_id FROM assigned )
        GROUP BY apartment.apartment_id;";
$result = mysqli_query($conn, $sql);
$apartments_with_vacancy = mysqli_fetch_all($result, MYSQLI_ASSOC);


$sql = "SELECT tenant_id, name FROM tenant;";
$result = mysqli_query($conn, $sql);
$tenants = mysqli_fetch_all($result, MYSQLI_ASSOC);


$houses = [];
$house_detail = [];
$tenant_detail = [];

if (isset($_GET['confirm_asigning'])) {

  $errors = [];
  if (empty($_GET["tenant_id"])) {
    $errors["tenant_id"] = "The tenant_id is required. <br/>";
  }
  if (empty($_GET["house_id"])) {
    $errors["house_id"] = "The house_id is required. <br/>";
  }
  if (empty($_GET["deposit_amt"])) {
    $errors["deposit_amt"] = "The deposit_amt is required. <br/>";
  }
  if (empty($_GET["placement_fee"])) {
    $errors["placement_fee"] = "The placement_fee is required. <br/>";
  }

  if (!array_filter($errors)) {
    $house_id = mysqli_real_escape_string($conn, $_GET["house_id"]);
    $tenant_id = mysqli_real_escape_string($conn, $_GET["tenant_id"]);
    $deposit_amt = mysqli_real_escape_string($conn, $_GET["deposit_amt"]);
    $placement_fee = mysqli_real_escape_string($conn, $_GET["placement_fee"]);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
    try {

      $sql = "INSERT INTO assigned(house_id,tenant_id,deposit_amt,placement_fee) VALUES('$house_id','$tenant_id','$deposit_amt','$placement_fee') ;";
      mysqli_query($conn, $sql);

      $sql = "SELECT * FROM OWNER
              JOIN apartment ON apartment.owner_id = OWNER.owner_id
              JOIN house ON house.house_id = '$house_id' AND house.apartment_id = apartment.apartment_id;";
      $result = mysqli_query($conn, $sql);
      $owner = mysqli_fetch_assoc($result);
      $owner_id = $owner["owner_id"];

      $sql = "INSERT INTO payment(house_id,owner_id,tenant_id,amount,payment_type,description) VALUES('$house_id','$owner_id','$tenant_id','$deposit_amt','DEPOSIT','House Deposit');";
      mysqli_query($conn, $sql);

      $sql = "INSERT INTO payment(house_id,owner_id,tenant_id,amount,payment_type,description) VALUES('$house_id','$owner_id','$tenant_id','$placement_fee','PLACEMENT_FEES','House Placement Fee');";
      mysqli_query($conn, $sql);

      /* If code reaches this point without errors then commit the data in the database */
      mysqli_commit($conn);
      header("Location: tenantDetails.php?id=" . $tenant_id);
    } catch (mysqli_sql_exception $exception) {
      mysqli_rollback($conn);
      echo "query error: " . mysqli_error($conn);
      throw $exception;
    }
  } else {
    header("Location: asignHouse.php");
  }
} else {

  if (isset($_GET['tenant_id']) && !empty($_GET['tenant_id'])) {
    $tenant_id = mysqli_real_escape_string($conn, $_GET["tenant_id"]);
    $sql = "SELECT * FROM tenant WHERE tenant_id = '$tenant_id';";
    $result = mysqli_query($conn, $sql);
    $tenant_detail = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }


  if (isset($_GET['apartment_id']) && !empty($_GET['apartment_id']) && isset($_GET['house_id']) && !empty($_GET['house_id'])) {
    $house_id = mysqli_real_escape_string($conn, $_GET["house_id"]);
    $sql = "SELECT * FROM house WHERE house_id = '$house_id';";
    $result = mysqli_query($conn, $sql);
    $house_detail = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }


  if (isset($_GET['apartment_id']) && !empty($_GET['apartment_id'])) {
    $apartment_id = mysqli_real_escape_string($conn, $_GET["apartment_id"]);
    $sql = "SELECT house_id, house_no
            FROM house
            WHERE apartment_id = '$apartment_id' AND
              house_id NOT IN( SELECT house_id FROM assigned );";
    $result = mysqli_query($conn, $sql);
    $houses = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
}

?>


<?php include "header.php"; ?>

<div id="tobePrint" class="flex justify-center items-center">
  <div class="w-full m-4  ">
    <form action="asignHouse.php" method="get" class="max-w-5xl mx-auto w-full  bg-gray-50 rounded-xl px-6 py-6 shadow overflow-hidden">
      <div class=" text-center">
        <h1 class="text-3xl text-gray-900 my-2">
          <b>Assign House</b>
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
              <?php foreach ($apartments_with_vacancy as $apartment) { ?>
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
                        } ?> disabled>------ Select House ------</option>
                <?php foreach ($houses as $house) { ?>
                  <option <?php if (isset($house_id) && $house["house_id"] == $house_id) {
                            echo "selected";
                          } ?> value="<?php echo $house['house_id']; ?>"><?php echo $house["house_no"] ?></option>
                <?php } ?>
              </select>
            </div>
          <?php }        ?>
          <?php if (isset($house_id) && $house_detail) { ?>
            <div class="border-t border-gray-200 py-4  bg-indigo-100 rounded-2xl">
              <h4 class=" font-medium text-base text-gray-900 uppercase   mb-2 ml-6 ">House Detail</h4>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 ">
                  House no.
                </dt>
                <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2   max-w-xs break-words">
                  <?php echo htmlspecialchars($house_detail[0]["house_no"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Vacancy Status
                </dt>
                <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">vacant
                  </span>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2  max-w-xs break-words">
                  <?php echo htmlspecialchars($house_detail[0]["description"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  House Type
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  <?php echo htmlspecialchars($house_detail[0]["type"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Rent
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  Rs. <?php echo htmlspecialchars($house_detail[0]["rent"]); ?>/month
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Deposit Amount
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  Rs. <?php echo htmlspecialchars($house_detail[0]["deposit_amt"]); ?> refundable
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Placement Fee
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                  Rs. <?php echo htmlspecialchars($house_detail[0]["placement_fee"]); ?> one-time fee
                </dd>
              </div>
            </div>
          <?php } ?>
        </div>

        <div class="flex-1 flex flex-col justify-start items-center gap-4 p-4">

          <div class="w-full  ">
            <label for="tenant_id" class=" label required">
              Select Tenant:
            </label>
            <select onchange="this.form.submit()" name="tenant_id" class="input" required>
              <option <?php if (!isset($tenant_id)) {
                        echo "selected";
                      } ?> disabled>------ Select Tenant ------</option>
              <?php foreach ($tenants as $tenant) { ?>
                <option <?php if (isset($tenant_id) && $tenant["tenant_id"] == $tenant_id) {
                          echo "selected";
                        } ?> value="<?php echo $tenant['tenant_id']; ?>"><?php echo $tenant["name"] ?></option>
              <?php } ?>
            </select>
          </div>

          <?php if (isset($tenant_id) && $tenant_detail) { ?>
            <div class="border-t border-gray-200  py-4 bg-indigo-100 rounded-2xl">
              <h4 class=" font-medium text-base text-gray-900 uppercase mb-2 ml-6 ">Tenant Detail</h4>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Full name
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($tenant_detail[0]["name"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Email address
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($tenant_detail[0]["email"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Phone no.
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($tenant_detail[0]["phone"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Occupation
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2  max-w-xs break-words ">
                  <?php echo htmlspecialchars($tenant_detail[0]["occupation"]); ?>
                </dd>
              </div>
              <div class="px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                  Address
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words ">
                  <?php echo htmlspecialchars($tenant_detail[0]["address"]); ?>
                </dd>
              </div>
            </div>
          <?php } ?>

          <?php if (isset($house_id) && $house_detail) { ?>

            <div class="w-full flex gap-4 justify-center items-center">
              <div class="w-full  ">
                <label for="deposit_amt" class=" label required">
                  Deposit Amount Paid (in Rs.)
                </label>
                <input name="deposit_amt" type="number" class="input" placeholder="e.g. 50000" max='<?php echo $house_detail[0]["deposit_amt"]; ?>' min='<?php echo $house_detail[0]["deposit_amt"]; ?>' value='<?php echo $house_detail[0]["deposit_amt"]; ?>' required>
              </div>

              <div class="w-full  ">
                <label for="placement_fee" class=" label required">
                  Placement Fee Paid (in Rs.)
                </label>
                <input name="placement_fee" type="number" class="input" placeholder="e.g. 2000" max='<?php echo $house_detail[0]["placement_fee"]; ?>' min='<?php echo $house_detail[0]["placement_fee"]; ?>' value='<?php echo $house_detail[0]["placement_fee"]; ?>' required>
              </div>
            </div>
          <?php } ?>

        </div>
      </div>

      <div class="w-full flex gap-4  ">
        <a href="/rms/index.php" class="btn secondary  w-auto ">
          Cancel </a>
        <input name="confirm_asigning" onclick="return confirm('Please, confirm the payment.\nPress OK to confirm.\npress CANCEL to abort the asignment')" type="submit" class="btn primary" value="Asign House">

      </div>

    </form>

  </div>
</div>

<?php include "footer.php"; ?>