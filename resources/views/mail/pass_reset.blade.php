<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <style>
    
    @font-face {
        font-family: google-sans-BOLD;
        src: 'url(\"GOOGLESANS-BOLD.TTF\") format(\"truetype\")';
    }

    @font-face{
        font-family: GOOGLESANS-MEDIUM;
        src: "url(\'GOOGLESANS-MEDIUM.TTF\') format(\'truetype\')";
    }

    body {
        margin: 0 auto;
        background: #ffcc6c;
    }    
  </style>

   <title>Sonrey Ordering System</title>
  </head>
  <body>
    <table style="width: 74%;border-collapse: collapse;margin:0 auto;background:#ffcc6c;text-align: center;color: #fff;margin-top: 4%;letter-spacing: 1px;">

        <tr>
            <td>
                <h1 style="text-transform: uppercase;margin-top: 60px;margin-bottom: 40px;font-size: 40px;font-family: google-sans-BOLD;"><b>account validation</b></h1>
            </td>
        </tr>
        <tr>
            <td>
                <p style="width: 60%;margin: auto;font-size: 22px;margin-bottom: 70px;font-family: GOOGLESANS-MEDIUM;line-height: 35px;">Hi <a href="#" style="color: #fff;"><b>{{$email}}</b></a>, you received this email for we received a request to reset your account password or verify your email</p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 80px;margin-bottom: 120px;font-family: google-sans-BOLD;"><b>{{$token}}</b></p>
            </td>
        </tr>
        <tr style="background: #fff;color: #f94f50;">
            <td>
                <p style="margin-top: 20px;margin-bottom: 20px;font-size: 22px;width:80%;margin-left:auto;margin-right: auto;font-family: GOOGLESANS-MEDIUM;">If you did not request this verification you can disregard this email.</p>
            </td>
        </tr>
    </table>


  </body>
</html>