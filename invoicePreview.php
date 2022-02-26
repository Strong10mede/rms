<?php
require("session.php");
require "connect.php";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
  $payment_id = mysqli_real_escape_string($conn, $_GET["id"]);

  $sql = "SELECT
            payment_id,
            payment_date,
            amount,
            payment_type,
            payment.description,
            tenant.tenant_id,
            tenant.name AS 'tenant_name',
            tenant.phone AS 'tenant_phone',
            tenant.email AS 'tenant_email',
            tenant.address AS 'tenant_address',
            owner.owner_id,
            owner.name AS 'owner_name',
            owner.phone AS 'owner_phone',
            owner.address AS 'owner_address',
            owner.email AS 'owner_email',
            house.house_id,
            house.house_no,
            house.type AS 'house_type',
            apartment.apartment_id,
            apartment.name AS 'apartment_name',
            apartment.location AS 'apartment_location'
          FROM payment
          JOIN tenant ON tenant.tenant_id = payment.tenant_id
          JOIN house ON house.house_id = payment.house_id
          JOIN owner ON owner.owner_id = payment.owner_id
          JOIN apartment ON apartment.owner_id = payment.owner_id AND apartment.apartment_id = house.apartment_id 
          WHERE payment_id='$payment_id';";
  $result = mysqli_query($conn, $sql);
  $payment = mysqli_fetch_assoc($result);
}


?>





<?php include "header.php"; ?>

<script type="text/javascript">
  function PrintDiv() {
    var mywindow = window.open("", "PRINT");

    mywindow.document.write("<html>");
    mywindow.document.write(document.head.outerHTML);
    mywindow.document.write("<body>");
    mywindow.document.write(document.getElementById("invoice_to_be_printed").outerHTML);
    mywindow.document.write("</body></html>");

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    setTimeout(() => {
      mywindow.print();
    }, 500)
    setTimeout(() => {
      mywindow.close();
    }, 1000)
    return false;
  }
</script>

<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5">
    <div class=" flex items-center ">
      <div class=" bg-purple-100  rounded-full p-3 text-purple-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Invoice
      </h1>
    </div>
  </div>
  <div class="p-5">
    <!-- <a href="/rms/addApartment.php" class="btn primary ">Add Apartment</a> -->
  </div>
</div>

<div class="max-w-4xl mx-auto transform scale-110 flex items-center justify-end m-8 py-4">
  <button class="btn secondary icon_btn w-auto" onclick="PrintDiv()">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    </svg>
    Print Invoice</button>
</div>

