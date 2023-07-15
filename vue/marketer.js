

const urlPath = window.location.pathname.split("/");
const length = urlPath.length;
const webPage = urlPath[length - 1];

let admin = Vue.createApp({
    data() {
        return {
            // baseUrl: 'http://localhost/cardify',
            baseUrl: 'https://app.cardify.co/',
            mainUrl: 'https://app.cardify.co/',
            email:'',
            password:'',
            loading:false,
            admin_details:{
                "id": 0,
                "email": "",
                "name": "",
                "status": "",
                "username": "",
                "about": "",
                "profile_img": "",
                "phoneno": "",
                "dob": "",
                "team_tag": "",
                "track_id": "",
                "ref_code": "",
                "balance": "",
                "teamname": "",
                "teamtag": ""
            },
            adminstat:{
                "availablebalance": "0",
                "totalearned": "0",
                "totalwithdrawed": "0",
                "allusercount": "0",
                "allusermonthcount": "0",
                "allusertodaycount": "0",
                "totaluser_level1_0": "0",
                "totaluser_level1": "0",
                "totaluser_level0": "0",
                "totaluser_level2_3": "0",
                "totaluser_level2": "0",
                "totaluser_level3": "0",
                "totaluser_success_vc": "0",
                "totaluser_failed_vc": "0",
                "totaluser_usage_vc": "0",
                "totaluser_success_wallet": "0",
                "totaluser_failed_wallet": "0",
                "totaluser_usage_wallet": "0",
                "totaluser_success_swap": "0",
                "totaluser_failed_swap": "0",
                "totaluser_usage_swap": "0",
                "totaluser_success_bills": "0",
                "totaluser_failed_bills": "0",
                "totaluser_usage_bills": "0",
                "total_user_used_refcode": "0",
                "total_user_used_refcode_today": "0",
                "recently_added_user": [
                    {
                        "id": 369,
                        "username": "",
                        "fname": " ",
                        "email": " ",
                        "lname": " "
                    },
                ],
                "recently_added_notifications": [
                    {
                        "username": "",
                        "fname": "",
                        "email": "",
                        "lname": "",
                        "notificationtext": ""
                    },
                ]
            },
            authToken:'',
            accno:'',
            accname:'',
            bankname:'',
            oldpass:'',
            newpass:'',
            confirmpass:'',
            sessionlog:null,
            users:null,
            perpage:10,
            page:1,
            sortrecent:true,
            search:'',
            verified_email:false,
            verified_phone:false,
            userlevel:'',
            userdatais:null,
        }
    },
    methods: {
        // AUTH   // auth
        async login() {
            const email = this.email;
            const password = this.password
            // console.log("email=", email, "password=", password);
            if (email == null || password == null) {
                this.error = "Kindly Enter all Fields"
                new Toasteur().error(this.error);;
            }

            let data = new FormData();
            data.append('email', this.email);
            data.append('password', this.password);

            const url = `${this.baseUrl}/api/marketers/auth/login.php`;

            const options = {
                method: "POST",
                data: data,
                url
            }
            try {
                const response = await axios(options);
                if (response.data.status) {
                    this.swalToast('success', response.data.text);
                    window.localStorage.setItem("topauthToken", response.data.data.authtoken);
                    let currentWebPage = window.localStorage.getItem("currentWebPage")
                    // if(currentWebPage){
                    //     window.location.href=currentWebPage;
                    //     return;
                    // }
                    window.location.href = "dashboard.php"
                }
                //console.log(response.data);

            } catch (error) {
                if (error.response) {
                    if (error.response.status == 400) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 401) {
                        window.localStorage.removeItem('token')
                        this.error = "User not Authorized";
                        // this.swalToast("error",this.error);
                        return
                    }


                    if (error.response.status == 405) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 500) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }
                }

                this.error = error.message || "Error Processing Request"
                this.swalToast("error", this.error);

            } finally {
                this.loading = false;
            }
        },
        // user
        async getAdminDetails() {
            const url = `${this.baseUrl}/api/marketers/userdata/get_marketer_data.php`;
            const options = {
                method: "GET",
                headers: {
                    "Content-type": "application/json",
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }

            try {
                this.admin_details = null;
                this.loading = true;
                let response = await axios(options)
                if (response.data.status) {
                    this.admin_details = response.data.data;
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status === 400) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);

                    }
                    if (error.response.status === 401) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                        window.location.href = "index.php"
                    }
                    if (error.response.status === 405) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                    if (error.response.status === 500) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                } else {
                    this.error = error.message || "Error processing request"
                    this.swalToast("error", this.error);
                }
            } finally {
                this.loading = false;
            }
        },
        async getAdminStat() {
            const url = `${this.baseUrl}/api/marketers/userdata/get_marketer_stat.php`;
            const options = {
                method: "GET",
                headers: {
                    "Content-type": "application/json",
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }

            try {
                this.adminstat = null;
                this.loading = true;
                let response = await axios(options)
                if (response.data.status) {
                    this.adminstat = response.data.data;
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status === 400) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);

                    }
                    if (error.response.status === 401) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                        window.location.href = "index.php"
                    }
                    if (error.response.status === 405) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                    if (error.response.status === 500) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                } else {
                    this.error = error.message || "Error processing request"
                    this.swalToast("error", this.error);
                }
            } finally {
                this.loading = false;
            }
        },
        // system
        async getToken() {
            this.loading = true
            const token = window.localStorage.getItem("topauthToken");
            this.authToken = token;
        },
        async logout() {
            window.localStorage.removeItem("topauthToken");
            window.location.href = this.mainUrl
        },
        async withdrawFund() {
                 this.loading = true;
             $('#modalForm').modal('hide');
            const accno = this.accno;
            const accname = this.accname
            const bankname = this.bankname
            // console.log("email=", email, "password=", password);
            if (accno == null || accname == null||bankname==null) {
                this.error = "Kindly Enter all Fields"
                new Toasteur().error(this.error);;
            }

            let data = new FormData();
            data.append('accno', this.accno);
            data.append('accname', this.accname);
            data.append('bankname', this.bankname);

            const url = `${this.baseUrl}/api/marketers/transactions/withdraw_fund.php`;

            const options = {
                method: "POST",
                data: data,
                headers: {
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }
            try {
                const response = await axios(options);
                if (response.data.status) {
                    this.swalToast('success', response.data.text);
                    this.getAdminStat() 
                    this.getAdminDetails()
                    
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status == 400) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 401) {
                        window.localStorage.removeItem('token')
                        this.error = "User not Authorized";
                        // this.swalToast("error",this.error);
                        window.location.href = "index.php"
                        return
                    }


                    if (error.response.status == 405) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 500) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }
                }

                this.error = error.message || "Error Processing Request"
                this.swalToast("error", this.error);

            } finally {
                this.loading = false;
            }
        },
        async updateProfile() {
                 this.loading = true;
             $('#profile-edit').modal('hide');
         
            let data = new FormData();
            data.append('name', this.admin_details.name);
            data.append('dob', this.admin_details.dob);
            data.append('pno', this.admin_details.phoneno);
            data.append('abt', this.admin_details.about);

            const url = `${this.baseUrl}/api/marketers/userdata/updateAdmin.php`;

            const options = {
                method: "POST",
                data: data,
                headers: {
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }
            try {
                const response = await axios(options);
                if (response.data.status) {
                    this.swalToast('success', response.data.text);
                    this.getAdminDetails()
                    
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status == 400) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 401) {
                        window.localStorage.removeItem('token')
                        this.error = "User not Authorized";
                        // this.swalToast("error",this.error);
                        window.location.href = "index.php"
                        return
                    }


                    if (error.response.status == 405) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 500) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }
                }

                this.error = error.message || "Error Processing Request"
                this.swalToast("error", this.error);

            } finally {
                this.loading = false;
            }
        },
        async updatePassword() {
                 this.loading = true;
          
         
            let data = new FormData();
            data.append('password', this.newpass);
            data.append('cpassword', this.confirmpass);
            data.append('oldpassword', this.oldpass);

            const url = `${this.baseUrl}/api/marketers/userdata/change_password.php`;
            $('#profile-edit').modal('hide');
            const options = {
                method: "POST",
                data: data,
                headers: {
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }
            try {
                const response = await axios(options);
                if (response.data.status) {
                    this.swalToast('success', response.data.text);
                    this.getAdminDetails()
                    
                }
                 $('#profile-edit').modal('hide');
            } catch (error) {
                   $('#profile-edit').modal('hide');
                if (error.response) {
                    if (error.response.status == 400) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 401) {
                        window.localStorage.removeItem('token')
                        this.error = "User not Authorized";
                        // this.swalToast("error",this.error);
                        window.location.href = "index.php"
                        return
                    }


                    if (error.response.status == 405) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 500) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }
                }

                this.error = error.message || "Error Processing Request"
                this.swalToast("error", this.error);

            } finally {
                this.loading = false;
            }
        },
        async getAdminSessionLog() {
            const url = `${this.baseUrl}/api/marketers/userdata/user_session_log.php`;
            const options = {
                method: "POST",
                headers: {
                    "Content-type": "application/json",
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }

            try {
                this.sessionlog = null;
                this.loading = true;
                let response = await axios(options)
                if (response.data.status) {
                    this.sessionlog = response.data.data.userdata;
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status === 400) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);

                    }
                    if (error.response.status === 401) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                        window.location.href = "index.php"
                    }
                    if (error.response.status === 405) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                    if (error.response.status === 500) {
                        this.error = error.response.data.error.text
                        this.swalToast("error", this.error);
                    }
                } else {
                    this.error = error.message || "Error processing request"
                    this.swalToast("error", this.error);
                }
            } finally {
                this.loading = false;
            }
        },
        async getAllUsers(load=1) {
            if(load==1){
                 this.loading = true;
            }
            let data = new FormData();
            data.append('userlevel', this.userlevel);
            data.append('userphone', this.verified_phone);
            data.append('useremail', this.verified_email);
            data.append('search', this.search);
            data.append('sortrecent', this.sortrecent);
            data.append('page', this.page);
            data.append('per_page', this.perpage);

            const url = `${this.baseUrl}/api/marketers/users/allusers.php`;

            const options = {
                method: "POST",
                data: data,
                headers: {
                    "Authorization": `Bearer ${this.authToken}`
                },
                url
            }
            try {
                const response = await axios(options);
                if (response.data.status) {
                    this.userdatais=response.data.data;
                      this.users = response.data.data.users;
                }

            } catch (error) {
                if (error.response) {
                    if (error.response.status == 400) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 401) {
                        window.localStorage.removeItem('token')
                        this.error = "User not Authorized";
                         window.location.href = "index.php"
                        // this.swalToast("error",this.error);
                        return
                    }


                    if (error.response.status == 405) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }

                    if (error.response.status == 500) {
                        this.error = error.response.data.text;
                        this.swalToast("error", this.error);
                        return
                    }
                }

                this.error = error.message || "Error Processing Request"
                this.swalToast("error", this.error);

            } finally {
                this.loading = false;
            }
        },
        //.....utilities..............
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
    },
    beforeMount() {
        if (!webPage.includes("index.php") && !webPage.includes("index")) {
            // window.localStorage.setItem("currentWebPage", webPage);
            this.loading = true;
            this.getToken();
            if (!this.authToken) {
                window.location.href = "index.php";
            }
        }
    },
    async mounted() {
        if (webPage.includes("index.php") || webPage.includes("index") || webPage == "") {
            await this.topUserTransactions();
        }
        if (webPage.includes("dashboard.php") || webPage.includes("dashboard")) {
            await this.getAdminStat();
        }
        if (webPage.includes("profile.php") || webPage.includes("profile")) {
            await this.getAdminSessionLog();
        }
        if (webPage.includes("users.php") || webPage.includes("users")) {
            await this.getAllUsers();
        }
        
        
        this.getAdminDetails()
    }
});
window.onload = function() {
    admin.mount('#marketers');
}