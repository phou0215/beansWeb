
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content='Bean Farm 관리 서비스'>
    <meta name='author' content='hanrim'>
    <link rel='stylesheet' type='text/css' href='/beans/account/css/signin.css'>
    <link href='/beans/assets/libs/bootstrap-3.3.4-dist/css/bootstrap.min.css' rel='stylesheet'>
    <title>Bean Farm 로그인</title>
  </head>

  <body>
      <div class='content'>
        <div id='desc'>
          <h1>WE LEARN THE LOVELY LANGUAGE</h1>
          <h1>FROM OUR PLANT</h1>
          <br>
          <p>We always try to research for better food and healthy life.</p>
        </div>
        <div id='sign_card'>
          <h3>Bean Farm Sign in</h3>
          <form id='login_form' name='loginform' enctype=''class='form-horizontal' action='/beans/account/login_ck.php' method='POST' onsubmit='return loginCk()' >
              <div class='slot1'>
                <input class='form-control' type='text' id='formGroupInputId' name='id' placeholder='ID' autocomplete='on'>
                <input class='form-control' type='password' id='formGroupInputPass' name='password' placeholder='PASSWORD'>
              </div>
              <div class='slot2'>
                <input id='log_btn' type='submit' value='Sign In'>
                <input id='sign_btn' type='button' value='Sign Up' onclick='movePage();'>
              </div>
          </form>
        </div>
    </div>
    <script type='text/javascript' src='/beans/account/js/login_ck.js'></script>
    <script type='text/javascript'>
      // window.onload = function(){
      //   document.getElementsByTagName('html')[0].classList.remove('no-display');
      // }
      function movePage(){
        location.href='/beans/account/signup.php';
      }
    </script>
  </body>
