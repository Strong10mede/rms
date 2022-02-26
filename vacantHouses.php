<?php
require("session.php");
require "connect.php";


$sql = 'SELECT
          house_id,
          house_no,
          house.type,
          rent,
          house.description,
          apartment.town,
          apartment.location,
          apartment.apartment_id,
          apartment.name AS "apartment_name",
          owner.owner_id,
          owner.name AS "owner_name"
          FROM
          house
          JOIN apartment ON apartment.apartment_id = house.apartment_id
          JOIN owner ON apartment.owner_id = owner.owner_id
          WHERE
          house.house_id NOT IN(
            SELECT
            house_id
            FROM
            assigned
          );';
$result = mysqli_query($conn, $sql);
$houses = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include "header.php"; ?>

<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-yellow-100  rounded-full p-3 text-yellow-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Vacant Houses
      </h1>
    </div>
  </div>
  <div class="p-5 flex gap-4">
    <a href="/rms/houses.php" class="btn secondary">All Houses</a>
    <a href="/rms/occupiedHouses.php" class="btn secondary">Occupied Houses</a>
    <a href="/rms/asignHouse.php" class="btn secondary">Asign House</a>
    <a href="/rms/addHouse.php" class="btn primary">Add House</a>
  </div>
</div>


<div class="flex flex-col max-w-6xl m-5">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden  sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
          <h3 class="text-xl leading-6 font-medium text-gray-300">
            Registered Vacant Houses
          </h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="pl-6 pr-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                House
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rent <span class="capitalize">(Rs.)</span>
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Apartment
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Location
              </th>
              <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Owner
              </th>
              <th scope="col" class="px-4 text-center py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Status
              </th>
              <th scope="col" class="relative pl-4 pr-6 py-3">
                <a href="/rms/addhouse.php" class="btn primary small">Add House</a>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($houses as $house) { ?>
              <tr>
                <td class="pl-6 pr-4 py-4">
                  <div class="flex items-center w-32 ">
                    <div class="max-w-xs w-44 break-words">
                      <div class="text-base font-medium text-gray-900">
                        <?php echo htmlspecialchars($house["house_no"]) ?>
                      </div>
                      <div class="text-sm text-gray-500 mt-1">
                        <?php echo htmlspecialchars($house["description"]) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class="text-sm text-gray-900">
                    <?php echo number_format(htmlspecialchars($house["rent"])); ?>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class="max-w-xs w-28 break-words">
                    <a class="link w-28" href="/rms/apartmentDetails.php?id=<?php echo $house["apartment_id"] ?>">
                      <?php echo htmlspecialchars($house["apartment_name"]) ?>
                    </a>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center ">
                    <div class="max-w-xs w-44 break-words">
                      <div class="text-base text-gray-900">
                        <?php echo htmlspecialchars($house["town"]) ?>
                      </div>
                      <div class="text-sm text-gray-500  mt-1">
                        <?php echo htmlspecialchars($house["location"]) ?>
                      </div>
                    </div>
                  </div>
                </td>

                <td class="px-4 py-4">
                  <div class="max-w-xs w-28 break-words">
                    <a class="link" href="/rms/ownerDetails.php?id=<?php echo $house["owner_id"] ?>">
                      <?php echo htmlspecialchars($house["owner_name"]) ?>
                    </a>
                  </div>
                </td>
                <td class="px-4 py-4">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">vacant
                  </span>
                </td>
                <td class="pl-4 pr-6 py-4 text-right text-sm font-medium">
                  <a href="houseDetails.php?id=<?php echo $house['house_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Details</a>
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