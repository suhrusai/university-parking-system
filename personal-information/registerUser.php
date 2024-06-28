<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('../assets/univeristy_background_image.jpg');
      background-size: cover;
    }
    .background-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
    }
  </style>
</head>
<body>
  <div class="background-overlay"></div>
  <div class="card" style="width:50rem">
    <div class="card-body">
        <h3 class="card-title">New user registeration</h3>
        <br>
        <form>
        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label"><b>Email</b></label>
            <div class="col-sm-10">
            <input type="email" class="form-control" id="email">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label"><b>Password</b></label>
            <div class="col-sm-10">
            <input type="password" class="form-control" id="password">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="firstName" class="col-sm-2 col-form-label"><b>First Name</b></label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="firstName">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="lastName" class="col-sm-2 col-form-label"><b>Last Name</b></label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="lastName">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="address" class="col-sm-2 col-form-label"><b>Address</b></label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="address">
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-10 col-sm-2">
            <a class="btn btn-primary" style="float: right">Register</a>
            </div>
        </div>
        </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