<div id="invoice_to_be_printed" class="max-w-4xl transform scale-110 border border-gray-900 bg-white rounded-md mx-auto m-20 px-20 py-16  break-words">

  <div>
    <h1 class="text-4xl font-extralight text-gray-600 mb-6 pb-3 uppercase text-center border-b border-gray-900 ">Invoice</h1>

  </div>


  <div class="flex justify-between mb-8">
    <div class="text-left">
      <p class=" text-xl font-medium">ARM System</p>
      <p class="text-gray-700 text-sm font-light">DBMS project,</p>
      <p class="text-gray-700 text-sm font-light">NIT Hamirpur,</p>
      <p class="text-gray-700 text-sm font-light">Hamirpur, Himachal Pradesh</p>
      <p class="text-gray-700 text-sm font-light">PIN. 170005</p>
    </div>
    <div class=" text-right">

      <h1 class="text-2xl font-medium text-indigo-600 "> <span class="text-base">INVOICE</span> #<?php echo $payment['payment_id']; ?></h1>
      <p class="text-base font-medium ">Date: <?php echo date_format(date_create('now', timezone_open('Asia/Kolkata')), "d/m/Y H:i:s") ?></p>
    </div>
  </div>

  <div class="flex justify-between mb-12 ">
    <div class="  w-60 ">
      <span class="text-gray-500 text-sm font-light">Invoice to:</span>
      <div class="text-gray-800 text-base font-light space-y-1 ">
        <p class="text-xl font-medium mt-1 text-black"><?php echo $payment['tenant_name']; ?></p>
        <p><?php echo $payment['tenant_phone']; ?></p>
        <p><?php echo $payment['tenant_email']; ?></p>
        <p class="text-sm"><?php echo $payment['tenant_address']; ?></p>
      </div>
    </div>
    <div class="  w-60 text-right ">
      <span class="text-gray-500 text-sm font-light">Payment to:</span>
      <div class="text-gray-800 text-base font-light space-y-1 ">
        <p class="text-xl font-medium mt-1 text-black"><?php echo $payment['owner_name']; ?></p>
        <p><?php echo $payment['owner_phone']; ?></p>
        <p><?php echo $payment['owner_email']; ?></p>
        <p class="text-sm"><?php echo $payment['owner_address']; ?></p>
      </div>
    </div>
  </div>

  <div class="max-w-md mb-12 space-y-1 ">
    <span class="text-gray-500 text-sm font-light ">Billing Details: </span>
    <div class="grid grid-cols-3 gap-4 items-baseline mt-1">
      <p class=" text-gray-800 text-sm">Subject:</p>
      <p class="text-base font-medium col-span-2  break-words"><?php echo $payment['description']; ?></p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-800 text-sm">House no.:</p>
      <p class="text-base font-medium col-span-2   break-words"><?php echo $payment['house_no']; ?></p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-800 text-sm">House Type: </p>
      <p class="text-base font-medium col-span-2   break-words"><?php echo $payment['house_type']; ?></p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-800 text-sm">Apartment: </p>
      <p class="text-base font-medium col-span-2   break-words"><?php echo $payment['apartment_name']; ?></p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-800 text-sm">Address: </p>
      <p class="text-base font-medium col-span-2   break-words"><?php echo $payment['apartment_location']; ?></p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-700 text-sm">STATUS: </p>
      <p class="text-base font-medium col-span-2 break-words text-green-500 bg-green-100  rounded-full px-2 w-max">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
        PAID
      </p>
    </div>
    <div class="grid grid-cols-3 gap-4 items-baseline">
      <p class=" text-gray-800 text-sm">Payment Date: </p>
      <p class="text-base font-medium col-span-2   break-words"><?php echo date_format(date_create($payment['payment_date'], timezone_open('Asia/Kolkata')), "d/m/Y  H:i:s"); ?></p>
    </div>
  </div>


  <div class="w-full mb-8 divide-y  ">
    <div class="grid grid-cols-12 text-gray-600 text-sm font-light divide-x uppercase">
      <div class="col-span-1 py-2 px-3 ">#</div>
      <div class="col-span-8 py-2 px-3">DESCRIPTION</div>
      <div class="col-span-3 text-right py-2 px-3">AMOUNT <span class="capitalize">(Rs.)</span></div>
    </div>
    <div class="grid grid-cols-12 font-medium border-t divide-x">
      <div class="col-span-1 py-2 px-3 ">1</div>
      <div class="col-span-8 py-2 px-3"><?php echo $payment['description']; ?></div>
      <div class="col-span-3 text-right py-2 px-3"> <?php echo number_format($payment['amount']); ?></div>
    </div>

    <div></div>
    <div></div>
    <div class="grid grid-cols-12 font-medium border-t divide-x">
      <div class="col-span-1 py-2 px-3 "></div>
      <div class="col-span-8 py-2 px-3 text-right">TOTAL </div>
      <div class="col-span-3 text-right py-2 px-3"><?php echo number_format($payment['amount']); ?></div>
    </div>
  </div>

  <div class="flex justify-end mb-8">
    <span class=" text-sm font-medium bg-indigo-50 flex flex-col items-center py-4 px-6 rounded-xl text-indigo-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
      </svg>
      Digitally
      verified by
      <br> <b>
        ARM System
      </b>
    </span>
  </div>

  <div class="max-w-2xl  ">
    <p class="text-gray-500 text-sm font-light ">Other Information</p>
    <p class="text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
    <p class="text-xs">Eveniet id aperiam ratione tempore sapiente quae sit est earum fuga atque!</p>
  </div>

</div>

<?php include "footer.php"; ?>