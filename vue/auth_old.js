function getAllUrlParams(url) {

    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
  
    // we'll store the parameters here
    var obj = {};
  
    // if query string exists
    if (queryString) {
  
      // stuff after # is not part of query string, so get rid of it
      queryString = queryString.split('#')[0];
  
      // split our query string into its component parts
      var arr = queryString.split('&');
  
      for (var i = 0; i < arr.length; i++) {
        // separate the keys and the values
        var a = arr[i].split('=');
  
        // set parameter name and value (use 'true' if empty)
        var paramName = a[0];
        var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];
  
        // (optional) keep case consistent
      //   paramName = paramName.toLowerCase();
      //   if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();
  
        // if the paramName ends with square brackets, e.g. colors[] or colors[2]
        if (paramName.match(/\[(\d+)?\]$/)) {
  
          // create key if it doesn't exist
          var key = paramName.replace(/\[(\d+)?\]/, '');
          if (!obj[key]) obj[key] = [];
  
          // if it's an indexed array e.g. colors[2]
          if (paramName.match(/\[\d+\]$/)) {
            // get the index value and add the entry at the appropriate position
            var index = /\[(\d+)\]/.exec(paramName)[1];
            obj[key][index] = paramValue;
          } else {
            // otherwise add the value to the end of the array
            obj[key].push(paramValue);
          }
        } else {
          // we're dealing with a string
          if (!obj[paramName]) {
            // if it doesn't exist, create property
            obj[paramName] = paramValue;
          } else if (obj[paramName] && typeof obj[paramName] === 'string'){
            // if property does exist and it's a string, convert it to an array
            obj[paramName] = [obj[paramName]];
            obj[paramName].push(paramValue);
          } else {
            // otherwise add the property
            obj[paramName].push(paramValue);
          }
        }
      }
    }
  
    return obj;
  }
  function makeid(length) {
      var result           = '';
      var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
     }
     return result;
  }
  
  var baseurl="http://localhost/project_defcon/backend/api/";
  var mainurl="http://localhost/project_defcon";
  const app = Vue.createApp({
      data: () => ({
          firstname:'',
          lastname:'',
          username:'',
          email:'',
          hearfrom:'',
          captachacode:'',
          phone:'',
          password: '',
          verifytype:'',
          referby:'',
          confirm_password:'',
          code:'',
          error: null,
          success: false,
          showsuccess:false,
          accesstoken:'',
          basedata:'',
          pin1:'',
          pin2:'',
          pin3:'',
          pin4:'',
          pin5:'',
          pin6:'',
          pin7:'',
          pin8:'',
      }),
      
      methods: {
          logUserIn:async function(){
              var lasturlis=localStorage.getItem('lasturl');
                      localStorage.removeItem('lasturl');
              if(lasturlis==null){
                       window.location.href =mainurl+'dashboard/index';
              }else{
                       window.location.href =mainurl+lasturlis
              }
          },
          checkauthenyication:async function() {
              //Data From The Form...
              var mainthis =this;
              const auth = {  
                  verifytype: '',
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
               var headers={'Authorization': "Bearer "+ this.accesstoken}
                  await axios.post(baseurl+"/user/auth/check_authentication.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          let access_token = response.data.data[0].access_token;
                          this.success = response.data.text;
                          new toastr.success(response.data.text, "Success");
                          if(response.data.data[0].verification==1 && response.data.data[0].auth_factor){
                              window.location.href =mainurl+'auth/otp?token='+response.data.data[0].access_token;
                          }
                          else if(response.data.data[0].verification==1 && !response.data.data[0].auth_factor){
                                  mainthis.logUserIn()
                          }
                          else{
                              mainthis.sendverifyotp();
                              window.location.href =mainurl+'auth/verify';
                          }
                      }
                  }).catch(function(error){
                  });
          },
          LogWithGoogle:async function(TYPE){
              axios.get(mainurl+"/config/googleAuth.php?type="+TYPE).then(function(response){
                      //console.log(response)
                      window.location.href =response.data;
                  }).catch(function(error){
                  });
          },
          login: async function() {
              var self=this;
              if (this.email == '' || this.password == ''){
                  self.error_modal("Please fill in all the fields to complete this registration");
              }else{
                  const auth = {  
                      email:this.email,
                      password: this.password
                  };
                  var form_data = new FormData();
                  for (var key in auth) {
                      form_data.append(key, auth[key]);
                  }
                  this.error = null;
                  $('.btn3').html(`<span class="indicator-progress d-block">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>`).addClass('disabled');;
                  await axios.post(baseurl+"user/auth/login.php", form_data).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('<span class="indicator-label">Sign In</span>');
                          let access_token = response.data.data[0].access_token;
                          this.success = response.data.text;
                          Swal.fire({
                              text: this.success,
                              icon: "success",
                              buttonsStyling: false,
                              confirmButtonText: "Ok, got it!",
                              customClass: {
                                  confirmButton: "btn btn-primary"
                              }
                          }).then(function (result) {
                              if (result.isConfirmed) { 
                                  form.querySelector('[name="email"]').value= "";
                                  form.querySelector('[name="password"]').value= ""; 
                                  location.href = './dashboard/index.php';
                              }
                          });
                          window.localStorage.setItem('token', access_token);
                          // if((response.data.data[0].verification==1||response.data.data[0].verification==2 || response.data.data[0].verification==3) && response.data.data[0].auth_factor){
                          //     if (response.data.data[0].token == 'google'){
                          //         window.location.href = mainurl+'auth/google-otp';
                          //     }else{
                          //         window.location.href = mainurl+'auth/otp?token='+response.data.data[0].token;
                          //     }
                              
                          // }
                          // else if((response.data.data[0].verification==1||response.data.data[0].verification==2 || response.data.data[0].verification==3) && !response.data.data[0].auth_factor){
                          //       mainthis.logUserIn()
                          // }
                          // else{
                          //     mainthis.sendverifyotp();
                          //     window.location.href =mainurl+'auth/verify';
                          // }
                      }else{
                          $('.btn3').html('Sign in').removeClass('disabled');; 
                      }
                  }).catch(function(error){
                      $('.btn3').html('Sign in').removeClass('disabled');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Login Unsucessful");
                              self.error_modal(this.error);
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              self.error_modal(this.error);
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              self.error_modal(this.error);
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          self.error_modal(this.error);
                      }
                     
                  })
                  
              }
             
          },
          register: async function(e) {
              e.preventDefault()
              var mainthis=this
              //Data From The Form...
              const auth = {  
                  email:this.email,
                  firstname:this.firstname,
                  lastname:this.lastname,
                  username:this.username,
                  password: this.password,
                  phone:this.phone,
                  referedby:this.referby,
                  hearfrom:this.hearfrom,
                  utm_source:getAllUrlParams().utm_source?getAllUrlParams().utm_source:'',
                  utm_medium:getAllUrlParams().utm_medium?getAllUrlParams().utm_medium:'',
                  utm_campaign:getAllUrlParams().utm_campaign?getAllUrlParams().utm_campaign:'',
              };
              //Converting it to form Data for API to Consume
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              if (this.email == '' || this.firstname == '' || this.lastname == '' || this.username == '' || this.password == ''){
                  new toastr.error("Please fill in all the fields to complete this registration", "Incomplete field parameters !");
              }
              else if (filter.test(this.email)) {
                  this.error = null;
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
                  await axios.post(baseurl+"user/auth/register.php", form_data).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Submit').removeClass('disabled');;
                       
                          let access_token = response.data.data[0].access_token;
                          mainthis.accesstoken=response.data.data[0].access_token;
                          mainthis.success = response.data.text;
                          
                          new toastr.success(response.data.text, "Success");
                          window.localStorage.setItem('token', access_token);
                          window.location.href =mainurl+'auth/verify';
                      }
                  }).catch(function(error){
                      $('.btn').html('Submit').removeClass('disabled');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
              else{
                  new toastr.error("Please provide a valid email address", "Invalid Credientials");
              }
          },
          googleregister: async function() {
              var mainthis=this
              //Data From The Form...
              const auth = {  
                  username:this.username,
                  password: this.password,
                  phone:this.phone,
              };
              //Converting it to form Data for API to Consume
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              if ( this.username == '' || this.password == ''){
                  new toastr.error("Please fill in all the fields to complete this registration", "Incomplete field parameters !");
              }
              else{
                  this.error = null;
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
                   var headers={'Authorization': "Bearer "+ this.accesstoken}
                  await axios.post(baseurl+"/user/auth/google-complete-register.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Complete Registration').removeClass('disabled');;
                       
                          let access_token = response.data.data[0].access_token;
                          mainthis.accesstoken=response.data.data[0].access_token;
                          mainthis.success = response.data.text;
                          
                          new toastr.success(response.data.text, "Success");
                          window.localStorage.setItem('token', access_token);
                          window.location.href =mainurl+'dashboard/index';
                      }
                  }).catch(function(error){
                      $('.btn').html('Complete Registration').removeClass('disabled');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              if (this.error == 'Reference number Does not exist.'){
                                  new toastr.error(this.error, "Account Creation Failed");
                                  this.logout();
                              }
                              else{
                                  new toastr.error(this.error, "Account Creation Failed");
                              }
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
          },
          forgot_password: async function() {
              const auth = {  
                  email:this.email
              };
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              if (filter.test(this.email)) {
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');;
                  await axios.post(baseurl+"user/auth/forgot_password.php", form_data).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Send Reset Link').removeClass('disabled');;
                          let access_token = response.data.data[0].access_token;
                          this.success = response.data.text;
                          new toastr.success(response.data.text, "Success");
                      }
                  }).catch(function(error){
                      $('.btn').html('Send Reset Link').removeClass('disabled');;
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
              else{
                  new toastr.error("Please provide a valid email address", "Invalid Credientials");
              }
          },
          reset_password: async function() {
              //Data From The Form...
              const auth = {  
                  password: this.confirm_password,
                  token:getAllUrlParams().token
              };
              //Converting it to form Data for API to Consume
  
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              if ( this.password == '' || this.confirm_password == ''){
                  $('.btn').html('Submit');
                  new toastr.error("Please fill in all the fields to complete this registration", "Incomplete field parameters !");
              }
              else if (this.confirm_password == this.password) {
                  this.error = null;
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
                  await axios.post(baseurl+"user/auth/reset_password.php?token="+getAllUrlParams().token, form_data).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Submit').removeClass('disabled');;
                          this.success = response.data.text;
                          swal("Success", response.data.text, "success");
                          window.location.href =mainurl+'auth/login';
                      }
                  }).catch(function(error){
                      $('.btn').html('Submit').removeClass('disabled');;
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Password Reset Failed");
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
              else{
                  new toastr.error("Password not equal", "Unequal fields");
              }
          },
          sendverifyotp: async function() {
              self = this
              let type = getAllUrlParams().type ? getAllUrlParams().type : 1;
              if (parseInt(type) == 1){
                  self.verifytype = 'email address';
              } 
              if (parseInt(type) == 2){
                  self.verifytype = 'phone number';
              } 
              //Data From The Form...
              
              
              if (parseInt(type) != 1 || parseInt(type) != 2){
                  //window.location.href = 'https://www.cardify.co/auth/verifyphone';
              }
              const auth = {  
                  verifytype: type
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
               var headers={'Authorization': "Bearer "+ this.accesstoken}
                  await axios.post(baseurl+"user/auth/send_verification_otp.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Submit');
                          self.success = response.data.text;
                          self.basedata = response.data.data;
                          if (parseInt(type) == 1){
                              self.verifytype = 'email address';
                          } 
                          if (parseInt(type) == 2){
                              self.verifytype = 'phone number';
                          } 
                          new toastr.success(response.data.text, "Success");
                      }
                  }).catch(function(error){
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.data.error.text == 'The format sent in does not match the correct format for the API'){
                              window.location.href =mainurl+'auth/login'
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
          },
          sendverifyotpfor2fa: async function() {
              self = this;
              //Data From The Form...
              let type = getAllUrlParams().token;
              if (type == 'TYGJOHFUIIH'){
                  this.verifytype = 2;
              } 
              if (type == 'TYGJOHFHYUFUJ'){
                  this.verifytype = 3;
              } 
              
              const auth = {  
                  verifytype: this.verifytype
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
               var headers={'Authorization': "Bearer "+ this.accesstoken}
               
                  await axios.post(baseurl+"user/otp/send_otp_verification_for_2fa.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          self.senddata = response.data.data;
                          new toastr.success(response.data.text, "Success");
                      }
                  }).catch(function(error){
                      
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.data.error.text == 'The format sent in does not match the correct format for the API'){
                              window.location.href =mainurl+'auth/login'
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
          },
          verify: async function() {
              var code = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val()+''+$('.pin5').val()
              let type = getAllUrlParams().code ? getAllUrlParams().code : code;
              var mainthis = this
              //Data From The Form...
              const auth = {  
                  token:getAllUrlParams().token,
                  code: type
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
              $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
               var headers={'Authorization': "Bearer "+ this.accesstoken}
                  await axios.post(baseurl+"user/auth/verify_otp.php",form_data,{headers}).then(function(response){
                      $('.btn').html(`Submit`).removeClass('disabled');
                      if (response.data.status == true){
                          this.success = response.data.text;
                          new toastr.success(response.data.text, "Success");
                          //window.location.href =mainurl+'auth/login.html?verify=true';
                          mainthis.logUserIn()
                      }
                  }).catch(function(error){
                      $('.btn').html(`Submit`).removeClass('disabled');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
          },
          verifyfor2fa: async function() {
              //Data From The Form...
              var mainthis =this
              let type = getAllUrlParams().token;
              if (type == 'TYGJOHFUIIH'){
                  this.verifytype = 2;
              } 
              if (type == 'TYGJOHFHYUFUJ'){
                  this.verifytype = 3;
              } 
              var code = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val()+''+$('.pin5').val()+''+$('.pin6').val()+''+$('.pin7').val()
              const auth = {  
                  type:this.verifytype,code 
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
               if (code.length != 7){
                  new toastr.error("Please fill in all the fields to complete this", "Incomplete field parameters !");
              }
              else{
                  var headers={'Authorization': "Bearer "+ this.accesstoken}
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
                  await axios.post(baseurl+"user/otp/verify_otp_to_check_2fa.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Submit');
                          this.success = response.data.text;
                          new toastr.success(response.data.text, "Success");
                          let access_token = response.data.data[0].access_token;
                          window.localStorage.setItem('token', access_token);
                          mainthis.logUserIn()
                      }
                  }).catch(function(error){
                      $('.btn').html('Submit');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
               
          },
          verifyforgoogle2fa: async function() {
              //Data From The Form...
              var mainthis =this
              var code = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val()+''+$('.pin5').val()+''+$('.pin6').val()
              const auth = {  
                  code 
              }
              var form_data = new FormData();
              for (var key in auth) {
                  form_data.append(key, auth[key]);
              }
              this.error = null;
               if (code.length != 6){
                  new toastr.error("Please fill in all the fields to complete this", "Incomplete field parameters !");
              }
              else{
                  var headers={'Authorization': "Bearer "+ this.accesstoken}
                  $('.btn').html(`<div class="d-flex justify-content-center">
                                       <div class="spinner-border" role="status">
                                          <span class="sr-only"></span>
                                       </div>
                                    </div>`).addClass('disabled');
                  await axios.post(baseurl+"user/otp/verify_otp_to_check_google_2fa_in.php",form_data,{headers}).then(function(response){
                      if (response.data.status == true){
                          $('.btn').html('Submit');
                          this.success = response.data.text;
                          new toastr.success(response.data.text, "Success");
                          let access_token = response.data.data[0].access_token;
                          window.localStorage.setItem('token', access_token);
                          mainthis.logUserIn()
                      }
                  }).catch(function(error){
                      $('.btn').html('Submit');
                      if (error.response){
                          if (error.response.status === 400){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 405){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                          if (error.response.status === 500){
                              this.error = error.response.data.text
                              new toastr.error(this.error, "Verify Otp Failed");
                          }
                      }else{
                          this.error = error.message || "Error processing request"
                          swal(this.error);
                      }
                     
                  })
              }
               
          },
          error_modal: async function (message){
              Swal.fire({
                  text: message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
              });
          },
          logout:async function() {
              window.localStorage.clear(); //clear all localstorage
              window.location.href =mainurl+'auth/login';
          },
      },
      beforeMount(){
          console.clear();
          var pathname=window.location.pathname.replace(/\/\//g, "/")
          this.accesstoken = localStorage.getItem("token");
          
          
          if (pathname.includes('/auth/verify') && getAllUrlParams().token!=undefined){
              this.verify();
          }
          else if (pathname.includes('/auth/verify')){
              
              this.sendverifyotp();
          }
          
          
          if (pathname.includes('/auth/login') && getAllUrlParams().verify!=undefined && getAllUrlParams().verify=="true"){
              this.success="Account verified successfully, kindly login";
              this.showsuccess=true;
              this.checkauthenyication();
          }
          if(pathname.includes('/sign-in')){
              this.checkauthenyication();
          }
          if(pathname.includes('/auth/otp')){
              this.sendverifyotpfor2fa();   
          }
          
      },
  });
  
  
  app.mount('#kt_app_root'); 