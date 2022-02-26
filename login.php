<?php
include("connect.php");
session_start();

$email = $password = "";
$errors = ["email" => "", "password" => ""];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // username and password sent from form 

  if (empty($_POST["email"])) {
    $errors["email"] = "An email is required. <br/>";
  } else {
    $email = htmlspecialchars($_POST["email"]);
  }

  if (empty($_POST["password"])) {
    $errors["password"] = "An password is required. <br/>";
  } else {
    $password = htmlspecialchars($_POST["password"]);
  }

  if (!array_filter($errors)) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT user_id FROM user WHERE email = '$email' AND password = '$password';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $count = mysqli_num_rows($result);

    // If result matched $email and $password, table row must be 1 row
    if ($count == 1) {
      $_SESSION['login_user_email'] = $email;

      header("Location: index.php");
    } else {
      $error = "Your Email or Password is invalid";
    }
  }
}
?>

<?php include "header.php";  ?>





<div class="banner_blur  ">
  <div class="flex-1 min-w-0 p-5 ">
    <div class="text-4xl text-gray-900 sm:truncate flex items-center ">
      <div class=" bg-indigo-100  rounded-full p-3 text-indigo-600 mr-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      </div>
      <h1>
        Log in
      </h1>
    </div>
  </div>
</div>

<div class="w-full p-6">
  <form action="" method="post" class="max-w-md mx-auto w-full flex flex-col justify-center items-center gap-4 bg-gray-50 rounded-xl p-4 shadow overflow-hidden">
    <div class="p-3 text-center">
      <h1 class="text-3xl text-gray-700 my-2 font-bold">
        Log into your account.
      </h1>
      <p class="text-sm text-gray-500">Or
        <a class="font-medium rounded-md text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500" href="/rms/signup.php"> create new admin account for free.</a>
      </p>
    </div>

    <div class="w-full  ">
      <label for="email" class="label required">
        Email
      </label>
      <div>
        <input name="email" type="email" class="input required" placeholder="example@email.com" autocomplete="email" maxlength="50" value="<?php echo $email; ?>" required>
        <p class="error_text"><?php echo $errors["email"] ? $errors["email"] : ""; ?> </p>
      </div>
    </div>
    <div class="w-full  ">
      <label for="password" class="label required">
        Password
      </label>
      <div>
        <input name="password" type="password" class="input" placeholder="Your password" autocomplete="current-password" maxlength="50" value="<?php echo $password; ?>" required>
        <p class="error_text"><?php echo $errors["password"] ? $errors["password"] : ""; ?> </p>
      </div>
    </div>

    <div class="w-full  ">
      <input name="submit" type="submit" class="btn primary" value="Log in">
    </div>

    <a class="text-sm text-right font-medium rounded-md text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500" href="/rms/signup.php">Don't have an account?</a>

    <div class="text-xs text-red-500  "><?php echo $error ? $error : ""; ?></div>
  </form>

</div>
<?php include "footer.php";  ?>