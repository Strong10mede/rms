<?php
require("session.php");

require "connect.php";
$house = "";

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
            apartment.type AS 'apartment_type',
            apartment.location,
            apartment.apartment_id,
            apartment.name AS 'apartment_name',
            owner.owner_id,
            owner.name AS 'owner_name',
            owner.phone AS 'owner_phone',
            tenant.tenant_id,
            tenant.name AS 'tenant_name',
            tenant.phone AS 'tenant_phone'
          FROM
            house
          JOIN apartment ON apartment.apartment_id = house.apartment_id
          JOIN owner ON apartment.owner_id = owner.owner_id
          LEFT JOIN assigned ON assigned.house_id = house.house_id
          LEFT JOIN tenant ON tenant.tenant_id = assigned.tenant_id
          WHERE
            house.house_id = '$id';";

  $result = mysqli_query($conn, $sql);
  $house = mysqli_fetch_assoc($result);
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
      <h1 class="text-4xl text-gray-900 sm:truncate">
        House Information
      </h1>
    </div>
  </div>
  <div class="p-5">
    <?php if (!isset($house["tenant_id"])) { ?>
      <a href="/rms/asignHouse.php?house_id=<?php echo $id; ?>&apartment_id=<?php echo $house["apartment_id"]; ?>" class="btn primary">Place Tenant</a>
    <?php } else { ?>
      <a href="/rms/vacateHouse.php?house_id=<?php echo $house["house_id"]; ?>" class="btn primary">Vacate House</a>
    <?php } ?>

  </div>
</div>


<div class="bg-white shadow overflow-hidden sm:rounded-lg m-5 max-w-6xl">
  <?php if ($house) { ?>
    <div class="p-6 bg-gray-900">
      <h3 class="text-3xl font-medium text-gray-300  overflow-hidden overflow-ellipsis">
        <?php echo htmlspecialchars($house["house_no"]); ?>
      </h3>
    </div>

    <div class="border-t border-gray-200  px-6 py-4 flex  gap-4 ">
      <div class="flex-1">

        <h4 class=" font-medium text-base text-gray-900 uppercase   mb-2  ">House Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            House no.
          </dt>
          <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($house["house_no"]); ?>
          </dd>
        </div>

        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Vacancy Status
          </dt>
          <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
            <?php if (!isset($house["tenant_id"])) { ?>
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 ">vacant
              </span>
            <?php } else { ?>
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-red-800">
                occupied
              </span>
            <?php } ?>
          </dd>
        </div>

        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Description
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words">
            <?php echo htmlspecialchars($house["description"]); ?>
          </dd>
        </div>

        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            House Type
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($house["type"]); ?>
          </dd>
        </div>


        <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2 ">Apartment Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Apartment
          </dt>
          <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/apartmentDetails.php?id=<?php echo $house["apartment_id"]; ?>" class="link">
              <?php echo htmlspecialchars($house["apartment_name"]); ?>
            </a>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Apartment Type
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($house["apartment_type"]); ?>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Town
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <?php echo htmlspecialchars($house["town"]); ?>
          </dd>
        </div>

        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Location
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 max-w-xs break-words">
            <?php echo htmlspecialchars($house["location"]); ?>
          </dd>
        </div>
      </div>

      <div class="flex-1">

        <h4 class=" font-medium text-base text-gray-900 uppercase  mb-2">Rent Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Rent
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($house["rent"])); ?> /month
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Deposit
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($house["deposit_amt"])); ?> refundable
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Placement Fee
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            Rs. <?php echo number_format(htmlspecialchars($house["placement_fee"])); ?> one-time fee
          </dd>
        </div>

        <?php if (isset($house["tenant_id"])) { ?>
          <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2">Tenant Detail</h4>
          <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Name
            </dt>
            <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
              <a href="/rms/tenantDetails.php?id=<?php echo $house["tenant_id"]; ?>" class="link">
                <?php echo htmlspecialchars($house["tenant_name"]); ?>
              </a>
            </dd>
          </div>
          <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Contact
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              <div>
                <?php echo htmlspecialchars($house["tenant_phone"]); ?>
              </div>
            </dd>
          </div>
        <?php } ?>


        <h4 class=" font-medium text-base text-gray-900 uppercase pt-4 mt-4 mb-2">Owner Detail</h4>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Name
          </dt>
          <dd class="mt-1 text-sm text-gray-900 hover:underline sm:mt-0 sm:col-span-2">
            <a href="/rms/ownerDetails.php?id=<?php echo $house["owner_id"]; ?>" class="link">
              <?php echo htmlspecialchars($house["owner_name"]); ?>
            </a>
          </dd>
        </div>
        <div class=" px-4 py-1.5 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-6">
          <dt class="text-sm font-medium text-gray-500">
            Contact
          </dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <div>
              <?php echo htmlspecialchars($house["owner_phone"]); ?>
            </div>
          </dd>
        </div>

      </div>

    </div>
    <div class="flex justify-end gap-4 pb-6 pr-6">

      <form action="houseDetails.php" method="POST" onsubmit="return confirm('Are you really want to delete the House Data and its Payment History as well?')">
        <input type="hidden" name="id_to_delete" value="<?php echo htmlspecialchars($house["house_id"]); ?>">
        <input type="submit" name="delete" value="DELETE" class="btn secondary danger small w-auto">
      </form>

      <form action="editHouse.php" method="POST">
        <input type="hidden" name="house_id" value="<?php echo htmlspecialchars($house["house_id"]); ?>">
        <input type="submit" name="submit" value="Edit Details" class="btn primary small">
      </form>
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

<?php include "footer.php" ?>

</html>