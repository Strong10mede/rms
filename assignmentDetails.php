<?php
require("session.php");
require "connect.php";
$details = "";
$payment_details = "";

if (isset($_POST["delete"])) {
  $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
  $sql = "DELETE FROM house WHERE house_id='$id_to_delete'";

  if (mysqli_query($conn, $sql)) {
    //successfullt deleted
    header("Location: index.php");
  } else {
    //failure
    echo 'query error:' . mysqli_error($conn);
  }
}


if (isset($_GET["id"])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);

  $sql = "SELECT
            house.house_id,
            house_no,
            house.type,
            rent,
            house.deposit_amt,
            house.placement_fee,
            house.description,
            apartment.town,
            apartment.location,
            apartment.apartment_id,
            apartment.name AS 'apartment_name',
            OWNER.owner_id,
            OWNER.name AS 'owner_name',
            OWNER.phone AS 'owner_phone',
            tenant.tenant_id,
            tenant.name AS 'tenant_name',
            tenant.phone AS 'tenant_phone',
            assigned.date
          FROM
            house
          JOIN assigned ON assigned.house_id = '$id' AND assigned.house_id = house.house_id
          JOIN apartment ON apartment.apartment_id = house.apartment_id
          JOIN OWNER ON apartment.owner_id = OWNER.owner_id
          JOIN tenant ON tenant.tenant_id = assigned.tenant_id;";

  $result = mysqli_query($conn, $sql);
  $details = mysqli_fetch_assoc($result);

  $tenant_id = $details['tenant_id'];
  $owner_id = $details['owner_id'];

  $sql = "SELECT
            amount,
            description,
            payment_type,
            payment_date,
            payment_id
          FROM
            payment
          WHERE
            house_id = '$id' AND owner_id = '$owner_id' AND tenant_id = '$tenant_id';";

  $result = mysqli_query($conn, $sql);
  $payment_details = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $total_rent_paid = 0;
  $deposit_paid = 0;
  $placement_fee_paid = 0;
  foreach ($payment_details as $payment) {
    if ($payment["payment_type"] == "RENT") {
      $total_rent_paid += $payment["amount"];
    } else if ($payment["payment_type"] == "DEPOSIT") {
      $deposit_paid += $payment["amount"];
    } else if ($payment["payment_type"] == "PLACEMENT_FEES") {
      $placement_fee_paid += $payment["amount"];
    }
  }


  //original
  // $currentTime = date_create('now', timezone_open('Asia/Kolkata'));
  // $dateOfAssignment = date_create(htmlspecialchars($details["date"]), timezone_open('Asia/Kolkata'));
  // $duration = date_diff($currentTime, $dateOfAssignment)->format("%y Year %m Month %d Day");

  // $date_diff = date_diff($currentTime, $dateOfAssignment);
  // $totalDays = $date_diff->format("%y") * 12 +  $date_diff->format("%m")  ;

  // $total_rent_should_pay = ($totalDays) * $details["rent"];
  // $diff = $total_rent_paid - $total_rent_should_pay;


  //temporary
  $currentTime = date_create('now', timezone_open('Asia/Kolkata'));
  $dateOfAssignment = date_create(htmlspecialchars($details["date"]), timezone_open('Asia/Kolkata'));
  $duration = date_diff($currentTime, $dateOfAssignment)->format("%a Days %h Hour");

  $date_diff = date_diff($currentTime, $dateOfAssignment);
  $totalDays = (int) $date_diff->format("%a");

  $total_rent_should_pay = ($totalDays) * $details["rent"];
  $diff = $total_rent_paid - $total_rent_should_pay;
}

?>

<?php include "header.php" ?>

<div class="banner_blur">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-yellow-100  rounded-full p-3 text-yellow-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>
      <h1 class="text-4xl py-1 text-gray-900 sm:truncate">
        House Assignment Information
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/vacateHouse.php?house_id=<?php echo $details["house_id"]; ?>" class="btn primary" onclick=" return confirm('Are you really want to vacate the house and clear all its payment history?')">Vacate House</a>
  </div>
</div>


