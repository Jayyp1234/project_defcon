var baseurl="https://app.cardify.co/api/";
var mainurl="https://app.cardify.co/";
	const { createApp } = Vue;
			createApp({
				data() {
					return {
					    selectedcoindata:"",
                        selectedtocoindata:"",
                        exchangeuser_data:'',
					    loading:false,
					    amounttosell:'',
					    livevalue:0,
					    cryptocurrencylist:[],
					    exchangecurrencylist:[],
					    searchcurrency:"",
					    searchtocurrency:"",
					    email:'',
					    phoneno:'',
                        password:'',
                        captachacode:'',
                        error:'',
                        
                        // Adding User Banks 
                        bankid:'',
                        account_name: '',
                        bank_code: '',
                        bank_name:'',
                        account_number:'',
                        banknamecode:'',
                        ref_code:'',
                        banks:'',
                        addresstosendto:'',
                        peerTimeinterval:'',
                        peerSuccessTimer:'',
                        internalLoading:false,
					};
				},
				methods: {
				    async showAlert(text){
                            const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-right',
                            iconColor: 'green',
                            customClass: {
                            popup: 'colored-toast'
                            },
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                            });
                            await Toast.fire({
                            icon: 'success',
                            title: text
                            })  
				    },
				    async  copyThetext(copyme){
            			if (navigator.clipboard && window.isSecureContext) {
            				// navigator clipboard api method'
            				await navigator.clipboard.writeText(copyme);
            				this.showAlert("Copied successfully")
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
				    computedScore($amount){
            if(isNaN($amount)){
                return 0;
            }
                          $amount=parseFloat($amount).toFixed(8)
            var formatter = new Intl.NumberFormat('en-US', { maximumSignificantDigits: 9 });
            return formatter.format($amount);
        },
                    //Crypto
                    getActiveCryptoMethods: async function(){
                            let self=this
                            let api=baseurl+"user/exchange/getallcryptocurrency.php?search="+this.searchcurrency
                        // 	var headers={'Authorization': "Bearer "+ this.accesstoken}
                        
                        	axios.get(api).then(function (response) {
                        	    if (!response.data.status) {
                        		} else {
                        		    let user_info = response.data.data;
                        		    self.cryptocurrencylist = user_info.userdata;
                        		}
                            });
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
                    getActiveExchangeMethods: async function(){
            let self=this
            let api=baseurl+"user/exchange/getactiveexchangemethods.php?search="+this.searchtocurrency
        // 	var headers={'Authorization': "Bearer "+ this.accesstoken}
        // {headers}
        	axios.get(api,).then(function (response) {
        	    if (!response.data.status) {
        		} else {
        		    let user_info = response.data.data;
        		    self.exchangecurrencylist = user_info.userdata;
                    self.selectedtocoindata=self.exchangecurrencylist[0]
                        
        		}
            });
        },
                    saveExchangeData(){
                        localStorage.setItem('amounttosell',this.amounttosell)
                        localStorage.setItem('selectedtocoindata', JSON.stringify(this.selectedtocoindata));
                        localStorage.setItem('selectedcoindata', JSON.stringify(this.selectedcoindata));
                       window.location.href = "processing.php"  
                    },
                    saveExchangeDataLogin(){
                        localStorage.setItem('amounttosell',this.amounttosell)
                        localStorage.setItem('selectedtocoindata', JSON.stringify(this.selectedtocoindata));
                        localStorage.setItem('selectedcoindata', JSON.stringify(this.selectedcoindata));
                    },
                    getExchangeSUmmary(){
                        this.amounttosell=localStorage.getItem('amounttosell');
                        this.selectedcoindata = JSON.parse(localStorage.getItem('selectedcoindata'));
                        
                        this.selectedtocoindata = JSON.parse(localStorage.getItem('selectedtocoindata'));
                        
                    },
                    getPaymentMethods(){
                            this.bank_name=localStorage.getItem('exchangeBankName');
                            this.account_name=localStorage.getItem('exchangeBankAccName');
                            this.account_number=localStorage.getItem('exchangeBankAccno');  
                            
                            // store address
                              // get user exchnage data
            this.exchangeuser_data=JSON.parse(localStorage.getItem('userexchange_data'));
                            
                    },
                    sendTologin: async function() {
                        this.saveExchangeDataLogin()
            var mainthis=this
            //Data from The form...
                const auth = {  
                        selectedcurrency:this.selectedcoindata.producttrackid,
                        selectedexchange: this.selectedtocoindata.trackid,
                        amount:this.amounttosell
                };
                var form_data = new FormData();
                for (var key in auth) {
                    form_data.append(key, auth[key]);
                }
            
                this.error = null;
                $('.btn').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/exchange/saveinternal_fromexter.php", form_data).then(function(response){
                    if (response.data.status == true){
                        $('.btn').html('Login');
                        let access_token = response.data.data[0].access_token;
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success");
                        window.location.href =mainurl+'auth/login?exchangetoken='+ access_token;
                     
                    }
                }).catch(function(error){
                    $('.btn').html('Login');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                                Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                               Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                    }
                   
                })
        },
                    proceedToExchangeAddress(){
                        var self=this
                        // check if bank is selected, check if currency and coin is selected
                        if(this.selectedtocoindata==''){
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
                        // generate address
                        //  store data in local and then nav to next page
                        // generate address
                         this.loading=true;
                        const auth = {  
                                exchangetid: this.selectedtocoindata.trackid,
                                email:this.email,
                                phone:this.phoneno,
                                currencytid:this.selectedcoindata.producttrackid,
                                accountname: this.account_name,
                                bankcode: this.bank_code,
                                bankname: this.bank_name,
                                accountnumber:this.account_number,
                        };
                        var form_data = new FormData();
                        for (var key in auth) {
                            form_data.append(key, auth[key]);
                        }
                        axios.post(baseurl+"user/exchange/exchange_external.php",form_data).then(function(response){
                    	    if (!response.data.status) {
                    	    	 Swal.fire( {icon: 'error',title:"Coin Address Generation Failed",text:response.data.text}).then(function(){
                                //   self.logout();
                                });
                                  self.loading=false;
                    		} else {
                    		    let user_info = response.data.data.userdata[0];
                    		       self.loading=false;
                                   // store userdata
                                localStorage.setItem('userexchange_data',JSON.stringify(user_info))
                                //   store bank/payment data
                                if(self.selectedtocoindata.exchangesystem==1){
                                    localStorage.setItem('exchangeBankName', self.bank_name);
                                    localStorage.setItem('exchangeBankAccName', self.account_name);
                                    localStorage.setItem('exchangeBankAccno', self.account_number);
                                }
                                
                                localStorage.setItem('amounttosell',self.amounttosell)
                                localStorage.setItem('selectedtocoindata', JSON.stringify(self.selectedtocoindata));
                                localStorage.setItem('selectedcoindata', JSON.stringify(self.selectedcoindata));
                                
                                
                                window.location.href ='payment.php'
                        
                    		    self.loading=false;
                    		}
                        }).catch(function(error){
                    if (error.response){
                        self.loading=false
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                                Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                               Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                    }
                   
                })
                        
                       
                    },
                    // Auth
                    login: async function() {
                        this.saveExchangeDataLogin()
            var mainthis=this
            //Data from The form...
           
            var recaptchaRes = grecaptcha.getResponse();
             
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (this.email == '' || this.password == ''){
                Swal.fire( {icon: 'error',title:"Error",text:"Please fill in all the fields to complete this registration"});
                return
            }else   if(recaptchaRes.length == 0) {
               Swal.fire( {icon: 'error',title:"Error",text:"Please complete the reCAPTCHA challenge!"});
               return;
            } else {
                // Add reCAPTCHA response to the POST
                this.captachacode=recaptchaRes;
            }
        	if(this.captachacode==""){
                Swal.fire( {icon: 'error',title:"Error",text:"Please complete the reCAPTCHA challenge!"});
            	return;
        	} else{
                const auth = {  
                        email:this.email,
                        password: this.password,
                        googlecode:this.captachacode
                };
                var form_data = new FormData();
                for (var key in auth) {
                    form_data.append(key, auth[key]);
                }
            
                this.error = null;
                $('.btn').html(`<div class="d-flex justify-content-center">
                                     <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                     </div>
                                  </div>`);
                await axios.post(baseurl+"user/auth/login.php", form_data).then(function(response){
                    if (response.data.status == true){
                        $('.btn').html('Login');
                        let access_token = response.data.data[0].access_token;
                        this.success = response.data.text;
                        new toastr.success(response.data.text, "Success");
                        window.localStorage.setItem('token', access_token);
                        if(response.data.data[0].verification==1 && response.data.data[0].auth_factor){
                            window.location.href =mainurl+'auth/otp?token='+response.data.data[0].token;
                        }
                        else if(response.data.data[0].verification==1 && !response.data.data[0].auth_factor){
                            window.location.href =mainurl+'dashboard/exchange.php?outer=1';
                        }
                        else{
                            mainthis.sendverifyotp();
                            window.location.href =mainurl+'auth/verify';
                        }
                    }
                }).catch(function(error){
                    $('.btn').html('Login');
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                                Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                               Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                             Swal.fire( {icon: 'error',title:"Error",text:error.response.data.text});
                    }
                   
                })
                
            }
           
        },
                    getAllBanks: async function() {
            let self=this
            let api=baseurl+"user/systems/getAllBanks.php";
        
        	axios.get(api).then(function (response) {
        	    if (!response.data.status) {
        		} else {
        		    let user_info = response.data.data;
        		    self.banks = user_info.userdata;
        		}
            })
        },
                    verifyBank: async function() {
            let self = this;
            var breakme=this.banknamecode.split("^");
            this.bank_code=breakme[0]
            this.bank_name=breakme[1]
            // this.loading=true;
            const auth = {  
                bankcode: this.bank_code,
                accountnumber:this.account_number
            };
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
            // var headers={'Authorization': "Bearer "+ this.accesstoken}
            await axios.post(baseurl+"user/systems/verifyBanks.php",form_data).then(function(response){
                // self.stopLoading();
        	    if (!response.data.status) {
        		} else {
        		    let user_info = response.data.data;
        		    self.account_name = user_info.userdata;
        		  //  new toastr.success(response.data.text, "Success", {duration:1000});
        		}
            }).catch(function (error) {
                // self.stopLoading();
                    if (error.response){
                        if (error.response.status === 400){
                            this.error = error.response.data.text;
                            }
                        if (error.response.status === 405){
                            this.error = error.response.data.text
                        }
                        if (error.response.status === 500){
                            this.error = error.response.data.text
                        }
                    }else{
                        this.error = error.message || "Error processing request"
                        // swal(this.error);
                    }
                });
        },
        
                    checkExchange_trans_SuccessNoti(){
             let self=this
           self.internalLoading=true
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {  
              orderid:self.addresstosendto,
            };
            // var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data).then(function (response) {
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
                 
                    
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   swal("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                    }
                }
            })
        },
        
                    contineousExchange_trans_SuccessNoti(){
             let self=this
          self.peerTimeinterval= setInterval(function () {
           
          	let api=baseurl+"user/notifications/check_success_trans.php";
          	var auth = {  
               orderid:self.addresstosendto,
            };
            // var headers={'Authorization': "Bearer "+ self.accesstoken}
            var form_data = new FormData();
            for (var key in auth) {
                form_data.append(key, auth[key]);
            }
        
            axios.post(api,form_data).then(function (response) {
                if (!response.data.status) {
                }else {
                    toastr.clear()
                          // stop timer
                    clearInterval(self.peerTimeinterval);
                    clearInterval(self.peerSuccessTimer);
                    // toastr.success(response.data.text,{ui:"is-dark"});
                    Swal.fire( {icon: 'success',title:"Success",text:response.data.text});
                }
            }).catch(function (error) {
                if (error.response) {
                   
                    if(error.response.status==400){
                         self.logout();
                    }else if(error.response.status==500){
                        //   swal("Error!", "Server error try again later", "error");
                        //       self.error="Server error try again later"
                    }else if(error.response.status==401){
                    }
                }
            })
                 },2000)
        },
				},
				beforeMount(){
				    var pathname=window.location.pathname.replace(/\/\//g, "/")
				  
				    this.getActiveCryptoMethods();
				    this.getActiveExchangeMethods();
				    if(pathname=="/processing.php"||pathname=="/processing"){
				        this.getExchangeSUmmary();
				        this.getLiveValue();
				          this.getAllBanks();
				        
				    }
				    if(pathname=="/payment.php"||pathname=="/payment"){
				        this.getPaymentMethods()
				          this.contineousExchange_trans_SuccessNoti()
				    }
				    
      
				} 
			}).mount("#app");