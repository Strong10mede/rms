<?php
require("session.php");
require "connect.php";


$sql = 'SELECT COUNT(owner_id) FROM owner;';
$result = mysqli_query($conn, $sql);
$total_owners = mysqli_fetch_row($result);

$sql = 'SELECT COUNT(tenant_id) FROM tenant;';
$result = mysqli_query($conn, $sql);
$total_tenants = mysqli_fetch_row($result);

$sql = 'SELECT COUNT(apartment_id) FROM apartment;';
$result = mysqli_query($conn, $sql);
$total_apartments = mysqli_fetch_row($result);

$sql = 'SELECT COUNT(house_id) FROM house;';
$result = mysqli_query($conn, $sql);
$total_houses = mysqli_fetch_row($result);

$sql = 'SELECT COUNT(payment_id) FROM payment;';
$result = mysqli_query($conn, $sql);
$total_payments = mysqli_fetch_row($result);
?>



<?php include "header.php"; ?>


<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5 ">
    <div class="text-4xl text-gray-900 sm:truncate flex items-center ">
      <div class=" bg-gray-100  rounded-full p-3 text-gray-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div>
      <h1>
        Dashboard
      </h1>
    </div>
  </div>
</div>

<div class="p-4 flex justify-start items-center gap-4">

  <a class="flex-grow bg-white hover:bg-gray-100 hover:scale-105 transform rounded-xl border shadow-lg px-4 py-2 max-w-sm w-auto min-w-0 overflow-hidden text-right flex justify-between items-center gap-8" href="/rms/owners.php">
    <div class=" flex-shrink-0 h-20 w-20 rounded-full bg-blue-100 text-blue-600 flex justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
    </div>
    <div>
      <h2 class="text-5xl font-bold leading-7 text-gray-800 my-4 ">
        <?php echo $total_owners[0]; ?>
      </h2>
      <p class="text-base font-medium text-gray-600 ">
        Registered <br> Owners
      </p>
    </div>
  </a>

  <a class="flex-grow bg-white hover:bg-gray-100 hover:scale-105 transform  rounded-xl border shadow-lg px-4 py-2 max-w-sm w-auto min-w-0 overflow-hidden text-right flex justify-between items-center gap-8" href="/rms/tenants.php">
    <div class=" flex-shrink-0 h-20 w-20 rounded-full bg-pink-100 text-pink-600 flex justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
    </div>
    <div>
      <h2 class="text-5xl font-bold leading-7 text-gray-800 my-4 ">
        <?php echo $total_tenants[0]; ?>
      </h2>
      <p class="text-base font-medium text-gray-600 ">
        Registered <br> Tenants
      </p>
    </div>
  </a>

  <a class="flex-grow bg-white hover:bg-gray-100 hover:scale-105 transform  rounded-xl border shadow-lg px-4 py-2 max-w-sm w-auto min-w-0 overflow-hidden text-right flex justify-between items-center gap-8" href="/rms/apartments.php">
    <div class=" flex-shrink-0 h-20 w-20 rounded-full bg-purple-100 text-purple-600 flex justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
      </svg>
    </div>
    <div>
      <h2 class="text-5xl font-bold leading-7 text-gray-800 my-4 ">
        <?php echo $total_apartments[0]; ?>
      </h2>
      <p class="text-base font-medium text-gray-600 ">
        Registered <br> Apartments
      </p>
    </div>
  </a>

  <a class="flex-grow bg-white hover:bg-gray-100 hover:scale-105 transform  rounded-xl border shadow-lg px-4 py-2 max-w-sm w-auto min-w-0 overflow-hidden text-right flex justify-between items-center gap-8" href="/rms/houses.php">
    <div class=" flex-shrink-0 h-20 w-20 rounded-full bg-yellow-100 text-yellow-600 flex justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
    </div>
    <div>
      <h2 class="text-5xl font-bold leading-7 text-gray-800 my-4 ">
        <?php echo $total_houses[0]; ?>
      </h2>
      <p class="text-base font-medium text-gray-600 ">
        Registered <br> Houses
      </p>
    </div>
  </a>

  <a class="flex-grow bg-white hover:bg-gray-100 hover:scale-105 transform  rounded-xl border shadow-lg px-4 py-2 max-w-sm w-auto min-w-0 overflow-hidden text-right flex justify-between items-center gap-8" href="/rms/payments.php">
    <div class=" flex-shrink-0 h-20 w-20 rounded-full bg-green-100 text-green-600 flex justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div>
      <h2 class="text-5xl font-bold leading-7 text-gray-800 my-4 ">
        <?php echo $total_payments[0]; ?>
      </h2>
      <p class="text-base font-medium text-gray-600 ">
        Payments <br> Done
      </p>
    </div>
  </a>
</div>

<?php include "footer.php"; ?>