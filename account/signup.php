<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content='Bean Farm 관리 서비스'>
    <meta name='author' content='hanrim'>
    <link rel='stylesheet' type='text/css' href='/beans/account/css/signup.css'>
    <link href='/beans/assets/libs/bootstrap-3.3.4-dist/css/bootstrap.min.css' rel='stylesheet'>
    <title>Bean Farm 회원가입</title>
  </head>

  <body>
    <div class='content'>
      <div id='desc'>
        <h1>GOODNESS AND HAPPINESS</h1>
        <h1>GROWN UP</h1>
        <br>
        <p>We always prepare to deliver best and high quality food</p>
      </div>
      <div id='sign_card'>
        <h3>Bean Farm Sign up</h3>
        <form name='join' method='post' action='/beans/account/sign_ck.php' onsubmit='return signupCk();'>
          <div class='slot1'>
            <table class='table table-sm'>
              <tbody>
                  <tr>
                    <td class='label' >아이디*</td>
                    <td class='tableBody'><input class='form-control' type='text' size='30' name='ident' placeholder='ID' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>비밀번호*</td>
                    <td class='tableBody'><input class='form-control' type='password' size='30' name='password' placeholder='PASSWORD' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>비밀번호 확인*</td>
                    <td class='tableBody'><input class='form-control' type='password' size='30' name='password2' placeholder='PASSWORD Check' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>이름*</td>
                    <td class='tableBody'><input class='form-control' type='text' size='30' name='name' placeholder='NAME' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>e-mail*</td>
                    <td class='tableBody'><input class='form-control' type='email' size='30' name='email' placeholder='E-MAIL' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>전화번호</td>
                    <td class='tableBody'><input class='form-control' type='tel' size='30' name='tel'
                      placeholder='Phone Number' pattern='^0[0-9]{8,11}' style='color:black' title='전화번호를 정확히 입력해 주세요'></td>
                  </tr>
                  <tr hidden>
                    <td class='label' hidden></td>
                    <td class='tableBody' hidden><input id='reqDate' class='form-control' type='date' name=reqDate style='color:black'></td>
                  </tr>
              </tbody>
            </table>
          </div>
          <div class='slot2'>
              <input id='log_btn' type='button' value='Sign In Page' onclick='movePage();'>
              <input id='sign_btn' type='submit' value='Create an Account'>
          </div>
        </form>
      </div>
    </div>
    <script type='text/javascript' src='/beans/account/js/signup_ck.js'></script>
    <script type='text/javascript'>
      window.onload = function(){
          // document.getElementsByTagName('html')[0].classList.remove('no-display');
          var date = new Date();
          var year = date.getFullYear();
          var month = date.getMonth()+1
          var day = date.getDate();
          if(month < 10){
              month = '0'+month;}
          if(day < 10){
              day = '0'+day;}
          var todayCon = year+'-'+month+'-'+day;
          document.getElementById('reqDate').defaultValue = todayCon;
      }
      function movePage(){
        location.href='/beans/account/signin.php';
      }

    </script>
  </body>
</html>
