<?php $title = "Login"; ?>
<?php include './auth_meta.php'; ?>

<!--end::Theme mode setup on page load-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
	<!--begin::Authentication - Sign-in -->
	<div class="d-flex flex-column flex-lg-row flex-column-fluid">
		<!--begin::Body-->
		<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
			<!--begin::Form-->
			<div class="d-flex flex-center flex-column flex-lg-row-fluid">
				<!--begin::Wrapper-->
				<div class="w-lg-500px p-10">
					<!--begin::Form-->
					<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="./index.php" action="#">
						<!--begin::Heading-->
						<div class="text-center mb-11">
							<!--begin::Title-->
							<img alt="Logo" src="./assets/media/logos/StellarShift Logo.png" class="h-90px me-3" />
							<!--end::Title-->
						</div>
						<!--begin::Heading-->
						<!--begin::Login options-->
						<div class="row g-3 mb-9">
							<!--begin::Col-->
							<div class="col-12">
								<!--begin::Google link=-->
								<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
									<img alt="Google Logo" src="./assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3" />Sign in
									with Google</a>
								<!--end::Google link=-->
							</div>
							<!--end::Col-->

						</div>
						<!--end::Login options-->
						<!--begin::Separator-->
						<div class="separator separator-content my-14">
							<span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
						</div>
						<!--end::Separator-->
						<!--begin::Input group=-->
						<div class="fv-row mb-6">
							<!--begin::Email-->
							<input aria-label="email-address" type="text" placeholder="Email" name="email" v-model="email" autocomplete="off" class="form-control bg-transparent" />
							<!--end::Email-->
						</div>
						<!--end::Input group=-->
						<div class="fv-row mb-3">
							<!--begin::Password-->
							<input type="password" placeholder="Password" name="password" autocomplete="off" v-model="password" class="form-control bg-transparent" />
							<!--end::Password-->
						</div>
						<!--end::Input group=-->
						<!--begin::Wrapper-->
						<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-6">
							<div></div>
							<!--begin::Link-->
							<a href="./reset-password.php" class="link-primary">Forgot Password ?</a>
							<!--end::Link-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Submit button-->
						<div class="d-grid mb-10">
							<button type="submit" id="kt_sign_in_submit" @click.prevent="login" class="btn btn3 btn-primary">
								<span class="indicator-label">Sign In</span>
							</button>
						</div>
						<!--end::Submit button-->
						<!--begin::Sign up-->
						<div class="text-gray-500 text-center fw-semibold fs-6">
							Not a Member yet?
							<a href="./sign-up.php" class="link-primary">Sign up</a>
						</div>
						<!--end::Sign up-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Form-->
			<!--begin::Footer-->
			<div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
				<!--begin::Languages-->
				<div class="me-10">
					<!--begin::Toggle-->
					<button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
						<img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="./assets/media/flags/nigeria.svg" alt="" />
						<span data-kt-element="current-lang-name" class="me-1">English</span>
					</button>
					<!--end::Toggle-->
				</div>
				<!--end::Languages-->
				<!--begin::Links-->
				<div class="d-flex fw-semibold text-primary fs-base gap-5">
					<a href="#" target="_blank">Plans</a>
					<a href="#" target="_blank">Contact Us</a>
				</div>
				<!--end::Links-->
			</div>
			<!--end::Footer-->
		</div>
		<!--end::Body-->
	</div>
	<!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->

<?php include './auth_footer.php'; ?>