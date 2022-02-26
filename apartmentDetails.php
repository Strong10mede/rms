<?php
require("session.php");

require "connect.php";
$apartment = "";

if (isset($_POST["delete"])) {
  $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
  $sql = "DELETE FROM apartment WHERE apartment_id='$id_to_delete'";

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

  $sql = "SELECT apartment_id,
                apartment.name AS 'name',
                owner.owner_id as 'owner_id',
                owner.name AS owner_name,
                owner.phone AS owner_phone,
                type,
                town,
                location,
                description
        FROM
                apartment
        JOIN owner ON 
                owner.owner_id = apartment.owner_id 
        WHERE apartment_id='$id';";

  $result = mysqli_query($conn, $sql);
  $apartment = mysqli_fetch_assoc($result);


  $sql = "SELECT
            house.house_id,
            house_no,
            house.type,
            rent,
            house.description,
            apartment.town,
            apartment.location,
            apartment.apartment_id,
            apartment.name AS 'apartment_name',
            owner.owner_id,
            owner.name AS 'owner_name',
            assigned.tenant_id
          FROM
            house
          JOIN apartment ON house.apartment_id='$id' AND apartment.apartment_id = house.apartment_id
          JOIN owner ON apartment.owner_id = owner.owner_id
          LEFT JOIN assigned ON assigned.house_id = house.house_id
          ORDER BY house_no;";
  $result = mysqli_query($conn, $sql);
  $houses = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $isVacant = false;
  foreach ($houses as $house) {
    if (!isset($house["tenant_id"])) {
      $isVacant = true;
      break;
    }
  }
}

?>

<?php include "header.php" ?>

<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-purple-100  rounded-full p-3 text-purple-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Apartments Information
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/addHouse.php?apartment_id=<?php echo $apartment['apartment_id']; ?>" class="btn primary ">Add House</a>
  </div>
</div>


<div class="flex items-start gap-5 p-5">

  <div class="bg-white shadow overflow-hidden sm:rounded-lg w-full  max-w-xl">
    <?php if ($apartment) { ?>
      <div class="p-6 bg-gray-900">
        <h3 class="text-3xl font-medium text-gray-300 overflow-hidden overflow-ellipsis">
          <?php echo htmlspecialchars($apartment["name"]); ?>
        </h3>
      </div>
      <div class="border-t border-gray-200 px-6 py-4">

        <!-- apartment -->
        <h4 class=" font-medium text-base text-gray-900 uppercase  mb-2 ">Apartment Detail</h4>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6 items-end">
          <dt class="text-sm font-medium text-gray-500">
            Apartment name
          </dt>
          <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($apartment["name"]); ?>
          </dd>
        </div>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Vacancy status
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">

            <?php if ($isVacant) { ?>
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">● available
              </span>
            <?php } else { ?>
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-red-800">
                ● fully occupied
              </span>
            <?php } ?>
          </dd>
        </div>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Description
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words">
            <?php echo htmlspecialchars($apartment["description"]); ?>
          </dd>
        </div>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Apartment Type
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($apartment["type"]); ?>
          </dd>
        </div>

        <!-- location  -->
        <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2 ">Location Detail</h4>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Town
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($apartment["town"]); ?>
          </dd>
        </div>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Location
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words">
            <?php echo htmlspecialchars($apartment["location"]); ?>
          </dd>
        </div>

        <!-- owner  -->
        <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2 ">Owner Detail</h4>

        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Owner
          </dt>
          <dd class="mt-1 text-base text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/ownerDetails.php?id=<?php echo $apartment["owner_id"]; ?>" class="link">
              <?php echo htmlspecialchars($apartment["owner_name"]); ?>
            </a>
          </dd>
        </div>
        <div class="bg-white px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Owner Contact
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($apartment["owner_phone"]); ?>
          </dd>
        </div>

        <div class="flex justify-end gap-4">

          <form action="apartmentDetails.php" method="POST" onsubmit="return confirm('Are you really want to delete the Apartment Data, its Houses\' details and Payment History as well?')" class="mt-3 flex justify-end">
            <input type="hidden" name="id_to_delete" value="<?php echo htmlspecialchars($apartment["apartment_id"]); ?>">
            <input type="submit" name="delete" value="DELETE" class="btn secondary danger small">
          </form>

          <form action="editApartment.php" method="POST" class="mt-3 flex justify-end">
            <input type="hidden" name="apartment_id" value="<?php echo htmlspecialchars($apartment["apartment_id"]); ?>">
            <input type="submit" name="submit" value="Edit Details" class="btn primary small">
          </form>

        </div>
      </div>





    <?php } else { ?>
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Apartment Information missing
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Apartment do not exist
        </p>
      </div>
    <?php } ?>
  </div>




  <?php if ($apartment) { ?>

    <div class="flex flex-col w-full max-w-xl ">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
              <h3 class="text-xl leading-6 font-medium text-gray-300">
                House Information
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-400">
                Houses included in
                <span class="text-gray-300 font-medium">
                  <?php echo $apartment["name"]; ?>
                </span>
              </p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="pl-6 pr-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    House
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Rent <span class="capitalize">(Rs.)</span>
                  </th>
                  <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                    Status
                  </th>
                  <th scope="col" class="relative pl-2  pr-6 py-3">
                    <a href="/rms/addHouse.php?apartment_id=<?php echo $apartment['apartment_id']; ?>" class="btn primary small">Add House</a>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($houses as $house) { ?>
                  <tr>
                    <td class="pl-6 pr-2 py-4">
                      <div class="flex items-center  ">
                        <div class="max-w-xs w-52 break-words">
                          <div class="text-base font-medium text-gray-900">
                            <?php echo htmlspecialchars($house["house_no"]) ?>
                          </div>
                          <div class="text-sm text-gray-500 mt-1">
                            <?php echo htmlspecialchars($house["description"]) ?>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-2 py-4">
                      <div class="text-sm text-gray-900">
                        <?php echo htmlspecialchars($house["rent"]) ?>
                      </div>
                    </td>

                    <td class="px-2 py-4">
                      <?php if (!isset($house["tenant_id"])) { ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">vacant
                        </span>
                      <?php } else { ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-red-800">
                          occupied
                        </span>
                      <?php } ?>
                    </td>
                    <td class="pl-2 pr-6 py-4 text-right text-sm font-medium">
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
  <?php } ?>


</div>

<?php include "footer.php" ?>

</html>