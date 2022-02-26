<?php
require("session.php");
require "connect.php";


$sql = 'SELECT  apartment_id,
                apartment.name AS "name",
                owner.owner_id as "owner_id",
                owner.name AS owner_name,
                type,
                town,
                location,
                description
        FROM
                apartment
        JOIN owner ON 
                owner.owner_id = apartment.owner_id;';
$result = mysqli_query($conn, $sql);
$apartments = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include "header.php"; ?>

<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-purple-100  rounded-full p-3 text-purple-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Apartments
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/addApartment.php" class="btn primary ">Add Apartment</a>
  </div>
</div>


<div class="flex flex-col max-w-6xl m-5">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden  sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
          <h3 class="text-xl leading-6 font-medium text-gray-300">
            Registered Apartments
          </h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="pl-6 pr-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Apartment
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Town
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Location
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Owner
              </th>
              <th scope="col" class="relative pl-4 pr-6 py-3">
                <a href="/rms/addApartment.php" class="btn primary small">Add Apartment</a>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($apartments as $apartment) { ?>
              <tr>
                <td class="pl-6 pr-4 py-4">
                  <div class="flex items-center ">
                    <div class=" max-w-xs w-60 break-words">
                      <div class="text-base font-medium text-gray-900">
                        <?php echo htmlspecialchars($apartment["name"]) ?>
                      </div>
                      <div class="text-sm text-gray-500 w-60 mt-1">
                        <?php echo htmlspecialchars($apartment["description"]) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class="text-sm text-gray-900">
                    <?php echo htmlspecialchars($apartment["type"]) ?>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class="text-sm text-gray-900 break-words w-28">
                    <?php echo htmlspecialchars($apartment["town"]) ?>
                  </div>
                </td>
                <td class="px-4 py-4 text-sm text-gray-500">
                  <div class=" max-w-xs break-words w-48">
                    <?php echo htmlspecialchars($apartment["location"]) ?>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class=" max-w-xs w-32 break-words">

                    <a class="link w-28" href="/rms/ownerDetails.php?id=<?php echo $apartment["owner_id"] ?>">
                      <?php echo htmlspecialchars($apartment["owner_name"]) ?>
                    </a>
                  </div>
                </td>
                <td class="pl-4 pr-6 py-4 text-right text-sm font-medium">
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

<?php include "footer.php"; ?>