<div class="bg-white shadow overflow-hidden sm:rounded-lg m-5 max-w-6xl">
  <?php if ($details) { ?>
    <div class="px-4 py-6 sm:px-6 flex gap-4 justify-between items-baseline">
      <div>
        <h3 class="text-2xl font-medium text-gray-700  overflow-hidden overflow-ellipsis">
          <?php echo date_format(date_create(htmlspecialchars($details["date"]), timezone_open('Asia/Kolkata')), "d/m/Y") ?>
          <span class="text-base font-normal text-gray-600 mx-8"> TO </span>
          <?php echo date_format(date_create('now', timezone_open('Asia/Kolkata')), "d/m/Y") ?>
        </h3>
      </div>
      <p class="text-xl font-medium text-gray-900">
        <span class="text-base font-normal text-gray-500">Duration: </span> <?php echo $duration; ?>
      </p>
    </div>

    <div class="border-t border-gray-200  px-6 py-4 flex  gap-4 ">
      <div class="flex-1">

        <h4 class=" font-medium text-base text-gray-900 uppercase mb-2">Rent Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Deposit
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($details["deposit_amt"])); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Placement Fee
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($details["placement_fee"])); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Rent
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($details["rent"])); ?> /month
          </dd>
        </div>

        <h4 class=" font-medium text-base text-gray-900 uppercase pt-2 mb-2  ">House Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            House no.
          </dt>
          <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($details["house_no"]); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            House Type
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($details["type"]); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Apartment
          </dt>
          <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/apartmentDetails.php?id=<?php echo $details["apartment_id"]; ?>" class="link">
              <?php echo htmlspecialchars($details["apartment_name"]); ?>
            </a>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Location
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words">
            <?php echo htmlspecialchars($details["town"]); ?>, <?php echo htmlspecialchars($details["location"]); ?>
          </dd>
        </div>

        <h4 class=" font-medium text-base text-gray-900 uppercase pt-2 mb-2">Owner Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Name
          </dt>
          <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/ownerDetails.php?id=<?php echo $details["owner_id"]; ?>" class="link">
              <?php echo htmlspecialchars($details["owner_name"]); ?>
            </a>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Contact
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <div>
              <?php echo htmlspecialchars($details["owner_phone"]); ?>
            </div>
          </dd>
        </div>
      </div>


      <div class="flex-1">

        <h4 class=" font-medium text-base text-gray-900 uppercase  mb-2">Payment Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Deposit Paid
          </dt>
          <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2 rounded-full px-2 w-max">
            Rs. <?php echo number_format(htmlspecialchars($deposit_paid)); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Placement Fee Paid
          </dt>
          <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2 rounded-full px-2 w-max">
            Rs. <?php echo number_format(htmlspecialchars($placement_fee_paid)); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Total Rent Paid
          </dt>
          <dd class="mt-1 text-sm font-medium text-gray-900 sm:mt-0 sm:col-span-2 rounded-full px-2 w-max">
            Rs. <?php echo number_format(htmlspecialchars($total_rent_paid)) . " / " . number_format(htmlspecialchars($total_rent_should_pay)); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Due </dt>
          <dd class=" mt-0 sm:col-span-2 flex items-center gap-6  ">
            <p class="text-sm font-medium text-red-500 bg-red-100 rounded-full px-2 w-max">

              Rs. <?php echo ($diff <= 0) ? number_format(abs($diff)) : "0"; ?>
            </p>
            <?php if ($diff < 0) { ?>

              <a href="payRent.php?house_id=<?php echo $details["house_id"] ?>&apartment_id=<?php echo $details["apartment_id"]; ?>" class="btn small primary w-auto">Pay Dues</a>
            <?php } ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Overpayment </dt>
          <dd class="mt-1 text-sm font-medium text-green-900 sm:mt-0 sm:col-span-2 bg-green-100 rounded-full px-2 w-max">
            Rs. <?php echo ($diff >= 0) ? number_format(abs($diff)) : "0"; ?>
          </dd>
        </div>

        <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2">Tenant Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Name
          </dt>
          <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/tenantDetails.php?id=<?php echo $details["tenant_id"]; ?>" class="link">
              <?php echo htmlspecialchars($details["tenant_name"]); ?>
            </a>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Contact
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <div>
              <?php echo htmlspecialchars($details["tenant_phone"]); ?>
            </div>
          </dd>
        </div>


      </div>

    </div>
    <div class="flex justify-end gap-4 pb-6 pr-6">
      <a href="payRent.php?house_id=<?php echo $details["house_id"] ?>&apartment_id=<?php echo $details["apartment_id"]; ?>" class="btn primary w-auto">Pay Rent</a>
    </div>

  <?php } else { ?>
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg leading-6 font-medium text-gray-900">
        House Information missing
      </h3>
      <p class="mt-1 max-w-2xl text-sm text-gray-500">
        House do not exist
      </p>
    </div>
  <?php } ?>
</div>



<div class="flex flex-col max-w-6xl m-5">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-white border-b">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Payment Information
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            paid by <span class="text-gray-800 font-medium"><?php echo $details["tenant_name"]; ?> </span> to <span class="text-gray-800 font-medium"> <?php echo $details["owner_name"]; ?>
            </span>
          </p>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="pl-6 pr-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
              </th>

              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                description
              </th>


              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                Amount <span class="capitalize">(Rs.)</span>
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Date
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right ">
                details
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($payment_details as $payment) { ?>
              <tr>
                <td class="pl-6 pr-4 py-4 ">
                  <div class="text-sm text-gray-500">
                    <?php echo htmlspecialchars($payment["payment_id"]) ?>
                  </div>
                </td>

                <td class="px-4 py-4 ">
                  <div class="text-sm text-gray-500   break-words ">
                    <?php echo htmlspecialchars($payment["payment_type"]) ?>

                  </div>
                </td>
                <td class="px-4 py-4 ">
                  <div class="text-sm text-gray-500 lowercase break-words ">
                    <?php echo htmlspecialchars($payment["description"]) ?>

                  </div>
                </td>

                <td class="px-4 py-4 ">
                  <div class="text-base text-gray-900 text-right">
                    <?php echo number_format(htmlspecialchars($payment["amount"])); ?>
                  </div>
                </td>

                <td class="px-4 py-4 ">
                  <div class="text-sm text-gray-500 lowercase break-words w-48 ">
                    <?php echo htmlspecialchars($payment["payment_date"]) ?>
                  </div>
                </td>

                <td class="pl-4 pr-6 py-4  text-right text-sm font-medium">
                  <a href="invoicePreview.php?id=<?php echo $payment['payment_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Invoice</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php" ?>

</html>