<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Editable Driver Information</title>
</head>
<body>
  <div class="container mt-5">
    <form>
      <div class="mb-3 row">
        <label for="firstName" class="col-sm-2 col-form-label"><b>First Name</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="firstName" value="Sai Suhrut">
        </div>
      </div>
      <div class="mb-3 row">
        <label for="lastName" class="col-sm-2 col-form-label"><b>Last Name</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="lastName" value="Sala">
        </div>
      </div>
      <div class="mb-3 row">
        <label for="address" class="col-sm-2 col-form-label"><b>Address</b></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="address" value="Apt 293, 304S 700W, Salt Lake City, Utah">
        </div>
      </div>
      <div class="row">
        <div class="col-sm-10 offset-sm-2">
          <a class="btn btn-primary">Save Changes</a>
        </div>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
