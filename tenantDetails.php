<?php
require("session.php");

require "connect.php";
$tenant = "";
if (isset($_POST["delete"])) {
  $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
  $sql = "DELETE FROM tenant WHERE tenant_id='$id_to_delete'";

  if (mysqli_query($conn, $sql)) {
    //successfully deleted
    header("Location: index.php");
  } else {
    //failure
    echo 'query error:' . mysqli_error($conn);
  }
}

if (isset($_GET["id"])) {
  $id = mysqli_real_escape_string($conn, $_GET['id']);

  $sql = "SELECT * FROM tenant WHERE tenant_id='$id'";
  $result = mysqli_query($conn, $sql);
  $tenant = mysqli_fetch_assoc($result);

  $sql = "SELECT payment_type, SUM(amount) AS 'total'
          FROM payment WHERE tenant_id = '$id'
          GROUP BY payment_type
          ORDER BY payment_type;";
  $result = mysqli_query($conn, $sql);
  $total_payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $sql = "SELECT
            payment_id,
            payment_date,
            amount,
            payment_type,
            payment.description,
            owner.owner_id,
            owner.name AS 'owner_name',
            house.house_id,
            house.house_no,
            apartment.apartment_id,
            apartment.name AS 'apartment_name'
          FROM
            payment
          JOIN house ON house.house_id = payment.house_id
          JOIN owner ON owner
            .owner_id = payment.owner_id
          JOIN apartment ON apartment.owner_id = payment.owner_id AND apartment.apartment_id = house.apartment_id
          WHERE payment.tenant_id='$id'
          ORDER BY payment_id DESC;";
  $result = mysqli_query($conn, $sql);
  $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $sql = "SELECT
            house.house_id,
            house_no,
            rent,
            assigned.date,
            assigned.deposit_amt,
            assigned.placement_fee,
            house.description,
            TYPE,
            SUM(amount) AS 'total_rent'
          FROM house
          JOIN assigned ON assigned.tenant_id = '$id' && house.house_id = assigned.house_id
          LEFT JOIN payment ON payment_type = 'RENT' AND payment.house_id = house.house_id
          GROUP BY house_id";
  $result = mysqli_query($conn, $sql);
  $houses = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<?php include "header.php" ?>

<div class="banner_blur ">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-pink-100  rounded-full p-3 text-pink-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Tenant Information
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/asignHouse.php?tenant_id=<?php echo $tenant["tenant_id"]; ?>" class="btn primary">Asign House</a>
  </div>
</div>



<div class="flex flex-col gap-5 m-5 max-w-6xl">
  <div class="flex gap-5">

    <div class="bg-white shadow overflow-hidden sm:rounded-lg w-full max-w-2xl">
      <?php if ($tenant) { ?>
        <div class="px-4 py-6 sm:px-6 bg-gray-900">
          <h3 class="text-3xl font-medium text-gray-300 overflow-hidden overflow-ellipsis">
            <?php echo htmlspecialchars($tenant["name"]); ?>
          </h3>
        </div>
        <div class="border-t border-gray-200  px-6 py-4">
          <h4 class=" font-medium text-base text-gray-900 uppercase mt-4 mb-2 ">Tenant Detail</h4>

          <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Full name
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 overflow-hidden overflow-ellipsis">
              <?php echo htmlspecialchars($tenant["name"]); ?>
            </dd>
          </div>
          <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Email address
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <?php echo htmlspecialchars($tenant["email"]); ?>
            </dd>
          </div>
          <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Phone no.
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <?php echo htmlspecialchars($tenant["phone"]); ?>
            </dd>
          </div>
          <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Occupation
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <?php echo htmlspecialchars($tenant["occupation"]); ?>
            </dd>
          </div>
          <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Address
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 w-full max-w-xs break-words">
              <?php echo htmlspecialchars($tenant["address"]); ?>
            </dd>
          </div>
          <div class="flex justify-end gap-4">
            <form action="tenantDetails.php" method="POST" onsubmit="return confirm('Are you really want to delete the Owner Data and its Apartment Data and Payment History as well?')">
              <input type="hidden" name="id_to_delete" value="<?php echo htmlspecialchars($tenant["tenant_id"]); ?>">
              <input type="submit" name="delete" value="DELETE" class="btn secondary danger small w-auto">
            </form>

            <form action="editTenant.php" method="POST" class="">
              <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars($tenant["tenant_id"]); ?>">
              <input type="submit" name="submit" value="Edit Details" class="btn primary small">
            </form>
          </div>
        </div>



      <?php } else { ?>
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Tenant Information missing
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Tenant do not exist
          </p>
        </div>
      <?php } ?>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg  max-w-lg">
      <?php if ($total_payments) { ?>
        <div class="p-6 bg-gray-900 ">
          <h3 class="text-2xl text-gray-300 overflow-hidden overflow-ellipsis">
            Payment Overview
          </h3>
        </div>
        <div class="border-t border-gray-200 text-sm px-6 py-4">

          <?php foreach ($total_payments as $payment) { ?>
            <div class="px-4 py-2 grid grid-cols-2 gap-2 items-end ">
              <dt class="text-base font-medium text-gray-500">
                TOTAL <?php echo htmlspecialchars($payment["payment_type"]); ?> PAID
              </dt>
              <dd class=" text-xl font-medium text-right text-gray-900 break-words overflow-hidden overflow-ellipsis">
                <?php echo number_format(htmlspecialchars($payment["total"])); ?>
              </dd>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="px-4 py-5 sm:px-6 bg-gray-900">
          <h3 class="text-lg leading-6 font-medium text-gray-300">
            Payment Details
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-400">
            No payment done yet
          </p>
        </div>
      <?php } ?>
    </div>
  </div>


  <?php if ($tenant) { ?>
    <div class="flex flex-col max-w-6xl w-full">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
              <h3 class="text-lg leading-6 font-medium text-gray-300">
                Houses Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-400">
                occupied by <span class=" text-gray-300 font-medium"> <?php echo $tenant["name"]; ?></span>
              </p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="pl-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    House
                  </th>
                  <th scope="col" class="pr-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right  w-20 break-words ">
                    Assigned date
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                    Deposited
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right ">
                    Placement Fee
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                    total rent paid
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                    <span class="text-red-700 bg-red-100 px-1.5 rounded-full">dues</span>/<span class="text-green-700 bg-green-100 px-1.5 rounded-full">credit</span>
                  </th>

                  <th scope=" col" class="relative pl-2 pr-6 py-3">
                    <a href="/rms/asignHouse.php?tenant_id=<?php echo $tenant["tenant_id"]; ?>" class="btn primary small">Asign House</a>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($houses as $house) { ?>
                  <tr>

                    <td class="pl-6 py-4">
                      <div class="flex items-center  ">
                        <div class="max-w-xs w-44 break-words">
                          <div>
                            <a href="houseDetails.php?id=<?php echo $house['house_id'] ?>" class="text-base font-medium text-gray-900 hover:underline">
                              <?php echo htmlspecialchars($house["house_no"]) ?>
                            </a>
                          </div>
                          <div class="text-sm text-gray-500 mt-1">
                            <?php echo htmlspecialchars($house["description"]) ?>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="pr-2 py-4 text-right">
                      <div class="text-sm text-gray-900 w-20 break-words ">
                        <?php echo date_format(date_create(htmlspecialchars($house["date"]), timezone_open('Asia/Kolkata')), "d/m/Y") ?>
                      </div>
                    </td>

                    <td class="px-2 py-4 text-right">
                      <div class="text-sm text-gray-900">
                        <?php echo number_format(htmlspecialchars($house["deposit_amt"])) ?>
                      </div>
                    </td>

                    <td class="px-2 py-4 text-right">
                      <div class="text-sm text-gray-900">
                        <?php echo number_format(htmlspecialchars($house["placement_fee"])) ?>
                      </div>
                    </td>
                    <td class="px-2 py-4 text-right">
                      <div class="text-sm text-gray-900">
                        <?php echo number_format(htmlspecialchars((int)$house["total_rent"])) ?>
                      </div>
                    </td>
                    <td class="px-2 py-4 text-right">
                      <?php

                      //temporary
                      $currentTime = date_create('now', timezone_open('Asia/Kolkata'));
                      $dateOfAssignment = date_create(htmlspecialchars($house["date"]), timezone_open('Asia/Kolkata'));

                      $date_diff = date_diff($currentTime, $dateOfAssignment);
                      $totalDays = (int) $date_diff->format("%a");

                      $total_rent_should_pay = ($totalDays) * $house["rent"];
                      $diff = (int)$house["total_rent"] - $total_rent_should_pay;
                      if ($diff < 0) {
                        echo ' <div class="text-sm px-2 rounded-full text-red-800 bg-red-100  ml-auto w-max">' . number_format(abs($diff)) . '</div>';
                      } else {
                        echo ' <div class="text-sm px-2 rounded-full text-green-800 bg-green-100  ml-auto w-max">' . number_format(abs($diff)) . '</div>';
                      }   ?>
                    </td>

                    <td class="pl-2 pr-6 py-4 text-right text-sm font-medium">
                      <a href="assignmentDetails.php?id=<?php echo $house['house_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Details</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php if ($tenant) { ?>
    <div class="flex flex-col max-w-6xl">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
              <h3 class="text-lg leading-6 font-medium text-gray-300">
                Payment Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-400">
                Paid by <span class=" text-gray-300 font-medium"> <?php echo $tenant["name"]; ?></span>
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
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                    House
                  </th>

                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Owner
                  </th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                    Amount <span class="capitalize">(Rs.)</span>
                  </th>



                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                    Date
                  </th>
                  <th scope="col" class="relative pl-4 pr-6 py-3">
                    <!-- <a href="/rms/addhouse.php" class="btn primary small">Add House</a> -->
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($payments as $payment) { ?>
                  <tr>
                    <td class="pl-6 pr-4 py-4 ">
                      <div class="text-sm text-gray-500">
                        <?php echo htmlspecialchars($payment["payment_id"]) ?>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="text-sm text-gray-500 lowercase break-words ">
                        <?php echo htmlspecialchars($payment["payment_type"]) ?>

                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="flex items-center  ">
                        <div class="max-w-xs w-36 break-words">
                          <a class="link" href="/rms/houseDetails.php?id=<?php echo $payment["house_id"] ?>">
                            <?php echo htmlspecialchars($payment["house_no"]) ?>
                          </a>
                          <div class="text-sm text-gray-500  mt-1">
                            <?php echo htmlspecialchars($payment["apartment_name"]) ?>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="max-w-xs w-28 break-words">
                        <a class="link" href="/rms/ownerDetails.php?id=<?php echo $payment["owner_id"] ?>">
                          <?php echo htmlspecialchars($payment["owner_name"]) ?>
                        </a>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="text-base text-gray-900 text-right">
                        <?php echo number_format(htmlspecialchars($payment["amount"])); ?>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="text-sm text-gray-500 lowercase break-words w-20 ">
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
  <?php } ?>

</div>

<?php include "footer.php"; ?>
<?php include "footer.php" ?>

</html>