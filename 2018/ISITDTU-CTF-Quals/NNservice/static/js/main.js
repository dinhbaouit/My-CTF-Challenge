
var input = $('.validate-input .input100');

function post(){
  var check = true;

  for(var i=0; i<input.length; i++) {
    if(validate(input[i]) == false){
      showValidate(input[i]);
      check=false;
    }
  }

  if(check==true){
    var page=$("#page").val().toLowerCase();
    var username = $('#username').val();
    var password = $('#password').val();
    var data={
      "username":username,
      "password":password
    };
    if(page=='register'){
      var nickname = $('#nickname').val();
      var email = $('#email').val();
      data={
        "username":username,
        "password":password,
        "email":email,
        "nickname":nickname
      };
    }
    $.post("/index.php/"+page,data,function(data,status){
      if(data.indexOf("error!")==-1){
        alert("Success!");
        if(page=='login')
          location.href="/index.php";
        else
          location.href="/index.php/login";
      }
      else{
        alert(data);
      }
    });
  }
};


$('.validate-form .input100').each(function(){
  $(this).focus(function(){
    hideValidate(this);
  });
});

function validate (input) {
  if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
    if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
      return false;
    }
  }
  else {
    if($(input).val().trim() == ''){
      return false;
    }
  }
}

function showValidate(input) {
  var thisAlert = $(input).parent();

  $(thisAlert).addClass('alert-validate');
}

function hideValidate(input) {
  var thisAlert = $(input).parent();

  $(thisAlert).removeClass('alert-validate');
}

var showPass = 0;
$('.btn-show-pass').on('click', function(){
  if(showPass == 0) {
    $(this).next('input').attr('type','text');
    $(this).find('i').removeClass('fa-eye');
    $(this).find('i').addClass('fa-eye-slash');
    showPass = 1;
  }
  else {
    $(this).next('input').attr('type','password');
    $(this).find('i').removeClass('fa-eye-slash');
    $(this).find('i').addClass('fa-eye');
    showPass = 0;
  }

});
