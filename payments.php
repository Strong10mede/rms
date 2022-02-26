<?php
require("session.php");
require "connect.php";


$sql = "SELECT
          payment_id,
          payment_date,
          amount,
          payment_type,
          payment.description,
          tenant.tenant_id,
          tenant.name AS 'tenant_name',
          owner.owner_id,
          owner.name AS 'owner_name',
          house.house_id,
          house.house_no,
          apartment.apartment_id,
          apartment.name AS 'apartment_name'
        FROM
          payment
        JOIN tenant ON tenant.tenant_id = payment.tenant_id
        JOIN house ON house.house_id = payment.house_id
        JOIN owner ON owner
           .owner_id = payment.owner_id
        JOIN apartment ON apartment.owner_id = payment.owner_id AND apartment.apartment_id = house.apartment_id
        ORDER BY payment_id DESC;";
$result = mysqli_query($conn, $sql);
$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include "header.php"; ?>

<div class="banner_blur">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-green-100  rounded-full p-3 text-green-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Payments
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/payRent.php" class="btn primary">Pay Rent</a>
  </div>
</div>


<div class="flex flex-col max-w-6xl m-5">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
          <h3 class="text-xl leading-6 font-medium text-gray-300">
            Payment History
          </h3>
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

<?php include "footer.php"; ?>