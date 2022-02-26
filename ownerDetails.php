<?php
require("session.php");

require "connect.php";
$owner = "";
if (isset($_POST["delete"])) {
  $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
  $sql = "DELETE FROM owner WHERE owner_id='$id_to_delete'";

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

  $sql = "SELECT * FROM owner WHERE owner_id='$id'";
  $result = mysqli_query($conn, $sql);
  $owner = mysqli_fetch_assoc($result);

  $sql = "SELECT payment_type, SUM(amount) AS 'total'
          FROM payment
          WHERE owner_id = '$id'
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
            tenant.tenant_id,
            tenant.name AS 'tenant_name',
            house.house_id,
            house.house_no,
            apartment.apartment_id,
            apartment.name AS 'apartment_name'
          FROM
            payment
          JOIN tenant ON tenant.tenant_id = payment.tenant_id
          JOIN house ON house.house_id = payment.house_id
          JOIN apartment ON apartment.owner_id = payment.owner_id AND apartment.apartment_id = house.apartment_id
          WHERE payment.owner_id='$id' 
          ORDER BY payment_id;";
  $result = mysqli_query($conn, $sql);
  $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $sql = "SELECT * FROM apartment WHERE owner_id='$id'";
  $result = mysqli_query($conn, $sql);
  $apartments = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>



<?php include "header.php" ?>
<div class="banner_blur">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-blue-100  rounded-full p-3 text-blue-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Owner Information
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/addApartment.php" class=" btn primary">Add Apartment</a>
  </div>
</div>



<div class="flex gap-5 flex-col m-5 max-w-6xl">
  <div class="flex gap-5">

    <div class="bg-white shadow overflow-hidden sm:rounded-lg w-full max-w-2xl">
      <?php if ($owner) { ?>
        <div class="p-6 bg-gray-900  ">
          <h3 class="text-3xl font-medium text-gray-300 overflow-hidden overflow-ellipsis">
            <?php echo htmlspecialchars($owner["name"]); ?>
          </h3>
        </div>
        <div class="border-t border-gray-200 text-sm px-6 py-4">
          <h4 class=" font-medium text-base text-gray-900 uppercase mt-4 mb-2 ">Owner Detail</h4>

          <div class="px-6 py-1.5 grid grid-cols-3 gap-2">
            <dt class="font-medium text-gray-500">
              Full name
            </dt>
            <dd class=" text-gray-900 col-span-2 break-words overflow-hidden overflow-ellipsis">
              <?php echo htmlspecialchars($owner["name"]); ?>
            </dd>
          </div>
          <div class="px-6 py-1.5 grid grid-cols-3 gap-2">
            <dt class="font-medium text-gray-500 ">
              Email address
            </dt>
            <dd class="text-gray-900 col-span-2 break-words">
              <?php echo htmlspecialchars($owner["email"]); ?>
            </dd>
          </div>
          <div class="px-6 py-1.5 grid grid-cols-3 gap-2">
            <dt class="font-medium text-gray-500">
              Phone no.
            </dt>
            <dd class="text-gray-900 col-span-2 break-words">
              <?php echo htmlspecialchars($owner["phone"]); ?>
            </dd>
          </div>

          <div class="px-6 py-1.5 grid grid-cols-3 gap-2">
            <dt class="font-medium text-gray-500">
              Address
            </dt>
            <dd class="text-gray-900 col-span-2 break-words">
              <?php echo htmlspecialchars($owner["address"]); ?>
            </dd>
          </div>

          <div class="flex justify-end gap-4">
            <form action="ownerDetails.php" onsubmit="return confirm('Are you really want to delete the Owner Data and its Apartment Data and Payment History as well?')" method="POST" class="mt-3 flex justify-end">
              <input type="hidden" name="id_to_delete" value="<?php echo htmlspecialchars($owner["owner_id"]); ?>">
              <input type="submit" name="delete" value="Delete" class="btn secondary danger small">
            </form>

            <form action="editOwner.php" method="POST" class="mt-3 flex justify-end">
              <input type="hidden" name="owner_id" value="<?php echo htmlspecialchars($owner["owner_id"]); ?>">
              <input type="submit" name="submit" value="Edit Details" class="btn primary small">
            </form>
          </div>
        </div>

      <?php } else { ?>
        <div class="px-4 py-5 sm:px-6 bg-gray-900">
          <h3 class="text-lg leading-6 font-medium text-gray-300">
            Owner Information missing
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-400">
            Owner do not exist
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
                TOTAL <?php echo htmlspecialchars($payment["payment_type"]); ?> RECEIVED
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
            No payment received yet
          </p>
        </div>
      <?php } ?>
    </div>

  </div>

  <?php if ($owner) { ?>
    <div class="flex flex-col w-full max-w-6xl">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden  sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
              <h3 class="text-lg leading-6 font-medium text-gray-300">
                Apartment Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-400">
                Owned by
                <span class="text-gray-300 font-medium">
                  <?php echo $owner["name"]; ?>
                </span>
              </p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 text-left">
                <tr>
                  <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Apartment
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Type
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Town
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                    Location
                  </th>

                  <th scope="col" class="relative text-center px-6 py-3">
                    <a href="/rms/addApartment.php" class="btn primary small">Add Apartment</a>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($apartments as $apartment) { ?>
                  <tr>
                    <td class="px-6 py-4  ">
                      <div class="flex items-center ">

                        <div>

                          <div class="text-base font-medium text-gray-900 break-words max-w-xs w-60 ">
                            <?php echo htmlspecialchars($apartment["name"]) ?>
                          </div>
                          <div class="text-sm text-gray-500  w-60 mt-1 break-words">
                            <?php echo htmlspecialchars($apartment["description"]) ?>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="text-sm text-gray-900 ">
                        <?php echo htmlspecialchars($apartment["type"]) ?>
                      </div>
                    </td>
                    <td class="px-4 py-4 ">
                      <div class="text-sm text-gray-900  break-words max-w-xs w-32">
                        <?php echo htmlspecialchars($apartment["town"]) ?>
                      </div>
                    </td>
                    <td class="px-4 py-4  ">
                      <div class="text-sm text-gray-500  break-words max-w-xs w-60">
                        <?php echo htmlspecialchars($apartment["location"]) ?>
                      </div>
                    </td>

                    <td class="px-6 py-4  text-right text-sm font-medium">
                      <a href="apartmentDetails.php?id=<?php echo $apartment['apartment_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Details</a>
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

  <?php if ($owner) { ?>
    <div class="flex flex-col max-w-6xl">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden  sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
              <h3 class="text-lg leading-6 font-medium text-gray-300">
                Payment Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-400">
                Received by
                <span class="text-gray-300 font-medium">
                  <?php echo $owner["name"]; ?>
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
                    Tenant
                  </th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Type
                  </th>
                  <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                    House
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
                      <div class="max-w-xs w-28 break-words">
                        <a class="link" href="/rms/tenantDetails.php?id=<?php echo $payment["tenant_id"] ?>">
                          <?php echo htmlspecialchars($payment["tenant_name"]) ?>
                        </a>
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
<?php include "footer.php" ?>

</html>