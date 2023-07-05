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
    //  if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

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

var baseurl="https://app.cardify.co/api/";
var mainurl="https://app.cardify.co/";
const app = Vue.createApp({
    data: () => ({
        firstname:'',
        lastname:'',
        username:null,
        fullname:'',
        billngn:0,
        billusd:0,
        bvnmethodis:0,
        showlevel1ddata:0,
        email:'',
        exchangebalance:0,
        exchangependbalance:0,
        gender:'',
        dob:'',
        address1 :'',
        postalcode:'',
        city:'',
        address2:'',
        state:'',
        phone:null,
        bvn:'',
        sendwithwhat:0,
        userdata:{},
        googleurl:"",
        next_of_kin_name:'',
        next_of_kin_email:'',
        next_of_kin_phoneno:'',
        next_of_kin_address: '',
        depositnotification: '',
        securitynotification: '',
        transfernotification: '',
        loadingsidebar:false,
        usersessionlog:[],
        userrefferalcode:'',
        userlevel_first:{},
        userlevel_second:{},
        userlevel_third:{},
        userreferals:'',
        userrefurl:'',
        usercoupons:'',
        referral_count:'',
        card_verified:0,
        usercard:'',
        userbanks:'',
        userwallets:'',
        userwallet_by_trackID: '',
        currencyreceivemethods: [],
        currencywithdrawalmethods: [],
        subcurrencywithdrawalmethods:[],
        swapcurrencywithdrawalmethods:[],
        usertransaction:[],
        personalbankacc:'',
        systemsettings:'',
        current_transaction:{},
        user_level: null,
        lastpasswordupdate: '',
        password: '',
        confirm_password:'',
        old_password:'',
        wallettrackid:'',
        notifications:'',
        verifytype:'',
        emailcheck:null,
        usersystembanks:[],
        
        // Adding User Banks 
        bankid:'',
        account_name: '',
        bank_code: '',
        bank_name:'',
        account_number:'',
        banknamecode:'',
        ref_code:'',
        banks:'',
        redeemcode:'',
        error: null,
        success: false,
        accesstoken: null,
        loading:true,
        
        // Withdrawal
        withdrawal_amount:'',
        withdrawal_username:'',
        
        //recieve methods
        deposit_method: '',
        deposit_amount:'',
        deposit_min:'',
        deposit_currency:'',
        set2fa:1,
        
        // pagination
        search:'',
        perpage:20,
        currentpage:1,
        totalpage:1,
        totalpage1:1,
        totalpagesetings:1,
        transtotalpage:1,
        sorttransstatus:'',
        sorttransttype:'',
        sortpeerstack:0,
        sortwallettrackid:0,
        
        // crypto
        cryptotrackid:"",
        cryptocurrencylist:[],
        cryptocurrencyaddress:[],
        subcurrencywallet:[],
        addresstoshow:"",
        currencytoshow:"",
        cointoshow:"",
        cryptocoinfirstname:"",
        showusdvalue:false,
        subwalletidtosend:"",
        subwalletusdamt:"",
        subwalletnairarate:"",
        subwalletlivevalue:0,
        toswapcoinusdvalue:0,
        toswapnairavalue:0,
        addressname:"",
        
        
        //Prices Page
        news:'',
        activecrypto:'',
        activecryptoname:'BTC',
        activecryptotoname:'USD',
        cryptodata:'',
        cryptocharts:'',
        yAxis:[],
        xAxis:[],
        activecryptotime:'hour',
        maintainanceinterval:"",
        currentTab:1,
        bvnotp:'',
        bvnverifytyp:1,
        bvnpno:'',
        showbvnotp:false,
        showbvnotpform:false,
        showform3:false,
        bvndob:'',
        bvnpno:'',
        bvnlname:'',
        bvnfname:'',
        
        
        // kyc
        kycdata:"",
        // kyc form
        dob:"",
        loginemail:"",
        fblink:"",
        twitterlink:"",
        instalink:"",
        telegramlink:"",
        country:"Nigeria",
        state:"",
        biztype:"",
        address:"",
        regtype:"",
        house_number:"", 
        reg_id_num:"",
        passportcode:"",
        passportimgname:"",
        businesscccode:"",
        businessimgname:"",
        regulationcode:"",
        regulationimgname:"",
        holdregname:"",
        holdregimgcode:"",
        
        
        // exchange
        exchangesyspayid:0,
        exchangesystid:"",
        exchangeuser_data:"",
        exchangecurrencylist:[],
        exchangebankdata:"",
        exchangePayMethod:'',
        selectedcoindata:"",
        selectedtocoindata:"",
        amounttosell:'',
        livevalue:0,
        searchcurrency:"",
        searchtocurrency:"",
        
        //peerstack
        recieve_merchants:[],
        withdrawal_merchants:[],
        current_merchant: {},
        current_amount:null,
        timeup:false,
        peerTimeinterval:'',
        peerSuccessTimer:'',
        internalLoading:false,
        peerstackAccess:false,
        peerStackWithdrawbank:'',
        showeye:true,
        ihvsentclick:false,

            
        //One App Bank
        oneappbankname:"",
        oneappacctname:"",
        oneappaccno:"",
        oneapppercent:0,
        
        //Pin 
        pin:'',
        pin1:'',
        pin2:'',
        pin3:'',
        pin4:'',
        pin5:'',
        pin6:'',
        pin7:'',
        pin8:'',
        pin9:'',
        pin10:'',
        is_email_confirm:null,
        codekey:'',
        code:'',
        
        // subwallet
        showbalance:true,
        showsubwalletusd:false,
        subwalletdata:null,
        subwalletsearch:'',
        subwallettrackid:'',
        subwalletnetwork:null,
        subwalletname:'',
        selectednetworkname:'',
        selectednetshortname:'',
        selectednetaddress:'',
        selectednetaddressmemo:'',
        externaladdress:'',
        externalmemo:'',
        externalmessage:'',
        network_cointid:'',
        subwalletsiebar:'',
        
        //Cards
        card_view:0,//1-create card 2-fund card 3-freez card 4- delete card 5-view card details 6-unfreeze card 7- unload card 8 -Activate card 9-update card pin
        
        // VIRTUAL CARD
        vc_plans:null,
        selectedPlan:null,
        accepttc:false,
        readtc:false,
        selectedCurrency:null,
        user_vc_fund_wallet:null,
        user_vc_unfund_wallet:null,
        amounttofund:5,
        amounttounload:0,
        vc_inflow:0,
        vc_outflow:0,
        vc_sort:1,
        vc_transcount:0,
        user_vc_list:[],
        selected_vc_card:null,
        selected_index:0,
        selected_vc_bal:0,
        selectedUnloadCurrency:null,
        
        // SWAP
        swapcurrencies:[],
        active_swapcurrencies:[],
        swaptocurrencies:[],
        active_swaptocurrencies:[],
        swapcurrencies_list:[]
        
        
    }),
    methods: {
        // system functions
        stopLoading:async function(){
            this.loading = false;
            this.loadingsidebar = false;
        },
        computedScore2dp($amount){
            if(isNaN($amount)){
                return 0;
            }
            $amount=parseFloat($amount).toFixed(3)
            var formatter = new Intl.NumberFormat('en-US', { maximumSignificantDigits: 3 });
            return formatter.format($amount);
        },
        computedScore($amount){
            if(isNaN($amount)){
                return 0;
            }
            $amount=parseFloat($amount).toFixed(8)
            var formatter = new Intl.NumberFormat('en-US', { maximumSignificantDigits: 9 });
            return formatter.format($amount);
        },
        logout:async function() {
            window.localStorage.clear(); //clear all localstorage
            window.location.href =mainurl+'auth/login';
        },
        truncate:function(value) {
            if (value.length > 45) {
                value = value.substring(0, 42);
            }
            return value
        },
        truncatelength:function(value,length) {
            if (value.length > length) {
                value = value.substring(0, length);
            }
            return value
        },
        redirectURL: function(value){
            window.location.href = value;
        },
        copyThetext(copyme){
			if (navigator.clipboard && window.isSecureContext) {
				// navigator clipboard api method'
				navigator.clipboard.writeText(copyme);
				toastr.success(copyme+" copied successfully", "Copied!");
			} else {
				// text area method
				let textArea = document.createElement("textarea");
				textArea.value = copyme;
				// make the textarea out of viewport
				textArea.style.position = "fixed";
				textArea.style.left = "-999999px";
				textArea.style.top = "-999999px";
				document.body.appendChild(textArea);
				textArea.focus();
				textArea.select();
				return new Promise((res, rej) => {
					// here the magic happens
					document.execCommand('copy') ? res() : rej();
					textArea.remove();
                    toastr.success("The text "+copyme+" is copied successfully", "Copied!");
				});
			}

		},
		notifyInfo: async function(value){
            new toastr.info(value,"Please Note");
        },
        notifyError: async function(value){
            new toastr.error(value,"Error");
        },
        notifySuccess: async function(value){
            new toastr.success(value,"Success");
        },
        shouldItShow(data){
		  var show=true;
            if (!data) {
                return false;
            } else if (data == '') {
                return false;
            } else if (data == 0) {
                return false;
            } else if (data == ' ') {
                return false;
            } else if (data == null) {
                return false;
            } else if (!data.toString().trim()) {
                return false
            } else if (typeof data == 'undefined') {
                return false
            } else if (data.length == 0) {
                return false
            }
		  return show;
		},
        
        // notification system
        checkfornewnoti(){
            let self=this
          	let api=baseurl+"user/notifications/checkifthereisnoti.php";
          	  var auth = {  
               notitype:1,
            };
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            self.maintainanceinterval= setInterval(function () {
                    axios.post(api,form_data,{headers}).then(function (response) {
                    	if (!response.data.status) {
                    	    
                        } 
                        else {
                            toastr.clear()
                            toastr.success(response.data.text,{ui:"is-dark"});
                        }
                    })
                    .catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            self.logout();
                        }else if(error.response.status==500){
                        }else if(error.response.status==401){
                           	self.logout();
                        }
                    }
            })

            }, 20000);
        },
        stopnotification(){
            let self=this
        	let api=baseurl+"user/notifications/stopnotification.php";
        		var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        		} else {
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                        }else if(error.response.status==500){
                        }else if(error.response.status==401){
                           	self.logout();
                        }
                    }
            })
        },
        
        setCurrentNotifyTrans:async function(index){
            this.current_transaction = this.notifications[index].transdata
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
            if ( this.username == '' || this.password == '' || this.phone == ''){
                new toastr.error("Please fill in all the fields to complete this registration", "Incomplete field parameters !");
            }
            else{
                this.error = null;
                $('.verif2').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                 var headers={'Authorization': "Bearer "+ this.accesstoken}
                await axios.post(baseurl+"/user/auth/google-complete-register.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){
                        $('.verif2').html('Complete Registration').removeClass('disabled');;
                     
                        let access_token = response.data.data[0].access_token;
                        mainthis.accesstoken=response.data.data[0].access_token;
                        
                        new toastr.success(response.data.text, "Success");
                        mainthis.getUserDetails();
                        $('.btn-close').click();
                    }
                }).catch(function(error){
                    $('.verif').html('Complete Registration').removeClass('disabled');
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
        // UPDATE USER settings
        updatePassword: async function() {
            //Data from The form...
            const auth = {  
                currentpassword:this.old_password,
                password: this.password
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( this.password == '' || this.confirm_password == '' || this.old_password == ''){
                $('.btn').html('Change password');
                new toastr.error("Please fill in all the fields to complete this registration", "Incomplete field parameters !");
            }
            else if (this.confirm_password != this.password){
                $('.btn').html('Change password');
                new toastr.error("Password not equal", "Unequal Fields !");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.change_password').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/profile/change_password.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){
                        $('.btn').html('Change password');
                        this.success = response.data.text;
                        Swal.fire("Success", response.data.text, "success").then((value) => {
                            window.localStorage.clear(); //clear all localstorage
                            window.location.href =mainurl+'auth/login.html';
                        });
                    }
                }).catch(function(error){
                    $('.change_password').html('Change password');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        Swal.fire(this.error);
                    }
                   
                })
                
            }
            
        },
        updateUsername: async function() {
            //Data from The form...
            const auth = {  
                username:this.username
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( this.username == ''){
                $('.change_username').text('Update Username');
                new toastr.error("Username Field Empty", "Incomplete field parameters !");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.change_username').html(`<div class="d-flex justify-content-center">
                                                 <div class="spinner-border" role="status">
                                                    <span class="sr-only"></span>
                                                 </div>
                                              </div>`);
                await axios.post(baseurl+"user/profile/update_username.php",form_data,{headers}).then(function(response){
                    $('.change_username').text('Update Username');
                    if (response.data.status == true){
                        $('.change_username').html('Update Username');
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success", {duration:1000});
                        $('.btn-close').click();
                    }
                }).catch(function(error){
                    $('.change_username').text('Update Username');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        new toastr.error(this.error, "Error!", {duration:1000});
                    }
                })
                
            }
            
        },
        updateProfile: async function() {
            var mainthis = this
            const auth = {  
                firstname:this.firstname,
                lastname:this.lastname,
                gender:this.gender,
                dob:this.dob,
                address1: this.address1,
                address2: this.address2,
                country:this.country,
                state:this.state,
                city:this.city,
                postalcode:this.postalcode,
                phone:this.phone
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( this.firstname == ''){
                $('.btn-3').text('Submit');
                new toastr.error("Username Field Empty", "Incomplete field parameters !");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.btn-3').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/profile/update_profile.php",form_data,{headers}).then(function(response){
                    $('.btn-3').text('Submit');
                    if (response.data.status == true){
                        $('.btn-3').html('Submit');
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success", {duration:1000});
                        $('.btn-close').click();
                    }
                    mainthis.getUserDetails();
                }).catch(function(error){
                    $('.btn-3').text('Update Username');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        new toastr.error(this.error, "Error processing request", {duration:1000});
                    }
                })
                
            }
            
        },
         updateBasicDetails: async function() {
            //Data from The form...
            const auth = {  
                address1:this.address1,
                state:this.state,
                country:this.country,
                pin:this.pin
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if ( this.country.length ==0){
                new toastr.error("Please fill in all the fields", "Incomplete field parameters !");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.change_pin').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/profile/update_basic_profile.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){
                        toastr.success(response.data.text, "Success");
                        self.getUserDetails();
                        $('.change_pin').html('Change pin');
                        $('#staticBackdrop-security-pin2').modal('hide');
                        $('#staticBackdrop-security-pin').modal('hide');
                        
                    }
                }).catch(function(error){
                    $('.change_pin').html('Update profile');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        // Swal.fire(this.error);
                    }
                   
                })
                
            }
            
        },
        updatePin: async function() {
            var first = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var second = $('.pin5').val()+''+$('.pin6').val()+''+$('.pin7').val()+''+$('.pin8').val();
            //Data from The form...
            const auth = {  
                currentpassword:first,
                password: second
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if ( first.length != 4 || second.length != 4){
                new toastr.error("Please fill in all the fields", "Incomplete field parameters !");
            }
            else if (first != second){
                new toastr.error("Pin not equal", "Unequal Fields !");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.change_pin').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/profile/change_pin.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){
                        toastr.success(response.data.text, "Success");
                        self.getUserDetails();
                        $('.change_pin').html('Change pin');
                        $('#staticBackdrop-security-pin2').modal('hide');
                        $('#staticBackdrop-security-pin').modal('hide');
                        
                    }
                }).catch(function(error){
                    $('.change_pin').html('Change pin');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        // Swal.fire(this.error);
                    }
                   
                })
                
            }
            
        },
		updateNextOfKin: async function() {
            const auth = {  
                next_of_kin_name:this.next_of_kin_name,
                next_of_kin_email:this.next_of_kin_email,
                next_of_kin_phoneno:this.next_of_kin_phoneno,
                next_of_kin_address:this.next_of_kin_address
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( this.next_of_kin_name == ''){
                $('.btn-3').text('Submit');
                Swal.fire("Incomplete Field parameters", "Username Field Empty", "warning");
            }
            else {
                this.error = null;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.btn-3').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/profile/update_next_of_kin.php",form_data,{headers}).then(function(response){
                    $('.btn-3').text('Submit');
                    if (response.data.status == true){
                        $('.btn-3').html('Submit');
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success", {duration:1000});
                        $('.btn-close').click();
                    }
                }).catch(function(error){
                    $('.btn-3').text('Submit');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        Swal.fire(this.error);
                    }
                })
                
            }
            
        },
        updateCommunication: async function() {
            let self = this;
            const auth = {  
                depositnotification:this.depositnotification,
                securitynotification:this.securitynotification,
                transfernotification:this.transfernotification
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/profile/update_communication.php",form_data,{headers}).then(function(response){
                
                if (response.data.status == true){
                    this.success = response.data.text;
                    new toastr.success(response.data.text, "Success", {duration:1000});
                    $('.btn-close').click();
                    
                }
                }).catch(function(error){
                    
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        Swal.fire(this.error);
                    }
                })
                
            
        },
        //  GET USER DATA
        getUserSessionLog: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/profile/userSessionlog.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usersessionlog = user_info.userdata;
        		    self.totalpage=user_info.totalpage
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserNotifications: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/notifications/getNotifications.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Fetching User Notification Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.notifications = user_info.userdata;
        		    self.totalpage1=user_info.totalpage
        		}
            }).catch(function (error) {
                    if (error.response) {
                        
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserDetails: async function(){
            let self=this
            let api= baseurl+"user/profile/get_user_detail.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	   // 	self.stopLoading();
        	    	new toastr.error(response.data.text,"Authorization Failed, Please Login Again");
        	    	//////this.logout();
        		} 
        		else {
        		    let user_info = response.data.data;
        		    self.userdata = user_info;
        		    if(self.userdata.loginfa==1){
        		    self.code=true
        		    }else{
        		     self.code=false   
        		    }
        		    self.fullname = user_info.Fullname;
        		    self.billusd = user_info.billusd;
        		    self.billngn = user_info.billngn;
        		    self.firstname = user_info.Firstname;
        		    self.lastname = user_info.Lastname;
        		    self.email = user_info.Email;
        		    self.username = user_info.Username;
        		    self.phone = user_info.phone;
        		    self.exchangebalance =user_info.exchangebalance;
        		    self.exchangependbalance =user_info.exchangependbalance;
        		    self.dob = user_info.DOB = '' ? '-': user_info.DOB;
        		    self.gender = user_info.Gender  = '' ? '-': user_info.Gender;
        		    self.address1 = user_info.Address1 = '' ? '-': user_info.Address1;
        		    self.postalcode=user_info.postalcode = '' ? '-': user_info.postalcode;
        		    self.city=user_info.city = '' ? '-': user_info.city;
        		    self.address2 = user_info.Address2 = '' ? '-': user_info.Address2;
        		    self.state = user_info.State = '' ? '-': user_info.State;
        		    self.country = user_info.Country = '' ? '-': user_info.Country;
                    self.next_of_kin_name= user_info.next_of_kin_name = '' ? '-': user_info.next_of_kin_name;
                    self.next_of_kin_email= user_info.next_of_kin_email = '' ? '-': user_info.next_of_kin_email;
                    self.next_of_kin_phoneno = user_info.next_of_kin_phoneno = '' ? '-': user_info.next_of_kin_phoneno;
                    self.next_of_kin_address = user_info.next_of_kin_address = '' ? '-': user_info.next_of_kin_address;
                    self.depositnotification = user_info.depositnotification;
                    self.securitynotification = user_info.securitynotification;
                    self.transfernotification = user_info.transfernotification;
                    self.user_level = user_info.user_level;
                    self.lastpasswordupdate= user_info.lastpasswordupdate;
                    self.userrefferalcode = user_info.referallink;
                    self.userrefurl = user_info.referralcode;
                    self.referral_count = user_info.referralcount;
                    self.card_verified=user_info.card_verified;
                    window.localStorage.setItem('keytag',user_info.keytag);
                    window.localStorage.setItem('id', self.username);
                    window.localStorage.setItem('email', self.email);
                    window.localStorage.setItem('name', self.fullname==null ?'' : self.fullname);
                    window.localStorage.setItem('user_level', self.user_level)
                    window.localStorage.setItem('created_at', self.userdata.created_at);
                    window.localStorage.setItem('phoneno', self.userdata.phone);
    		        self.stopLoading();
    		        
    		        if(self.username==null){
    		             $('.show-greg').click(); 
    		        }else  if(self.userdata.regmethod!=1 && (self.userdata.lastpasswordupdate==''||self.userdata.lastpasswordupdate==' ')){
    		            $('.show-pass').click();
    		        }else if (self.userdata.is_phone_verified==0){
    		            $('.show-phone-verify').click();
    		        }else if(self.userdata.is_pin_added==0){
    		            $('.show-pin').click();
    		        }
    		       
        		}
            }).catch(function (error) {
                    self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           new toastr.error("Unauthorised", "Error!");
                           self.logout();
                        }
                    }
                });
        },
        getUserLevels: async function(){
            let self=this
            let api= baseurl+"user/profile/get_user_level.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken,}
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	   // 	new toastr.error(response.data.text,"Error Fetching Level Authorization");
        	    	//////this.logout();
        		} 
        		else {
        		    let user_info = response.data.data;
        		    self.userlevel_first = user_info.userdata[0];
        		    self.userlevel_second = user_info.userdata[1];
        		    self.userlevel_third = user_info.userdata[2];
        		}
            }).catch(function (error) {
                    self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                        }else if(error.response.status==500){
                        }else if(error.response.status==401){
                           self.logout();
                        }
                    }
                });
        },
        getUserTransaction: async function() {
            let self=this
            var auth={};
            	if(getAllUrlParams().currency!=undefined){
            	    	auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
                currency:getAllUrlParams().currency,
                statussort:this.sorttransstatus,
                statustype:this.sorttransttype,
                sortpeerstack:this.sortpeerstack,
                sorttrackid:this.sortwallettrackid,
                      };
            	}else{
            	    auth = {  
                        pg:this.currentpage,
                        perpage:this.perpage,
                        search:this.search,
                        statussort:this.sorttransstatus,
                        statustype:this.sorttransttype,
                        sortpeerstack:this.sortpeerstack,
                        sorttrackid:this.sortwallettrackid
                    };
            	}
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            let api= baseurl+"user/transaction/getUserTransaction.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usertransaction = user_info.userdata;
        		    self.transtotalpage=user_info.totalpage;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserCards: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/systems/getCards.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usercard = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserSubWallet: async function(){
            let self=this
             this.loading = true;
            let api=baseurl+"user/wallet/user_subwallet_list.php?currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.post(api,{},{headers}).then(function (response) {
        	        this.loading = true;
        	    if (!response.data.status) {
        	        self.stopLoading()
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		   
        		    let user_info = response.data.data;
        		    self.subcurrencywallet = user_info.userdata;
        		     self.stopLoading()
        		}
            });
        },
        getUserSubWalletWithUSD: async function(){
            let self=this
             this.loading = true;
            let api=baseurl+"user/currency/getUserSubWalletwithliveusd.php?currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	        this.loading = true;
        	    if (!response.data.status) {
        	        self.stopLoading()
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		   
        		    let user_info = response.data.data;
        		    self.subcurrencywallet = user_info.userdata;
        		     self.stopLoading()
        		}
            });
        },
        getPersonalBanksacc: async function(){
            let self=this
            let api= baseurl+"user/transaction/getpersonalbankaccount.php"
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	        self.stopLoading();
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                         self.stopLoading();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    if (user_info.userdata == 'empty bvn'){
        		        $('.sc-kDDrLX.btyzAG.d-none').click();
        		        new toastr.error(response.data.text, "Error", {duration:1000});
        		    }else{
        		        self.personalbankacc = user_info.userdata;
        		        self.stopLoading();
        		    }
        		}
            }).catch(function (error) {
                      self.stopLoading(); 
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getoneAppBank: async function(bankname,bankcode){
            let self=this
            let api= baseurl+"user/transaction/getpersonalbankaccount.php?name="+bankname+"&code="+bankcode
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Login Session Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.personalbankacc = user_info.userdata;
        		}
            });
        },
        getUserReferrals: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
                refcode:self.userrefurl
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/profile/get_user_referrals.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.userreferals = user_info.userdata;
        		    self.totalpage=user_info.totalpage;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserCoupons: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/profile/get_user_coupons_history.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usercoupons = user_info.userdata;
        		    self.totalpage=user_info.totalpage;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserWallets: async function() {
            let self=this
            let api=baseurl+"user/currency/getUserWallets.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        		} else {
        		    let user_info = response.data.data;
        		    self.userwallets = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUserWalletsByTrackID: async function() {
            let self=this
            this.wallettrackid=getAllUrlParams().tag;
            let api= baseurl+"user/currency/getUserWalletInfobyTrackID.php?tag="+getAllUrlParams().tag;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.userwallet_by_trackID = user_info.userdata
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
 
        getUserBanks: async function() {
            let self=this
            var auth={};
            auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/systems/getBanks.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Login Session Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.userbanks = user_info.userdata;
        		    self.totalpage=user_info.totalpage
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        // DELETE USER DATA
        deleteCards: async function(id) {
            let api= baseurl+"user/systems/deleteUserCards.php?card_id="+id;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	let self = this;
        	axios.get(api,{headers}).then(function(response) {
        	    if (!response.data.status) {
        	    	self.logout();
        		} else {
        		    self.getUserCards();
        		    new toastr.success("Your Card has been Deleted Successfully", "Success");
        		}
            }).catch(function (error) {
                    if (error.response) {
                       if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                    }
                });
        },
        deleteBanks: async function(id) {
            let api=baseurl+"user/systems/deleteUserBanks.php?card_id="+id;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	let self = this;
        	this.loading=true;
        	axios.get(api,{headers}).then(function(response) {
        	    self.stopLoading();
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        self.logout();
                    });
        		} else {
        		    self.getUserBanks();
        		    new toastr.success("Your Bank has been Deleted Successfully", "Success");
        		}
            }).catch(function (error) {
                self.stopLoading();
                    if (error.response) {
                       if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!");
                        }
                    }
                });
        },
        // USER ACTIONS
        generatebankacc: async function (code,name){
            let self=this
            let api=baseurl+"user/transaction/generateMonifyacc.php?code="+code+"&name="+name
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    if (user_info.userdata == 'empty bvn'){
        		        $('.sc-kDDrLX.btyzAG.d-none').click();
        		        new toastr.error(response.data.text, "Error", {duration:1000});
        		    }
        		    else{
        		        self.getPersonalBanksacc();
        		        $('.jjjjjjjj').click();
        		        new toastr.success(response.data.text, "Success", {duration:1000});
        		    }
        		   self.stopLoading();
        		}
            }).catch(function (error) {
                self.stopLoading();
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error("Error!", error.response.data.text);
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getsystemactivebanks: async function() {
            let self=this
            var auth={};
            auth = {  
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/transaction/getAllOneAppBank.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Login Session Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usersystembanks = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        redeemCode: async function() {
            let self=this
            var auth = {  
                code:this.redeemcode
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api=baseurl+"user/systems/redeemcode.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            if (this.redeemcode == '' || this.redeemcode == ' '){
                self.notifyError('The field cannot be empty !');
            }
        	else{
        	    axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    new toastr.success(response.data.text, "Success", {duration:1000});
                    $('.btn-close').click();
        		}
                }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            self.notifyError(error.response.data.text);
                        }else if(error.response.status==500){
                            self.notifyError(error.response.data.text);
                        }else if(error.response.status==401){
                           self.notifyError(error.response.data.text);
                        }
                    }
                });
        	}
        },
        verifyBank: async function() {
            let self = this;
            this.account_name =null
            var breakme=this.banknamecode.split("^");
            this.bank_code=breakme[0]
            this.bank_name=breakme[1]
            if(this.account_number.length>=10){
                this.loading=true;
            }
            
            const auth = {  
                bankcode: this.bank_code,
                accountnumber:this.account_number
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/systems/verifyBanks.php",form_data,{headers}).then(function(response){
            
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
                            self.account_name =null
                                self.stopLoading();
        		} else {
        		    let user_info = response.data.data;
        		    self.account_name = user_info.userdata;
        		    new toastr.success(response.data.text, "Success", {duration:1000});
        		        self.stopLoading();
        		}
            }).catch(function (error) {
                self.account_name ="Invalid account number"
               
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
;                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                        }
                         self.stopLoading();
                    }else{
                        this.error = error.message || "Error processing request"
                         self.stopLoading();
                        Swal.fire(this.error);
                        
                    }
                });
        },
        addUserBanks: async function() {
            let self = this;
            this.loading=true;
            
            const auth = {  
                accountname: this.account_name,
                bankcode: this.bank_code,
                bankname: this.bank_name,
                accountnumber:this.account_number,
                firstname:this.firstname,
                lastname:this.lastname,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/systems/addUserBanks.php",form_data,{headers}).then(function(response){
                self.stopLoading();
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.getUserBanks()
        		    new toastr.success(response.data.text, "Success", {duration:1000});
                    $('.btn-close').click();
        		}
            }).catch(function (error) {
                self.stopLoading();
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        Swal.fire(this.error);
                    }
                });
        },
        sendverifyotp: async function(id) {
            this.verifytype = id;
            const auth = {  
                verifytype: id,
                set2fa:this.set2fa,
                method:this.sendwithwhat
            }
            $('.verify-password-mod button').css('background-color','black');
            if (this.sendwithwhat == 0){
                $('.verify-password-mod button').eq(0).css('background-color','#12a733');
            }
            else if (this.sendwithwhat == 1){
                $('.verify-password-mod button').eq(1).css('background-color','#12a733');
            }
            else{
                $('.verify-password-mod button').eq(2).css('background-color','#12a733');
            }
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
             var headers={'Authorization': "Bearer "+ this.accesstoken}
                await axios.post(baseurl+"user/otp/send_otp_verification_for_2fa_in.php",form_data,{headers}).then(function(response){
                    $('.verify-password-mod button').css('background-color','black');
                    if (response.data.status == true){
                        this.success = response.data.text;
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
                        Swal.fire(this.error);
                    }
                   
                })
        },
        verify: async function(id,type) {
            self = this;
            if (type == 2){
                var code = $('.email_v1').val()+''+$('.email_v2').val()+''+$('.email_v3').val()+''+$('.email_v4').val()+''+$('.email_v5').val()+''+$('.email_v6').val()+''+$('.email_v7').val();
            }
            else if (type == 4){
                var code = $('.verif1').val()+''+$('.verif2').val()+''+$('.verif3').val()+''+$('.verif4').val()+''+$('.verif5').val()+''+$('.verif6').val()+''+$('.verif7').val();
            }
            else{
                var code = $('.phone1').val()+''+$('.phone2').val()+''+$('.phone3').val()+''+$('.phone4').val()+''+$('.phone5').val()+''+$('.phone6').val()+''+$('.phone7').val();
            }
            //Data From The Form...
            const auth = {  
                identity:id,type,code
            }
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
            if (code.length != 7){
                new toastr.error('Please Complete all fields and try again.', "Incomplete Parameter!");
            }
            else{
              var headers={'Authorization': "Bearer "+ this.accesstoken}
                if (type == 2){
                    $('.email-verification_trigger').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                }
                else if (type == 4){
                    $('.verification-prompt_trigger').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                }
                else{
                    $('.phone-verification_trigger').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                }
                await axios.post(baseurl+"user/otp/verify_otp_to_set_2fa.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){;
                        new toastr.success(response.data.text, "Success");
                        self.getUserDetails();
                        
                        if (type == 2){
                            $('.secsec').hide();
                            $('.secure-1').show();
                            $('.email-verification_trigger').hide();
                        }
                        else if (type == 4){
                            self.is_email_confirm = true;
                        }
                        else{
                            $('.secsec').hide();
                            $('.secure-1').show();
                            $('.phone-verification_trigger').hide();
                        }
                    }
                    self.getUserDetails()
                }).catch(function(error){
                    if (type == 2){
                        $('.email-verification_trigger').hide();
                    }
                    else{
                        $('.phone-verification_trigger').hide();
                    }
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
                        Swal.fire(this.error);
                    }
                   
                })  
            }
             
        },
        popup2faQRCode(){
            let self=this
        	let api=baseurl+"user/otp/send_otp_verification_for_google_2fa.php";
        		var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	        
        		} else {
        	        self.googleurl = response.data.data.url;
        	        self.codekey = response.data.data.key;
        	        $('.google').val('');
        		}
            }).catch(function (error) {
            })
        },
        set_google_auth_app: async function(){
            self = this;
            var code = $('.google1').val()+''+$('.google2').val()+''+$('.google3').val()+''+$('.google4').val()+''+$('.google5').val()+''+$('.google6').val();
            //Data From The Form...
            const auth = {  
                code: code
            }
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
            if (code.length != 6){
                new toastr.error('Please Complete all fields and try again.', "Incomplete Parameter!");
            }
            else{
              var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.verify_google_2fa').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                $('.verify_google_2fa').css({'pointer-events':'none','opacity':0.4});
                await axios.post(baseurl+"user/otp/verify_otp_to_set_2fa_with_google.php",form_data,{headers}).then(function(response){
                    $('.verify_google_2fa').html('Submit');
                        $('.verify_google_2fa').css({'pointer-events':'all','opacity':1});
                    if (response.data.status == true){
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success");
                        window.location.reload();
                        //setTimeout(self.logout(), 2000);
                    }
                    self.getUserDetails()
                }).catch(function(error){
                    $('.verify_google_2fa').html('Submit');
                    $('.verify_google_2fa').css({'pointer-events':'all','opacity':1});
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
                        Swal.fire(this.error);
                    }
                   
                })  
            }
             
        },
        update_login_app: async function(){
            self = this;
            var code = this.code;
            //Data From The Form...
            const auth = {  
                code: code
            }
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
            var headers={'Authorization': "Bearer "+ this.accesstoken}
                await axios.post(baseurl+"user/otp/update-login-key.php",form_data,{headers}).then(function(response){
                    if (response.data.status == true){
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success");
                        //setTimeout(self.logout(), 2000);
                        self.getUserDetails();
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
                    }else{
                        this.error = error.message || "Error processing request"
                        Swal.fire(this.error);
                    }
                   
                })  
            
             
        },
        verify_inner: async function(id,type) {
            self = this;
            //Data From The Form...
            const auth = {  
                identity:id,
                type:type,
                code: this.code
            }
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
             var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.verif').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/otp/verify_phone.php",form_data,{headers}).then(function(response){
                    
                    if (response.data.status == true){
                        $('.verif').html('Verify Phone Number');
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success");
                        self.getUserDetails();
                        $('#verify-phone').modal('hide');
                    }
                }).catch(function(error){
                    $('.verif').html('Verify Phone Number');
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
                        Swal.fire(this.error);
                    }
                   
                })
        },
        remove2fa: async function(id) {
            self = this;
            //Data From The Form...
            var code = $('.verify1').val()+''+$('.verify2').val()+''+$('.verify3').val()+''+$('.verify4').val()+''+$('.verify5').val()+''+$('.verify6').val()+''+$('.verify7').val();
            const auth = {identity:id,code}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
             var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.verification-removal_trigger').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                if (code.length != 7){
                    new toastr.error('Please Complete all fields and try again.', "Incomplete Parameter!");
                }
                else{
                    await axios.post(baseurl+"user/otp/verify_otp_to_remove_2fa.php",form_data,{headers}).then(function(response){
                        if (response.data.status == true){
                            $('.verification-removal_trigger').html('Submit').removeClass('disabled');
                            this.success = response.data.text;
                            new toastr.success(response.data.text, "Success");
                            self.getUserDetails();
                            $('.btn-close').click();
                        }
                    }).catch(function(error){
                        $('.verification-removal_trigger').html('Submit').removeClass('disabled');
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
                            Swal.fire(this.error);
                        }
                       
                    })
                }
                
        },
        removegoogle2fa:  async function(id) {
            self = this;
            //Data From The Form...
            var code = $('.verify1').val()+''+$('.verify2').val()+''+$('.verify3').val()+''+$('.verify4').val()+''+$('.verify5').val()+''+$('.verify6').val();
            const auth = {code}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.error = null;
             var headers={'Authorization': "Bearer "+ this.accesstoken}
                $('.verification-removal_trigger').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`).addClass('disabled');
                if (code.length != 6){
                    new toastr.error('Please Complete all fields and try again.', "Incomplete Parameter!");
                }
                else{
                    await axios.post(baseurl+"user/otp/verify_otp_to_remove_google_2fa.php",form_data,{headers}).then(function(response){
                        if (response.data.status == true){
                            $('.verification-removal_trigger').html('Submit').removeClass('disabled');
                            this.success = response.data.text;
                            new toastr.success(response.data.text, "Success");
                            self.getUserDetails();
                            $('.btn-close').click();
                        }
                    }).catch(function(error){
                        $('.verification-removal_trigger').html('Submit').removeClass('disabled');
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
                            Swal.fire(this.error);
                        }
                       
                    })
                }
                
        },
        
        //   MAIN SYSTEM SETTINGS
        getSystemSettings: async function() {
            let self=this
            let api= baseurl+"user/profile/getsystem_settings.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.systemsettings = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getCurrencyRecieveMethods: async function(){
            let self=this
            let api=baseurl+"user/currency/getcurrencyreceivemethods.php?currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Fetching Currency Method Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.currencyreceivemethods = user_info.userdata;
        		}
            });
        },
        getCurrencyWithdrawMethods: async function(){
            let self=this
            let api=baseurl+"user/currency/getcurrencywithdrawalmethod.php?currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Fetching Currency Method Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.currencywithdrawalmethods = user_info.userdata;
        		}
            });
        },
        getSubCurrencyWithdrawMethods: async function(){
            let self=this
            let api=baseurl+"user/currency/getsubcurrencywithdrawalmethod.php?currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.subcurrencywithdrawalmethods = user_info.userdata;
        		}
        		
        	
            });
        },
        getSwapWithdrawMethods: async function(){
            let self=this
            let api=baseurl+"user/currency/getsubcurrencywithdrawalmethod.php?swap=1&currency="+getAllUrlParams().currency;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.swapcurrencywithdrawalmethods = user_info.userdata;
        		}
            });
        },
        get_Allactive_exchange_to_methdos: async function() {
            let self=this
          
            var auth={};
            	    	auth = {  
                      };
        
                   this.cleanpin()
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                 this.loading=true;
            let api= baseurl+"user/swap/get_allswap_method_list.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.loading=false;
                    self.swapcurrencies_list = user_info.userdata;
                  
        		}
            })
        },
        getAllBanks: async function() {
            let self=this
            let api=baseurl+"user/systems/getAllBanks.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.banks = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        setCurrentTrans:async function(index){
            this.current_transaction = this.usertransaction[index];
        },
        getStatus: function(index,iscrypto=0,isexcconf=0){
            var status="";
            //<!-- status 0- pending, 1- successful, 2- in wallet, 3- Cancled , 4- Scam flagged-->
            if(index==0){
                status="Pending";
            }else if(index==1){
                if(iscrypto==1){
                     status="Confirmed";
                }else{
                     status="Successful";
                }
            }else if(index==2){
              
                 if(iscrypto==1&&isexcconf==0){
                     status="Incoming";
                }else{
                     status="Processing";
                }
            }else if(index==3){
                status="Canceled";
            }else if(index==4){
                status="Scam flagged";
            }else if(index==5){
                status="Awaiting Approval";
            }else if(index==6){
                status="Reversed";
            }
            return status;
        },
        activateProfileTab(){
        if(getAllUrlParams().tab!=undefined){
            if(getAllUrlParams().tab=='3'){
               this.currentTab=3
            }
            if(getAllUrlParams().tab=='6'){
               this.currentTab=6
            }
        }
        },
       
       
        //Withdrawal Methods 
        payWithNGNUsername: async function (){
            this.error = null;
            var pin= $('.acpin1').val()+''+$('.acpin2').val()+''+$('.acpin3').val()+''+$('.acpin4').val();
            let self = this;
            const auth = {  
                username: this.withdrawal_username,
                amttopay: this.withdrawal_amount,
                type:this.deposit_method,
                currency:this.deposit_currency,
                wallettrackid:this.wallettrackid,
                bankid:this.bankid,
                pin:pin,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            if (parseInt(this.userwallet_by_trackID.walletbal) >= parseInt(this.withdrawal_amount)){
                if (this.username == this.withdrawal_username || this.email == this.withdrawal_username){
                    self.stopLoading();
                    self.error = "Cannot Intiate withdrawal, Username Cannot be the same as sender's";
                }
                else{  
                    var userdat = self.withdrawal_username ? self.withdrawal_username : 'account number' 
                    
                    Swal.fire({
                      title: "Are you sure?",
                      text: 'You are about to transfer '+self.userwallet_by_trackID.name +' '+self.withdrawal_amount+' to '+userdat,
                      icon: "warning",
                      buttons: true,
                      showCancelButton: true,
                      confirmButtonText: 'Yes, Send it!',
                      cancelButtonText: 'No, cancel!',
                    })
                    .then((result) => {
                      if (result.isConfirmed) {
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/transaction/addTransaction.php",form_data,{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    self.withdrawal_username="";
                    		    self.withdrawal_amount="";
                    		    self.userbanks = response.data.data.userdata;
                    		    new toastr.success(response.data.text, "Success", {duration:1000});
                    		    self.getUserWalletsByTrackID();
                    		    self.getUserTransaction();
                                // $('.btn-close').click();
                                
                                     $('#exampleModalToggle').modal('hide');
                                $('#WithdrawUsername').modal('hide');
                                $('#offcanvasRight').offcanvas('hide')
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 405){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 500){
                                        self.error = error.response.data.text
                                    }
                                }else{
                                    self.error = error.message || "Error processing request"
                                    Swal.fire(this.error);
                                }
                            });
                      } else {
                        Swal.fire("Transaction Cancelled !");
                      }
                    });
                }
            }
            else{
                self.stopLoading();
                self.error = "Insufficient Amount, Cannot Intiate withdrawal";
            }
        },
        //Crypto
        sendSwapCrypto: async function (){
            this.error = null;
            let self = this;
            const auth = {  
                username: this.withdrawal_username,
                amttopay: this.withdrawal_amount,
                type:this.deposit_method,
                currency:this.deposit_currency,
                wallettrackid:this.subwalletidtosend,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            if (parseInt(this.subwalletusdamt) >= parseInt(this.withdrawal_amount)){
                if (this.username == this.withdrawal_username || this.email == this.withdrawal_username){
                    self.stopLoading();
                    self.error = "Cannot Intiate withdrawal, Username Cannot be the same as sender's";
                }
                else{  
                    var popmsg="";
                    var confirmtext="";
                    var userdat = self.withdrawal_username ? self.withdrawal_username : 'account number' 
                    if(this.deposit_method==1){
                        confirmtext="Yes, Send it!";
                        popmsg='You are about to transfer '+self.cryptocoinfirstname +' '+self.withdrawal_amount+' to '+userdat
                    }else{
                        confirmtext='Yes, Swap it!';
                         popmsg='You are about to swap '+self.cryptocoinfirstname +' '+self.withdrawal_amount
                    }
                    Swal.fire({
                      title: "Are you sure?",
                      text: popmsg,
                      icon: "warning",
                      buttons: true,
                      showCancelButton: true,
                      confirmButtonText: confirmtext,
                      cancelButtonText: 'No, cancel!',
                    })
                    .then((result) => {
                      if (result.isConfirmed) {
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/transaction/sendswapcrypto.php",form_data,{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    self.withdrawal_username="";
                    		    self.withdrawal_amount="";
                    		    new toastr.success(response.data.text, "Success", {duration:1000});
                    		       var pathname=window.location.pathname.replace(/\/\//g, "/")
                                    if(pathname.includes('/dashboard/subwallet-withdrawal')){
                                        self.redirectURL("subwallet_details")
                                    }else{
                                    self.getUserWalletsByTrackID();
                                    self.getUserSubWallet();
                                    self.getUserTransaction();
                                    }
                                    
                    		       $('#SendCoinToUserName').modal('hide');
                    		           $('#swapCoin').modal('hide');
                    		       
                                $('#WithdrawUsername').modal('hide');
                                $('#cryptoSendCoin2').offcanvas('hide')
                                  $('#cryptoSwapCoin2').offcanvas('hide')
                                
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 405){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 500){
                                        self.error = error.response.data.text
                                    }
                                }else{
                                    self.error = error.message || "Error processing request"
                                    Swal.fire(this.error);
                                }
                            });
                      } else {
                        Swal.fire("Transaction Cancelled !");
                      }
                    });
                }
            }
            else{
                self.stopLoading();
                self.error = "Insufficient Amount, Cannot Intiate withdrawal";
            }
        },
        swapcoinrate:function(){
            this.toswapcoinusdvalue=this.computedScore(this.subwalletlivevalue * this.withdrawal_amount)
            this.toswapnairavalue=this.computedScore(this.subwalletlivevalue * this.withdrawal_amount * this.subwalletnairarate);
        },
        sendcryptomodal(){
            $('#ViewSubWallet').modal('hide');
            $('#ViewSubWalletSwap').modal('hide');
             $('#ViewSubWalletSend').modal('hide');
            
            
        },
        getActiveCryptoMethods: async function(){
            let self=this
            let api=baseurl+"user/pricechart/getallactivecryptomethod.php?search="+this.searchcurrency
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.cryptocurrencylist = user_info.userdata;
        		     if(self.cryptocurrencylist.length>0){
        		    self.activecrypto = user_info.userdata[0];
        		     }
        		}
            });
        },
        getActiveExchangeCryptoMethods: async function(){
            let self=this
            let api=baseurl+"user/exchange/getallcryptocurrency.php?search="+this.searchcurrency
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.cryptocurrencylist = user_info.userdata;
        		    if(self.cryptocurrencylist.length>0){
        		    self.activecrypto = user_info.userdata[0];
        		    }
        		}
            });
        },
        getCoinAddresses: async function(){
                   let self = this;
             this.loadingsidebar=true;
            const auth = {  
                cryptotrackid: this.cryptotrackid,
                currency:getAllUrlParams().currency
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/currency/getUserCoinAddress.php",form_data,{headers}).then(function(response){
        	    if (!response.data.status) {
        	    	Swal.fire("Fetching Coin Address Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    
        		    let user_info = response.data.data;
        		    self.cryptocurrencyaddress = user_info.userdata;
        		    self.loadingsidebar=false;
        		}
            });
        },
        generateCoinAddress: async function(){
                   let self = this;
             this.loadingsidebar=true;
            const auth = {  
                cryptotrackid: this.cryptotrackid,
                currency:getAllUrlParams().currency,
                addressname:this.addressname
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/transaction/generatecryptoAddress.php",form_data,{headers}).then(function(response){
        	    if (!response.data.status) {
        	    	Swal.fire("Coin Address Generation Failed", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
                      this.loadingsidebar=false;
        		} else {
        		       $('#newAddress').modal('hide');
        		    self.error=''
        		    let user_info = response.data.data;
        		    self.cryptocurrencyaddress = user_info.userdata;
        		    self.loadingsidebar=false;
        		}
            }).catch(function (error) {
                        self.loadingsidebar=false;
                            if (error.response){
                                if (error.response.status === 400){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 405){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 500){
                                    self.error = error.response.data.text
                                }
                               new toastr.error(self.error, "Error!", {duration:1000});
                            }else{
                                self.error = error.message || "Error processing request"
                                Swal.fire(self.error);
                            }
                        });
        },
        
        //NGN DEPOSIT
        initiateTransaction: async function(){
            this.error = null;
             this.loading=true;
            let self = this;
            const auth = {  
                amount: this.recieve_amount,
                type:this.deposit_method,
                currency:this.deposit_currency,
                email:this.email,
                firstname:this.firstname,
                lastname:this.lastname,
                phone:this.phone,
                wallettrackid:this.wallettrackid
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            if (parseInt(this.recieve_amount) > parseInt(this.deposit_min)){
                if (this.recieve_amount == ''){
                    self.error = "Cannot Intiate Deposit";
                     self.stopLoading();
                }
                else{
                    this.loading=true;
                    var headers={'Authorization': "Bearer "+ this.accesstoken}
                    await axios.post(baseurl+"user/transaction/initiateDeposit.php",form_data,{headers}).then(function(response){
                        self.stopLoading();
                	    if (!response.data.status) {
                	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                ////this.logout();
                            });
                		} else {
                		    let user_info = response.data.data;
                		    self.redirectURL(user_info.redirect_url);
                		}
                    }).catch(function (error) {
                        self.stopLoading();
                            if (error.response){
                                if (error.response.status === 400){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 405){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 500){
                                    self.error = error.response.data.text
                                }
                                Swal.fire(self.error);
                            }else{
                                self.error = error.message || "Error processing request"
                                Swal.fire(this.error);
                            }
                        });
                }
            }
            else{
                self.stopLoading();
                self.error = "Insufficient Amount, Can Only Deposit a minimum of "+self.deposit_min;
            }
        },
        verifyPayments: async function(){
            self = this;
            if(getAllUrlParams().trxref){
                const auth = {  
                    ref: getAllUrlParams().trxref,
                    type:1
                };
                var form_data = new FormData();
                for (var key in auth) {
                    form_data.append(key, auth[key]);
                }
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                await axios.post(baseurl+"user/transaction/verifyPayments.php",form_data,{headers}).then(function(response){
                    self.stopLoading();
            	    if (!response.data.status) {
            	      new toastr.error(response.data.text, "Error", {duration:3000});
            		} else {
            		    let user_info = response.data.data;
            		    new toastr.success(response.data.text, "Success", {duration:3000});
            		    self.getUserWallets();
            		    self.getUserTransaction();
            		           setTimeout(
                        function () {
                            self.redirectURL(mainurl+'dashboard/index.php')
    },
                         3000);
            		}
                }).catch(function (error) {
                        self.stopLoading();
                            if (error.response){
                                if (error.response.status === 400){
                                    self.error = error.response.data.text
                                        new toastr.error(error.response.data.text, "Error", {duration:3000});
                                                setTimeout(
                        function () {
                            self.redirectURL(mainurl+'dashboard/index.php')
    },
                         3000);
                                }
                                if (error.response.status === 405){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 500){
                                    self.error = error.response.data.text
                                }
                            }else{
                                self.error = error.message || "Error processing request"
                                Swal.fire(this.error);
                            }
                        });
            }
            else if(getAllUrlParams().transref){
                const auth = {  
                    ref: getAllUrlParams().transref,
                    type:3
                };
                var form_data = new FormData();
                for (var key in auth) {
                    form_data.append(key, auth[key]);
                }
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                await axios.post(baseurl+"user/transaction/verifyPayments.php",form_data,{headers}).then(function(response){
                    self.stopLoading();
            	    if (!response.data.status) {
            	    	  new toastr.error(response.data.text, "Error", {duration:1000});
            		} else {
            		    let user_info = response.data.data;
            		    new toastr.success(response.data.text, "Success", {duration:1000});
            		    self.getUserWallets();
            		    self.getUserTransaction();
            		    self.redirectURL(mainurl+'dashboard/index.php');
            		}
                }).catch(function (error) {
                        self.stopLoading();
                            if (error.response){
                                if (error.response.status === 400){
                                         self.error = error.response.data.text
                                        new toastr.error(error.response.data.text, "Error", {duration:3000});
                                                setTimeout(
                        function () {
                            self.redirectURL(mainurl+'dashboard/index.php')
    },
                         3000);
                                }
                                if (error.response.status === 405){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 500){
                                    self.error = error.response.data.text
                                }
                            }else{
                                self.error = error.message || "Error processing request"
                                Swal.fire(this.error);
                            }
                        });
            }
        },
        
        //Prices 
        getNews: async function() {
            let self=this
            var auth = {  
                cryptotag:this.activecryptoname
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/pricechart/getCryptonews.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.news = user_info.userdata.Data;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getCryptodata: async function() {
            let self=this
            var auth = {  
                cryptotag:this.activecryptoname,
                cryptototag:this.activecryptotoname
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/pricechart/getCryptodata.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.cryptodata = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getCryptocharts: async function() {
            let self=this
            var auth = {  
                cryptotag:this.activecryptoname,
                cryptototag:this.activecryptotoname,
                time:this.activecryptotime,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/pricechart/getCryptocharts.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    self.yAxis = [];
        		    self.xAxis = [];
        		    $('#chart').empty();
        		    let user_info = response.data.data;
        		    self.cryptocharts = user_info.userdata;
        		    
        		    for (let i = 0; i < self.cryptocharts.Data.length; i++) {
                      self.yAxis.push(self.cryptocharts.Data[i].close);
                      self.xAxis.push(self.cryptocharts.Data[i].time*1000);
                    }
                    
                    var options = {
                      series: [{
                      name: "",
                      data: self.yAxis
                    }],
                      chart: {
                          type: 'area',
                          height: 350,
                            zoom: {
                                enabled: true
                            }
                        },
                        dataLabels: {
                           enabled: false
                        },
                        stroke: {
                           curve: 'straight'
                        },
                        
                        title: {
                          text: '',
                          align: 'left'
                        },
                        labels: self.xAxis,
                        xaxis: {
                          type: 'datetime',
                           labels: {
                              show: true,
                              datetimeUTC: true,
                              datetimeFormatter: {
                                  hour: 'HH:mm',
                              },
                          },
                        },
                        legend: {
                          horizontalAlign: 'left'
                        }
                        
                    };
            
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                  
                  
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        togglepricecurrency: async function(){
            let self=this
            let api=baseurl+"user/pricechart/getallactivecryptomethod.php?type="+this.activecryptoname
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.activecrypto = user_info.userdata[0];
        		}
        		
            });
            self.getNews();
            self.getCryptodata();
            self.getCryptocharts();
        },
        togglepricespeccurrency: async function(id){
            let self=this
            let api=baseurl+"user/pricechart/getallactivecryptomethod.php?type="+id
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.activecrypto = user_info.userdata[0];
        		}
        		
            });
        },
        
        //Peerstack 
        getPeerstackRecieveMerchants: async function() {
            let self=this
            var auth = {
                amount:  this.recieve_amount
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/peerstack/get_deposit_merchants.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.recieve_merchants = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getPeerstackWithdrawalMerchants: async function() {
            let self=this
            var auth = {  
                amount:  this.withdrawal_amount
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/peerstack/get_withdrawal_merchants.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Error", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.withdrawal_merchants = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning").then(function(){
                               self.logout();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        setPeerstackIndex: async function(index,amount){
            this.current_merchant = this.recieve_merchants[index];
            this.current_amount = amount
        },
        setPeerstackIndex2: async function(index,amount){
            this.current_merchant = this.withdrawal_merchants[index];
            this.current_amount = amount
        },
        peerstackDeposit(){
             // store cureency name
            localStorage.setItem('peerstackdepositamt',this.recieve_amount)
            localStorage.setItem('peerstackdepositWalltid',this.wallettrackid)
            localStorage.setItem('peerstackdepositCurrency',this.deposit_currency)
            localStorage.setItem('peerstackdepositMethod',this.deposit_method)
            // save wallet and currency tag
            this.redirectURL('peerstack_recieve')
        },
        savePeerStackWithdrawBank(){
             this.peerStackWithdrawbank=this.userbanks[this.bankid]
        },
        peerstackWithdraw(){
             // store cureency name
            //  save bank
            localStorage.setItem('peerstackWithdrawalamt',this.withdrawal_amount)
            localStorage.setItem('peerstackWithdrawalWalltid',this.wallettrackid)
            localStorage.setItem('peerstackWithdrawalCurrency',this.deposit_currency)
            localStorage.setItem('peerstackWithdrawalMethod',this.deposit_method)
            localStorage.setItem('peerStackSelectedbnk', JSON.stringify(this.peerStackWithdrawbank));
            // save wallet and currency tag
            this.redirectURL('peerstack_withdrawal')
        },
        getPeerStackAmount(){
                this.recieve_amount=localStorage.getItem('peerstackdepositamt');
        },
        getPeerStackWithdrawAmount(){
            this.withdrawal_amount=localStorage.getItem('peerstackWithdrawalamt');
            this.peerStackWithdrawbank= JSON.parse(localStorage.getItem('peerStackSelectedbnk'));
        },
        peerStackWithdrawal: async function (){
            this.error = null;
            let self = this;
            const auth = {  
                username: "1No_eopeNoenoueoiIUdkeiosuykj",
                amttopay: this.withdrawal_amount,
                bankid:this.peerStackWithdrawbank.id,
                merchant_id:this.current_merchant.merchant_trackid,
                type:localStorage.getItem('peerstackWithdrawalMethod'),
                currency:localStorage.getItem('peerstackWithdrawalCurrency'),
                wallettrackid:localStorage.getItem('peerstackWithdrawalWalltid')
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/transaction/addTransaction.php",form_data,{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                                let orderid = response.data.data[0].orderid;
                                let amttoget = response.data.data[0].amttoget;
                                // save merchantdata 
                                localStorage.setItem('peerMerchantData', JSON.stringify(self.current_merchant));
                                localStorage.setItem('peerTransaref', orderid);
                                // get ref to show in next page
                                localStorage.setItem('peerstackdepositamt',amttoget)
                    		     
                                self.redirectURL('./peerstack_withdrawal_confirmation.php')
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                         new toastr.error( error.response.data.text, "Error", {duration:1000});
                                    }
                                    if (error.response.status === 405){
                                         new toastr.error( error.response.data.text, "Error", {duration:1000});
                                    }
                                    if (error.response.status === 500){
                                         new toastr.error( error.response.data.text, "Error", {duration:1000});
                                    }
                                }else{
                                    self.error = error.message || "Error processing request"
                                     new toastr.error( self.error, "Error", {duration:1000});
                                }
                            });
        },
        movePeerStackTosummary(){
            // generate ytransaction with ref
            //  send to api
            this.error = null;
            this.loading=true;
            let self = this;
            // user details is gotten from get user details function
             // get from local storage currency and wallet trackid
            const auth = {  
                amount: this.recieve_amount,
                email:this.email,
                firstname:this.firstname,
                lastname:this.lastname,
                phone:this.phone,
                merchant_id:this.current_merchant.merchant_trackid,
                type:localStorage.getItem('peerstackdepositMethod'),
                currency:localStorage.getItem('peerstackdepositCurrency'),
                wallettrackid:localStorage.getItem('peerstackdepositWalltid')
                
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            // if (parseInt(this.recieve_amount) > parseInt(this.deposit_min)){
                if (this.recieve_amount == ''){
                    self.error = "Cannot Intiate Deposit";
                     self.stopLoading();
                }
                else{
                    this.loading=true;
                    var headers={'Authorization': "Bearer "+ this.accesstoken}
                     axios.post(baseurl+"user/transaction/initiateDeposit.php",form_data,{headers}).then(function(response){
                        self.stopLoading();
                	    if (!response.data.status) {
                	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                ////this.logout();
                            });
                		} else {
                		    let orderid = response.data.data[0].orderid;
                            // save merchantdata 
                            localStorage.setItem('peerMerchantData', JSON.stringify(self.current_merchant));
                            localStorage.setItem('peerTransaref', orderid);
                            // get ref to show in next page
                            localStorage.setItem('peerstackdepositamt',self.recieve_amount)
                            self.redirectURL('peerstack_recieve_confirmation')
                		}
                    }).catch(function (error) {
                        self.stopLoading();
                            if (error.response){
                                if (error.response.status === 400){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 405){
                                    self.error = error.response.data.text
                                }
                                if (error.response.status === 500){
                                    self.error = error.response.data.text
                                }
                            }else{
                                self.error = error.message || "Error processing request"
                                Swal.fire(this.error);
                            }
                        });
                }
            // }
            // else{
            //     self.stopLoading();
            //     self.error = "Insufficient Amount, Can Only Deposit a minimum of "+self.deposit_min;
            // }  
        },
        getPeerDepositSUmmary(){
             this.recieve_amount=localStorage.getItem('peerstackdepositamt');
             this.peerTransref=localStorage.getItem('peerTransaref');
             this.peerMerchantselected = JSON.parse(localStorage.getItem('peerMerchantData'));
             this.getSingleTrans(this.peerTransref)
        },
        getSingleTrans: async function (transid){
            this.error = null;
            let self = this;
            const auth = {  
               transid: transid,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            this.loading=true;
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            axios.post(baseurl+"user/transaction/gettransdetails.php",form_data,{headers}).then(function(response){
                self.stopLoading();
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.current_transaction = user_info.userdata[0]; 
        		}
            }).catch(function (error) {
                self.stopLoading();
                    if (error.response){
                        if (error.response.status === 400){
                             new toastr.error( error.response.data.text, "Error", {duration:1000});
                        }
                        if (error.response.status === 405){
                             new toastr.error( error.response.data.text, "Error", {duration:1000});
                        }
                        if (error.response.status === 500){
                             new toastr.error( error.response.data.text, "Error", {duration:1000});
                        }
                    }else{
                        self.error = error.message || "Error processing request"
                         new toastr.error( self.error, "Error", {duration:1000});
                    }
                });
        },
        timerPad(val){
            var valString = val + "";
            if (valString.length < 2) {
                    return "0" + valString;
            } else {
                return valString;
            }
        },
        startTimerCount(){
            var totalSeconds = 1800; 
            var self=this
            self.peerTimeinterval= setInterval(function () {
                var minutesLabel = document.getElementById("minutes");
                var secondsLabel = document.getElementById("seconds");
                --totalSeconds;
                if(parseInt(totalSeconds / 60)>=0&&totalSeconds % 60>=0){
                    if(secondsLabel!=undefined){
                    secondsLabel.innerHTML = self.timerPad(totalSeconds % 60);
                    minutesLabel.innerHTML = self.timerPad(parseInt(totalSeconds / 60));
                    }
                }else{
                    // cancel transaction
                    self.checkPeer_trans_SuccessNoti(1)
                }
                
            },1000)
        },
        contineousPeer_trans_SuccessNoti(){
             let self=this
          self.peerTimeinterval= setInterval(function () {
           
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {  
               orderid:self.peerTransref,
               trastype:1,
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
                if (!response.data.status) {
                }else {
                    toastr.clear()
                          // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    // toastr.success(response.data.text,{ui:"is-dark"});
                    Swal.fire( {icon: 'success',title:"Success",text:response.data.text});
                        setTimeout(
                        function () {
                            self.redirectURL(mainurl+'dashboard/index.php')
    },
                         3000);
                    
                    
                    
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   Swal.fire("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                        //   Swal.fire("Error!", "Unauthorised", "error");
                           self.logout();
                    }
                }
            })
                 },2000)
        },
        checkPeer_trans_SuccessNoti(fromtime=0){
             let self=this
           self.internalLoading=true
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {   
               orderid:self.peerTransref,
                  trastype:1,
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
         
            axios.post(api,form_data,{headers}).then(function (response) {
                if (!response.data.status) {
                   if(fromtime==1){
                        self.timeup=true
                        // stop timer
                        clearInterval(self.peerTimeinterval);
                        clearInterval(self.peerSuccessTimer);
                        // call cancle trans function
                        self.cancle_peertrans()
                    }
                     toastr.error(response.data.text,{ui:"is-dark"});
                     self.internalLoading=false
                }else {
                    self.internalLoading=false
                    toastr.clear()
                          // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    // toastr.success(response.data.text,{ui:"is-dark"});
                    Swal.fire( {icon: 'success',title:"Success",text:response.data.text});
                        setTimeout(
                        function () {
                            self.redirectURL(mainurl+'dashboard/index.php')
    },
                         3000);
                    
                    
                    
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   Swal.fire("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                        //   Swal.fire("Error!", "Unauthorised", "error");
                           self.logout();
                    }
                }
            })
        },
        ihvsentPeerStack(){
            let self=this
            this.contineousPeer_trans_SuccessNoti()
          self.internalLoading=true
             let api=baseurl+"user/notifications/ihvsentit.php";
             var auth = {   
              orderid:self.peerTransref,
              merchant_trackid:self.peerMerchantselected.merchant_trackid,
           };
           var headers={'Authorization': "Bearer "+ self.accesstoken}
           var form_data = new FormData();
           for (var key in auth) {
               form_data.append(key, auth[key]);
           }
        
           axios.post(api,form_data,{headers}).then(function (response) {
               if (!response.data.status) {
                  if(fromtime==1){
                       self.timeup=true
                       // stop timer
                       clearInterval(self.peerTimeinterval);
                       clearInterval(self.peerSuccessTimer);
                       // call cancle trans function
                       self.cancle_peertrans()
                   }
                    toastr.error(response.data.text,{ui:"is-dark"});
                    self.internalLoading=false
               }else {
                   self.internalLoading=false
                  self.ihvsentclick=true
                  self.getSingleTrans(self.peerTransref)
                   Swal.fire( {icon: 'info',title:"",text:response.data.text});
               }
           }).catch(function (error) {
               if (error.response) {
                  
                   if(error.response.status==400){
                        self.logout();
                   }else if(error.response.status==500){
                       //   Swal.fire("Error!", "Server error try again later", "error");
                       //       self.error="Server error try again later"
                   }else if(error.response.status==401){
                       //   Swal.fire("Error!", "Unauthorised", "error");
                          self.logout();
                   }
               }
           })
       },
        cancle_peertrans(selfclick=0){
            let self=this
          	let api=baseurl+"user/peerstack/cancle_peerstack_trans.php";
          	var auth = {  
               orderid:this.peerTransref,
            };
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            axios.post(api,form_data,{headers}).then(function (response) {
                // if (!response.data.status) {
                // }else {
                         // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    self.getSingleTrans(self.peerTransref)
                    toastr.clear()
                    self.timeup=true;
                    window.location.reload();
               
                    if(selfclick==1){
                        self.redirectURL(mainurl+'dashboard/index.php')
                    // }
                    // toastr.error(response.data.text,{ui:"is-dark"});
                }
            }).catch(function (error) {
               
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   Swal.fire("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                        //   Swal.fire("Error!", "Unauthorised", "error");
                           self.logout();
                    }
                }
            })
        },
        verifyPinPeerstack(){
            this.error = null;
            this.loading=true;
            let self = this;
            var pin = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            const auth = {pin};
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
         
                    this.loading=true;
                    var headers={'Authorization': "Bearer "+ this.accesstoken}
                    axios.post(baseurl+"user/auth/verifypin.php",form_data,{headers}).then(function(response){
                        self.stopLoading();
                	    if (!response.data.status) {
                	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                ////this.logout();
                            });
                		} else {
                		    let user_info = response.data.data;
                		     new toastr.success( response.data.text, "Success", {duration:1000});
                		     self.peerStackWithdrawal()
                		}
                    }).catch(function (error) {
                        self.stopLoading();
                            if (error.response){
                                if (error.response.status === 400){
                                       new toastr.error( error.response.data.text, "Error", {duration:1000});
                                }
                                if (error.response.status === 405){
                                     new toastr.error( error.response.data.text, "Error", {duration:1000});
                                }
                                if (error.response.status === 500){
                                     new toastr.error( error.response.data.text, "Error", {duration:1000});
                                }
                            }else{
                                self.error = error.message || "Error processing request"
                                 new toastr.error( self.error, "error", {duration:1000});
                            }
                        });
            },
        
        
        // kyc
        getuserkycdata(load=1){
            let self=this
            if(load==1){
            // this.startLoading()
            } 

            let api=baseurl+"user/kyc/userkyc.php";
            	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    	if (!response.data.status) {
        	    	self.error=response.data.text
        		} else {
    		      //  self.stopLoading()
    		        self.error=""
    		        var alldata=response.data.data[0];
                        self.kycdata =alldata
        		}
            }).catch(function (error) {
                    //  self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                            //Swal.fire("Error!", error.response.data.text, "error");
                            //  self.error=error.response.data.text
                        }else if(error.response.status==500){
                           //Swal.fire("Error!", "Server error try again later", "error");
                            //   self.error="Server error try again later"
                        }else if(error.response.status==401){
                           //Swal.fire("Error!", "Unauthorised", "error");
                           self.logout()
                        }
                    }
            })
        },
        getcompletebvn(load=1){
            let self=this
            var pin = $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            const auth = {pin};
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            $('.change_pin').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
            axios.post(baseurl+"user/kyc/get_bvn.php",form_data,{headers}).then(function(response){
                $('.change_pin').html('Submit');
        	    if (!response.data.status) {
        	    	self.error=response.data.text
        		} else {
                    self.kycdata.bvn = response.data.data[0].bvn;
                    $('#staticBackdrop-security-pin').modal('hide');
                    self.pin1 = self.pin2 = self.pin3 = self.pin4 = '';
                    self.showeye = false;
                    new toastr.success(response.data.text, "Success", {duration:1000});
                };
            }).catch(function (error) {
                    $('.change_pin').html('Submit');
                    if (error.response) {
                        if(error.response.status==400){
                            //Swal.fire("Error!", error.response.data.text, "error");
                             self.error=error.response.data.text
                             new toastr.error(self.error, "Error", {duration:1000});
                        }else if(error.response.status==500){
                           //Swal.fire("Error!", "Server error try again later", "error");
                               self.error="Server error try again later"
                        }else if(error.response.status==401){
                           //Swal.fire("Error!", "Unauthorised", "error");
                           self.logout()
                        }
                    }
            })
        },
        verifyKYC: async function() {
            const auth = {  
                type:this.bvnverifytyp,
                bvn:this.bvn,
                otp:this.bvnotp,
                fname:this.bvnfname,
                lname:this.bvnlname,
                pno:this.bvnpno,
                dob:this.bvndob,
                method:this.bvnmethodis
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            if ( this.bvn == ''){
                $('.verifykyc3').text('Submit');
                new toastr.error("Please Fill In All Fields", "Incomplete field parameters !");
            }
            else {
                this.error = null;
                var self = this;
                var headers={'Authorization': "Bearer "+ this.accesstoken}
                if(this.bvnmethodis==1){
                               $('.verifykyc2').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                }else if(this.bvnverifytyp==1){
                     $('.verifykyc3').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                }else{
                   $('.verifykyc').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);   
                }
     
                                  
                await axios.post(baseurl+"user/kyc/verify_bvn.php",form_data,{headers}).then(function(response){
                      if(self.bvnmethodis==1){
                         $('.verifykyc2').text('Send OTP Via Whatsapp');
                     }else if(self.bvnverifytyp==1){
                              $('.verifykyc3').text('Submit');
                     }else{
                       $('.verifykyc').text('Verify');   
                     }
                    if (response.data.status == true){
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success", {duration:1000});
                        self.getUserDetails(); 
                        if(self.bvnverifytyp==1&&response.data &&response.data.data &&response.data.data[0] && typeof response.data.data[0].phoneno !== 'undefined'){
                            self.showbvnotp=true;
                            self.bvnverifytyp=2
                            self.bvnpno=response.data.data[0].phoneno
                        }else{
                           $('.btn-close').click(); 
                           location.reload();
                        } 
                    }else{
                         new toastr.error(response.data.text, "Error", {duration:1000});
                    }
                    self.getUserDetails();
                }).catch(function(error){
                     if(self.bvnmethodis==1){
                         $('.verifykyc2').text('Send OTP Via Whatsapp');
                     }else if(self.bvnverifytyp==1){
                              $('.verifykyc3').text('Submit');
                     }else{
                       $('.verifykyc').text('Verify');   
                     }
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                            new toastr.error(this.error, "Error!", {duration:1000});
                        }
                        if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        new toastr.error(this.error, "Error processing request", {duration:1000});
                    }
                })
                
            }
            
        }, 
        submitKycTG(){
            let self=this
          this.loading=true
            let api=baseurl+"user/kyc/userkycform_tg.php";
           	var headers={'Authorization': "Bearer "+ this.accesstoken}

         const auth  = {
            	'regtype':this.regtype,
            	'passimage':this.passportcode,
            	'passimgname':this.passportimgname,
            	'regimage':this.regulationcode,
            	'regimgname':this.regulationimgname,
        	}
           
        	axios.post(api,auth,{headers}).then(function (response) {
        	    	if (!response.data.status) {
        	    	self.stopLoading()
        	    	self.error=response.data.text
        		} else {
        		    self.showform3=false;
        		    self.getUserDetails();
    		        self.stopLoading()
    		        self.error=""
    		        new toastr.success( response.data.text,"Success!", {duration:1000});
        		}
            }).catch(function (error) {
                     self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text, "error");
                             self.error=error.response.data.text
                        }else if(error.response.status==500){
                           Swal.fire("Error!", "Server error try again later", "error");
                               self.error="Server error try again later"
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised", "error");
                           self.logout()
                        }
                    }
            })
        },
        submitKyc(){
            let self=this
          this.loading=true
            let api=baseurl+"user/kyc/userkycform.php";
           	var headers={'Authorization': "Bearer "+ this.accesstoken}

         const auth  = {
            	'facebook':this.fblink,
            	'twitter':this.twitterlink,
            	'insta':this.instalink,
            	'telegram':this.telegramlink,
            	'email':this.loginemail,
            	'country':this.country,
            	'state':this.state,
            	'address':this.address,
            	'biztype':this.biztype,
            	'regtype':this.regtype,
            	'house_number':this.house_number,
            	'reg_id_num':this.reg_id_num,
            	'passimage':this.passportcode,
            	'passimgname':this.passportimgname,
            	'regimage':this.regulationcode,
            	'regimgname':this.regulationimgname,
            	'hregimage':this.holdregimgcode,
            	'hregimgname':this.holdregname,
            	'bizccimage':this.businesscccode,
            	'bizccimgname':this.businessimgname,
            	'dob':this.dob,
        	}
           
        	axios.post(api,auth,{headers}).then(function (response) {
        	    	if (!response.data.status) {
        	    	self.stopLoading()
        	    	self.error=response.data.text
        		} else {
        		    self.showform3=false;
        		    self.getUserDetails();
    		        self.stopLoading()
    		        self.error=""
    		        new toastr.success( response.data.text,"Success!", {duration:1000});
        		}
            }).catch(function (error) {
                     self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text, "error");
                             self.error=error.response.data.text
                        }else if(error.response.status==500){
                           Swal.fire("Error!", "Server error try again later", "error");
                               self.error="Server error try again later"
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised", "error");
                           self.logout()
                        }
                    }
            })
        },
        updateMyVerification(){
            let self=this
          this.loading=true
            let api=baseurl+"user/kyc/userkycform_mini.php";
           	var headers={'Authorization': "Bearer "+ this.accesstoken}

         const auth  = {
            	
            	'regtype':this.regtype,
            	'house_number':this.house_number,
            	'reg_id_num':this.reg_id_num,
            	'regimage':this.regulationcode,
            	'regimgname':this.regulationimgname,
            
        	}
           
        	axios.post(api,auth,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	self.stopLoading()
        	    	self.error=response.data.text
        		} else {
        		    self.showform3=false;
        		    self.getUserDetails();
    		        self.stopLoading()
    		        self.error=""
    		         $('#card-verification-id').modal('hide');
    		        new toastr.success( response.data.text,"Success!", {duration:1000});
    		           Swal.fire("Success!", response.data.text, "success");
        		}
            }).catch(function (error) {
                     self.stopLoading()
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text, "error");
                             self.error=error.response.data.text
                        }else if(error.response.status==500){
                           Swal.fire("Error!", "Server error try again later", "error");
                               self.error="Server error try again later"
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised", "error");
                           self.logout()
                        }
                    }
            })
        },
        onFileChangePassport(e) {
			e.stopPropagation();
			e.preventDefault();
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length) return;
			for (var i = files.length - 1; i >= 0; i--) {
			    this.passportimgname=files[i].name
            // .log(files[i]) image data
			    this.createImage(i, files[i],1);
			}
		},
		onFileChangeBizcc(e) {
			e.stopPropagation();
			e.preventDefault();
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length) return;
			for (var i = files.length - 1; i >= 0; i--) {
			    this.businessimgname=files[i].name
            // .log(files[i]) image data
			    this.createImage(i, files[i],4);
			}
		},
		onFileChangeRegcard(e) {
			e.stopPropagation();
			e.preventDefault();
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length) return;
			for (var i = files.length - 1; i >= 0; i--) {
			    this.regulationimgname=files[i].name
            // .log(files[i]) image data
			    this.createImage(i, files[i],2);
			}
		},
		onFileChangeRegcardf(e) {
			e.stopPropagation();
			e.preventDefault();
			var files = e.target.files || e.dataTransfer.files;
			if (!files.length) return;
			for (var i = files.length - 1; i >= 0; i--) {
			    this.holdregname=files[i].name
            // .log(files[i]) image data
			    this.createImage(i, files[i],3);
			}
		},
		createImage(id, file,type) {
		  //  type 1 = passport,2= regfront 3 reg back 4 biz cc
			var image = new Image()
			var reader = new FileReader()
			reader.onload = (e) => {
				image = {
					file: e.target.result,
					progress: ''
				}
				//log(e.target.result)// base64code
				if(type==1){
				    		this.passportcode=e.target.result;
				}else if(type==2){
				    	this.regulationcode=e.target.result;
				}else if(type==3){
				    	this.holdregimgcode	=e.target.result;
				}else if(type==4){
				    this.businesscccode=e.target.result;
				}		
				
			}
			reader.readAsDataURL(file)
		},
		activatebizimage(){
                  var userselectedtype = this.biztype
                if(userselectedtype==2){
                    document.getElementById("bcc").style.display = "block"; 
                }else{
                    document.getElementById("bcc").style.display = "none"; 
                }
        },
        
        // Exchange
        getActiveExchangeMethods: async function(){
            let self=this
            let api=baseurl+"user/exchange/getactiveexchangemethods.php?search="+this.searchtocurrency
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.exchangecurrencylist = user_info.userdata;
                    self.selectedtocoindata=self.exchangecurrencylist[0]
                    self.breakexchange()
                        
        		}
            });
        },
        breakexchange(){
          var exchnageer=this.selectedtocoindata.joincode.split("|");
          this.exchangesyspayid=exchnageer[1];
          this.exchangesystid=exchnageer[0];
        },
        getLiveValue: async function(){
        let self=this
        let api=baseurl+"user/currency/getCryptoliverate.php?cointid="+this.selectedcoindata.producttrackid
        
        axios.get(api).then(function (response) {
            if (!response.data.status) {
        	} else {
        	    
        	    let user_info = response.data.data;
        	    self.livevalue=user_info.livevalue;
        	    
        	}
        });
        },
        selectExchangeBank(index){
            if(this.exchangesyspayid==1){
                this.exchangebankdata=this.userbanks[index]
            }
            // set active
            this.exchangePayMethod=index
        },
        proceedToExchangeAddress(){
            var self=this
            // check if bank is selected, check if currency and coin is selected
            if(this.exchangesyspayid==0){
                 Swal.fire( {icon: 'error',title:"Error",text:"Please select currency"});
                return
            }
            if(this.amounttosell==0||this.amounttosell==''){
                 Swal.fire( {icon: 'error',title:"Error",text:"Input amount to sell"});
                return
            }
            if(this.selectedcoindata.producttrackid==''){
                  Swal.fire( {icon: 'error',title:"Error",text:"Please select assets"});
                return
            }
            if(this.exchangebankdata==''){
                Swal.fire( {icon: 'error',title:"Error",text:"Select payment method"});
                return
            }
            // generate address
            //  store data in local and then nav to next page
            // generate address
             this.loading=true;
            const auth = {  
               exchangetid: this.exchangesystid,
               paymentid:this.exchangebankdata.id,
               currencytid:this.selectedcoindata.producttrackid,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            var headers={'Authorization': "Bearer "+ this.accesstoken}
            axios.post(baseurl+"user/exchange/generate_exchange_address.php",form_data,{headers}).then(function(response){
        	    if (!response.data.status) {
        	    	 Swal.fire( {icon: 'error',title:"Coin Address Generation Failed",text:response.data.text}).then(function(){
                    //   self.logout();
                    });
                      self.loadingsidebar=false;
        		} else {
        		    let user_info = response.data.data.userdata[0];
        		    
                       // store userdata
                    localStorage.setItem('userexchange_data',JSON.stringify(user_info))
                    //   store bank/payment data
                    if(self.exchangesyspayid==1){
                    localStorage.setItem('exchangeBank', JSON.stringify(self.exchangebankdata));
                    }
                    
                    localStorage.setItem('amounttosell',self.amounttosell)
                    localStorage.setItem('selectedtocoindata', JSON.stringify(self.selectedtocoindata));
                    localStorage.setItem('selectedcoindata', JSON.stringify(self.selectedcoindata));
                    
                    
                    self.redirectURL('exchange_details')
            
        		    self.loading=false;
        		}
            });
            
           
        },
        getAllExchangeData(){
            // get exchange payment method
            this.exchangebankdata = JSON.parse(localStorage.getItem('exchangeBank'));
            // get user exchnage data
            this.exchangeuser_data=JSON.parse(localStorage.getItem('userexchange_data'));
            this.amounttosell=localStorage.getItem('amounttosell');
            this.selectedcoindata = JSON.parse(localStorage.getItem('selectedcoindata'));
            this.selectedtocoindata = JSON.parse(localStorage.getItem('selectedtocoindata'));
        },
        contineousExchange_trans_SuccessNoti(){
             let self=this
          self.peerTimeinterval= setInterval(function () {
           
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {  
               orderid:self.exchangeuser_data.address,
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
                if (!response.data.status) {
                }else {
                    toastr.clear()
                          // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    // toastr.success(response.data.text,{ui:"is-dark"});
                    Swal.fire( {icon: 'success',title:"Success",text:response.data.text});
                    self.sorttransttype=4;
                    self.getUserTransaction();
                    self.getUserDetails(); 
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   Swal.fire("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                        //   Swal.fire("Error!", "Unauthorised", "error");
                           self.logout();
                    }
                }
            })
                 },2000)
        },
        checkExchange_trans_SuccessNoti(){
             let self=this
           self.internalLoading=true
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {  
              orderid:self.exchangeuser_data.address,
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
                if (!response.data.status) {
                     toastr.error(response.data.text,{ui:"is-dark"});
                     self.internalLoading=false
                }else {
                    self.internalLoading=false
                    toastr.clear()
                          // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    // toastr.success(response.data.text,{ui:"is-dark"});
                    Swal.fire( {icon: 'success',title:"Success",text:response.data.text});
                     self.sorttransttype=4;
                    self.getUserTransaction();
                    self.getUserDetails(); 
                    
                    
                    
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   Swal.fire("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                        //   Swal.fire("Error!", "Unauthorised", "error");
                           self.logout();
                    }
                }
            })
        },
        // get exchnage summary from external
        getExchangeSUmmary(){
                this.amounttosell=localStorage.getItem('amounttosell');
                this.selectedcoindata = JSON.parse(localStorage.getItem('selectedcoindata'));
               
                
                this.selectedtocoindata = JSON.parse(localStorage.getItem('selectedtocoindata'));
                
                this.getLiveValue()
                this.breakexchange
        },
        
        // subwallet flows
        moveToSubWalletPage(){
            localStorage.setItem('wallet_trackid',this.wallettrackid)
            localStorage.setItem('main_wallet',getAllUrlParams().currency)
            this.redirectURL('all_subwallet')
        },
        getSubwalletCoinValue: async function() {
            let self=this
            var sendWallegttrackid=localStorage.getItem('wallet_trackid');
            this.wallettrackid=sendWallegttrackid
            let api= baseurl+"user/currency/getUserWalletInfobyTrackID.php?tag="+sendWallegttrackid;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        	axios.get(api,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.userwallet_by_trackID = user_info.userdata
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        searchForACoin(){
            this.showsubwalletusd=false
            this.getUserSubWalletList()
        },
        toggleSubwalletUSD: async function() {
            this.showsubwalletusd=!this.showsubwalletusd
            this.getUserSubWalletListUsd()
        },
        getUserSubWalletList: async function() {
            let self=this
            var sendWalletTag=localStorage.getItem('main_wallet');
            let api= baseurl+"user/wallet/user_subwallet_list.php";
          	var auth = {  
              currency:sendWalletTag,
              search:this.subwalletsearch
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.subwalletdata = user_info.userdata
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        getUserSubWalletListUsd: async function() {
            let self=this
            var sendWalletTag=localStorage.getItem('main_wallet');
            let api= baseurl+"user/wallet/user_subwallet_list_with_usd.php";
          	var auth = {  
              currency:sendWalletTag,
              search:this.subwalletsearch
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.subwalletdata = user_info.userdata
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        generateUserSubWallet: async function() {
            let self=this
            var sendWalletTag=localStorage.getItem('main_wallet');
            let api= baseurl+"user/wallet/generate_user_subwallet.php";
          	var auth = {  
              currency:sendWalletTag,
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = false;
        		} else {
        		    self.loading = false;
        		    self.getUserSubWalletList();
        		    
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        moveToSingleSubWalletPage(trackid){
            localStorage.setItem('subwallet_trackid',trackid)
            this.redirectURL('subwallet_details')
        },
        getSingleUserSubWalletList: async function() {
            let self=this
            var sendWalletTag=localStorage.getItem('main_wallet');
            var subwalletTid = localStorage.getItem('subwallet_trackid');
            this.subwallettrackid=subwalletTid;
            let api= baseurl+"user/wallet/user_subwallet_list_with_usd.php";
          	var auth = {  
              currency:sendWalletTag,
              subtid:subwalletTid
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.subwalletdata = user_info.userdata[0]
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        getSingleUserSubWalletListUsd: async function() {
           let self=this
            var sendWalletTag=localStorage.getItem('main_wallet');
            var subwalletTid = localStorage.getItem('subwallet_trackid');
            let api= baseurl+"user/wallet/user_subwallet_list_with_usd.php";
          	var auth = {  
              currency:sendWalletTag,
              subtid:subwalletTid
            };
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data,{headers}).then(function (response) {
        	    self.loading = true;
        	    if (!response.data.status) {
        	        self.loading = true;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.subwalletdata = user_info.userdata[0]
        		}
            }).catch(function (error) {
                    self.loading = true;
                    if (error.response) {
                        if(error.response.status==400){
                            new toastr.error(error.response.data.text, "Not Found");
                        }else if(error.response.status==500){
                            new toastr.error(error.response.data.text, "Internal Server Error");
                        }else if(error.response.status==401){
                           new toastr.error(error.response.data.text, "Unauthorized !");
        	                  self.logout();
                        }
                    }
                });
        },
        getUserSubTransHistory: async function() {
            let self=this
            var auth={};
                 var sendWalletTag=localStorage.getItem('main_wallet');
            var subwalletTid = localStorage.getItem('subwallet_trackid');
            	    	auth = {  
                pg:this.currentpage,
                perpage:this.perpage,
                search:this.search,
                currency:sendWalletTag,
                statussort:this.sorttransstatus,
                statustype:this.sorttransttype,
                sortpeerstack:this.sortpeerstack,
                sorttrackid:subwalletTid
                      };
        
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            let api= baseurl+"user/transaction/getUserTransaction.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.usertransaction = user_info.userdata;
        		    self.transtotalpage=user_info.totalpage;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getSubWalletNetwork: async function() {
            let self=this
            	    self.loading = true;
            var auth={};
                 var sendWalletTag=localStorage.getItem('main_wallet');
            var subwalletTid = localStorage.getItem('subwallet_trackid');
            	    	auth = {  
                wallettid:subwalletTid,
                      };
        
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            let api= baseurl+"user/wallet/get_wallet_network.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	        self.loading = false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
        		    self.subwalletnetwork = user_info.userdata;
        		}
            }).catch(function (error) {
                self.loading = false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getNetworkAddress: async function(trackid,name,shortname,netname){
          
            this.subwalletname=name
            this.selectednetworkname=netname
            this.selectednetshortname=shortname
            
                 let self=this
                   self.loading = true;
            var auth={};
                 var sendWalletTag=localStorage.getItem('main_wallet');
            var subwalletTid = localStorage.getItem('subwallet_trackid');
            	    	auth = {  
                wallettid:subwalletTid,
                cryptotrackid:trackid,
                currency:sendWalletTag,
                addressname:this.firstname
                      };
        
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            let api= baseurl+"user/wallet/get_network_address.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
                      self.loading = false;
        		} else {
        		    self.loading = false;
        		    let user_info = response.data.data;
                    //self.subwalletnetwork = user_info.userdata;
                    self.selectednetaddress=user_info.userdata[0].address
                    self.selectednetaddressmemo=user_info.userdata[0].memo
                   $('#staticBackdrop-document-one').modal('hide');
                      $('#staticBackdrop-document-oneQR').modal('show');
                    // document.getElementById("select_modal_part").style.display = "none";
                    // document.getElementById("qr_modal_part").style.display = "block";
        		}
            }).catch(function (error) {
                self.loading = false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
 
        },
        setSubWalletSendToUserName(){
            // amount 
            this.deposit_method=1;
            this.deposit_currency=localStorage.getItem('main_wallet');
            this.subwalletidtosend=localStorage.getItem('subwallet_trackid');
             this.subwalletusdamt=this.subwalletdata.balance
        },
        setSubWalletSendToExternal(){
            // amount 
            this.deposit_method=4;
            this.deposit_currency=localStorage.getItem('main_wallet');
            this.subwalletidtosend=localStorage.getItem('subwallet_trackid');
            this.subwalletusdamt=this.subwalletdata.balance
        },
        sendExternalAddress: async function (){
            this.error = null;
            let self = this;
              var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
              var user2fapin= "";
              if($('.pin11').val()!=undefined){
              user2fapin=$('.pin5').val()+''+$('.pin6').val()+''+$('.pin7').val()+''+$('.pin8').val()+''+$('.pin9').val()+''+$('.pin10').val()+''+$('.pin11').val();
              }else{
                 user2fapin=$('.pin5').val()+''+$('.pin6').val()+''+$('.pin7').val()+''+$('.pin8').val()+''+$('.pin9').val()+''+$('.pin10').val();   
              }
            this.withdrawal_username='null';
            this.deposit_method=4;
            const auth = {   
                username: this.withdrawal_username,
                amttopay: this.withdrawal_amount,
                type:this.deposit_method,
                currency:this.deposit_currency,
                wallettrackid:this.subwalletidtosend,
                address:this.externaladdress,
                memo:this.externalmemo,
                message:this.externalmessage,
                network_cointid:this.network_cointid.trackid,
                pin:pin,
                my2fa: user2fapin
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
             if(this.withdrawal_amount==''||this.withdrawal_amount==0||isNaN(this.withdrawal_amount)) {
                    self.stopLoading();
                      new toastr.error("Amount is needed", "Error!");
            }else
    
            if (parseInt(this.subwalletusdamt) >= parseInt(this.withdrawal_amount)){
                if (this.username == this.withdrawal_username || this.email == this.withdrawal_username){
                    self.stopLoading();
                    self.error = "Cannot Intiate withdrawal, Username Cannot be the same as sender's";
                }else if(this.externaladdress==''||this.externaladdress==0) {
                         self.stopLoading();
                    	Swal.fire("Error", "Address is needed","warning") 
                }else{  
                    var popmsg="";
                    var confirmtext="";
                    var userdat = self.externaladdress ? self.externaladdress : 'address' 
                    confirmtext="Yes, Send it!";
                    popmsg='You are about to transfer '+self.cryptocoinfirstname +' '+self.withdrawal_amount+' to '+userdat
                 
         
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/transaction/sendswapcrypto.php",form_data,{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    self.withdrawal_username="";
                    		    self.withdrawal_amount="";
                    		    new toastr.success(response.data.text, "Success", {duration:1000});
                    		       var pathname=window.location.pathname.replace(/\/\//g, "/")
                    		        $('#Externalwallet').modal('hide');
                                    if(pathname.includes('/dashboard/subwallet-withdrawal')){
                                        self.redirectURL("subwallet_details")
                                    }else{
                                    self.getUserWalletsByTrackID();
                                    self.getUserSubWallet();
                                    self.getUserTransaction();
                                    }
                                    
                    		       $('#SendCoinToUserName').modal('hide');
                    		           $('#swapCoin').modal('hide');
                    		       
                                $('#WithdrawUsername').modal('hide');
                                $('#cryptoSendCoin2').offcanvas('hide')
                                  $('#cryptoSwapCoin2').offcanvas('hide')
                                
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 405){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 500){
                                        self.error = error.response.data.text
                                    }
                                    Swal.fire(self.error);
                                }else{
                                    self.error = error.message || "Error processing request"
                                    Swal.fire(self.error);
                                }
                            });
                 
                    
                }
            }
            else{
                self.stopLoading();
                self.error = "Insufficient Amount, Cannot Intiate withdrawal";
            }
        },
        validateAddress: async function (){
            this.error = null;
            let self = this;
            this.withdrawal_username='null';
            this.deposit_method=4;
            const auth = {   
                type:this.deposit_method,
                address:this.externaladdress,
                network_cointid:this.network_cointid.trackid,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
  
            this.loading=true;
            var headers={'Authorization': "Bearer "+ self.accesstoken}
            axios.post(baseurl+"user/wallet/validate_useraddress.php",form_data,{headers}).then(function(response){
                self.stopLoading();
        	    if (!response.data.status) {
        	   // 	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
            //             ////this.logout();
            //         });
                      new toastr.error(response.data.text, "Error", {duration:1000});
        		} else {
        		   
                    new toastr.success(response.data.text, "Success", {duration:1000});
                    
                    
                    $('#external-transfer').modal('show');
                    $('#Externalwallet').modal('hide');
                    
        		}
            }).catch(function (error) {
                self.stopLoading();
                    if (error.response){
                        if (error.response.status === 400){
                            self.error = error.response.data.text
                        }
                        if (error.response.status === 405){
                            self.error = error.response.data.text
                        }
                        if (error.response.status === 500){
                            self.error = error.response.data.text
                        }
                        Swal.fire(self.error);
                    }else{
                        self.error = error.message || "Error processing request"
                        Swal.fire(self.error);
                    }
                });

          
        },
        getCoinFeeWithNetwork: async function (clicked=0){
            this.error = null;
            let self = this;
            self.loading = true;
            const auth = {  
                amttopay: this.withdrawal_amount,
                wallettid:this.subwalletidtosend,
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            if(this.withdrawal_amount==''||this.withdrawal_amount==0||isNaN(this.withdrawal_amount)) {
                    self.stopLoading();
                      new toastr.error("Amount is needed", "Error!");
            }else
            if (parseInt(this.subwalletusdamt) >= parseInt(this.withdrawal_amount)){
                 if(this.externaladdress==''||this.externaladdress==0) {
                    self.stopLoading();
                       new toastr.error("Address is needed", "Error!");
                }else{  
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/wallet/get_network_address_withfee.php",form_data,{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	        self.loading = false;
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    
                                self.loading = false;
                                let user_info = response.data.data;
                                self.subwalletnetwork = user_info.userdata;
                    	
                    	        if(clicked==1){
                                 $('#staticBackdrop-document-two').modal('show');
                                 $('#Externalwallet').modal('hide');
                    	        }
                                 
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 405){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 500){
                                        self.error = error.response.data.text
                                    }
                                }else{
                                    self.error = error.message || "Error processing request"
                                    Swal.fire(this.error);
                                }
                            });
                }
            }
            else{
                self.stopLoading();
                self.error = "Insufficient Amount, Cannot Intiate withdrawal";
            }
        },
        networkSelected(index){
            this.network_cointid=this.subwalletnetwork[index]
              $('#staticBackdrop-document-two').modal('hide');
                                 $('#Externalwallet').modal('show');
        },
        getUserSubLink: async function() {
            let self=this
            var auth={};
            	    	auth = {  
                currencytag:"USD256",
                      };
        
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            
            let api= baseurl+"user/wallet/get_user_subwallet_link.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.subwalletsiebar = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        sidebarmoveToSubWalletPage(wallettrackid,currency){
               localStorage.setItem('wallet_trackid',wallettrackid)
            localStorage.setItem('main_wallet',currency)
            this.redirectURL('all_subwallet')
        },
        
        // VIRTUAL CARD
        getVirtualCardPlans: async function() {
            let self=this
            let api= baseurl+"user/virtual_card/card_plan_list.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.vc_plans = user_info.userdata;
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUser_VcFundWallet: async function() {
            let self=this
            let api= baseurl+"user/virtual_card/user_allowed_subwallet_for_vc.php?plan="+this.selectedPlan.trackid;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.user_vc_fund_wallet = user_info.userdata;
        		    if(self.user_vc_fund_wallet.length >0){
        		        self.selectedCurrency=self.user_vc_fund_wallet[0]
        		    }
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getUser_UnFundWallet: async function() {
            let self=this
            let api= baseurl+"user/virtual_card/user_allowed_unloadwallet.php?plan="+this.selectedPlan.trackid;
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.user_vc_unfund_wallet = user_info.userdata;
        		    if(self.user_vc_unfund_wallet.length >0){
        		        self.selectedUnloadCurrency=self.user_vc_unfund_wallet[0]
        		    }
        		}
            }).catch(function (error) {
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        selectedaPlan: async function(index){
            this.selectedPlan=this.vc_plans[index]
            this.cleanpin();
            this.getUser_UnFundWallet();
             this.getUser_VcFundWallet();
            $('#exampleModalToggle').modal('hide');
            if( this.selectedPlan.supplier==2){
                   if(this.card_verified==1){
                    //   create card modal
                      $('#exampleModalToggle4').modal('show');
                   }else if(this.card_verified==2){
                    //   verification under processing modal
                    //   $('#card-verification-process').modal('show');
                        Swal.fire("Note!", "Verification under processing, check back in 10 minute time.", "info");
                   }else if(this.card_verified==0){
                    //    verification form modal
                      $('#card-verification-id').modal('show');
                   } 
            }else{
                //   create card modal
                 $('#exampleModalToggle4').modal('show'); 
            }
           
        },
        selectedVcCurrency: async function(index){
            this.selectedCurrency=this.user_vc_fund_wallet[index]
            this.cleanpin()
        },
        selectedUnloadVcCurrency: async function(index){
            this.selectedUnloadCurrency=this.user_vc_unfund_wallet[index]
            this.cleanpin()
        },
        generateUserVcCard: async function() {
            let self=this
             var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                currencytid:this.selectedCurrency.trackid,
                cardptid:this.selectedPlan.trackid,
                pin:pin,
                amount:this.amounttofund,
                showhtml:1,
                      };
        
            this.cleanpin()
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                 this.loading=true;
            let api= baseurl+"user/virtual_card/create_card.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                          window.location.reload();
                    });
        		} else {
        		    self.loading=false;
        		    let user_info = response.data.data;
        		    $('#card-security-pin').modal('hide');
        		       
                    self.getAllUserVcCards()
        		    Swal.fire("Successful", response.data.text,"success").then(function(){
                          window.location.reload();
                    });
        		     
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            if(error.response.data.text=="1"){
                                $('.show-pin').click();
                            }else{
                            //      Swal.fire("Error!", error.response.data.text,"warning").then(function(){
                            //       window.location.reload();
                            //   });
                               
                               Swal.fire({
  icon: 'warning',
  title: 'Notice!!',
  html: error.response.data.text,
}).then(function(){
                                   window.location.reload();
                               });
                            }
                        }else if(error.response.status==500){
                               Swal.fire("Notice!!",  "Server error try again later","warning").then(function(){
                               window.location.reload();
                           });
                        }else if(error.response.status==504){
                               Swal.fire("Notice!!",  "Server error try again later","warning").then(function(){
                               window.location.reload();
                           });
                        }else if(error.response.status==401){
                           Swal.fire("Notice!!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getVCInflowOutFlow: async function() {
            let self=this
            var auth={};
            	    	auth = {  
                sort:this.vc_sort
                      };
           
     
            
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/vc_history_summary.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		  self.vc_inflow= user_info.totalinlflow
                self.vc_outflow= user_info.totaloutflow
                self.vc_transcount=user_info.totaltrans
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getAllUserVcCards: async function() {
                 let self=this
            let api= baseurl+"user/virtual_card/uservc_card_list.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
                self.loading=true;
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.user_vc_list= user_info.userdata;
        		    for (let i = 0; i < self.user_vc_list.length; i++) {
            		    if(self.user_vc_list[i].cards.length > 0){
            		        
            		        self.selected_vc_card=self.user_vc_list[i].cards[0];
            		        self.selectedPlan=self.user_vc_list[i]
            		        self.getUser_UnFundWallet()
            		        self.getUser_VcFundWallet()
            		        
            		        
            		        self.perpage=6
                            self.sortwallettrackid=self.selected_vc_card.trackid;
                            self.getUserTransaction();
                            self.loading=false;
                            break;
            		    }
        		    }
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getAllSingleUserVcCard: async function() {
                 let self=this
                    var auth={};
            	    	auth = {  
                vctid:this.sortwallettrackid,
                maintid:localStorage.getItem('vcMain_trackid'),
                      };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        	    
            let api= baseurl+"user/virtual_card/single_user_vc_maindata.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
                  self.loading=true;
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.user_vc_list= user_info.userdata;
        		    if(self.user_vc_list[0].cards.length > 0){
        		        
        		        self.selected_vc_card=self.user_vc_list[0].cards[0];
        		        self.selectedPlan=self.user_vc_list[0]
        		        
        		        
        		        self.perpage=6
                        self.sortwallettrackid=self.selected_vc_card.trackid;
                        self.getUserTransaction();
                        self.getUser_VcFundWallet()
                        self.getUser_UnFundWallet()
                         self.loading=false;
        		    }
        		}
            }).catch(function (error) {
                     self.loading=false;
                     self.selected_vc_card=null
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
                
        },
        selected_VC_Card:async function(data,index){
            this.selected_vc_card=this.user_vc_list[data].cards[index];
            this.cleanpin()
            this.selectedPlan=this.user_vc_list[data]
            this.selected_index=index
            this.perpage=6
            this.sortwallettrackid=this.selected_vc_card.trackid
            this.getUserTransaction();
             this.getUser_UnFundWallet();
              this.getUser_VcFundWallet();
        },
        save_VC_Card_for_details:async function(data,index){
            localStorage.setItem('vc_trackid',this.user_vc_list[data].cards[index].trackid)
            localStorage.setItem('vcMain_trackid',this.user_vc_list[data].trackid)
            // this.selected_vc_card=this.user_vc_list[data].cards[index];
            this.redirectURL('./card-details.php')
        },
        getCardDataSelected(){
            this.sortwallettrackid=localStorage.getItem('vc_trackid');
            this.cleanpin()
        },
        getSingleVCdata(){
                 let self=this
                        var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                vctid:this.selected_vc_card.trackid,
                pin:pin
                      };
                      this.cleanpin()
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/single_vc_data.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		      $('#card-security-pin').modal('hide');
        		    if(user_info.userdata.length > 0){
        		        self.selected_vc_card=user_info.userdata[0]
        		    }
        		      self.loading=false;
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getSingleVCBal(){
                 let self=this
            var auth={};
            	    	auth = {  
                vctid:this.selected_vc_card.trackid,
                      };
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/card_balalce.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    
        		    if(user_info.userdata){
        		        self.selected_vc_bal=user_info.userdata
        		    }
        		      $('#unloadModal').modal('show');
        		      self.loading=false;
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        getDeleteSingleVCBal(){
                 let self=this
            var auth={};
            	    	auth = {  
                vctid:this.selected_vc_card.trackid,
                      };
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/card_balalce.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    
        		    if(user_info.userdata){
        		        self.selected_vc_bal=user_info.userdata
        		        self.amounttounload=parseFloat(self.selected_vc_bal)-1;
        		    }
        		      $('#unloaddeleteModal').modal('show');
        		      self.loading=false;
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        activate_update_card_pin(){
                 let self=this
                        var pin= $('.acpin1').val()+''+$('.acpin2').val()+''+$('.acpin3').val()+''+$('.acpin4').val()+''+$('.acpin5').val()+''+$('.acpin6').val()+''+$('.acpin7').val()+''+$('.acpin8').val();
            var auth={};
            	    	auth = {  
                vctid:this.selected_vc_card.trackid,
                pin:pin
                      };
                             this.cleanpin()
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/activate_update_vc_pin.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) { 
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		      $('#ActivateFundModal').modal('hide');
        		         self.getAllUserVcCards()
        		    	Swal.fire("Successful", response.data.text,"success").then(function(){
                         window.location.reload();
                    });
                    
        		      self.loading=false;
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        freezeUserCard(type){
                 let self=this
                        var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                vctid:this.selected_vc_card.trackid,
                pin:pin,
                type:type
                      };
                             this.cleanpin()
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/freeze_user_vc.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		     self.getAllUserVcCards()
        		    let user_info = response.data.data;
        		      $('#card-security-pin').modal('hide');
        		     
        		       Swal.fire("Successful", response.data.text,"success")
        		      self.loading=false;
        		        window.location.reload();
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        deleteUserCard(){
                 let self=this
                        var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                currencytid:this.selectedUnloadCurrency.trackid,
                cardtid:this.selected_vc_card.trackid,
                amount:this.amounttounload,
                pin:pin
                      };
                             this.cleanpin()
              this.loading=true;
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/delete_user_vc.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		     self.getAllUserVcCards()
        		    let user_info = response.data.data;
        		      $('#card-security-pin').modal('hide');
        		     
        		       Swal.fire("Successful", response.data.text,"success")
        		      self.loading=false;
        		        window.location.reload();
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        fundUserVcCard: async function() {
            let self=this
              var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                currencytid:this.selectedCurrency.trackid,
                cardtid:this.selected_vc_card.trackid,
                amount:this.amounttofund,
                pin:pin,
                showhtml:1,
                      };
        
            this.cleanpin()
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                 this.loading=true;
            let api= baseurl+"user/virtual_card/fund_vc.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    $('#card-security-pin').modal('hide');
        		    Swal.fire("Successful", response.data.text,"success").then(function(){
                          window.location.reload();
                    });
        		        self.loading=false;
                    self.getAllUserVcCards()
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        unloadUserVcCard: async function() {
            let self=this
             var pin= $('.pin1').val()+''+$('.pin2').val()+''+$('.pin3').val()+''+$('.pin4').val();
            var auth={};
            	    	auth = {  
                currencytid:this.selectedUnloadCurrency.trackid,
                cardtid:this.selected_vc_card.trackid,
                amount:this.amounttounload,
                pin:pin
                      };
        
                   this.cleanpin()
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                 this.loading=true;
            let api= baseurl+"user/virtual_card/unload_vc.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    $('#card-security-pin').modal('hide');
        		    Swal.fire("Successful", response.data.text,"success")
        		        self.loading=false;
                    self.getAllUserVcCards()
        		}
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        checkdatachecker: async function() {
            let self=this
           
            var auth={};
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            let api= baseurl+"user/virtual_card/update_carddata.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
       
            }).catch(function (error) {
                     self.loading=false;
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        cleanpin(){
             $('.pin1').val('')
             $('.pin2').val('')
             $('.pin3').val('')
             $('.pin4').val('')
        },
        
        
        // SWAP
        getActiveSwapCryptoMethods: async function(){
            let self=this
            let api=baseurl+"user/swap/getall_swap_currency.php?search="+this.searchcurrency
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
        
        	axios.get(api,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.swapcurrencies = user_info.userdata;
        		    if(self.swapcurrencies.length>0){
        	
        		        if(localStorage.getItem('swap_from_wallet')==null){
            		        self.active_swapcurrencies = user_info.userdata[0];
        		        }else{
        		            var found=0;
        		            var walltid=localStorage.getItem('swap_from_wallet');
        		            for(var j=0;j<user_info.userdata.length;j++){
        		                if(user_info.userdata[j].trackid==walltid){
        		                     found=1;
        		                     self.active_swapcurrencies = user_info.userdata[j];
        		                     break;
        		                }
        		            }
        		            if(found==0){
        		                 self.active_swapcurrencies = user_info.userdata[0];
        		            }
        		        }
            		    self.get_active_exchange_to_methdos();
            		    
        		    }
        		}
            });
        },
        get_active_exchange_to_methdos: async function() {
            let self=this
          
            var auth={};
            	    	auth = {  
               cointrackid:this.active_swapcurrencies.cointrackid,
               search:this.searchtocurrency
                      };
        
                   this.cleanpin()
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
                 this.loading=true;
            let api= baseurl+"user/swap/get_coin_to_swap_to.php";
        	var headers={'Authorization': "Bearer "+ this.accesstoken}
            
        	axios.post(api,form_data,{headers}).then(function (response) {
        	    if (!response.data.status) {
        	          self.loading=false;
        	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                        ////this.logout();
                    });
        		} else {
        		    let user_info = response.data.data;
        		    self.loading=false;
                    self.swaptocurrencies = user_info.userdata;
                       if(self.swaptocurrencies.length>0){
        		    self.active_swaptocurrencies = user_info.userdata[0];
        		    }
        		}
            }).catch(function (error) {
                     self.loading=false;
                     self.active_swaptocurrencies=[]
                        self.swaptocurrencies = []
                    if (error.response) {
                        if(error.response.status==400){
                            Swal.fire("Error!", error.response.data.text ,"warning");
                        }else if(error.response.status==500){
                            Swal.fire("Error!", "Server error try again later","warning");
                        }else if(error.response.status==401){
                           Swal.fire("Error!", "Unauthorised","warning").then(function(){
                               self.logout();
                           });
                        }
                    }
                });
        },
        async proceedToSwapAddress(){
            const url = baseurl+"user/swap/swap_user_coin.php";
            const data = new FormData();
            data.append('amttopay', this.amounttosell);
            data.append('currency', this.active_swaptocurrencies.currency_from_tag);
            data.append('towallettrackid', this.active_swaptocurrencies.trackid);
            data.append('wallettrackid', this.active_swapcurrencies.trackid);
            var convertto=0;
            if(this.active_swaptocurrencies.multiply_it==1){
               convertto= this.computedScore(this.active_swaptocurrencies.conversion_rate * this.amounttosell)
            }else{
               convertto= this.computedScore(this.amounttosell/this.active_swaptocurrencies.conversion_rate) 
            }
            const options = {
                method: "POST",
                headers: { 
                    "Authorization": `Bearer ${this.accesstoken}`
                },
                data,
                url
            }
                    var popmsg="";
                    var confirmtext=""; 
                    confirmtext='Yes, Swap it!';
                    popmsg='You are about to swap '+ this.amounttosell +' '+ this.active_swapcurrencies.name +' to '+ convertto+' '+ this.active_swaptocurrencies.coin_to_name
                    Swal.fire({
                      title: "Are you sure?",
                      text: popmsg,
                      icon: "warning",
                      buttons: true,
                      showCancelButton: true,
                      confirmButtonText: confirmtext,
                      cancelButtonText: 'No, cancel!',
                    }).then(async(result) => {
                      if (result.isConfirmed) {
                        this.loading = true
                             try {
                                    const response = await axios(options);
                                    if(response.data.status){
                                       this.swalToast('success',response.data.text);
                                        Swal.fire("Success!", response.data.text ,"success");
                                        this.sorttransttype=3; 
                                        this.getActiveSwapCryptoMethods()
                                        this.getUserTransaction(); 
                                        this.loading = false;
                                    }else{
                                        this.loading = false;
                                       this.swalToast('error',"An error occured, try again later !");
                                    }
                             } catch (error) {
                                // //console.log(error);
                                 this.loading = false;
                                if (error.response){
                                    if (error.response.status == 400){
                                        const errorMsg = error.response.data.text;
                                        this.swalToast('error',errorMsg);
                                        return
                                    }
                
                                    if (error.response.status == 401){
                                        const errorMsg = "User not Authorized";
                                        this.swalToast('error',errorMsg);
                                        
                                        return
                                    }
                
                                    if (error.response.status == 405){
                                        const errorMsg = error.response.data.text;
                                        this.swalToast('error',errorMsg);
                                        return
                                    }
                
                                    if (error.response.status == 500){
                                        const errorMsg = error.response.data.text;
                                        this.swalToast('error',errorMsg);
                                        return
                                    }
                                }
                
                                new Toasteur().error(error.message || "Error processing request")
    
                                    this.loading = false;
                             }finally {
                    this.loading = false;
                }
                      }else{
                           this.loading = false;
                          this.swalToast('error',"Transaction Cancelled !");
                      }
                    });
        },
        swap_from_crypto_page(tid){
               localStorage.setItem('swap_from_wallet',tid)
                window.location.href ='swap';
        },
        
        
        swalToast(icon, title) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: false,
            })
            Toast.fire({
                icon: icon,
                title: title
            })
        },
        deleteUserAccount: async function (){
                        var self =this
                    this.error = null;
 
                    Swal.fire({
                      title: "Are you sure?",
                      text: 'Your entire set of data will be erased.',
                      icon: "warning",
                      buttons: true,
                      showCancelButton: true,
                      confirmButtonText: 'Yes, Delete!',
                      cancelButtonText: 'No, cancel!',
                    })
                    .then((result) => {
                      if (result.isConfirmed) {
                        this.loading=true;
                        var headers={'Authorization': "Bearer "+ self.accesstoken}
                        axios.post(baseurl+"user/auth/delete_account.php",{},{headers}).then(function(response){
                            self.stopLoading();
                    	    if (!response.data.status) {
                    	    	Swal.fire("Opps, an error occured, try again later", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		   
                    		    new toastr.success(response.data.text, "Success", {duration:1000});
                                self.logout()
                    		}
                        }).catch(function (error) {
                            self.stopLoading();
                                if (error.response){
                                    if (error.response.status === 400){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 405){
                                        self.error = error.response.data.text
                                    }
                                    if (error.response.status === 500){
                                        self.error = error.response.data.text
                                    }
                                }else{
                                    self.error = error.message || "Error processing request"
                                    Swal.fire(this.error);
                                }
                            });
                      } else {
                        Swal.fire(" Cancelled !");
                      }
                    });
              
        },
        
        
    },
    beforeMount(){
        this.error =''
        var pathname=window.location.pathname.replace(/\/\//g, "/")
        this.accesstoken = localStorage.getItem("token");
        // alert(this.accesstoken)
        if (pathname.includes('/dashboard/index')||pathname =="/dashboard/"){
                this.perpage=25;
        }
        if (pathname.includes('/dashboard/peerstack_withdrawal_confirmation')){
                this. getPeerDepositSUmmary()
        }
        if (pathname.includes('/dashboard/peerstack_recieve_confirmation')){
                this. getPeerDepositSUmmary()
                this.startTimerCount()
                // this.contineousPeer_trans_SuccessNoti()
        } 
        if (pathname.includes('/dashboard/peerstack_chat')){
            this. getPeerDepositSUmmary()
        } 
        if (pathname.includes('/dashboard/cards')){
            // below to genrate user crypto wallet
            localStorage.setItem('main_wallet',"USD256")
               this.generateUserSubWallet();
                this.getAllUserVcCards()
            //   this.getVCInflowOutFlow()
            // this.getUser_VcFundWallet()
            // this.getUser_UnFundWallet()
            this.getVirtualCardPlans()
         
                 
        } 
        if (pathname.includes('/dashboard/card-details')){
            this.getCardDataSelected()
            // this.getVCInflowOutFlow()
            // this.getUser_VcFundWallet()
            // this.getUser_UnFundWallet()
            this.getVirtualCardPlans()
            this.getAllSingleUserVcCard()
                 
        } 
        if (pathname.includes('/dashboard/peerstack_recieve')){
            this.getPeerStackAmount()
            this.getPeerstackRecieveMerchants();
        }
        if (pathname.includes('/dashboard/peerstack_withdrawal')){
            this. getPeerStackWithdrawAmount();
            this.getPeerstackWithdrawalMerchants();
        }
                if (pathname.includes('/dashboard/all_subwallet')){
                      this.generateUserSubWallet();
            this. getSubwalletCoinValue();
            this.getUserSubWalletList();
          
        }
                           if (pathname.includes('/dashboard/subwallet_details')){
            this.getSingleUserSubWalletListUsd();
            this.getUserSubTransHistory();
            this.getSubWalletNetwork();
        }
        if (pathname.includes('/dashboard/subwallet-withdrawal')){
            this.getSingleUserSubWalletList();
        }
           
            
        if(pathname.includes('/dashboard/prices')){
            this.getActiveCryptoMethods();
            this.getNews();
            this.getCryptodata();
            this.getCryptocharts();
        }
        if(pathname.includes('/dashboard/exchange')){
            this.sorttransttype=4;
            this.getActiveExchangeCryptoMethods(); 
            this.getActiveExchangeMethods();
            this.getUserTransaction();
            this.getUserBanks();
            this.getPersonalBanksacc();
            this.getAllBanks();
            if(getAllUrlParams().outer==1){
                 this.getExchangeSUmmary();
            }
        }
        if(pathname.includes('/dashboard/exchange_details')){
               this.sorttransttype=4;
            this.getAllExchangeData()
            this.getUserTransaction();
            this.contineousExchange_trans_SuccessNoti()
        }
        if(pathname.includes('/dashboard/swap')){
            this.sorttransttype=3;
            this.getActiveSwapCryptoMethods(); 
            this.getUserTransaction();
        }
        if (pathname.includes('/dashboard/activity')){
            this.perpage=35;
            this.getUserWallets();
            }
        if (pathname.includes('/dashboard/index')||pathname =="/dashboard/" || pathname.includes('/dashboard/activity')){
               // below to genrate user crypto wallet
            localStorage.setItem('main_wallet',"USD256")
               this.generateUserSubWallet();
            this.getUserTransaction();
            this.getUserWallets();
            this.verifyPayments();
        }
        if (pathname.includes('/dashboard/referrals')){
            this.getUserReferrals();
        }
        if (pathname.includes('/dashboard/coupons')){
            this.getUserCoupons();
        }
        if(pathname.includes('/dashboard/wallet')){
               this.getUserSubWallet();
            this.getUserWalletsByTrackID();
            this.getCurrencyRecieveMethods();
            this.getCurrencyWithdrawMethods();
            // this.getSubCurrencyWithdrawMethods();
            this.getUserTransaction();
            // this.getUserWallets();
            this.getUserBanks();
            this.getAllBanks();
            this.getsystemactivebanks();
            this.getActiveCryptoMethods();
            this.get_Allactive_exchange_to_methdos()
           	this.getSwapWithdrawMethods();
         
        }
        if (pathname.includes('/dashboard/profile')){
            this.getUserLevels();
            this.getUserSessionLog();
            this.activateProfileTab();
            
        }
        if (pathname.includes('/dashboard/physicalcard')){
            this.getUserCards();
        }
        if (pathname.includes('/dashboard/banks')){
            this.getUserBanks();
            this.getAllBanks();
        }
        this.getUserSubLink();
        this.getuserkycdata();
        this.getUserDetails();
        this.getSystemSettings();
        this.getUserNotifications();
        this.checkfornewnoti()
        this.checkdatachecker()
    },
});
app.mount('#app'); 