<?php include './auth_meta.php'; ?>
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Multi-steps-->
			<div
				class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column stepper-multistep"
				id="kt_create_account_stepper">
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-lg-row-auto w-lg-350px w-xl-500px">
					<div
						class="d-flex flex-column position-lg-fixed top-0 bottom-0 w-lg-350px w-xl-500px scroll-y bgi-size-cover bgi-position-center"
						style="background-image: url(assets/media/misc/auth-bg.png)">
						<!--begin::Header-->
						<div class="d-flex flex-center py-10 py-lg-20 mt-lg-20">
							<!--begin::Logo-->
							<a href="./index.html">
								<img alt="Logo" src="./assets/media/logos/custom-1.png" class="h-70px" />
							</a>
							<!--end::Logo-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="d-flex flex-row-fluid justify-content-center p-10">
							<!--begin::Nav-->
							<div class="stepper-nav">
								<!--begin::Step 1-->
								<div class="stepper-item current" data-kt-stepper-element="nav">
									<!--begin::Wrapper-->
									<div class="stepper-wrapper">
										<!--begin::Icon-->
										<div class="stepper-icon rounded-3">
											<i class="ki-duotone ki-check fs-2 stepper-check"></i>
											<span class="stepper-number">1</span>
										</div>
										<!--end::Icon-->
										<!--begin::Label-->
										<div class="stepper-label">
											<h3 class="stepper-title fs-2">Account Type</h3>
											<div class="stepper-desc fw-normal">Select your account type</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Line-->
									<div class="stepper-line h-40px"></div>
									<!--end::Line-->
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Wrapper-->
									<div class="stepper-wrapper">
										<!--begin::Icon-->
										<div class="stepper-icon rounded-3">
											<i class="ki-duotone ki-check fs-2 stepper-check"></i>
											<span class="stepper-number">2</span>
										</div>
										<!--end::Icon-->
										<!--begin::Label-->
										<div class="stepper-label">
											<h3 class="stepper-title fs-2">Proprietor Info</h3>
											<div class="stepper-desc fw-normal">Provide Proprietor's info</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Line-->
									<div class="stepper-line h-40px"></div>
									<!--end::Line-->
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Wrapper-->
									<div class="stepper-wrapper">
										<!--begin::Icon-->
										<div class="stepper-icon">
											<i class="ki-duotone ki-check fs-2 stepper-check"></i>
											<span class="stepper-number">3</span>
										</div>
										<!--end::Icon-->
										<!--begin::Label-->
										<div class="stepper-label">
											<h3 class="stepper-title fs-2">Company Details</h3>
											<div class="stepper-desc fw-normal">Setup your business details</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Line-->
									<div class="stepper-line h-40px"></div>
									<!--end::Line-->
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Wrapper-->
									<div class="stepper-wrapper">
										<!--begin::Icon-->
										<div class="stepper-icon">
											<i class="ki-duotone ki-check fs-2 stepper-check"></i>
											<span class="stepper-number">4</span>
										</div>
										<!--end::Icon-->
										<!--begin::Label-->
										<div class="stepper-label">
											<h3 class="stepper-title">Password & Security</h3>
											<div class="stepper-desc fw-normal">Provide a strong password for your company</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Line-->
									<div class="stepper-line h-40px"></div>
									<!--end::Line-->
								</div>
								<!--end::Step 4-->
								<!--begin::Step 5-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Wrapper-->
									<div class="stepper-wrapper">
										<!--begin::Icon-->
										<div class="stepper-icon">
											<i class="ki-duotone ki-check fs-2 stepper-check"></i>
											<span class="stepper-number">5</span>
										</div>
										<!--end::Icon-->
										<!--begin::Label-->
										<div class="stepper-label">
											<h3 class="stepper-title">Completed</h3>
											<div class="stepper-desc fw-normal">Your account is created</div>
										</div>
										<!--end::Label-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 5-->
							</div>
							<!--end::Nav-->
						</div>
						<!--end::Body-->
					</div>
				</div>
				<!--begin::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<!--begin::Content-->
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-650px w-xl-700px p-10 p-lg-15 mx-auto">
							<!--begin::Form-->
							<form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">
								<!--begin::Step 1-->
								<div class="current" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-15">
											<!--begin::Title-->
											<h2 class="fw-bold d-flex align-items-center text-dark">Select Company Account</h2>
											<!--end::Title-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row">
											<!--begin::Row-->
											<div class="row">
												<!--begin::Col-->
												<div class="col-lg-6">
													<!--begin::Option-->
													<input
														type="radio"
														class="btn-check"
														name="account_type"
														value="corporate"
														id="kt_create_account_form_account_type_corporate" />
													<label
														class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
														for="kt_create_account_form_account_type_corporate">
														<i class="ki-duotone ki-briefcase fs-3x me-5">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
														<!--begin::Info-->
														<span class="d-block fw-semibold text-start">
															<span class="text-dark fw-bold d-block fs-4 mb-2">Company Account</span>
															<span class="text-muted fw-semibold fs-6">Create company account to mane users</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div class="" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-15">
											<!--begin::Title-->
											<h2 class="fw-bold text-dark">Company Account Info</h2>
											<!--end::Title-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="mb-10 fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center form-label mb-3"
												>Specify Staff Size
												<span
													class="ms-1"
													data-bs-toggle="tooltip"
													title="Provide your staff size to help us setup your billing">
													<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i> </span
											></label>
											<!--end::Label-->
											<!--begin::Row-->
											<div class="row mb-2" data-kt-buttons="true">
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size" value="1-1" />
														<span class="fw-bold fs-3">1-1</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 active">
														<input
															type="radio"
															class="btn-check"
															name="account_team_size"
															checked="checked"
															value="2-10" />
														<span class="fw-bold fs-3">2-10</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size" value="10-50" />
														<span class="fw-bold fs-3">10-50</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col">
													<!--begin::Option-->
													<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
														<input type="radio" class="btn-check" name="account_team_size" value="50+" />
														<span class="fw-bold fs-3">50+</span>
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
											<!--begin::Hint-->
											<div class="form-text">Customers will see this shortened version of your statement descriptor</div>
											<!--end::Hint-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="mb-10 fv-row">
											<!--begin::Label-->
											<label class="form-label mb-3">Proprietor's Fullname</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input
												type="text"
												class="form-control form-control-lg form-control-solid"
												name="account_name"
												placeholder=""
												value="" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="form-label required">Proprietor's Position</label>
											<!--end::Label-->
											<!--begin::Input-->
											<select
												name="properitor_position"
												class="form-select form-select-lg form-select-solid"
												data-control="select2"
												data-placeholder="Select..."
												data-allow-clear="true"
												data-hide-search="true">
												<option></option>
												<option value="1">CEO</option>
												<option value="2">Managing Director</option>
												<option value="3">Sole Proprietor</option>
											</select>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 2-->
								<!--xxegin::Step 3-->
								<div class="" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-12">
											<!--begin::Title-->
											<h2 class="fw-bold text-dark">Company Details</h2>
											<!--end::Title-->
										</div>
										<!--end::Heading-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="form-label required">Company Name</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input name="business_name" class="form-control form-control-lg form-control-solid" value="" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="form-label required">Company Corporation Type</label>
											<!--end::Label-->
											<!--begin::Input-->
											<select
												name="business_type"
												class="form-select form-select-lg form-select-solid"
												data-control="select2"
												data-placeholder="Select..."
												data-allow-clear="true"
												data-hide-search="true">
												<option></option>
												<option value="1">S Corporation</option>
												<option value="1">C Corporation</option>
												<option value="2">Sole Proprietorship</option>
												<option value="3">Non-profit</option>
												<option value="4">Limited Liability</option>
												<option value="5">General Partnership</option>
											</select>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--end::Label-->
											<label class="form-label">Company's Description</label>
											<!--end::Label-->
											<!--begin::Input-->
											<textarea
												name="business_description"
												class="form-control form-control-lg form-control-solid"
												rows="3"></textarea>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-0">
											<!--begin::Label-->
											<label class="fs-6 fw-semibold form-label required">Company's Contact Email</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input name="business_email" class="form-control form-control-lg form-control-solid" value="" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-0 mt-8">
											<!--begin::Label-->
											<label class="fs-6 fw-semibold form-label required">Company's Contact Hotline</label>
											<!--end::Label-->
											<!--begin::Input-->
											<input
												name="business_phone"
												class="form-control form-control-lg form-control-solid"
												value=""
												type="tel"
												placeholder="e.g +234 **** **** **" />
											<!--end::Input-->
										</div>
										<!--end::Input group-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								<div class="" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-10 pb-lg-3">
											<!--begin::Title-->
											<h2 class="fw-bold text-dark">Password</h2>
											<!--end::Title-->
										</div>
										<!--end::Heading-->
										<div class="d-flex flex-center align-items-start flex-column flex-lg-row-fluid">
											<!--begin::Wrapper-->
											<div class="w-lg-500px py-10">
												<!--begin::Input group-->
												<div class="fv-row mb-8" data-kt-password-meter="true">
													<!--begin::Wrapper-->
													<div class="mb-1">
														<!--begin::Input wrapper-->
														<div class="position-relative mb-3">
															<input
																class="form-control bg-transparent"
																type="password"
																placeholder="Password"
																name="password"
																autocomplete="off" />
															<span
																class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
																data-kt-password-meter-control="visibility">
																<i class="ki-duotone ki-eye-slash fs-2"></i>
																<i class="ki-duotone ki-eye fs-2 d-none"></i>
															</span>
														</div>
														<!--end::Input wrapper-->
														<!--begin::Meter-->
														<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
															<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
															<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
															<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
															<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
														</div>
														<!--end::Meter-->
													</div>
													<!--end::Wrapper-->
													<!--begin::Hint-->
													<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
													<!--end::Hint-->
												</div>
												<!--end::Input group=-->
												<!--end::Input group=-->
												<div class="fv-row mb-8">
													<!--begin::Repeat Password-->
													<input
														placeholder="Repeat Password"
														name="confirm-password"
														type="password"
														autocomplete="off"
														class="form-control bg-transparent" />
													<!--end::Repeat Password-->
												</div>
												<!--end::Input group=-->
												<!--begin::Accept-->
												<div class="fv-row mb-8">
													<label class="form-check form-check-inline">
														<input
															class="form-check-input"
															aria-label="terms-policy"
															type="checkbox"
															name="toc"
															value="1" />
														<span class="form-check-label fw-semibold text-gray-700 fs-base ms-1"
															>I Accept the <a href="#" class="ms-1 link-primary">Terms & Condition</a></span
														>
													</label>
												</div>
												<!--end::Accept-->
											</div>
											<!--end::Wrapper-->
										</div>
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 4-->
								<!--begin::Step 5-->
								<div class="" data-kt-stepper-element="content">
									<!--begin::Wrapper-->
									<div class="w-100">
										<!--begin::Heading-->
										<div class="pb-8 pb-lg-10">
											<!--begin::Title-->
											<h2 class="fw-bold text-dark">Your Are Done!</h2>
											<!--end::Title-->
											<!--begin::Notice-->
											<div class="text-muted fw-semibold fs-6">
												If you need more info, please
												<a href="./sign-in.html" class="link-primary fw-bold">Sign In</a>.
											</div>
											<!--end::Notice-->
										</div>
										<!--end::Heading-->
										<!--begin::Body-->
										<div class="mb-0">
											<!--begin::Text-->
											<div class="fs-6 text-gray-600 mb-5">
												Writing headlines for blog posts is as much an art as it is a science and probably warrants its own
												post, but for all advise is with what works for your great & amazing audience.
											</div>
											<!--end::Text-->
											<!--begin::Alert-->
											<!--begin::Notice-->
											<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
												<!--begin::Icon-->
												<i class="ki-duotone ki-information fs-2tx text-warning me-4">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
												</i>
												<!--end::Icon-->
												<!--begin::Wrapper-->
												<div class="d-flex flex-stack flex-grow-1">
													<!--begin::Content-->
													<div class="fw-semibold">
														<h4 class="text-gray-900 fw-bold">We need your attention!</h4>
														<div class="fs-6 text-gray-700">
															To start using great tools, please,
															<a href="./utilities/wizards/vertical.html" class="fw-bold">Create Team Platform</a>
														</div>
													</div>
													<!--end::Content-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Notice-->
											<!--end::Alert-->
										</div>
										<!--end::Body-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Step 5-->
								<!--begin::Actions-->
								<div class="d-flex flex-stack pt-15">
									<div class="mr-2">
										<button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
											<i class="ki-duotone ki-arrow-left fs-4 me-1">
												<span class="path1"></span>
												<span class="path2"></span> </i
											>Previous
										</button>
									</div>
									<div>
										<button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
											<span class="indicator-label"
												>Submit
												<i class="ki-duotone ki-arrow-right fs-4 ms-2">
													<span class="path1"></span>
													<span class="path2"></span> </i
											></span>
											<span class="indicator-progress"
												>Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span
											></span>
										</button>
										<button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
											Continue
											<i class="ki-duotone ki-arrow-right fs-4 ms-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</button>
									</div>
								</div>
								<!--end::Actions-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Multi-steps-->
		</div>
		<!--end::Root-->
<?php include './auth_footer.php'; ?>