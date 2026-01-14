<?php
session_start();

include "koneksi.php";

// check if session exists, but allow re-login.
// if (isset($_SESSION['username'])) {
//     header("location:admin.php");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT username 
                            FROM user 
                            WHERE username=? AND password=?");

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $hasil = $stmt->get_result();
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    //periksa login
    if (!empty($row)) {
      //menciptakan session
      $_SESSION['username'] = $row['username'];

      //menuju ke halaman admin
      header("location:admin.php");
    } else {
      header("location:login.php");
    }

    $stmt->close();
    $conn->close();
  } else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Login | My Daily Journal</title>
      <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
      <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
      <link rel="icon" href="img/logo.png" />
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="style.css" />
    </head>

    <body>
      <div class="container mt-5 pt-5">
        <div class="row">
          <div class="col-12 col-sm-8 col-md-6 m-auto">
            <div class="card border-0 shadow rounded-5">
              <div class="card-body p-5">
                <div class="text-center mb-3">
                  <i class="bi bi-person-circle h1 display-4 text-pink"></i>
                  <h4 class="fw-bold mt-3">My Daily Journal</h4>
                  <hr />
                </div>
                <form action="" method="post">
                  <input
                    type="text"
                    name="username"
                    class="form-control my-4 py-2 rounded-4"
                    placeholder="Username" />
                  <input
                    type="password"
                    name="password"
                    class="form-control my-4 py-2 rounded-4"
                    placeholder="Password" />
                  <div class="text-center my-3 d-grid">
                    <button class="btn btn-danger rounded-4 py-2">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    </body>

    </html>
<?php
}
?>