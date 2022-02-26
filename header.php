<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/custom-forms@0.2.1/dist/custom-forms.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./style.css?<?php echo time(); ?>">
  <link rel="stylesheet" href="./tailwind.css">

  <title>Apartment Renting Management</title>

</head>

<body class="bg-gray-600 ">

  <div class="  flex min-h-screen   ">
    <div class=" sticky top-0 flex flex-col w-64 h-screen px-4 py-8 bg-gray-900 ">
      <h2 class="text-3xl font-semibold text-gray-200  text-white">
        ARM System
      </h2>
      <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="space-y-2">
          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="mx-4 font-medium">Dashboard</span>
          </a>

          <hr class="border-gray-700" />

          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/owners.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="mx-4 font-medium">Owners</span>
          </a>
          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/tenants.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="mx-4 font-medium">Tenants</span>
          </a>

          <hr class="border-gray-700" />

          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/apartments.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="mx-4 font-medium">Apartments</span>
          </a>
          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/houses.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="mx-4 font-medium">Houses</span>
          </a>

          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/asignHouse.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="mx-4 font-medium">Asign House</span>
          </a>
          <hr class="border-gray-700" />

          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/payments.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="mx-4 font-medium">Payments</span>
          </a>
          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/payRent.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="mx-4 font-medium">Pay Rent</span>
          </a>
          <hr class="border-gray-700" />

          <a class="flex items-center px-4 py-2 text-gray-400 transition-colors duration-200 transform rounded-md hover:bg-gray-800 hover:text-gray-200" href="/rms/logout.php">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="mx-4 font-medium">Logout</span>
          </a>

        </nav>

      </div>
    </div>

    <div class="w-auto flex flex-col items-center w-full">

      <div class="container">