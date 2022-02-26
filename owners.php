<?php
require("session.php");
require "connect.php";


$sql = 'SELECT * FROM owner;';
$result = mysqli_query($conn, $sql);
$owners = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include "header.php"; ?>

<div class=" banner_blur ">
  <div class="flex-1 min-w-0 p-5">
    <div class="flex items-center ">
      <div class=" bg-blue-100  rounded-full p-3 text-blue-700 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
      <h1 class="text-4xl text-gray-900 sm:truncate">
        Owners
      </h1>
    </div>
  </div>
  <div class="p-5">
    <a href="/rms/addOwner.php" class="btn primary">Add Owner</a>
  </div>
</div>


<div class="flex flex-col max-w-6xl m-5">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-900 border-b">
          <h3 class="text-xl leading-6 font-medium text-gray-300">
            Registered Owners
          </h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Owner
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Phone
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  ">
                Address
              </th>
              <th scope="col" class="relative px-3 py-3">

                <a href="/rms/addOwner.php" class="btn primary small">Add Owner</a>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($owners as $owner) { ?>
              <tr>
                <td class="px-6 py-4 break-words  w-8 ">
                  <div class="flex items-center">
                    <div>
                      <div class="text-base font-medium text-gray-900 break-words  w-60">
                        <?php echo htmlspecialchars($owner["name"]) ?>
                      </div>
                      <div class="text-sm text-gray-500 break-words  w-48">
                        <?php echo htmlspecialchars($owner["email"]) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 ">
                    <?php echo htmlspecialchars($owner["phone"]) ?>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 break-words  max-w-xs ">
                  <?php echo htmlspecialchars($owner["address"]) ?>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <a href="ownerDetails.php?id=<?php echo $owner['owner_id']; ?>" class="text-indigo-600 hover:text-indigo-900">Details</a>
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