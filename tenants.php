<?php
require("session.php");
require "connect.php";


$sql = 'SELECT * FROM tenant;';
$result = mysqli_query($conn, $sql);
$tenants = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include "header.php"; ?>

<div class="banner_blur">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-pink-100  rounded-full p-3 text-pink-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Tenants
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/addTenant.php" class="btn primary">Add Tenant</a>
  </div>
</div>


<div class="flex flex-col max-w-6xl m-5 ">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 ">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8 ">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg ">
        <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
          <h3 class="text-xl leading-6 font-medium text-gray-300">
            Registered Tenants
          </h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 ">
            <tr>
              <th scope="col" class="pl-6 pr-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tenant
              </th>
              <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Phone
              </th>
              <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Occupation
              </th>
              <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Address
              </th>
              <th scope="col" class="relative pl-2 pr-6 py-3">
                <a href="/rms/addTenant.php" class="btn primary small">Add Tenant</a>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($tenants as $tenant) { ?>
              <tr>
                <td class="pl-6 pr-2 py-4">
                  <div class="flex items-center">
                    <div>
                      <div class="text-base font-medium text-gray-900 break-words w-48">
                        <?php echo htmlspecialchars($tenant["name"]) ?>
                      </div>
                      <div class="text-sm text-gray-500 break-words w-48">
                        <?php echo htmlspecialchars($tenant["email"]) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-2 py-4">
                  <div class="text-sm text-gray-900 break-words w-28">
                    <?php echo htmlspecialchars($tenant["phone"]) ?>
                  </div>
                </td>
                <td class="px-2 py-4">
                  <div class="text-sm text-gray-900 break-words w-28">
                    <?php echo htmlspecialchars($tenant["occupation"]) ?>
                  </div>
                </td>
                <td class="px-2 py-4   text-sm text-gray-500  break-words w-48 max-w-xs">
                  <?php echo htmlspecialchars($tenant["address"]) ?>
                </td>
                <td class="pl-2 pr-6 py-4 text-right text-sm font-medium">
                  <a href="tenantDetails.php?id=<?php echo $tenant['tenant_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Details</a>
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