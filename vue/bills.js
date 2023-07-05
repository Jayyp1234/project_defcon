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
        
        var baseurl="https://app.cardify.co/api/user/blog/";
        var baseurl1="https://app.cardify.co/api/user/";
        var mainurl="https://blog.cardify.co/";
			const { createApp } = Vue;
			createApp({
				data() {
					return {
					    pagenum:25,
						authors:[],
						categories:[],
						recentblogs:[],
						blogsbysegment:[],
						is_author:false,
						author_data:null,
						header :null,
						is_category:false,
						is_tag:false,
						bloglength:0,
						authorheader:true,
						blog:null,
						tag:[],
						
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
                        showform3:false,

                        // state management
                        loading: false,
                        success: null,
                        successData: null,
                        error: null
                        
                        
					};
				},
				methods: {
					getBlogAuthors: async function(){
                        let self=this
                        let api= baseurl+"get_authors.php";
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.authors = response.data.data.userdata;
                    		    
                    		}
                        }).catch(function (error) {
                                self.stopLoading();
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlogCategories: async function(){
                        let self=this
                        let categories = baseurl+"get_categories.php";
                    	axios.get(categories).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.categories = response.data.data.userdata;
                    		    
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getRecentBlog: async function(){
                        let self=this
                        let categories = baseurl+"get_recent_blogs.php?length="+this.perpage;
                    	axios.get(categories).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.recentblogs = response.data.data.userdata;
                    		    self.bloglength = response.data.data.length;
                    		    
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlogAuthorsbyId: async function(){
                        let self=this
                        let api= baseurl+"get_all_blogs_by_author_id.php?perpage="+this.perpage+"&authorname="+getAllUrlParams().author;
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.blogsbysegment = response.data.data.userdata;
                    		    self.bloglength =  response.data.data.length;
                    		    self.author_data = response.data.data.author_details;
                    		    
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlogCategorybyId: async function(){
                        let self=this
                        let api= baseurl+"get_all_blogs_by_category_id.php?perpage="+this.perpage+"&category="+getAllUrlParams().category;
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.blogsbysegment = response.data.data.userdata;
                    		    self.bloglength =  response.data.data.length;
                    		    self.header = response.data.data.title;
                    		    
                    		    
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlogTagsbyId: async function(){
                        let self=this
                        let api= baseurl+"get_all_blogs_by_tags.php?perpage="+this.perpage+"&tag="+getAllUrlParams().tag;
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.blogsbysegment = response.data.data.userdata;
                    		    self.bloglength =  response.data.data.length;
                    		    self.header = response.data.data.title;
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlog: async function(){
                        let self=this
                        let categories = baseurl+"get_blog_data_by_name.php?name="+getAllUrlParams().title+"&tag="+getAllUrlParams().tagid;  
                    	axios.get(categories).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.blog = response.data.data.userdata;
                    		    self.tag = response.data.data.userdata.tags.split(','); 
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
                    },
                    getBlogbyCategories: async function(){
                        let self=this
                        let categories = baseurl+"get_all_blogs_by_categories.php";
                    	axios.get(categories).then(function (response) {
                    	    if (!response.data.status) {
                    	    	new toastr.error(response.data.text,"Error Fetching Level Authorization");
                    		} 
                    		else {
                    		    self.blogsbysegment = response.data.data.userdata;
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                    }else if(error.response.status==500){
                                    }else if(error.response.status==401){
                                       //self.logout();
                                    }
                                }
                            });
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
                        let api= baseurl1+"pricechart/landing/getCryptonews.php";
                    	axios.post(api,form_data).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Error", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    let user_info = response.data.data;
                    		    self.news = user_info.userdata.Data;
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                        swal("Error!", error.response.data.text ,"warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==500){
                                        swal("Error!", "Server error try again later","warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==401){
                                       swal("Error!", "Unauthorised","warning").then(function(){
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
                        let api= baseurl1+"pricechart/landing/getCryptodata.php";
                    	axios.post(api,form_data).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Error", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    let user_info = response.data.data;
                    		    self.cryptodata = user_info.userdata;
                    		}
                        }).catch(function (error) {
                                if (error.response) {
                                    if(error.response.status==400){
                                        swal("Error!", error.response.data.text ,"warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==500){
                                        swal("Error!", "Server error try again later","warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==401){
                                       swal("Error!", "Unauthorised","warning").then(function(){
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
                        let api= baseurl1+"pricechart/landing/getCryptocharts.php";
                    	axios.post(api,form_data).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Error", response.data.text,"warning").then(function(){
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
                                        swal("Error!", error.response.data.text ,"warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==500){
                                        swal("Error!", "Server error try again later","warning").then(function(){
                                           self.logout();
                                       });
                                    }else if(error.response.status==401){
                                       swal("Error!", "Unauthorised","warning").then(function(){
                                           self.logout();
                                       });
                                    }
                                }
                            });
                    },
                    togglepricecurrency: async function(){
                        let self=this
                        let api=baseurl1+"pricechart/landing/getallactivecryptomethod.php?type="+this.activecryptoname
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Login Unsucessful", response.data.text,"warning").then(function(){
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
                        let api=baseurl1+"pricechart/landing/getallactivecryptomethod.php?type="+id
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Login Unsucessful", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    let user_info = response.data.data;
                    		    self.activecrypto = user_info.userdata[0];
                    		}
                    		
                        });
                    },
                    getActiveCryptoMethods: async function(){
                        let self=this
                        let api=baseurl1+"pricechart/landing/getallactivecryptomethod.php"
                    
                    	axios.get(api).then(function (response) {
                    	    if (!response.data.status) {
                    	    	swal("Login Unsucessful", response.data.text,"warning").then(function(){
                                    ////this.logout();
                                });
                    		} else {
                    		    let user_info = response.data.data;
                    		    self.cryptocurrencylist = user_info.userdata;
                    		    self.activecrypto = user_info.userdata[0];
                    		}
                        });
                    },
                    sendPostRequest: async function(postObject = {}, url , headers = "" , refreshCallback){
                        // content type 1 - content type js 2 - formdata

                        let self = this;

                        let data = new FormData();

                        for (const  key in  postObject ){
                            data.append(key, postObject[key]);
                        }

                        const options = {
                            method: "POST",
                            url,
                            data,
                            headers: (headers == "" )? {"Content-type": "application/json"}: headers
                        }

                        try {
                            self.loading  = true;
                            const response = await axios(options)
                            if (response.data.status) {
                                self.success = response.data.text;
                                self.successData = response.data.data;
                                new toastr.success(response.data.text, self.success);
                                refreshCallback();
                            }else{
                                this.swalToast("error", 'Unable to register')   
                            }
                        } catch (error) {
                            if (error.response){
                                if (error.response.status == 400){
                                    const errorMsg = error.response.data.text;
                                    this.swalToast("error",errorMsg);
                                    return
                                }
            
                                if (error.response.status == 401){
                                    this.error = "User not Authorized";
                                    this.swalToast("error",this.error);
                                    window.localStorage.setItem("token", '')
                                    this.authToken = null;
                                    window.location = `${this.baseurl}`;
                                    return
                                }
            
                                if (error.response.status == 405){
                                    const errorMsg = error.response.data.text;
                                    this.swalToast("error",errorMsg);
                                    return
                                }
            
                                if (error.response.status == 500){
                                    const errorMsg = error.response.data.text;
                                    this.swalToast("error",errorMsg);
                                    return
                                }
                            }
            
                            this.swalToast("error",error.message || "Error processing request")
                            
                        }finally {
                            self.loading  = false;
                        }

                    }

				},
				beforeMount(){
                    var pathname=window.location.pathname.replace(/\/\//g, "/")
                    if (pathname.includes('/index')||pathname =="/"){
                        this.perpage=25;
                        this.getBlogAuthors();
                        this.getBlogCategories();
                        this.getRecentBlog();
                        this.getBlogbyCategories();
                    }
                    if (pathname.includes('/all')){
                        this.perpage=25;
                        this.getRecentBlog();
                    }
                    if(pathname.includes('/meta') && getAllUrlParams().author!=undefined){
                        this.perpage=25;
                        this.getBlogAuthorsbyId();
                        this.is_author = true
                    }
                    if(pathname.includes('/meta') && getAllUrlParams().category!=undefined){
                        this.perpage=25;
                        this.getBlogCategorybyId();
                        this.is_category = true
                    }
                    if(pathname.includes('/meta') && getAllUrlParams().tag!=undefined){
                        this.perpage=25;
                        this.getBlogTagsbyId();
                        this.is_tag = true
                    }
                    if(pathname.includes('/blog') && getAllUrlParams().title!=undefined){
                        this.getBlog();
                    }
                    if(pathname.includes('/prices')){
                        this.getActiveCryptoMethods();
                        this.getNews();
                        this.getCryptodata();
                        this.getCryptocharts();
                    }
                },
			}).mount("#app");