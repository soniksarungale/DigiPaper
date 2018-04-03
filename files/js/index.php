
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>403 - Forbidden: Access is denied.</title>
  <style media="screen">
    .error{
      letter-spacing: 2px;
      box-sizing: border-box;
      width: 100%;
      height: auto;
      border: 1px solid grey;
      padding: 10px;
      font-size: 20px;
      margin-top: 50px;
      position: relative;
      border-radius: 3px;
      font-family: sans-serif;
      line-height: 30px;
    }
    .error-heading{
      color: red;
      font-size: 30px;
      position: absolute;
      top: -30px;
      margin-left: 20px;
      background-color: white;
      padding: 10px 20px;
      border: 1px solid grey;
      border-radius: 3px;
      font-weight: 600;
    }
    .error-detail{
      margin-top: 30px;
      margin-left: 20px;
      padding-bottom: 10px;
    }
    @media only screen and (max-width:700px) {
      .error{
        font-size: 16px;
        letter-spacing: 1px;
      }
      .error-heading{
        font-size: 25px;
      }
    }
    @media only screen and (max-width:550px) {
      .error-heading{
        font-size: 20px;
        padding: 10px;
        left: 0px;
        margin-left: 10px;
        margin-right: 10px;
      }
      body{
        margin: 4px;
      }
      .error-detail{
        margin-left: 5px;
        margin-top: 20px;
      }
    }
    @media only screen and (max-width:400px) {
      .error-detail{
        margin-top: 50px;
      }
    }
  </style>
</head>
<body>
  <div class="error">
    <div class="error-heading">
      403 - Forbidden: Access is denied
    </div>
    <div class="error-detail">
      You do not have permission to view this directory or page using the credentials that you supplied.
    </div>
  </div>
</body>
</html>