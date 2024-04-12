<?php $title = "Register"; ?>
<?php include './auth_meta.php'; ?>

<!--begin::Root-->
<div class="row col-12 m-0 align-items-start flex-root" id="kt_app_root">
	<!-- v-image-content @::start  -->
	<div class="col-lg-6 d-none d-lg-block v-all-images-container">
		<div class="v-image-presentation">
			<div class="v-floating-stars">
				<svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" width="339" height="1024" viewBox="0 0 339 1024" fill="none">
					<path d="M206.706 226.202L228.957 286.335L229.037 286.55L229.252 286.63L289.385 308.881L229.252 331.132L229.037 331.212L228.957 331.428L206.706 391.56L184.455 331.428L184.375 331.212L184.16 331.132L124.027 308.881L184.16 286.63L184.375 286.55L184.455 286.335L206.706 226.202Z" stroke="currentColor" />
					<path d="M206.706 50.7464L228.957 110.879L229.037 111.094L229.252 111.174L289.385 133.425L229.252 155.676L229.037 155.756L228.957 155.972L206.706 216.104L184.455 155.972L184.375 155.756L184.16 155.676L124.027 133.425L184.16 111.174L184.375 111.094L184.455 110.879L206.706 50.7464Z" stroke="currentColor" />
					<path d="M206.706 419.811L228.957 480.245L229.036 480.46L229.252 480.54L289.391 502.906L229.252 525.271L229.036 525.351L228.957 525.567L206.706 586.001L184.455 525.567L184.376 525.351L184.16 525.271L124.021 502.906L184.16 480.54L184.376 480.46L184.455 480.245L206.706 419.811Z" stroke="currentColor" />
					<path d="M84.1196 134.896L106.371 195.029L106.45 195.245L106.666 195.324L166.798 217.575L106.666 239.826L106.45 239.906L106.371 240.122L84.1196 300.254L61.8686 240.122L61.7888 239.906L61.5732 239.826L1.44077 217.575L61.5732 195.324L61.7888 195.245L61.8686 195.029L84.1196 134.896Z" stroke="currentColor" />
					<path d="M84.1196 -40.5592L106.371 19.5732L106.45 19.7888L106.666 19.8686L166.798 42.1196L106.666 64.3706L106.45 64.4504L106.371 64.666L84.1196 124.798L61.8686 64.666L61.7888 64.4504L61.5732 64.3706L1.44077 42.1196L61.5732 19.8686L61.7888 19.7888L61.8686 19.5732L84.1196 -40.5592Z" stroke="currentColor" />
					<path d="M84.1196 494.202L106.371 554.334L106.45 554.55L106.666 554.629L166.798 576.88L106.666 599.131L106.45 599.211L106.371 599.427L84.1196 659.559L61.8686 599.427L61.7888 599.211L61.5732 599.131L1.44077 576.88L61.5732 554.629L61.7888 554.55L61.8686 554.334L84.1196 494.202Z" stroke="currentColor" />
					<path d="M84.1196 317.508L106.371 377.64L106.45 377.856L106.666 377.936L166.798 400.187L106.666 422.438L106.45 422.518L106.371 422.733L84.1196 482.866L61.8686 422.733L61.7888 422.518L61.5732 422.438L1.44077 400.187L61.5732 377.936L61.7888 377.856L61.8686 377.64L84.1196 317.508Z" stroke="currentColor" />
					<path d="M338.592 134.896L360.843 195.029L360.923 195.245L361.139 195.324L421.271 217.575L361.139 239.826L360.923 239.906L360.843 240.122L338.592 300.254L316.341 240.122L316.261 239.906L316.046 239.826L255.913 217.575L316.046 195.324L316.261 195.245L316.341 195.029L338.592 134.896Z" stroke="currentColor" />
					<path d="M338.592 -40.5592L360.843 19.5732L360.923 19.7888L361.139 19.8686L421.271 42.1196L361.139 64.3706L360.923 64.4504L360.843 64.666L338.592 124.798L316.341 64.666L316.261 64.4504L316.046 64.3706L255.913 42.1196L316.046 19.8686L316.261 19.7888L316.341 19.5732L338.592 -40.5592Z" stroke="currentColor" />
					<path d="M338.592 494.202L360.843 554.334L360.923 554.55L361.139 554.629L421.271 576.88L361.139 599.131L360.923 599.211L360.843 599.427L338.592 659.559L316.341 599.427L316.261 599.211L316.046 599.131L255.913 576.88L316.046 554.629L316.261 554.55L316.341 554.334L338.592 494.202Z" stroke="currentColor" />
					<path d="M338.592 317.508L360.843 377.64L360.923 377.856L361.139 377.936L421.271 400.187L361.139 422.438L360.923 422.518L360.843 422.733L338.592 482.866L316.341 422.733L316.261 422.518L316.046 422.438L255.913 400.187L316.046 377.936L316.261 377.856L316.341 377.64L338.592 317.508Z" stroke="currentColor" />
					<path d="M206.706 928.202L228.957 988.335L229.037 988.55L229.252 988.63L289.385 1010.88L229.252 1033.13L229.037 1033.21L228.957 1033.43L206.706 1093.56L184.455 1033.43L184.375 1033.21L184.16 1033.13L124.027 1010.88L184.16 988.63L184.375 988.55L184.455 988.335L206.706 928.202Z" stroke="currentColor" />
					<path d="M206.706 752.746L228.957 812.879L229.037 813.094L229.252 813.174L289.385 835.425L229.252 857.676L229.037 857.756L228.957 857.972L206.706 918.104L184.455 857.972L184.375 857.756L184.16 857.676L124.027 835.425L184.16 813.174L184.375 813.094L184.455 812.879L206.706 752.746Z" stroke="currentColor" />
					<path d="M84.1196 836.896L106.371 897.029L106.45 897.245L106.666 897.324L166.798 919.575L106.666 941.826L106.45 941.906L106.371 942.122L84.1196 1002.25L61.8686 942.122L61.7888 941.906L61.5732 941.826L1.44077 919.575L61.5732 897.324L61.7888 897.245L61.8686 897.029L84.1196 836.896Z" stroke="currentColor" />
					<path d="M84.1196 661.441L106.371 721.573L106.45 721.789L106.666 721.869L166.798 744.12L106.666 766.371L106.45 766.45L106.371 766.666L84.1196 826.798L61.8686 766.666L61.7888 766.45L61.5732 766.371L1.44077 744.12L61.5732 721.869L61.7888 721.789L61.8686 721.573L84.1196 661.441Z" stroke="currentColor" />
					<path d="M84.1196 1019.51L106.371 1079.64L106.45 1079.86L106.666 1079.94L166.798 1102.19L106.666 1124.44L106.45 1124.52L106.371 1124.73L84.1196 1184.87L61.8686 1124.73L61.7888 1124.52L61.5732 1124.44L1.44077 1102.19L61.5732 1079.94L61.7888 1079.86L61.8686 1079.64L84.1196 1019.51Z" stroke="currentColor" />
					<path d="M338.592 836.896L360.843 897.029L360.923 897.245L361.139 897.324L421.271 919.575L361.139 941.826L360.923 941.906L360.843 942.122L338.592 1002.25L316.341 942.122L316.261 941.906L316.046 941.826L255.913 919.575L316.046 897.324L316.261 897.245L316.341 897.029L338.592 836.896Z" stroke="currentColor" />
					<path d="M338.592 661.441L360.843 721.573L360.923 721.789L361.139 721.869L421.271 744.12L361.139 766.371L360.923 766.45L360.843 766.666L338.592 826.798L316.341 766.666L316.261 766.45L316.046 766.371L255.913 744.12L316.046 721.869L316.261 721.789L316.341 721.573L338.592 661.441Z" stroke="currentColor" />
					<path d="M338.592 1019.51L360.843 1079.64L360.923 1079.86L361.139 1079.94L421.271 1102.19L361.139 1124.44L360.923 1124.52L360.843 1124.73L338.592 1184.87L316.341 1124.73L316.261 1124.52L316.046 1124.44L255.913 1102.19L316.046 1079.94L316.261 1079.86L316.341 1079.64L338.592 1019.51Z" stroke="currentColor" />
				</svg>

			</div>
			<div class="v-svg-container col-lg-10 mx-auto">
				<svg class="animated w-100 h-100" id="freepik_stories-shared-workspace" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs">
					<style>
						svg#freepik_stories-shared-workspace:not(.animated) .animable {
							opacity: 0;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Floor--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) fadeIn;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Shadows--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) fadeIn;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Window--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideUp;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Board--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideRight;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--character-3--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideDown;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--character-2--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideDown;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Desk--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideLeft;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--Plants--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) lightSpeedRight;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--character-1--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) slideRight;
							animation-delay: 0s;
						}

						svg#freepik_stories-shared-workspace.animated #freepik--speech-bubble--inject-127 {
							animation: 1s 1 forwards cubic-bezier(.36, -0.01, .5, 1.38) fadeIn;
							animation-delay: 0s;
						}

						@keyframes fadeIn {
							0% {
								opacity: 0;
							}

							100% {
								opacity: 1;
							}
						}

						@keyframes slideUp {
							0% {
								opacity: 0;
								transform: translateY(30px);
							}

							100% {
								opacity: 1;
								transform: inherit;
							}
						}

						@keyframes slideRight {
							0% {
								opacity: 0;
								transform: translateX(30px);
							}

							100% {
								opacity: 1;
								transform: translateX(0);
							}
						}

						@keyframes slideDown {
							0% {
								opacity: 0;
								transform: translateY(-30px);
							}

							100% {
								opacity: 1;
								transform: translateY(0);
							}
						}

						@keyframes slideLeft {
							0% {
								opacity: 0;
								transform: translateX(-30px);
							}

							100% {
								opacity: 1;
								transform: translateX(0);
							}
						}

						@keyframes lightSpeedRight {
							from {
								transform: translate3d(50%, 0, 0) skewX(-20deg);
								opacity: 0;
							}

							60% {
								transform: skewX(10deg);
								opacity: 1;
							}

							80% {
								transform: skewX(-2deg);
							}

							to {
								opacity: 1;
								transform: translate3d(0, 0, 0);
							}
						}
					</style>
					<g id="freepik--Floor--inject-127" class="animable" style="transform-origin: 250px 355.72px;">
						<ellipse id="freepik--floor--inject-127" cx="250" cy="355.72" rx="240.78" ry="139.01" style="fill: rgb(250, 250, 250); transform-origin: 250px 355.72px;" class="animable"></ellipse>
					</g>
					<g id="freepik--Shadows--inject-127" class="animable" style="transform-origin: 243.678px 363.254px;">
						<path id="freepik--Shadow--inject-127" d="M463.08,335,211.42,480.35a2.18,2.18,0,0,1-2,0L57.64,392.69a.61.61,0,0,1,0-1.15L309.3,246.15a2.25,2.25,0,0,1,2,0l151.79,87.66A.61.61,0,0,1,463.08,335Z" style="fill: rgb(230, 230, 230); transform-origin: 260.397px 363.254px;" class="animable"></path>
						<path id="freepik--shadow--inject-127" d="M362.82,471.27l-73.91-42.53a1.18,1.18,0,0,1,0-2.23l74.84-43.45a4.25,4.25,0,0,1,3.85,0l73.92,42.53a1.18,1.18,0,0,1,0,2.23l-74.84,43.45A4.27,4.27,0,0,1,362.82,471.27Z" style="fill: rgb(230, 230, 230); transform-origin: 365.215px 427.165px;" class="animable"></path>
						<path id="freepik--shadow--inject-127" d="M98.51,395.28,24.59,352.75a1.18,1.18,0,0,1,0-2.23l74.84-43.44a4.27,4.27,0,0,1,3.86,0l73.91,42.54a1.18,1.18,0,0,1,0,2.23l-74.84,43.44A4.22,4.22,0,0,1,98.51,395.28Z" style="fill: rgb(230, 230, 230); transform-origin: 100.895px 351.184px;" class="animable"></path>
						<path id="freepik--shadow--inject-127" d="M102.64,435.82c11.24,6.56,11.24,17.2,0,23.76s-29.45,6.56-40.69,0-11.24-17.2,0-23.76S91.41,429.26,102.64,435.82Z" style="fill: rgb(230, 230, 230); transform-origin: 82.295px 447.7px;" class="animable"></path>
					</g>
					<g id="freepik--Window--inject-127" class="animable" style="transform-origin: 80.22px 102.365px;">
						<g id="freepik--window--inject-127" class="animable" style="transform-origin: 80.22px 102.365px;">
							<g id="freepik--window--inject-127" class="animable" style="transform-origin: 80.22px 102.365px;">
								<polygon points="22.04 183.23 24.32 183.39 137.26 118.14 136.12 117.31 22.04 183.23" style="fill: rgb(250, 250, 250); transform-origin: 79.65px 150.35px;" id="eln7zcc9n4zi" class="animable"></polygon>
								<polygon points="136.12 117.31 24.32 181.91 24.32 87.26 136.12 22.65 136.12 117.31" style="fill: rgb(255, 255, 255); transform-origin: 80.22px 102.28px;" id="el42qbwxy58f1" class="animable"></polygon>
								<path d="M139.55,18,22,85.94V187.35l117.51-67.89Zm-2.29,100.1L24.32,183.39V87.26L137.26,22Z" style="fill: rgb(240, 240, 240); transform-origin: 80.775px 102.675px;" id="ellvkbtyv1fa" class="animable"></path>
								<polygon points="137.26 118.14 136.12 117.31 136.12 22.65 137.26 22.01 137.26 118.14" style="fill: rgb(224, 224, 224); transform-origin: 136.69px 70.075px;" id="elswdleou5rc" class="animable"></polygon>
								<polygon points="139.55 18.04 138.41 17.38 20.89 85.28 22.04 85.94 139.55 18.04" style="fill: rgb(250, 250, 250); transform-origin: 80.22px 51.66px;" id="elned3htghpm" class="animable"></polygon>
								<polygon points="20.89 85.28 22.04 85.94 22.04 187.35 20.89 186.69 20.89 85.28" style="fill: rgb(224, 224, 224); transform-origin: 21.465px 136.315px;" id="elemoq9y44ltj" class="animable"></polygon>
								<polygon points="24.32 134.47 136.12 69.84 137.26 70.53 24.32 135.78 24.32 134.47" style="fill: rgb(250, 250, 250); transform-origin: 80.79px 102.81px;" id="elrl4coilxnfk" class="animable"></polygon>
								<polygon points="137.26 70.53 137.26 73.08 24.32 138.34 24.32 135.78 137.26 70.53" style="fill: rgb(240, 240, 240); transform-origin: 80.79px 104.435px;" id="el7pkzo72hiw6" class="animable"></polygon>
							</g>
							<g id="freepik--Blinds--inject-127" class="animable" style="transform-origin: 80.52px 77.61px;">
								<polygon points="24.32 89.17 136.12 24.57 136.72 27.26 24.32 92.22 24.32 89.17" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 58.395px;" id="ell32dfflgr5h" class="animable"></polygon>
								<polygon points="24.32 89.17 24.32 90.4 136.36 25.64 136.12 24.57 24.32 89.17" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 57.485px;" id="el857mbeiuqu" class="animable"></polygon>
								<polygon points="24.32 93.44 136.12 28.84 136.72 31.52 24.32 96.49 24.32 93.44" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 62.665px;" id="el8uy9c2y5ba8" class="animable"></polygon>
								<polygon points="24.32 93.44 24.32 94.67 136.36 29.91 136.12 28.84 24.32 93.44" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 61.755px;" id="el52dit6y95x9" class="animable"></polygon>
								<polygon points="24.32 97.71 136.12 33.11 136.72 35.79 24.32 100.76 24.32 97.71" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 66.935px;" id="el05wi8yopyc1m" class="animable"></polygon>
								<polygon points="24.32 97.71 24.32 98.94 136.36 34.18 136.12 33.11 24.32 97.71" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 66.025px;" id="elchm8bf0c62l" class="animable"></polygon>
								<polygon points="24.32 101.98 136.12 37.38 136.72 40.06 24.32 105.03 24.32 101.98" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 71.205px;" id="elvhh5xu9at" class="animable"></polygon>
								<polygon points="24.32 101.98 24.32 103.2 136.36 38.45 136.12 37.38 24.32 101.98" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 70.29px;" id="eluiistdhzpm" class="animable"></polygon>
								<polygon points="24.32 106.25 136.12 41.65 136.72 44.33 24.32 109.3 24.32 106.25" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 75.475px;" id="elqbezm1r8ps9" class="animable"></polygon>
								<polygon points="24.32 106.25 24.32 107.47 136.36 42.72 136.12 41.65 24.32 106.25" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 74.56px;" id="elnrpcrkf3om" class="animable"></polygon>
								<polygon points="24.32 110.52 136.12 45.92 136.72 48.6 24.32 113.57 24.32 110.52" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 79.745px;" id="elzy83e2fbo5" class="animable"></polygon>
								<polygon points="24.32 110.52 24.32 111.74 136.36 46.99 136.12 45.92 24.32 110.52" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 78.83px;" id="eljv1e5whrdnh" class="animable"></polygon>
								<polygon points="24.32 114.79 136.12 50.19 136.72 52.87 24.32 117.84 24.32 114.79" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 84.015px;" id="elsjct9c7gxx" class="animable"></polygon>
								<polygon points="24.32 114.79 24.32 116.01 136.36 51.26 136.12 50.19 24.32 114.79" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 83.1px;" id="elot8fnqlfkx" class="animable"></polygon>
								<polygon points="24.32 119.06 136.12 54.46 136.72 57.14 24.32 122.11 24.32 119.06" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 88.285px;" id="ely7c8lbe6m9" class="animable"></polygon>
								<polygon points="24.32 119.06 24.32 120.28 136.36 55.52 136.12 54.46 24.32 119.06" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 87.37px;" id="el8xbez432h3f" class="animable"></polygon>
								<polygon points="24.32 123.32 136.12 58.73 136.72 61.41 24.32 126.38 24.32 123.32" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 92.555px;" id="eldnj5f6g5v8r" class="animable"></polygon>
								<polygon points="24.32 123.32 24.32 124.55 136.36 59.79 136.12 58.73 24.32 123.32" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 91.64px;" id="elvnqic1h16ss" class="animable"></polygon>
								<polygon points="24.32 127.59 136.12 63 136.72 65.68 24.32 130.65 24.32 127.59" style="fill: rgb(240, 240, 240); transform-origin: 80.52px 96.825px;" id="eltoxejp3mau" class="animable"></polygon>
								<polygon points="24.32 127.59 24.32 128.82 136.36 64.06 136.12 63 24.32 127.59" style="fill: rgb(224, 224, 224); transform-origin: 80.34px 95.91px;" id="el4hwpwhxlu3x" class="animable"></polygon>
							</g>
						</g>
					</g>
					<g id="freepik--Board--inject-127" class="animable" style="transform-origin: 413.786px 112.732px;">
						<g id="freepik--board--inject-127" class="animable" style="transform-origin: 413.786px 112.732px;">
							<g id="freepik--board--inject-127" class="animable" style="transform-origin: 413.597px 114.154px;">
								<path d="M349,20.22l-.9.52V129.51c0,2.93,1.78,6.33,4,7.6l122.12,70.5a2.58,2.58,0,0,0,2.48.26c.2-.09.88-.5,1-.59a3.5,3.5,0,0,0,1.37-3.2V95.32Z" style="fill: rgb(31, 111, 235); transform-origin: 413.597px 114.147px;" id="eleyuxlgjgag" class="animable"></path>
								<g id="elolm3aq8rqd">
									<path d="M474.24,207.61l-122.12-70.5c-2.2-1.27-4-4.67-4-7.6V20.74l130.07,75.1V204.6C478.21,207.53,476.43,208.88,474.24,207.61Z" style="fill: rgb(255, 255, 255); opacity: 0.8; transform-origin: 413.155px 114.414px;" class="animable"></path>
								</g>
							</g>
							<g id="freepik--Tube--inject-127" class="animable" style="transform-origin: 413.786px 58.5989px;">
								<path d="M478.86,99.68l3-5.27L348.77,17.55h0a1.47,1.47,0,0,0-1.56.11,4.76,4.76,0,0,0-2.15,3.73,1.51,1.51,0,0,0,.59,1.36h0l.05,0h0Z" style="fill: rgb(69, 90, 100); transform-origin: 413.456px 58.5283px;" id="elc499syv8286" class="animable"></path>
								<path d="M478.21,98.28a4.74,4.74,0,0,1,2.16-3.72c1.19-.69,2.15-.13,2.15,1.24a4.76,4.76,0,0,1-2.15,3.73C479.18,100.21,478.21,99.66,478.21,98.28Z" style="fill: rgb(55, 71, 79); transform-origin: 480.365px 97.0427px;" id="eljjzkj2ms67r" class="animable"></path>
							</g>
							<g id="freepik--Chart--inject-127" class="animable" style="transform-origin: 413.845px 116.54px;">
								<path d="M467.92,105.87,359.74,43.42c-1.68-1-3-.18-3,1.76v76.75a6.74,6.74,0,0,0,3.05,5.28l108.16,62.45c1.69,1,3,.18,3-1.76V111.15A6.73,6.73,0,0,0,467.92,105.87Z" style="fill: rgb(250, 250, 250); transform-origin: 413.845px 116.54px;" id="elojn1by5s7z" class="animable"></path>
								<polygon points="456.31 166.56 461.34 169.47 461.34 109.28 456.31 106.38 456.31 166.56" style="fill: rgb(224, 224, 224); transform-origin: 458.825px 137.925px;" id="elpnr1zmcfz8" class="animable"></polygon>
								<polygon points="449.16 162.44 454.19 165.34 454.19 105.15 449.16 102.25 449.16 162.44" style="fill: rgb(224, 224, 224); transform-origin: 451.675px 133.795px;" id="elphbttjyigu" class="animable"></polygon>
								<polygon points="456.31 166.56 461.34 169.47 461.34 135.78 456.31 132.87 456.31 166.56" style="fill: rgb(31, 111, 235); transform-origin: 458.825px 151.17px;" id="elzr4xn537nxq" class="animable"></polygon>
								<polygon points="449.16 162.44 454.19 165.34 454.19 138.74 449.16 135.83 449.16 162.44" style="fill: rgb(31, 111, 235); transform-origin: 451.675px 150.585px;" id="elofnn76ps3sc" class="animable"></polygon>
								<g id="elsi5la31ipag">
									<polygon points="449.16 162.44 454.19 165.34 454.19 138.74 449.16 135.83 449.16 162.44" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 451.675px 150.585px;" class="animable"></polygon>
								</g>
								<path d="M449.86,172.35l10.78,6.22c.39.23.7,0,.7-.53v-.14a1.68,1.68,0,0,0-.7-1.34l-10.78-6.22c-.39-.23-.7,0-.7.53V171A1.66,1.66,0,0,0,449.86,172.35Z" style="fill: rgb(69, 90, 100); transform-origin: 455.25px 174.455px;" id="el7fdxwv4z2u9" class="animable"></path>
								<polygon points="435.88 154.77 440.92 157.68 440.92 97.49 435.88 94.58 435.88 154.77" style="fill: rgb(224, 224, 224); transform-origin: 438.4px 126.13px;" id="eldi3dgl4k9la" class="animable"></polygon>
								<polygon points="428.74 150.65 433.77 153.55 433.77 93.36 428.74 90.46 428.74 150.65" style="fill: rgb(224, 224, 224); transform-origin: 431.255px 122.005px;" id="elhdfy9jdrr6f" class="animable"></polygon>
								<polygon points="435.88 154.77 440.92 157.68 440.92 148.35 435.88 145.44 435.88 154.77" style="fill: rgb(31, 111, 235); transform-origin: 438.4px 151.56px;" id="elqs794oyckk" class="animable"></polygon>
								<polygon points="428.74 150.65 433.77 153.55 433.77 136.71 428.74 133.8 428.74 150.65" style="fill: rgb(31, 111, 235); transform-origin: 431.255px 143.675px;" id="elvymbbnu8mc" class="animable"></polygon>
								<g id="elbk21fkfib5j">
									<polygon points="428.74 150.65 433.77 153.55 433.77 136.71 428.74 133.8 428.74 150.65" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 431.255px 143.675px;" class="animable"></polygon>
								</g>
								<polygon points="415.08 142.76 420.11 145.67 420.11 85.48 415.08 82.57 415.08 142.76" style="fill: rgb(224, 224, 224); transform-origin: 417.595px 114.12px;" id="el7rn81j8xnx3" class="animable"></polygon>
								<polygon points="407.93 138.63 412.96 141.54 412.96 81.35 407.93 78.44 407.93 138.63" style="fill: rgb(224, 224, 224); transform-origin: 410.445px 109.99px;" id="eluimrndboln" class="animable"></polygon>
								<polygon points="415.08 142.76 420.11 145.67 420.11 106.89 415.08 103.98 415.08 142.76" style="fill: rgb(31, 111, 235); transform-origin: 417.595px 124.825px;" id="elg69g15u7mh" class="animable"></polygon>
								<polygon points="407.93 138.63 412.96 141.54 412.96 99.28 407.93 96.37 407.93 138.63" style="fill: rgb(31, 111, 235); transform-origin: 410.445px 118.955px;" id="elbyp961meb78" class="animable"></polygon>
								<g id="elub892i2ietj">
									<polygon points="407.93 138.63 412.96 141.54 412.96 99.28 407.93 96.37 407.93 138.63" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 410.445px 118.955px;" class="animable"></polygon>
								</g>
								<polygon points="394.27 130.75 399.31 133.66 399.31 73.47 394.27 70.56 394.27 130.75" style="fill: rgb(224, 224, 224); transform-origin: 396.79px 102.11px;" id="el70vo6ji7pue" class="animable"></polygon>
								<polygon points="387.13 126.62 392.16 129.53 392.16 69.34 387.13 66.43 387.13 126.62" style="fill: rgb(224, 224, 224); transform-origin: 389.645px 97.98px;" id="elesxzj4owob5" class="animable"></polygon>
								<polygon points="394.27 130.75 399.31 133.66 399.31 99.97 394.27 97.06 394.27 130.75" style="fill: rgb(31, 111, 235); transform-origin: 396.79px 115.36px;" id="elyxowxh4hvy8" class="animable"></polygon>
								<polygon points="387.13 126.62 392.16 129.53 392.16 89.45 387.13 86.54 387.13 126.62" style="fill: rgb(31, 111, 235); transform-origin: 389.645px 108.035px;" id="el3e9ff5b37ji" class="animable"></polygon>
								<g id="elta073vh4jpq">
									<polygon points="387.13 126.62 392.16 129.53 392.16 89.45 387.13 86.54 387.13 126.62" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 389.645px 108.035px;" class="animable"></polygon>
								</g>
								<polygon points="373.47 118.74 378.5 121.64 378.5 61.46 373.47 58.55 373.47 118.74" style="fill: rgb(224, 224, 224); transform-origin: 375.985px 90.095px;" id="eljp9dedaham" class="animable"></polygon>
								<polygon points="366.32 114.61 371.36 117.52 371.36 57.33 366.32 54.42 366.32 114.61" style="fill: rgb(224, 224, 224); transform-origin: 368.84px 85.97px;" id="elzyrh4bsewok" class="animable"></polygon>
								<polygon points="373.47 118.74 378.5 121.64 378.5 68.43 373.47 65.53 373.47 118.74" style="fill: rgb(31, 111, 235); transform-origin: 375.985px 93.585px;" id="elj9htapa69jl" class="animable"></polygon>
								<polygon points="366.32 114.61 371.36 117.52 371.36 90.91 366.32 88 366.32 114.61" style="fill: rgb(31, 111, 235); transform-origin: 368.84px 102.76px;" id="eldavktjwpe8b" class="animable"></polygon>
								<g id="el6o4q78x8tkk">
									<polygon points="366.32 114.61 371.36 117.52 371.36 90.91 366.32 88 366.32 114.61" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 368.84px 102.76px;" class="animable"></polygon>
								</g>
								<path d="M429.44,160.56l10.78,6.22c.38.23.7,0,.7-.53v-.14a1.7,1.7,0,0,0-.7-1.34l-10.78-6.22c-.39-.23-.7,0-.7.53v.14A1.66,1.66,0,0,0,429.44,160.56Z" style="fill: rgb(69, 90, 100); transform-origin: 434.83px 162.665px;" id="elxul1qo4ij2b" class="animable"></path>
								<path d="M408.63,148.55l10.78,6.22c.39.23.7,0,.7-.53v-.14a1.66,1.66,0,0,0-.7-1.34l-10.78-6.22c-.39-.23-.7,0-.7.53v.14A1.69,1.69,0,0,0,408.63,148.55Z" style="fill: rgb(69, 90, 100); transform-origin: 414.02px 150.655px;" id="eln2rf8t6t03l" class="animable"></path>
								<path d="M387.83,136.54l10.78,6.22c.38.22.7,0,.7-.53v-.14a1.69,1.69,0,0,0-.7-1.34l-10.78-6.23c-.39-.22-.7,0-.7.54v.14A1.69,1.69,0,0,0,387.83,136.54Z" style="fill: rgb(69, 90, 100); transform-origin: 393.22px 138.64px;" id="el2vmeins1bsl" class="animable"></path>
								<path d="M367,124.53l10.78,6.22c.39.22.7,0,.7-.53v-.14a1.66,1.66,0,0,0-.7-1.34L367,122.51c-.38-.22-.7,0-.7.53v.14A1.72,1.72,0,0,0,367,124.53Z" style="fill: rgb(69, 90, 100); transform-origin: 372.39px 126.63px;" id="el32twxmle50w" class="animable"></path>
							</g>
						</g>
					</g>
					<g id="freepik--character-3--inject-127" class="animable" style="transform-origin: 104.66px 264.817px;">
						<g id="freepik--Character--inject-127" class="animable" style="transform-origin: 104.66px 264.817px;">
							<g id="freepik--Chair--inject-127" class="animable" style="transform-origin: 94.3898px 270.612px;">
								<path d="M65.05,364.46a5.42,5.42,0,0,0,.13,1.19,2.75,2.75,0,0,0,1.07,1.67l0,0a3.13,3.13,0,0,0,3.3-.25,10,10,0,0,0,4.55-7.88,3.22,3.22,0,0,0-1.28-2.88h0l-.22-.13h0a2.86,2.86,0,0,0-1.93-.08,5.62,5.62,0,0,0-1.12.49A10,10,0,0,0,65.05,364.46Z" style="fill: rgb(69, 90, 100); transform-origin: 69.582px 361.823px;" id="ellbfahey7zb" class="animable"></path>
								<path d="M66.29,354.69a4.16,4.16,0,0,1,2-.61,2.39,2.39,0,0,1,1.28.36l.12.06,2.93,1.69a2.86,2.86,0,0,0-1.93-.08,5.62,5.62,0,0,0-1.12.49,10,10,0,0,0-4.54,7.86,5.42,5.42,0,0,0,.13,1.19,2.75,2.75,0,0,0,1.07,1.67l-3-1.75-.05,0-.25-.15-.08-.06a3.34,3.34,0,0,1-1.11-2.79A10,10,0,0,1,66.29,354.69Z" style="fill: rgb(38, 50, 56); transform-origin: 67.1736px 360.7px;" id="el79g0wfc4wbg" class="animable"></path>
								<path d="M114.21,338a5.49,5.49,0,0,0,.13,1.19,2.76,2.76,0,0,0,1.07,1.66l0,0a3.16,3.16,0,0,0,3.3-.26,10,10,0,0,0,4.55-7.87A3.22,3.22,0,0,0,122,329.9v0l-.22-.12h0a2.74,2.74,0,0,0-1.93-.08,5.1,5.1,0,0,0-1.12.49A10,10,0,0,0,114.21,338Z" style="fill: rgb(69, 90, 100); transform-origin: 118.74px 335.379px;" id="elfzpwb8ll6e" class="animable"></path>
								<path d="M115.45,328.26a4.18,4.18,0,0,1,2-.62,2.38,2.38,0,0,1,1.27.36l.12.07,2.93,1.69a2.74,2.74,0,0,0-1.93-.08,5.1,5.1,0,0,0-1.12.49,10,10,0,0,0-4.54,7.86,5.49,5.49,0,0,0,.13,1.19,2.76,2.76,0,0,0,1.07,1.66l-3-1.74-.06,0-.25-.15-.08,0a3.35,3.35,0,0,1-1.11-2.79A10,10,0,0,1,115.45,328.26Z" style="fill: rgb(38, 50, 56); transform-origin: 116.319px 334.26px;" id="elqinx24dir0t" class="animable"></path>
								<path d="M105.44,374.78a5.42,5.42,0,0,1-.13,1.19,2.75,2.75,0,0,1-1.07,1.67l0,0a3.13,3.13,0,0,1-3.3-.25,10,10,0,0,1-4.55-7.88,3.23,3.23,0,0,1,1.28-2.88h0l.22-.13h0a2.8,2.8,0,0,1,1.93-.07,5,5,0,0,1,1.12.48A10,10,0,0,1,105.44,374.78Z" style="fill: rgb(69, 90, 100); transform-origin: 100.908px 372.144px;" id="el7qmsa4adsg9" class="animable"></path>
								<path d="M104.2,365a4.18,4.18,0,0,0-2-.62,2.39,2.39,0,0,0-1.28.36l-.12.06-2.93,1.69a2.8,2.8,0,0,1,1.93-.07,5,5,0,0,1,1.12.48,10,10,0,0,1,4.54,7.86,5.42,5.42,0,0,1-.13,1.19,2.75,2.75,0,0,1-1.07,1.67l3-1.74,0,0,.25-.15.08-.05a3.37,3.37,0,0,0,1.11-2.79A10,10,0,0,0,104.2,365Z" style="fill: rgb(38, 50, 56); transform-origin: 103.291px 371px;" id="eltou00hbmsdq" class="animable"></path>
								<path d="M137.35,359.15a6.14,6.14,0,0,1-.12,1.19,2.81,2.81,0,0,1-1.08,1.67l0,0a3.13,3.13,0,0,1-3.3-.25,10,10,0,0,1-4.55-7.88,3.22,3.22,0,0,1,1.28-2.88h0l.22-.13h0a2.88,2.88,0,0,1,1.94-.08,5.55,5.55,0,0,1,1.11.49A10,10,0,0,1,137.35,359.15Z" style="fill: rgb(69, 90, 100); transform-origin: 132.818px 356.512px;" id="el9604pd5ysvb" class="animable"></path>
								<path d="M136.11,349.38a4.13,4.13,0,0,0-2-.61,2.42,2.42,0,0,0-1.28.36l-.12.06-2.93,1.69a2.88,2.88,0,0,1,1.94-.08,5.55,5.55,0,0,1,1.11.49,10,10,0,0,1,4.54,7.86,6.14,6.14,0,0,1-.12,1.19,2.81,2.81,0,0,1-1.08,1.67l3-1.75.05,0,.26-.15s.05,0,.07-.06a3.34,3.34,0,0,0,1.12-2.79A10,10,0,0,0,136.11,349.38Z" style="fill: rgb(38, 50, 56); transform-origin: 135.231px 355.39px;" id="elnxrsiqlmiv" class="animable"></path>
								<path d="M75.67,340a5.49,5.49,0,0,1-.13,1.19,2.67,2.67,0,0,1-1.08,1.66l0,0a3.13,3.13,0,0,1-3.31-.26,10,10,0,0,1-4.54-7.87,3.22,3.22,0,0,1,1.28-2.88h0l.21-.13h0a2.86,2.86,0,0,1,1.93-.08,5.31,5.31,0,0,1,1.11.49A10,10,0,0,1,75.67,340Z" style="fill: rgb(69, 90, 100); transform-origin: 71.133px 337.352px;" id="elqewtu635eh9" class="animable"></path>
								<path d="M74.43,330.21a4.16,4.16,0,0,0-2-.61,2.42,2.42,0,0,0-1.28.36L71,330l-2.93,1.69a2.86,2.86,0,0,1,1.93-.08,5.31,5.31,0,0,1,1.11.49A10,10,0,0,1,75.67,340a5.49,5.49,0,0,1-.13,1.19,2.67,2.67,0,0,1-1.08,1.66l3-1.74,0,0,.26-.16a.18.18,0,0,0,.08-.05A3.34,3.34,0,0,0,79,338.07,10.06,10.06,0,0,0,74.43,330.21Z" style="fill: rgb(38, 50, 56); transform-origin: 73.54px 336.225px;" id="elpou16qjqs7g" class="animable"></path>
								<polygon points="102.8 330.37 119.02 325.72 119.02 329.19 102.8 338.55 102.8 330.37" style="fill: rgb(235, 235, 235); transform-origin: 110.91px 332.135px;" id="el88q1rtruisj" class="animable"></polygon>
								<polygon points="102.8 330.37 101.19 329.39 117.47 324.95 119.02 325.72 102.8 330.37" style="fill: rgb(245, 245, 245); transform-origin: 110.105px 327.66px;" id="elwcekbqr1029" class="animable"></polygon>
								<polygon points="95.78 340.71 72.77 332.1 72.77 328.59 95.78 330.64 95.78 340.71" style="fill: rgb(235, 235, 235); transform-origin: 84.275px 334.65px;" id="elozcv3niwvlt" class="animable"></polygon>
								<polygon points="72.77 328.59 74.47 328.14 97.14 329.61 95.78 330.64 72.77 328.59" style="fill: rgb(245, 245, 245); transform-origin: 84.955px 329.39px;" id="elo401kv3e48" class="animable"></polygon>
								<path d="M95.16,339.61c0,2.6,8,2.6,8,0V309.45h-8Z" style="fill: rgb(250, 250, 250); transform-origin: 99.16px 325.505px;" id="elv4bgcugtmus" class="animable"></path>
								<path d="M101.16,333.69l4.4,28.56h-3l-4.12-28.56A5,5,0,0,0,101.16,333.69Z" style="fill: rgb(245, 245, 245); transform-origin: 102px 347.97px;" id="eln63idzym7a" class="animable"></path>
								<polygon points="98.43 333.69 98.43 341.53 102.55 367.09 102.55 362.25 98.43 333.69" style="fill: rgb(235, 235, 235); transform-origin: 100.49px 350.39px;" id="elzgyb29hsb3o" class="animable"></polygon>
								<rect x="102.55" y="362.25" width="3.01" height="4.84" style="fill: rgb(224, 224, 224); transform-origin: 104.055px 364.67px;" id="el6so54ge8hcp" class="animable"></rect>
								<polygon points="97.06 341.3 67.4 356.85 67.4 352.06 97.06 333.71 97.06 341.3" style="fill: rgb(235, 235, 235); transform-origin: 82.23px 345.28px;" id="el4jwus334o1o" class="animable"></polygon>
								<path d="M97.07,333.71a3.32,3.32,0,0,1-1.91-.86l-29.61,18,1.85,1.25Z" style="fill: rgb(245, 245, 245); transform-origin: 81.31px 342.475px;" id="eliqbb6i2ph4" class="animable"></path>
								<polygon points="67.4 356.85 67.4 352.06 65.55 350.81 65.55 355.45 67.4 356.85" style="fill: rgb(224, 224, 224); transform-origin: 66.475px 353.83px;" id="elmom0gtqe6sh" class="animable"></polygon>
								<polygon points="134.46 351.36 102.77 340.51 102.77 333.39 134.46 346.57 134.46 351.36" style="fill: rgb(235, 235, 235); transform-origin: 118.615px 342.375px;" id="eld6eoy8hoa9o" class="animable"></polygon>
								<path d="M102.77,333.39c.26-.07.37-.53.39-1.23l32.24,14-.94.39Z" style="fill: rgb(245, 245, 245); transform-origin: 119.085px 339.355px;" id="elegmxclql93" class="animable"></path>
								<polygon points="135.4 346.18 135.4 350.81 134.46 351.36 134.46 346.57 135.4 346.18" style="fill: rgb(224, 224, 224); transform-origin: 134.93px 348.77px;" id="elwbftemllhvb" class="animable"></polygon>
								<g id="eln9jti1vywv">
									<rect x="102.55" y="362.25" width="3.01" height="4.84" style="opacity: 0.1; transform-origin: 104.055px 364.67px;" class="animable"></rect>
								</g>
								<g id="el35ccqt4zwys">
									<polygon points="67.4 356.85 67.4 352.06 65.55 350.81 65.55 355.45 67.4 356.85" style="opacity: 0.1; transform-origin: 66.475px 353.83px;" class="animable"></polygon>
								</g>
								<g id="ely8hof12pezf">
									<polygon points="135.4 346.18 135.4 350.81 134.46 351.36 134.46 346.57 135.4 346.18" style="opacity: 0.1; transform-origin: 134.93px 348.77px;" class="animable"></polygon>
								</g>
								<path d="M104.77,288.74H93.56V321.4c0,3.22,11.21,3.22,11.21,0Z" style="fill: rgb(240, 240, 240); transform-origin: 99.165px 306.277px;" id="elb0hjw2o0w2" class="animable"></path>
								<path d="M150.24,284.39v3L101,315.84a6.35,6.35,0,0,1-6.31,0L45.48,287.42v-3Z" style="fill: rgb(55, 71, 79); transform-origin: 97.86px 300.535px;" id="elbpdaihco13" class="animable"></path>
								<g id="elv51yfdgab1n">
									<rect x="122.95" y="243.09" width="5.24" height="26" style="fill: rgb(55, 71, 79); transform-origin: 125.57px 256.09px; transform: rotate(180deg);" class="animable"></rect>
								</g>
								<path d="M125.61,237.23l27.44,15.85-10.47,6L111.15,241l6.5-3.75A8,8,0,0,1,125.61,237.23Z" style="fill: rgb(31, 111, 235); transform-origin: 132.1px 247.63px;" id="elncyqakfm7s" class="animable"></path>
								<g id="elsoiuen8y5o">
									<path d="M125.61,237.23l27.44,15.85-10.47,6L111.15,241l6.5-3.75A8,8,0,0,1,125.61,237.23Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 132.1px 247.63px;" class="animable"></path>
								</g>
								<polygon points="111.15 243.8 111.15 240.98 142.58 259.13 142.58 261.94 111.15 243.8" style="fill: rgb(31, 111, 235); transform-origin: 126.865px 251.46px;" id="elrf04o1qizmb" class="animable"></polygon>
								<g id="elfgto05siqx">
									<polygon points="111.15 243.8 111.15 240.98 142.58 259.13 142.58 261.94 111.15 243.8" style="opacity: 0.3; transform-origin: 126.865px 251.46px;" class="animable"></polygon>
								</g>
								<polygon points="153.05 253.08 153.05 255.89 142.58 261.94 142.58 259.13 153.05 253.08" style="fill: rgb(31, 111, 235); transform-origin: 147.815px 257.51px;" id="el8w5h363tqdn" class="animable"></polygon>
								<g id="elv696567caee">
									<polygon points="153.05 253.08 153.05 255.89 142.58 261.94 142.58 259.13 153.05 253.08" style="opacity: 0.15; transform-origin: 147.815px 257.51px;" class="animable"></polygon>
								</g>
								<path d="M77.41,268.72a4.72,4.72,0,0,0,2.42-4.41l-1.22-17.76c-.08-1.3-1.11-1.82-2.29-1.17l-8.85,4.93a4.13,4.13,0,0,0-2,3.54l1.23,18a4.69,4.69,0,0,1-2.45,4.43l5.27-3.06Z" style="fill: rgb(38, 50, 56); transform-origin: 72.0443px 260.69px;" id="el01ybfkg4o5bi" class="animable"></path>
								<path d="M78.78,269.27a4.72,4.72,0,0,0,2.41-4.41L80,247.1c-.09-1.3-1.12-1.82-2.3-1.16l-8.85,4.92a4.13,4.13,0,0,0-2,3.55l1.23,18a4.68,4.68,0,0,1-2.46,4.43l5.28-3.06Z" style="fill: rgb(55, 71, 79); transform-origin: 73.4094px 261.247px;" id="elquoxip7t5xo" class="animable"></path>
								<path d="M45.49,284.39l49.17,28.4a6.35,6.35,0,0,0,6.39,0l49.19-28.4a10.47,10.47,0,0,0-5.23-9.06l-4.42-2.55c-.27-.17-.54-.31-.83-.48l-36-20.77a11.75,11.75,0,0,0-11.83,0l-41.22,23.8-.1.07-.09.07A10.41,10.41,0,0,0,45.49,284.39Z" style="fill: rgb(31, 111, 235); transform-origin: 97.865px 281.792px;" id="elx5e2h9rk2li" class="animable"></path>
								<g id="el668ocs1l2io">
									<path d="M150.24,284.39a10.47,10.47,0,0,0-5.23-9.06l-4.42-2.55c6.18,3.65,8.72,6.09,4.39,8.58-5.22,3-34,19.63-43.82,25.31a6.59,6.59,0,0,1-3.3.89v6.06a6.7,6.7,0,0,0,3.3-.89Z" style="opacity: 0.15; transform-origin: 124.05px 293.2px;" class="animable"></path>
								</g>
								<g id="elvbwjwvzn9o">
									<path d="M94.56,306.67C86.28,301.89,64.69,289.4,56,284.39c-9.14-5.23-6.28-8.26-5.42-8.92a10.41,10.41,0,0,0-5,8.92l49.06,28.34a6.63,6.63,0,0,0,3.31.89v-6.06A6.59,6.59,0,0,1,94.56,306.67Z" style="opacity: 0.3; transform-origin: 71.765px 294.545px;" class="animable"></path>
								</g>
								<path d="M82.84,165.6c-.58-.28-2.92-1.47-3.53-1.77-2.06-1-4.79-.79-7.69.89l-26.07,15c-5.85,3.37-10.23,11.36-9.79,17.85l4.43,64.91,3.54,1.77L91,237l-4.43-64.87C86.32,168.88,84.92,166.62,82.84,165.6Z" style="fill: rgb(55, 71, 79); transform-origin: 63.3648px 213.739px;" id="elr7hoe2fba4b" class="animable"></path>
								<path d="M89.33,213.06l-2.79-40.92c-.22-3.26-1.62-5.52-3.7-6.54-.58-.28-2.92-1.47-3.53-1.77-2.06-1-4.79-.79-7.69.89l-26.07,15a20.27,20.27,0,0,0-7.36,8Z" style="fill: rgb(69, 90, 100); transform-origin: 63.76px 188.144px;" id="elopt1e5xngs" class="animable"></path>
								<path d="M82.84,165.61c-2.06-1-4.78-.8-7.69.88l-26.06,15c-5.85,3.37-10.23,11.36-9.79,17.85l4.43,64.91,4.85,2.39L95.82,239.4,91.4,174.54c-.22-3.2-1.57-5.44-3.59-6.48C87,167.63,83.67,166,82.84,165.61Z" style="fill: rgb(31, 111, 235); transform-origin: 67.5448px 215.822px;" id="elopko2eal29q" class="animable"></path>
								<g id="elzoyoaqtzyq">
									<path d="M41.74,189.46a18.92,18.92,0,0,0-2.44,9.9l4.43,64.91,4.85,2.39L95.82,239.4l-1.63-23.93Z" style="opacity: 0.3; transform-origin: 67.5554px 228.06px;" class="animable"></path>
								</g>
								<g id="el7ikwakdavv7">
									<path d="M91.4,174.54c-.22-3.2-1.57-5.44-3.59-6.48-.84-.43-4.14-2.05-5-2.45-2.06-1-4.78-.8-7.69.88l-26.06,15a20.21,20.21,0,0,0-7.35,8l52.45,26Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 67.935px 190.247px;" class="animable"></path>
								</g>
								<path d="M48.58,266.66l-4.43-64.9c-.44-6.49,3.94-14.49,9.79-17.86l26.07-15c5.84-3.37,10.94-.83,11.39,5.66l4.42,64.86Z" style="fill: rgb(31, 111, 235); transform-origin: 69.9698px 217.035px;" id="elra0tnotj6ro" class="animable"></path>
							</g>
							<g id="freepik--character--inject-127" class="animable" style="transform-origin: 107.069px 264.817px;">
								<g id="freepik--Bottom--inject-127" class="animable" style="transform-origin: 113.442px 324.53px;">
									<path d="M108.26,345.71l2.37,0,1.57-2.59c.75-1.25,2.14-1.82,2.49-1.27,0,0,1.4-3.38-1.24-3.58S108.26,345.71,108.26,345.71Z" style="fill: rgb(31, 111, 235); transform-origin: 111.66px 341.988px;" id="el6xeu4l0p2rf" class="animable"></path>
									<path d="M111.48,329c-1,2.24-.5,6.26-1.68,9.06a42.8,42.8,0,0,1-2.8,5.35l1.26,2.29,2.55-4.22c1.16-1.91,3.16-1.92,3.55-.64a11.2,11.2,0,0,0,.48-6.06C114.1,331.79,112.81,329.84,111.48,329Z" style="fill: rgb(38, 50, 56); transform-origin: 111.027px 337.35px;" id="elbn53zy8vhk8" class="animable"></path>
									<path d="M120.53,324.42c-4.67-.57-8.63,3.21-9,4.41-.5,1.62-.29,4.88,1.66,8.89,1.69,3.48,2.26,9,2.21,12.67-.07,5.85-1.83,11.15,1.23,15.13,4.75,6.18,15.77,11.5,15.56,4.52-.16-5.22-1.08-9.25-2-14.48-1.84-10.41-3.29-20.77-3.29-20.77Z" style="fill: rgb(38, 50, 56); transform-origin: 121.746px 348.853px;" id="el5fm2sclqdju" class="animable"></path>
									<g id="elem31t3gvld5">
										<path d="M113.07,327c-.18,3.45,2,11.64,6.75,19.25s11.27,17.18,12.61,22.54c.09-2.47-1-7-1.81-11l-.06-.26c0-.23-.09-.46-.13-.69l-.06-.32c-.06-.3-.12-.59-.17-.88v-.06c-.57-3.25-1.11-6.5-1.58-9.43-.37-2.34-.7-4.48-1-6.26-.14-.88-.26-1.68-.36-2.36-.05-.34-.09-.66-.13-.94-.17-1.13-.26-1.78-.26-1.78l-6.37-10.37c-.31,0-.61-.05-.9-.06A10.24,10.24,0,0,0,113.07,327Z" style="opacity: 0.15; transform-origin: 122.747px 346.585px;" class="animable"></path>
									</g>
									<path d="M119.17,332.77a101.25,101.25,0,0,1,1.36,15.3c0,5.6,1.2,9.27,3.17,11.74,2.16,2.74,7.52,4.21,6.82-1.55-.49-4-3.62-23.47-3.62-23.47Z" style="fill: rgb(255, 189, 167); transform-origin: 124.876px 347.496px;" id="elfvxefwrc1zn" class="animable"></path>
									<path d="M129.43,361.88c.83-.5,1.33-1.61,1.09-3.62-.11-.9-.36-2.6-.67-4.68-.45-2.58-.87-5.12-1.24-7.45s-.7-4.48-1-6.26c-.14-.88-.26-1.68-.36-2.36-.05-.34-.09-.66-.13-.94-.16-1.11-.25-1.75-.26-1.77h0l-7.73-2a103.35,103.35,0,0,1,1.36,14.57C123.45,352,126.94,357.3,129.43,361.88Z" style="fill: rgb(240, 153, 122); transform-origin: 124.855px 347.34px;" id="el0d0j8oalb4u9" class="animable"></path>
									<path d="M150.06,266.7c.1,11.34-34,41.1-45.09,40.17-7.36-6.94-8.91-24.57-8.91-24.57l33.36-23.66Z" style="fill: rgb(31, 111, 235); transform-origin: 123.06px 282.766px;" id="eli4ek877zfop" class="animable"></path>
									<path d="M159.22,274.39c-3.61-4.35-6-6-21.11-15-13.84-8.25-31.51-15.52-31.51-15.52L92.68,263.94l16.61,13.32,24.59,12.58a31.3,31.3,0,0,0-5.29,9.87c-.65,2-6.18,23.53-6.18,23.53l13.35,13.09s15-28.26,21.41-41.94C163.23,281.52,160.58,276,159.22,274.39Z" style="fill: rgb(255, 189, 167); transform-origin: 126.831px 290.1px;" id="elt4261fj57df" class="animable"></path>
									<path d="M140.15,327.79c-.49-4.07-1.67-13-1.66-13-.05-.3-1.15-8.06-1.25-8.74v-.07h0c-.27-1.74-.51-3.21-.72-4.24a26.11,26.11,0,0,0-4.24-9.66h0l-.37.58a.43.43,0,0,0,0,.07l-.36.59a.75.75,0,0,1-.07.12l-.35.62-.07.13-.36.68a.91.91,0,0,0-.07.14c-.12.24-.25.48-.37.74l-.06.12c-.12.26-.25.53-.37.82a.2.2,0,0,0,0,.08c-.13.3-.26.6-.39.92v0c-.13.33-.27.67-.4,1l3.5,11.66h0l6.13,20.49Z" style="fill: rgb(240, 153, 122); transform-origin: 134.595px 311.46px;" id="el8rfjkbc4uk" class="animable"></path>
									<path d="M128.2,286.93c-1.38-1.52-2.82-3-4.25-4.34-5.4-5.2-17.19-15.87-17.19-15.87l-3,.54,10.43,12.49Z" style="fill: rgb(255, 168, 167); transform-origin: 115.98px 276.825px;" id="elbwiep32ukn8" class="animable"></path>
									<path d="M135.1,396.44l2-1.32s-.11-1.57-.2-3,.71-2.72,1.3-2.47c0,0-.78-3.57-3-2.22S135.1,396.44,135.1,396.44Z" style="fill: rgb(31, 111, 235); transform-origin: 136.193px 391.783px;" id="elahghrlix25d" class="animable"></path>
									<path d="M128.16,380.92c.46,2.41,3.18,5.41,3.82,8.38a43.55,43.55,0,0,1,.78,6l2.34,1.15-.33-4.91c-.15-2.24,1.25-3.15,2.3-2.32a10,10,0,0,0-2.83-5.48C131.91,381.69,129.73,380.83,128.16,380.92Z" style="fill: rgb(38, 50, 56); transform-origin: 132.615px 388.682px;" id="elcic1bl5qbfh" class="animable"></path>
									<path d="M130.22,368.71c-1.13-.07-1.21,1.87-1.89,4.33a12.51,12.51,0,0,0-.36,7.37c.93,3.11,5,4.39,8.26,7.88,6.44,6.85,6.79,12,11.79,14.51,8.12,4,15.58,2.15,18.17.42,6.44-4.31,2.22-5.5-2.57-9.09a42.05,42.05,0,0,1-8.18-8.34Z" style="fill: rgb(38, 50, 56); transform-origin: 148.526px 386.95px;" id="elcbfrfun3fs" class="animable"></path>
									<path d="M160.14,391.14c-.75-.9-3-3.41-3.95-4.52-4.21-5.09-7.33-11.72-9.78-17.21-1.34-3-3.27-9.29-5-19.56s-6-43.91-8.46-51.62-30.71-33.14-30.71-33.14a210.36,210.36,0,0,1,4.34-21.2l-47.09,3.36c-1.93,8.82-3.27,12.32-.5,21.41s12.07,15.55,23.92,23.7c10.15,7,23.53,15.13,26.38,16.92,2.49,1.57,4,3.48,4.24,9.16.23,5.88,1,13.54,10.72,32.23,3.37,7.34,4.61,10,5.69,14.11.54,2,.38,2.91.23,5.26a5.68,5.68,0,0,0,1.31,3.68c.9,1.14,1.84,2.22,2.84,3.28,2.66,2.82,7.7,9.18,11.16,13.73,5,6.55,13.17,5.4,15.13,3.61C161.63,393.43,160.8,391.94,160.14,391.14Z" style="fill: rgb(255, 189, 167); transform-origin: 109.251px 319.812px;" id="elx930l71u2f" class="animable"></path>
								</g>
								<g id="freepik--Top--inject-127" class="animable" style="transform-origin: 107.069px 215.657px;">
									<path id="freepik--Hair--inject-127" d="M110.68,180.53c-.55,2.34-2,4.39-4.67,4.37-.17,2.31.07,4.74-.81,6.93a10.59,10.59,0,0,1-7.65,5.8,11.87,11.87,0,0,1-7.37-1.16C89.4,196,88.77,195,88,194.74c-.55,1.95-2.64,3.71-4.26,4.71A14.58,14.58,0,0,1,73,201.5c-4.51-.92-8-3.72-9.07-8.23-.83.43-1.51,1.13-2.38,1.57a12.35,12.35,0,0,1-5.69,1.08,10.64,10.64,0,0,1-7.66-3,9.27,9.27,0,0,1-2.71-6,11.75,11.75,0,0,1,.3-3.88,6.51,6.51,0,0,0,.68-1.89,7,7,0,0,1-5.91-7.31,11.75,11.75,0,0,1,2-6.38c1.49-2.1,3.44-2.59,5.66-3.58,4.16-1.85,7.91-4.25,8.11-10,.38-10.95,1.74-20.33,10.86-21.59,1.74-9.91,22.52-11,33.6,0,6.55,6.52,3.71,19.08.74,21.25l-8.68,5.11c3.32,3.26,7.35,5.21,11.14,7.81a16,16,0,0,1,6.71,9.79A10.59,10.59,0,0,1,110.68,180.53Z" style="fill: rgb(38, 50, 56); transform-origin: 75.7288px 163.111px;" class="animable"></path>
									<g id="freepik--Arm--inject-127" class="animable" style="transform-origin: 128.75px 211.305px;">
										<path d="M110.37,184.78c-4.62-4.32-7.51-5-14.44-5.63a89.6,89.6,0,0,0,1,13.78s16.5,30,18.08,32.79l11.78-12.16S114.32,188.48,110.37,184.78Z" style="fill: rgb(255, 189, 167); transform-origin: 111.359px 202.435px;" id="eliihuvop1koq" class="animable"></path>
										<path d="M95.56,178.79c2,.22,4,.38,6.08.74,4.18.72,8.09,3,12,8.91,2.41,3.62,9,15.78,9,15.78-.63,4.82-7,12.71-17.5,10.75Z" style="fill: rgb(55, 71, 79); transform-origin: 109.1px 197.033px;" id="el4kr332gd2o8" class="animable"></path>
										<path d="M161.94,243.82h0l0,0Z" style="fill: rgb(31, 111, 235); transform-origin: 161.94px 243.82px;" id="elz4x0mwqk8u" class="animable"></path>
									</g>
									<g id="freepik--Chest--inject-127" class="animable" style="transform-origin: 103.174px 242.425px;">
										<g id="eljrgxfyl26e">
											<rect x="67.44" y="204.36" width="44.46" height="47.17" style="fill: rgb(55, 71, 79); transform-origin: 89.67px 227.945px; transform: rotate(-3.98deg);" class="animable"></rect>
										</g>
										<path d="M111,244c-.69-3.35-3-7.06-3.44-10.71a138.71,138.71,0,0,1-.38-17.66c7.26-8.84,5.23-15.93,3.26-19.57-3.66-6.75-18.06-16.86-18.06-16.86l-6.31-.67a115.27,115.27,0,0,1-15.93.06l-8,5c2.63,8.05.59,14.06.15,18.72S58.71,213,58.71,213l2.86,8.17h0s2.65,7.72,3.44,10.47c.53,1.87-1.24,5.18-2.06,6.72a44.72,44.72,0,0,0-4.52,12c-2.15,8.37-2.25,18.95,4.59,27.13S105,306.87,105,306.87s.33-9.68,9.55-19.58,29-20.62,38.4-19.21c0,0-11.4-6.73-21-12.8A205,205,0,0,0,111,244Z" style="fill: rgb(31, 111, 235); transform-origin: 105.041px 242.7px;" id="elsn0tmhh9atp" class="animable"></path>
										<g id="elkcgufr3iehm">
											<path d="M111,244c-.69-3.35-3-7.06-3.44-10.71a138.71,138.71,0,0,1-.38-17.66c7.26-8.84,5.23-15.93,3.26-19.57-3.66-6.75-18.06-16.86-18.06-16.86l-6.31-.67a115.27,115.27,0,0,1-15.93.06l-8,5c2.63,8.05.59,14.06.15,18.72S58.71,213,58.71,213l2.86,8.17h0s2.65,7.72,3.44,10.47c.53,1.87-1.24,5.18-2.06,6.72a44.72,44.72,0,0,0-4.52,12c-2.15,8.37-2.25,18.95,4.59,27.13S105,306.87,105,306.87s.33-9.68,9.55-19.58,29-20.62,38.4-19.21c0,0-11.4-6.73-21-12.8A205,205,0,0,0,111,244Z" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 105.041px 242.7px;" class="animable"></path>
										</g>
										<g id="elj43ulj2phkl">
											<path d="M95.56,216.14s-4.12,5.46-13.38,4.19-11.27-8.17-11.27-8.17-.29,9,10.77,10.44C92.58,224.06,95.56,216.14,95.56,216.14Z" style="fill: rgb(31, 111, 235); opacity: 0.5; transform-origin: 83.2346px 217.47px;" class="animable"></path>
										</g>
										<path d="M70.14,178.6a30.92,30.92,0,0,0,3.14,8.5c2.49,4.65,7.91,11,10.85,35.68,1.28,10.79,4,38.15,4,38.15s-21.87,3.25-31.51-6.69A57.29,57.29,0,0,1,62.89,236c1.35-2.33,2.07-3.51,0-7.91s-5.1-13.61-5.1-13.61-5.44-21.32-4.21-26C59.15,184.48,70.14,178.6,70.14,178.6Z" style="fill: rgb(69, 90, 100); transform-origin: 70.7645px 220.019px;" id="elhuh5lwyvr9s" class="animable"></path>
										<path d="M86.47,178.58l-.06-.6,9.15.81s6.08,6,9.6,9.65c2.32,2.45,6.6,6.26,7.28,13.8.9,10,3.75,44.3,3.75,44.3l-4.62-2.3s-2.45-31.38-4-41.18C106.17,194.27,92.35,183.7,86.47,178.58Z" style="fill: rgb(69, 90, 100); transform-origin: 101.3px 212.26px;" id="el4pfitxm51ts" class="animable"></path>
									</g>
									<g id="freepik--Head--inject-127" class="animable" style="transform-origin: 80.9053px 163.948px;">
										<path d="M68.5,164.82a5.91,5.91,0,0,1-5.22-2.3c-2.64-3.23-2.74-6.38-1.33-8.41a3.45,3.45,0,0,1,5-1c1.92,1.34,1.75,3,3.28,3.43,1.15.34,2.26,1.12,3.24-4.52.91-5.25,1.8-9.73,4.5-12,4.05-3.36,9.42-1.88,10.86.3a5.43,5.43,0,0,1,7.36-1.51c2.52,1.52,4.13,4.74,4.53,14.82.4,10.27-2.1,17.41-4.53,19-1.84,1.21-4.12,1.37-8,1.35l-.17,6.27s5.81,3.49,3.73,9.67a38.77,38.77,0,0,1-10.9-2,19.77,19.77,0,0,1-10.73-9.34l.63-15.29S70.5,164.55,68.5,164.82Z" style="fill: rgb(255, 189, 167); transform-origin: 80.9053px 163.948px;" id="elhxkpoihiufb" class="animable"></path>
										<path d="M84.72,152.8a1.71,1.71,0,0,1-1.57,1.86,1.73,1.73,0,0,1-1.8-1.64,1.69,1.69,0,1,1,3.37-.22Z" style="fill: rgb(38, 50, 56); transform-origin: 83.0338px 152.906px;" id="elt1hf4jqiwp" class="animable"></path>
										<path d="M97.26,152.86a1.69,1.69,0,1,1-3.38.11,1.69,1.69,0,1,1,3.38-.11Z" style="fill: rgb(38, 50, 56); transform-origin: 95.57px 152.915px;" id="el4qahfkk44cd" class="animable"></path>
										<path d="M98.42,149.62l-3.5-2a1.94,1.94,0,0,1,2.7-.79A2.13,2.13,0,0,1,98.42,149.62Z" style="fill: rgb(38, 50, 56); transform-origin: 96.7856px 148.094px;" id="elwlt06x0toxo" class="animable"></path>
										<path d="M82.77,148.16,79,150.26a2.06,2.06,0,0,1,.76-2.91A2.27,2.27,0,0,1,82.77,148.16Z" style="fill: rgb(38, 50, 56); transform-origin: 80.7261px 148.669px;" id="elybtmvkwhcbh" class="animable"></path>
										<path d="M83.32,162.14,88.87,164a2.94,2.94,0,0,1-4,1.74A2.75,2.75,0,0,1,83.32,162.14Z" style="fill: rgb(177, 102, 104); transform-origin: 85.9935px 164.071px;" id="elx05hr1mgd1" class="animable"></path>
										<path d="M86.5,165.94a3.08,3.08,0,0,0-3-2.78,2.85,2.85,0,0,0-.29,0,2.59,2.59,0,0,0,1.73,2.56A3.36,3.36,0,0,0,86.5,165.94Z" style="fill: rgb(242, 143, 143); transform-origin: 84.8537px 164.556px;" id="el049d91fdd6gl" class="animable"></path>
										<polygon points="88.61 150.28 89.25 160.38 94.81 158.57 88.61 150.28" style="fill: rgb(240, 153, 122); transform-origin: 91.71px 155.33px;" id="elo1410bp7gf" class="animable"></polygon>
										<path d="M88.22,174a35.63,35.63,0,0,1-10.81-2.58c-1.25-.56-2.91-2.9-4.24-5.48,0,0,.87,5.06,3.56,6.85,4.09,2.73,11.43,3.53,11.43,3.53Z" style="fill: rgb(240, 153, 122); transform-origin: 80.695px 171.13px;" id="elssz01vm6vn" class="animable"></path>
									</g>
									<path d="M69.89,212.9S68,192.27,59.44,187.45c-4.42,1.8-9.21,6.1-10.43,10.69-1.43,5.41-.65,13,2.28,24.82,2.46,9.86,5,20,7.72,27.47l17.76-4.36Z" style="fill: rgb(255, 189, 167); transform-origin: 62.542px 218.94px;" id="elx7s70nf3ca8" class="animable"></path>
									<path d="M69.14,205.62s-2.73-18.17-12.73-19.08c-7.09,4-8.7,8.82-8.83,13.4a79.29,79.29,0,0,0,1.89,18c1.46,6.7,4.76,17.58,4.76,17.58s12.38.47,19.4-4.65Z" style="fill: rgb(55, 71, 79); transform-origin: 60.6031px 211.036px;" id="elrfaaischizf" class="animable"></path>
									<g id="freepik--Hands--inject-127" class="animable" style="transform-origin: 116.295px 239.967px;">
										<path d="M132.72,260.47c-2.33-1.87-5.68-4.43-8.34-5a14,14,0,0,0-1.86-.37,19.3,19.3,0,0,0,2.58-.81,4.56,4.56,0,0,0,2.07-1.71c.19-.31.3-.78,0-1a.64.64,0,0,0-.38-.1c-1,.08-2.06.07-3.09.06a23.54,23.54,0,0,1-3-.05,11.35,11.35,0,0,0-1.73-.21,18.83,18.83,0,0,0-4.09.56,28,28,0,0,1-11.71.91A62.33,62.33,0,0,1,94.39,251L84.1,248.1l-6.84-1.89-.49-.14L59,250.43c2.68,7.45,3.6,10.73,8.71,11.5,8.55,1.3,33.83,2.3,33.83,2.3s.73.27,1.89.57a30,30,0,0,0,4.5,1.44c2.94.57,6.46-.85,8.92-1.24s6.75.31,9.14,1,4.76-3.13,6-2.83S135,262.33,132.72,260.47Z" style="fill: rgb(255, 189, 167); transform-origin: 96.411px 256.222px;" id="elftkbmfx9sbv" class="animable"></path>
										<path d="M169.17,235.29c-2.39-1.93-6.85-6-8.07-6.73-3.32-2-9.74-4.68-9.74-4.68-6-2.3-24.58-10.32-24.58-10.32L115,225.72c1.57,2.75,2.5,3.55,6.18,4.51,5.63,1.46,25.41,5,25.41,5l1,.3a3.48,3.48,0,0,1,1.75,1.22,10.12,10.12,0,0,1,1.1,1.86c1,2.27.95,3,2,5.48s1.59,2.22,3.28,4.1,2.25,2.92,3,2.75,1.18-2.13.63-3.81-2.17-3.18-2.44-3.88-.18-1.69,1.72-1.28,4.32,2.78,6.15,4.37,3.31,4,5,5,1.59-.7,1.71-2.18,2.09-4.57,2.1-6.94S171.55,237.22,169.17,235.29Z" style="fill: rgb(255, 189, 167); transform-origin: 144.295px 232.6px;" id="el0cda7w07mlmh" class="animable"></path>
										<path d="M121.32,253.84a8.75,8.75,0,0,0-3.44.94,18.81,18.81,0,0,1,4.64.31,19.3,19.3,0,0,0,2.58-.81,4.9,4.9,0,0,0,1.89-1.44C125.71,253.92,123,253.84,121.32,253.84Z" style="fill: rgb(240, 153, 122); transform-origin: 122.435px 253.965px;" id="el86imk7k0ey" class="animable"></path>
									</g>
								</g>
							</g>
							<polygon points="71.44 276.62 76.68 276.62 76.68 302.62 71.56 301.02 71.44 276.62" style="fill: rgb(55, 71, 79); transform-origin: 74.06px 289.62px;" id="elhxfr3yl04f" class="animable"></polygon>
							<path d="M74.1,270.76l27.44,15.85-10.47,6.05L59.64,274.51l6.5-3.75A8,8,0,0,1,74.1,270.76Z" style="fill: rgb(31, 111, 235); transform-origin: 80.59px 281.18px;" id="eldduzdbl97k" class="animable"></path>
							<g id="elrejnn0kgsnk">
								<path d="M74.1,270.76l27.44,15.85-10.47,6.05L59.64,274.51l6.5-3.75A8,8,0,0,1,74.1,270.76Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 80.59px 281.18px;" class="animable"></path>
							</g>
							<polygon points="59.64 277.32 59.64 274.51 91.07 292.65 91.07 295.47 59.64 277.32" style="fill: rgb(31, 111, 235); transform-origin: 75.355px 284.99px;" id="el6nglrlxy39h" class="animable"></polygon>
							<g id="elv323zx4550s">
								<polygon points="59.64 277.32 59.64 274.51 91.07 292.65 91.07 295.47 59.64 277.32" style="opacity: 0.3; transform-origin: 75.355px 284.99px;" class="animable"></polygon>
							</g>
							<polygon points="101.54 286.61 101.54 289.42 91.07 295.47 91.07 292.65 101.54 286.61" style="fill: rgb(31, 111, 235); transform-origin: 96.305px 291.04px;" id="elid7ulxrv1y" class="animable"></polygon>
							<g id="el3ovwwcc3h7i">
								<polygon points="101.54 286.61 101.54 289.42 91.07 295.47 91.07 292.65 101.54 286.61" style="opacity: 0.15; transform-origin: 96.305px 291.04px;" class="animable"></polygon>
							</g>
						</g>
					</g>
					<g id="freepik--character-2--inject-127" class="animable" style="transform-origin: 211.645px 194.777px;">
						<g id="freepik--character--inject-127" class="animable" style="transform-origin: 211.645px 194.777px;">
							<g id="freepik--chair--inject-127" class="animable" style="transform-origin: 206.955px 202.767px;">
								<path d="M177.61,296.61a5.52,5.52,0,0,0,.13,1.2,2.76,2.76,0,0,0,1.07,1.66l0,0a3.15,3.15,0,0,0,3.31-.25,10.06,10.06,0,0,0,4.54-7.87,3.22,3.22,0,0,0-1.28-2.88v0l-.21-.13h0a2.8,2.8,0,0,0-1.93-.07,5.25,5.25,0,0,0-1.11.48A10,10,0,0,0,177.61,296.61Z" style="fill: rgb(69, 90, 100); transform-origin: 182.142px 293.978px;" id="el32com5uocn" class="animable"></path>
								<path d="M178.85,286.85a4.18,4.18,0,0,1,2-.62,2.44,2.44,0,0,1,1.28.36l.11.07,2.93,1.68a2.8,2.8,0,0,0-1.93-.07,5.25,5.25,0,0,0-1.11.48,10,10,0,0,0-4.55,7.86,5.52,5.52,0,0,0,.13,1.2,2.76,2.76,0,0,0,1.07,1.66l-3-1.74-.05,0-.26-.15-.08-.05a3.35,3.35,0,0,1-1.11-2.79A10,10,0,0,1,178.85,286.85Z" style="fill: rgb(38, 50, 56); transform-origin: 179.719px 292.85px;" id="elfbbukjuidv" class="animable"></path>
								<path d="M226.77,270.18a5.49,5.49,0,0,0,.13,1.19A2.7,2.7,0,0,0,228,273l0,0a3.13,3.13,0,0,0,3.31-.26,10,10,0,0,0,4.54-7.87,3.22,3.22,0,0,0-1.28-2.88h0l-.21-.13h0a2.86,2.86,0,0,0-1.93-.08,5.31,5.31,0,0,0-1.11.49A10,10,0,0,0,226.77,270.18Z" style="fill: rgb(69, 90, 100); transform-origin: 231.317px 267.502px;" id="elqgde29wythr" class="animable"></path>
								<path d="M228,260.41a4.16,4.16,0,0,1,2-.61,2.42,2.42,0,0,1,1.28.36l.11.06,2.93,1.69a2.86,2.86,0,0,0-1.93-.08,5.31,5.31,0,0,0-1.11.49,10,10,0,0,0-4.55,7.86,5.49,5.49,0,0,0,.13,1.19A2.7,2.7,0,0,0,228,273l-3-1.74-.05,0-.26-.16a.25.25,0,0,1-.08-.05,3.34,3.34,0,0,1-1.11-2.79A10.06,10.06,0,0,1,228,260.41Z" style="fill: rgb(38, 50, 56); transform-origin: 228.904px 266.4px;" id="elqzqx490raq" class="animable"></path>
								<path d="M218,306.94a6.22,6.22,0,0,1-.12,1.19,2.78,2.78,0,0,1-1.08,1.66l0,0a3.16,3.16,0,0,1-3.3-.26,10,10,0,0,1-4.55-7.87,3.2,3.2,0,0,1,1.29-2.88h0l.22-.13h0a2.83,2.83,0,0,1,1.94-.08,5.24,5.24,0,0,1,1.11.49A10,10,0,0,1,218,306.94Z" style="fill: rgb(69, 90, 100); transform-origin: 213.468px 304.286px;" id="elkdjuyurn9h" class="animable"></path>
								<path d="M216.76,297.17a4.15,4.15,0,0,0-2-.62,2.42,2.42,0,0,0-1.28.36l-.12.07-2.93,1.69a2.83,2.83,0,0,1,1.94-.08,5.24,5.24,0,0,1,1.11.49,10,10,0,0,1,4.54,7.86,6.22,6.22,0,0,1-.12,1.19,2.78,2.78,0,0,1-1.08,1.66l3-1.74.05,0,.26-.15a.15.15,0,0,0,.07-.05,3.36,3.36,0,0,0,1.12-2.79A10,10,0,0,0,216.76,297.17Z" style="fill: rgb(38, 50, 56); transform-origin: 215.881px 303.17px;" id="elbtshhjktet6" class="animable"></path>
								<path d="M249.92,291.3a6.21,6.21,0,0,1-.13,1.2,2.77,2.77,0,0,1-1.08,1.66l0,0a3.16,3.16,0,0,1-3.3-.26,10,10,0,0,1-4.54-7.87,3.22,3.22,0,0,1,1.28-2.88l0,0,.22-.12h0a2.77,2.77,0,0,1,1.94-.08,4.84,4.84,0,0,1,1.11.49A10,10,0,0,1,249.92,291.3Z" style="fill: rgb(69, 90, 100); transform-origin: 245.388px 288.659px;" id="el0hlpvxq8tf2n" class="animable"></path>
								<path d="M248.67,281.54a4.12,4.12,0,0,0-2-.62,2.42,2.42,0,0,0-1.28.36l-.11.07L242.32,283a2.77,2.77,0,0,1,1.94-.08,4.84,4.84,0,0,1,1.11.49,10,10,0,0,1,4.55,7.85,6.21,6.21,0,0,1-.13,1.2,2.77,2.77,0,0,1-1.08,1.66l3-1.74.05,0,.26-.15.07-.05a3.33,3.33,0,0,0,1.12-2.79A10,10,0,0,0,248.67,281.54Z" style="fill: rgb(38, 50, 56); transform-origin: 247.771px 287.52px;" id="elsw3i25v55z" class="animable"></path>
								<path d="M188.23,272.13a5.35,5.35,0,0,1-.13,1.19A2.75,2.75,0,0,1,187,275l0,0a3.13,3.13,0,0,1-3.3-.25,10,10,0,0,1-4.55-7.88,3.23,3.23,0,0,1,1.28-2.88h0l.22-.13h0a2.8,2.8,0,0,1,1.93-.07,5.55,5.55,0,0,1,1.12.48A10.06,10.06,0,0,1,188.23,272.13Z" style="fill: rgb(69, 90, 100); transform-origin: 183.683px 269.504px;" id="elxe83jy1y8t" class="animable"></path>
								<path d="M187,262.37a4.18,4.18,0,0,0-2-.62,2.39,2.39,0,0,0-1.28.36l-.12.06-2.93,1.69a2.8,2.8,0,0,1,1.93-.07,5.55,5.55,0,0,1,1.12.48,10.06,10.06,0,0,1,4.54,7.86,5.35,5.35,0,0,1-.13,1.19A2.75,2.75,0,0,1,187,275l3-1.74.06,0,.25-.15.08-.05a3.37,3.37,0,0,0,1.11-2.79A10,10,0,0,0,187,262.37Z" style="fill: rgb(38, 50, 56); transform-origin: 186.091px 268.375px;" id="elxa3ygynfhj" class="animable"></path>
								<polygon points="215.36 262.53 231.58 257.88 231.58 261.34 215.36 270.7 215.36 262.53" style="fill: rgb(235, 235, 235); transform-origin: 223.47px 264.29px;" id="elvrzfdaekb48" class="animable"></polygon>
								<polygon points="215.36 262.53 213.76 261.55 230.03 257.1 231.58 257.88 215.36 262.53" style="fill: rgb(245, 245, 245); transform-origin: 222.67px 259.815px;" id="elnlgo7u3b87m" class="animable"></polygon>
								<polygon points="208.34 272.87 185.34 264.25 185.34 260.74 208.34 262.8 208.34 272.87" style="fill: rgb(235, 235, 235); transform-origin: 196.84px 266.805px;" id="eljycpzvuri9" class="animable"></polygon>
								<polygon points="185.34 260.74 187.03 260.3 209.71 261.76 208.34 262.8 185.34 260.74" style="fill: rgb(245, 245, 245); transform-origin: 197.525px 261.55px;" id="el4uow5p4vi85" class="animable"></polygon>
								<path d="M207.72,271.76c0,2.61,8,2.61,8,0V241.6h-8Z" style="fill: rgb(250, 250, 250); transform-origin: 211.72px 257.659px;" id="elbbkskfp8ojs" class="animable"></path>
								<path d="M213.72,265.84l4.4,28.56h-3L211,265.84A4.92,4.92,0,0,0,213.72,265.84Z" style="fill: rgb(245, 245, 245); transform-origin: 214.56px 280.12px;" id="el2091ib8ds9k" class="animable"></path>
								<polygon points="211 265.84 211 273.69 215.11 299.24 215.11 294.4 211 265.84" style="fill: rgb(235, 235, 235); transform-origin: 213.055px 282.54px;" id="el0vg7uao8ef7g" class="animable"></polygon>
								<rect x="215.11" y="294.4" width="3.01" height="4.84" style="fill: rgb(224, 224, 224); transform-origin: 216.615px 296.82px;" id="el4fujya0qvx" class="animable"></rect>
								<polygon points="209.63 273.45 179.97 289 179.97 284.21 209.63 265.86 209.63 273.45" style="fill: rgb(235, 235, 235); transform-origin: 194.8px 277.43px;" id="eljctqvkyuj8k" class="animable"></polygon>
								<path d="M209.63,265.86a3.32,3.32,0,0,1-1.91-.86l-29.61,18,1.86,1.25Z" style="fill: rgb(245, 245, 245); transform-origin: 193.87px 274.625px;" id="elx5lw11sp4bp" class="animable"></path>
								<polygon points="179.97 289 179.97 284.21 178.11 282.96 178.11 287.6 179.97 289" style="fill: rgb(224, 224, 224); transform-origin: 179.04px 285.98px;" id="elkfwz3kpafih" class="animable"></polygon>
								<polygon points="247.02 283.51 215.33 272.66 215.33 265.54 247.02 278.72 247.02 283.51" style="fill: rgb(235, 235, 235); transform-origin: 231.175px 274.525px;" id="elsf4jvcng49" class="animable"></polygon>
								<path d="M215.33,265.54c.26-.07.37-.53.39-1.23l32.24,14-.94.38Z" style="fill: rgb(245, 245, 245); transform-origin: 231.645px 271.5px;" id="ellydrvp39mgn" class="animable"></path>
								<polygon points="247.96 278.34 247.96 282.97 247.02 283.51 247.02 278.72 247.96 278.34" style="fill: rgb(224, 224, 224); transform-origin: 247.49px 280.925px;" id="eltq1ft8cvyok" class="animable"></polygon>
								<g id="el0xwa06zzesw">
									<rect x="215.11" y="294.4" width="3.01" height="4.84" style="opacity: 0.1; transform-origin: 216.615px 296.82px;" class="animable"></rect>
								</g>
								<g id="elep8mjw3uglb">
									<polygon points="179.97 289 179.97 284.21 178.11 282.96 178.11 287.6 179.97 289" style="opacity: 0.1; transform-origin: 179.04px 285.98px;" class="animable"></polygon>
								</g>
								<g id="eloiv7gmk63ce">
									<polygon points="247.96 278.34 247.96 282.97 247.02 283.51 247.02 278.72 247.96 278.34" style="opacity: 0.1; transform-origin: 247.49px 280.925px;" class="animable"></polygon>
								</g>
								<path d="M217.33,220.89H206.12v32.67c0,3.22,11.21,3.22,11.21,0Z" style="fill: rgb(240, 240, 240); transform-origin: 211.725px 238.433px;" id="elwqrc9fxbda" class="animable"></path>
								<path d="M262.8,216.55v3L213.58,248a6.28,6.28,0,0,1-6.3,0l-49.23-28.42v-3Z" style="fill: rgb(55, 71, 79); transform-origin: 210.425px 232.699px;" id="elymsh0a8zr2p" class="animable"></path>
								<g id="elov92jul7aif">
									<rect x="235.51" y="175.24" width="5.24" height="26" style="fill: rgb(55, 71, 79); transform-origin: 238.13px 188.24px; transform: rotate(180deg);" class="animable"></rect>
								</g>
								<path d="M238.17,169.38l27.45,15.85-10.48,6.05-31.42-18.14,6.49-3.76A8,8,0,0,1,238.17,169.38Z" style="fill: rgb(31, 111, 235); transform-origin: 244.67px 179.8px;" id="ellt9tmmtvgbh" class="animable"></path>
								<g id="elcw5skc9k5im">
									<path d="M238.17,169.38l27.45,15.85-10.48,6.05-31.42-18.14,6.49-3.76A8,8,0,0,1,238.17,169.38Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 244.67px 179.8px;" class="animable"></path>
								</g>
								<polygon points="223.72 175.95 223.72 173.14 255.14 191.28 255.14 194.09 223.72 175.95" style="fill: rgb(31, 111, 235); transform-origin: 239.43px 183.615px;" id="el30ty8zs68r" class="animable"></polygon>
								<g id="elbzmmy1jni45">
									<polygon points="223.72 175.95 223.72 173.14 255.14 191.28 255.14 194.09 223.72 175.95" style="opacity: 0.3; transform-origin: 239.43px 183.615px;" class="animable"></polygon>
								</g>
								<polygon points="265.62 185.23 265.62 188.04 255.14 194.09 255.14 191.28 265.62 185.23" style="fill: rgb(31, 111, 235); transform-origin: 260.38px 189.66px;" id="el8veysqpq4am" class="animable"></polygon>
								<g id="elp0hcfj0l409">
									<polygon points="265.62 185.23 265.62 188.04 255.14 194.09 255.14 191.28 265.62 185.23" style="opacity: 0.15; transform-origin: 260.38px 189.66px;" class="animable"></polygon>
								</g>
								<path d="M190,200.87a4.7,4.7,0,0,0,2.42-4.41l-1.21-17.76c-.09-1.3-1.12-1.82-2.3-1.16L180,182.47a4.11,4.11,0,0,0-2,3.54l1.23,18a4.68,4.68,0,0,1-2.45,4.43l5.27-3.06Z" style="fill: rgb(38, 50, 56); transform-origin: 184.605px 192.847px;" id="elcwli2xkihdo" class="animable"></path>
								<path d="M191.34,201.43a4.7,4.7,0,0,0,2.41-4.42l-1.21-17.76c-.09-1.3-1.11-1.82-2.3-1.16L181.39,183a4.12,4.12,0,0,0-2,3.54l1.23,18A4.68,4.68,0,0,1,178.2,209l5.27-3.05Z" style="fill: rgb(55, 71, 79); transform-origin: 185.98px 193.402px;" id="el4gdc7kbnc8j" class="animable"></path>
								<path d="M158.05,216.54,207.22,245a6.4,6.4,0,0,0,6.39,0l49.19-28.41a10.47,10.47,0,0,0-5.23-9.06l-4.42-2.54c-.27-.17-.54-.32-.83-.49l-36-20.77a11.8,11.8,0,0,0-11.83,0l-41.22,23.8-.09.07s-.08,0-.1.08A10.38,10.38,0,0,0,158.05,216.54Z" style="fill: rgb(31, 111, 235); transform-origin: 210.425px 213.997px;" id="el2s82ywgdvr9" class="animable"></path>
								<g id="elwk2dfciivu">
									<path d="M262.8,216.54a10.47,10.47,0,0,0-5.23-9.06l-4.42-2.54c6.18,3.64,8.72,6.08,4.4,8.57l-43.83,25.32a6.68,6.68,0,0,1-3.29.88v6.06a6.58,6.58,0,0,0,3.29-.89Z" style="opacity: 0.15; transform-origin: 236.615px 225.355px;" class="animable"></path>
								</g>
								<g id="el98997mg7a3e">
									<path d="M207.12,238.83c-8.27-4.79-29.87-17.28-38.61-22.29-9.14-5.22-6.28-8.25-5.42-8.91a10.38,10.38,0,0,0-5,8.91l49.06,28.34a6.56,6.56,0,0,0,3.32.89v-6.06A6.62,6.62,0,0,1,207.12,238.83Z" style="opacity: 0.3; transform-origin: 184.28px 226.7px;" class="animable"></path>
								</g>
								<path d="M195.4,97.75,191.87,96c-2.06-1-4.78-.79-7.69.88l-26.07,15c-5.85,3.36-10.23,11.36-9.79,17.85l4.43,64.9,3.54,1.77,47.24-27.26-4.42-64.86C198.88,101,197.48,98.77,195.4,97.75Z" style="fill: rgb(55, 71, 79); transform-origin: 175.91px 145.898px;" id="el9wo34mmlbmp" class="animable"></path>
								<path d="M201.9,145.22l-2.79-40.92c-.23-3.27-1.63-5.53-3.71-6.55L191.87,96c-2.06-1-4.78-.79-7.69.88l-26.07,15a20.15,20.15,0,0,0-7.35,7.95Z" style="fill: rgb(69, 90, 100); transform-origin: 176.33px 120.308px;" id="elk2avx0v7tok" class="animable"></path>
								<path d="M195.4,97.76c-2.06-1-4.78-.79-7.68.88l-26.07,15c-5.85,3.37-10.23,11.36-9.79,17.86l4.43,64.9,4.85,2.4,47.24-27.27L204,106.69c-.22-3.2-1.57-5.43-3.59-6.47C199.53,99.78,196.23,98.17,195.4,97.76Z" style="fill: rgb(31, 111, 235); transform-origin: 180.105px 147.978px;" id="el5918i36b1qa" class="animable"></path>
								<g id="el40pmh0j8ahh">
									<path d="M154.3,121.61a18.93,18.93,0,0,0-2.44,9.91l4.43,64.9,4.85,2.4,47.24-27.27-1.63-23.92Z" style="opacity: 0.3; transform-origin: 180.115px 160.215px;" class="animable"></path>
								</g>
								<g id="elqgqgqt2syck">
									<path d="M204,106.69c-.22-3.2-1.57-5.43-3.59-6.47-.84-.44-4.14-2-5-2.46-2.06-1-4.78-.79-7.68.88l-26.07,15a20.21,20.21,0,0,0-7.35,8l52.45,26Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 180.535px 122.398px;" class="animable"></path>
								</g>
								<path d="M161.14,198.82l-4.42-64.91c-.45-6.49,3.94-14.48,9.78-17.85l26.07-15c5.85-3.37,11-.84,11.39,5.65l4.42,64.86Z" style="fill: rgb(31, 111, 235); transform-origin: 182.534px 149.194px;" id="el8cr3qo0h02o" class="animable"></path>
							</g>
							<g id="freepik--character--inject-127" class="animable" style="transform-origin: 218.271px 194.777px;">
								<g id="freepik--bottom--inject-127" class="animable" style="transform-origin: 223.448px 252.596px;">
									<path d="M261.42,239.2s-17,30.76-19.76,37.08c-2.09,4.7-3.74,8.19-4.31,11.94s.14,9.64.74,12.59c0,0,2,7.95.64,7.89s-12.29-4.64-16.13-8.57-2.19-8.11.17-16.11,6.75-19.18,6.75-19.18a5.43,5.43,0,0,0,1.14-.95c1.75-1.72,8.29-26.35,9.69-32Z" style="fill: rgb(175, 97, 82); transform-origin: 240.866px 270.295px;" id="elny6baupia4" class="animable"></path>
									<path d="M234.79,252.28l9.8,18.11c2.07-4,4.85-9.2,7.56-14.21-.6-5.65-1.4-12.75-2.14-17.89-.17-1.17-.35-2.23-.53-3.21l-9.13-3.16C239.55,235.12,237.09,244.49,234.79,252.28Z" style="fill: rgb(135, 76, 76); transform-origin: 243.47px 251.155px;" id="elwx4w8r7i7vi" class="animable"></path>
									<path d="M221,272c-.74,1.78-.54,4.48-1.54,7.36a53,53,0,0,1-4.31,9.48L212.77,293l1.14.65L218.3,286c1.34-2.34,2.85-3.82,4.12-3.43C222.42,282.59,226.57,275.14,221,272Z" style="fill: rgb(38, 50, 56); transform-origin: 218.338px 282.825px;" id="elmpk5mjuhesh" class="animable"></path>
									<path d="M230.24,264c-1.83,1.56-4.82,2.47-7.4,4.94a7.22,7.22,0,0,0-2.05,3.9c.37,1.46.78,2.13,1.09,3.17a11.07,11.07,0,0,1,.72,5.23,78,78,0,0,1-2.37,10.83c-1,3.52-1.73,5.56.1,8.58,1.42,2.35,7.09,7.25,11.28,9.92,5.71,3.63,9.86,1.95,8.9-1.54s-2-7.34-2.42-8.2a12.25,12.25,0,0,0-5.92-2.26c-4.1-.39-6.22.95-8.74-2.7-2.29-3.31-.94-10.4,3.94-18.64C232.91,267.86,230.24,264,230.24,264Z" style="fill: rgb(55, 71, 79); transform-origin: 229.927px 288.301px;" id="elcwljxhfwaq" class="animable"></path>
									<path d="M251.1,192.25c-13.17-10.08-26.66-19-26.66-19l-16.38,23.79,15.51,12.6,20.16,12.21s-1.85,1.76-3.63,9c-.45,1.87-3.23,12.36-3.63,14.12,3.5,4.46,10.87,8.62,17.76,7.32,0,0,6.39-11.11,13.91-24.42,6.56-11.63,3-17.14,1.75-18.67C266.53,205.16,264.18,202.27,251.1,192.25Z" style="fill: rgb(69, 90, 100); transform-origin: 240.02px 212.891px;" id="eluaxgof9allp" class="animable"></path>
									<path d="M243.73,221.89s-1.85,1.76-3.63,9c-.45,1.87-3.23,12.36-3.63,14.12,3.09,3.93,9.18,7.62,15.29,7.56-.53-4.86-1.16-10.2-1.75-14.3C248.58,228.37,246.68,225.91,243.73,221.89Z" style="fill: rgb(55, 71, 79); transform-origin: 244.115px 237.23px;" id="eln4a5sw2uoee" class="animable"></path>
									<path d="M243.73,221.89c-1.28-1.44-20.9-24.85-20.9-24.85l-4.42,3.22,5.16,9.42Z" style="fill: rgb(55, 71, 79); transform-origin: 231.07px 209.465px;" id="elbpwbiwo28so" class="animable"></path>
									<path d="M249.94,261.35c2.44,19.49,4.06,32,5.41,36.93A71.46,71.46,0,0,0,260,311.5a34.24,34.24,0,0,0,7,8.67s6.34,5.21,5.18,6-12.66,3.52-18.08,2.62-6.55-5.25-9.38-13.1-5.9-19.46-5.9-19.46a5.29,5.29,0,0,0,.36-1.43c.39-2.43-3.62-11.3-5.81-16.67Z" style="fill: rgb(200, 133, 106); transform-origin: 252.846px 295.16px;" id="ellmw90r06kzc" class="animable"></path>
									<path d="M236.17,307c.46,1.88,2.21,3.94,3.12,6.85a53.11,53.11,0,0,1,2.12,10.2c.48,4.22.55,4.82.55,4.82l1.31-.14s-.69-6.11-1-8.79,0-4.76,1.3-5.2C243.57,314.74,242.51,306.28,236.17,307Z" style="fill: rgb(55, 71, 79); transform-origin: 239.87px 317.913px;" id="elupeelvwmnj" class="animable"></path>
									<path d="M238.87,295.11c-.56,2.34-2.43,4.84-3.05,8.36a7.23,7.23,0,0,0,.66,4.36c1.16,1,1.88,1.26,2.75,1.91a10.93,10.93,0,0,1,3.68,3.79,80,80,0,0,1,4.49,10.13c1.29,3.42,1.89,5.51,5.15,6.87,2.54,1.06,10,1.65,15,1.32,6.75-.44,9.11-4.25,6.27-6.49s-6-4.74-6.8-5.19a12.28,12.28,0,0,0-6.11,1.68c-3.54,2.1-4.45,4.44-8.65,3-3.8-1.32-6.91-7.84-7.84-17.36C243.32,296.66,238.87,295.11,238.87,295.11Z" style="fill: rgb(69, 90, 100); transform-origin: 255.364px 313.526px;" id="elqbdelfk2r6" class="animable"></path>
									<path d="M220.59,242.82c2.85,3.1,2.93,3.1,3.53,8.38a66.65,66.65,0,0,0,2.11,11.34c1.56,5.43,6,13.27,10.37,24,4.92,1.77,13-.82,16.21-4.64,0,0-4.68-40.52-6-48a27,27,0,0,0-6.35-13.21c-9-11.15-21.92-23.87-21.92-23.87a164.72,164.72,0,0,1,3.61-18.53l-48.85,2.48c-1.45,7.87-2.4,13.89.37,21.75,3,8.41,9.37,14.71,22.85,23.08,10.88,6.76,21.92,14.91,24.06,17.23" style="fill: rgb(69, 90, 100); transform-origin: 212.353px 232.699px;" id="el8o7q1mttwz6" class="animable"></path>
									<path d="M227,188.43a16.59,16.59,0,0,1-7.78,9.11l1.67,1.62S226.35,196.22,227,188.43Z" style="fill: rgb(55, 71, 79); transform-origin: 223.11px 193.795px;" id="elkoma8wy05k" class="animable"></path>
								</g>
								<g id="freepik--top--inject-127" class="animable" style="transform-origin: 206.629px 125.268px;">
									<path d="M205.93,108.73c9.18-.32,11.75.56,14.81,4.78,4.06,5.59,12,28,14.36,39.18.65,3.05-13.59,9.47-13.59,9.47L213.35,142Z" style="fill: rgb(175, 97, 82); transform-origin: 220.526px 135.415px;" id="elrejwk1ysz4" class="animable"></path>
									<g id="freepik--chest--inject-127" class="animable" style="transform-origin: 199.592px 150.576px;">
										<path d="M184.4,109.85A88.41,88.41,0,0,1,199,108.23l7,.5a130.65,130.65,0,0,1,13.72,11.57c2.84,2.89,11.33,13.58,3.86,24.84l1.42,20.76,5.88,11.91s-4.71,9.11-19.88,12.91c-12.22,3.07-31,3.78-39-3.51,0,0,.61-7,1.25-12,1-8.05-1.25-21.81-1.25-21.81s-2-10.19-3.42-19.49c-1.88-12,6.49-21.21,6.49-21.21Z" style="fill: rgb(240, 240, 240); transform-origin: 199.592px 150.576px;" id="eljkg5a7c6qw" class="animable"></path>
									</g>
									<g id="freepik--head--inject-127" class="animable" style="transform-origin: 192.721px 94.7411px;">
										<path d="M212.1,79.48c3.21-3.58,3.63-15.28-7.8-19.62S177.46,57,176.62,68.41c-3.35.72-6.56,6.38-4.68,13.77,1.6,6.31,11.82,18.86,11.82,18.86Z" style="fill: rgb(38, 50, 56); transform-origin: 192.721px 79.3261px;" id="el3svej9b9lxs" class="animable"></path>
										<path d="M181.05,95.38a5.9,5.9,0,0,1-5.29-2c-2.79-3.08-3-6.2-1.74-8.28,1.08-1.71,3.71-2.8,5.56-1.64s2,2.25,2.78,3.65,4.07,2.36,3.39-5.26c3.05-.93,6.07-8,4.71-11.72,6.69.89,15.47.74,17.36-1.69,1,.68,5.35,4.54,4,13.63,2.22,10.2-1.12,17.49-3.46,19.2-1.77,1.3-3.47,2.12-7.29,2.28l.39,6s6.76,13.6,6.73,22.32c0,0-3.38-5.4-13.17-13.33l-10.64-8.61-1.16-16.07S183,95,181.05,95.38Z" style="fill: rgb(200, 133, 106); transform-origin: 192.915px 100.155px;" id="elxzk3l3qasq" class="animable"></path>
										<path d="M190.46,70.1c2.41,2.41,14.63,8.64,22.11,3C212.57,66.43,203.33,61.21,190.46,70.1Z" style="fill: rgb(38, 50, 56); transform-origin: 201.515px 70.3892px;" id="elnhiwsen2wtk" class="animable"></path>
										<path d="M197.62,83.8a1.68,1.68,0,1,1-1.09-2.16A1.71,1.71,0,0,1,197.62,83.8Z" style="fill: rgb(38, 50, 56); transform-origin: 196.03px 83.2452px;" id="ellyy7b8woscg" class="animable"></path>
										<path d="M210.16,79.15l-3.58-1.83a1.94,1.94,0,0,1,2.65-.92A2.12,2.12,0,0,1,210.16,79.15Z" style="fill: rgb(38, 50, 56); transform-origin: 208.466px 77.6681px;" id="elnvaodezuka" class="animable"></path>
										<path d="M206.25,83a1.68,1.68,0,1,0,1-2.22A1.71,1.71,0,0,0,206.25,83Z" style="fill: rgb(38, 50, 56); transform-origin: 207.81px 82.3655px;" id="elygo9og25bvp" class="animable"></path>
										<path d="M196.25,77.77l-3.65,2.28a2.07,2.07,0,0,1,.61-2.94A2.26,2.26,0,0,1,196.25,77.77Z" style="fill: rgb(38, 50, 56); transform-origin: 194.24px 78.4161px;" id="el91b4c39mzm7" class="animable"></path>
										<path d="M196.34,92.22l5.6,1.59a2.93,2.93,0,0,1-3.86,1.92A2.74,2.74,0,0,1,196.34,92.22Z" style="fill: rgb(135, 76, 76); transform-origin: 199.063px 94.0716px;" id="elqxes0tkr71" class="animable"></path>
										<path d="M199.69,95.83a3.06,3.06,0,0,0-3.17-2.6,2.36,2.36,0,0,0-.28,0,2.58,2.58,0,0,0,1.84,2.46A3.43,3.43,0,0,0,199.69,95.83Z" style="fill: rgb(242, 143, 143); transform-origin: 197.965px 94.5475px;" id="elmbjouda6qa" class="animable"></path>
										<polygon points="201.02 80.19 202.15 90.18 207.58 88.1 201.02 80.19" style="fill: rgb(175, 97, 82); transform-origin: 204.3px 85.185px;" id="elpjv841gw40i" class="animable"></polygon>
										<path d="M201.09,103.52a35.6,35.6,0,0,1-10.87-2c-1.27-.5-3-2.74-4.47-5.24,0,0,1.11,5,3.87,6.63,4.19,2.51,11.62,2.93,11.62,2.93Z" style="fill: rgb(175, 97, 82); transform-origin: 193.495px 101.06px;" id="elhi9mjqb621" class="animable"></path>
									</g>
									<g id="freepik--Coffee--inject-127" class="animable" style="transform-origin: 236.944px 152.209px;">
										<path d="M244.46,160.86a6.77,6.77,0,0,0,2.66-.37c1.18-.43,2.2-1.7,3.13-3.87.79-1.83,2.05-5.84,1.16-7.91-1.13-2.63-4.31-2.73-6.43-2.79l-.73,0-.11,2.51c.25,0,.65.09.92.1,1.19,0,3.81.44,4.09,1.11,1,1.63-1.53,7.9-2.62,8.57a9.75,9.75,0,0,1-4.09,0l-.82,2.1A9.49,9.49,0,0,0,244.46,160.86Z" style="fill: rgb(31, 111, 235); transform-origin: 246.668px 153.396px;" id="elq9gop85ooff" class="animable"></path>
										<g id="elbyqjqij38lj">
											<g style="opacity: 0.2; transform-origin: 246.668px 153.396px;" class="animable">
												<path d="M244.46,160.86a6.77,6.77,0,0,0,2.66-.37c1.18-.43,2.2-1.7,3.13-3.87.79-1.83,2.05-5.84,1.16-7.91-1.13-2.63-4.31-2.73-6.43-2.79l-.73,0-.11,2.51c.25,0,.65.09.92.1,1.19,0,3.81.44,4.09,1.11,1,1.63-1.53,7.9-2.62,8.57a9.75,9.75,0,0,1-4.09,0l-.82,2.1A9.49,9.49,0,0,0,244.46,160.86Z" id="el2elodyp52c2" class="animable" style="transform-origin: 246.668px 153.396px;"></path>
											</g>
										</g>
										<path d="M222.28,142.89c.24-1.07,1.29-2.12,3.17-3a22.59,22.59,0,0,1,15.77-.17c1.89.8,3,1.83,3.24,2.89l.06.39c.82,7-.36,14.82-2.82,19.36a1,1,0,0,0,0,.1.16.16,0,0,0,0,.07h0a5.51,5.51,0,0,1-2,2,13.23,13.23,0,0,1-12,.13,5.43,5.43,0,0,1-2.08-1.92h0a.25.25,0,0,1,0-.07.47.47,0,0,1-.05-.1c-2.56-4.49-3.89-12.28-3.23-19.33Z" style="fill: rgb(31, 111, 235); transform-origin: 233.475px 152.209px;" id="eletp7cynwybk" class="animable"></path>
										<g id="ela4jo3sx6jck">
											<g style="opacity: 0.1; transform-origin: 233.475px 152.209px;" class="animable">
												<path d="M222.28,142.89c.24-1.07,1.29-2.12,3.17-3a22.59,22.59,0,0,1,15.77-.17c1.89.8,3,1.83,3.24,2.89l.06.39c.82,7-.36,14.82-2.82,19.36a1,1,0,0,0,0,.1.16.16,0,0,0,0,.07h0a5.51,5.51,0,0,1-2,2,13.23,13.23,0,0,1-12,.13,5.43,5.43,0,0,1-2.08-1.92h0a.25.25,0,0,1,0-.07.47.47,0,0,1-.05-.1c-2.56-4.49-3.89-12.28-3.23-19.33Z" style="fill: rgb(255, 255, 255); transform-origin: 233.475px 152.209px;" id="elq9bnbt4hyp" class="animable"></path>
											</g>
										</g>
										<path d="M225.45,139.93c-4.33,1.93-4.3,5,.07,6.85a22.64,22.64,0,0,0,15.77-.17c4.34-1.94,4.31-5-.07-6.85A22.59,22.59,0,0,0,225.45,139.93Z" style="fill: rgb(31, 111, 235); transform-origin: 233.374px 143.268px;" id="el3vxt4v5dl79" class="animable"></path>
										<g id="elz1phxz1czn">
											<path d="M225.45,139.93c-4.33,1.93-4.3,5,.07,6.85a22.64,22.64,0,0,0,15.77-.17c4.34-1.94,4.31-5-.07-6.85A22.59,22.59,0,0,0,225.45,139.93Z" style="fill: rgb(255, 255, 255); opacity: 0.3; transform-origin: 233.374px 143.268px;" class="animable"></path>
										</g>
										<path d="M227,141.1c-3.5,1.27-3.48,3.28,0,4.47a22.33,22.33,0,0,0,12.73-.13c3.5-1.27,3.48-3.28,0-4.48A22.21,22.21,0,0,0,227,141.1Z" style="fill: rgb(31, 111, 235); transform-origin: 233.365px 143.265px;" id="el6bbp1qx32jn" class="animable"></path>
										<path d="M227,143.8a6.81,6.81,0,0,0-1.76.91,6.79,6.79,0,0,0,1.77.86,22.33,22.33,0,0,0,12.73-.13,6.43,6.43,0,0,0,1.75-.91,6.63,6.63,0,0,0-1.77-.86A22.3,22.3,0,0,0,227,143.8Z" style="fill: rgb(38, 50, 56); transform-origin: 233.365px 144.62px;" id="elp8nz0h54yy" class="animable"></path>
									</g>
									<path d="M244.73,149.14c2.22-.55,3.48,1.85,3.15,6s-3,11.64-9.32,9.48c-2.44-.84-4.05-5-1.4-6.75a42.26,42.26,0,0,0,6.21-4.6A5.66,5.66,0,0,0,244.73,149.14Z" style="fill: rgb(175, 97, 82); transform-origin: 241.863px 157.033px;" id="el8hhtf71p76r" class="animable"></path>
									<path d="M170.87,170.89a198.31,198.31,0,0,1-8.06-29.5c-3.34-17.15-.35-25.22,12.17-28.66,6.05,10.9,5.91,27,5.91,27,.93,6.27,5.46,23.11,5.46,23.11,12.9-1.45,26.49-4,28.92-5.17,1.25-.61,2.32-2.12,3.15-3.13,1.14-1.37,2.43-3.16,3.76-3.81a8,8,0,0,0,4.11,4.74,10.11,10.11,0,0,0,4.07,1.09c1.37.13,3.57,0,8.79-1.34,1.16-.3,2.6-.58,3.6.27s-.53,1.82-1,2.52c-.59.86.08,1.81-.2,2.76-.19.65-.76.79-.8,1.52s.22,1.36-.18,1.88a10.51,10.51,0,0,1-4.78,3.21,31.42,31.42,0,0,1-8.19,1.78c-1.7.09-3.59-.33-7.08.63-6.47,1.77-18.25,7.76-30.21,10.07S174.38,181.82,170.87,170.89Z" style="fill: rgb(200, 133, 106); transform-origin: 202.312px 146.868px;" id="ele7irorzi3ka" class="animable"></path>
								</g>
							</g>
							<polygon points="184 208.77 189.24 208.77 189.24 234.77 184.12 233.18 184 208.77" style="fill: rgb(55, 71, 79); transform-origin: 186.62px 221.77px;" id="eld4cq204l1s" class="animable"></polygon>
							<path d="M186.66,202.91l27.44,15.85-10.47,6.05L172.2,206.67l6.5-3.76A8,8,0,0,1,186.66,202.91Z" style="fill: rgb(31, 111, 235); transform-origin: 193.15px 213.33px;" id="elhgryr5pnhp8" class="animable"></path>
							<g id="elqsc5bng25ii">
								<path d="M186.66,202.91l27.44,15.85-10.47,6.05L172.2,206.67l6.5-3.76A8,8,0,0,1,186.66,202.91Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 193.15px 213.33px;" class="animable"></path>
							</g>
							<polygon points="172.2 209.48 172.2 206.67 203.63 224.81 203.63 227.62 172.2 209.48" style="fill: rgb(31, 111, 235); transform-origin: 187.915px 217.145px;" id="el6nwq2eybqy" class="animable"></polygon>
							<g id="eliye6w644n3s">
								<polygon points="172.2 209.48 172.2 206.67 203.63 224.81 203.63 227.62 172.2 209.48" style="opacity: 0.3; transform-origin: 187.915px 217.145px;" class="animable"></polygon>
							</g>
							<polygon points="214.1 218.76 214.1 221.57 203.63 227.62 203.63 224.81 214.1 218.76" style="fill: rgb(31, 111, 235); transform-origin: 208.865px 223.19px;" id="elcsxbc56khqh" class="animable"></polygon>
							<g id="elle0cll5rfr">
								<polygon points="214.1 218.76 214.1 221.57 203.63 227.62 203.63 224.81 214.1 218.76" style="opacity: 0.15; transform-origin: 208.865px 223.19px;" class="animable"></polygon>
							</g>
						</g>
					</g>
					<g id="freepik--Desk--inject-127" class="animable" style="transform-origin: 260.36px 304.579px;">
						<g id="freepik--desk--inject-127" class="animable" style="transform-origin: 260.36px 304.579px;">
							<g id="freepik--desk--inject-127" class="animable" style="transform-origin: 260.36px 305.154px;">
								<polygon points="210.43 370.75 214.49 368.4 214.49 471.86 210.43 474.21 210.43 370.75" style="fill: rgb(69, 90, 100); transform-origin: 212.46px 421.305px;" id="el5dsi90aams5" class="animable"></polygon>
								<polygon points="210.43 370.75 206.37 368.4 206.37 471.86 210.43 474.21 210.43 370.75" style="fill: rgb(55, 71, 79); transform-origin: 208.4px 421.305px;" id="elhogfogtnw8f" class="animable"></polygon>
								<polygon points="79.13 294.97 83.19 292.62 83.19 396.08 79.13 398.43 79.13 294.97" style="fill: rgb(69, 90, 100); transform-origin: 81.16px 345.525px;" id="el43c60ugbtn4" class="animable"></polygon>
								<polygon points="79.13 294.97 75.07 292.62 75.07 396.08 79.13 398.43 79.13 294.97" style="fill: rgb(55, 71, 79); transform-origin: 77.1px 345.525px;" id="el013ch139l7hf" class="animable"></polygon>
								<polygon points="448.77 233.15 452.83 230.8 452.83 334.26 448.77 336.61 448.77 233.15" style="fill: rgb(69, 90, 100); transform-origin: 450.8px 283.705px;" id="el4vkw3m2ndu4" class="animable"></polygon>
								<polygon points="448.77 233.15 444.71 230.8 444.71 334.26 448.77 336.61 448.77 233.15" style="fill: rgb(55, 71, 79); transform-origin: 446.74px 283.705px;" id="elnylpljl76pe" class="animable"></polygon>
								<g id="freepik--desk--inject-127" class="animable" style="transform-origin: 260.36px 255.79px;">
									<path d="M464.07,228.13v-2.41a2.2,2.2,0,0,0-1-1.72L311.29,136.34a2.18,2.18,0,0,0-2,0L57.64,281.73a2.17,2.17,0,0,0-1,1.72v2.41a2.2,2.2,0,0,0,1,1.72l151.79,87.66a2.18,2.18,0,0,0,2,0L463.08,229.85A2.17,2.17,0,0,0,464.07,228.13Z" style="fill: rgb(31, 111, 235); transform-origin: 260.355px 255.79px;" id="elfc4mn3fck1a" class="animable"></path>
									<g id="eldaskl47h6c9">
										<g style="opacity: 0.7; transform-origin: 260.355px 255.79px;" class="animable">
											<path d="M464.07,228.13v-2.41a2.2,2.2,0,0,0-1-1.72L311.29,136.34a2.18,2.18,0,0,0-2,0L57.64,281.73a2.17,2.17,0,0,0-1,1.72v2.41a2.2,2.2,0,0,0,1,1.72l151.79,87.66a2.18,2.18,0,0,0,2,0L463.08,229.85A2.17,2.17,0,0,0,464.07,228.13Z" style="fill: rgb(255, 255, 255); transform-origin: 260.355px 255.79px;" id="elq5zv1suadaj" class="animable"></path>
										</g>
									</g>
									<path d="M210.43,370.77v4.71a2,2,0,0,0,1-.24L463.08,229.86a2.21,2.21,0,0,0,1-1.72v-2.42a2.18,2.18,0,0,0-.81-1.59c.36.31.3.73-.18,1L211.42,370.54A2,2,0,0,1,210.43,370.77Z" style="fill: rgb(31, 111, 235); transform-origin: 337.255px 299.805px;" id="elgqna0r5d5ro" class="animable"></path>
									<g id="elq61aoz6xgem">
										<g style="opacity: 0.6; transform-origin: 337.255px 299.805px;" class="animable">
											<path d="M210.43,370.77v4.71a2,2,0,0,0,1-.24L463.08,229.86a2.21,2.21,0,0,0,1-1.72v-2.42a2.18,2.18,0,0,0-.81-1.59c.36.31.3.73-.18,1L211.42,370.54A2,2,0,0,1,210.43,370.77Z" style="fill: rgb(255, 255, 255); transform-origin: 337.255px 299.805px;" id="elgbcj35zihsb" class="animable"></path>
										</g>
									</g>
									<path d="M463.08,225.15,211.42,370.53a2.18,2.18,0,0,1-2,0L57.64,282.87a.6.6,0,0,1,0-1.14L309.3,136.34a2.18,2.18,0,0,1,2,0L463.08,224A.61.61,0,0,1,463.08,225.15Z" style="fill: rgb(31, 111, 235); transform-origin: 260.357px 253.435px;" id="eltyeoylez6yg" class="animable"></path>
									<g id="el9ncgpdyuq34">
										<g style="opacity: 0.8; transform-origin: 260.357px 253.435px;" class="animable">
											<path d="M463.08,225.15,211.42,370.53a2.18,2.18,0,0,1-2,0L57.64,282.87a.6.6,0,0,1,0-1.14L309.3,136.34a2.18,2.18,0,0,1,2,0L463.08,224A.61.61,0,0,1,463.08,225.15Z" style="fill: rgb(255, 255, 255); transform-origin: 260.357px 253.435px;" id="elcl6x77oeedt" class="animable"></path>
										</g>
									</g>
								</g>
							</g>
							<g id="freepik--Keyboard--inject-127" class="animable" style="transform-origin: 138.035px 262.461px;">
								<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 138.035px 262.461px;">
									<g id="freepik--shadow--inject-127">
										<path d="M105,272.43l16.87,9.83a2,2,0,0,0,1.8,0l47.45-27.36a.55.55,0,0,0,0-1L154.27,244a2,2,0,0,0-1.8,0L105,271.39A.55.55,0,0,0,105,272.43Z" style="opacity: 0.2; transform-origin: 138.035px 263.13px;" class="animable"></path>
									</g>
									<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 138.165px 261.545px;">
										<path d="M169.85,251.84a.64.64,0,0,0-.37-.51l-15-8.67a2,2,0,0,0-1.79,0l-45.82,26.5a.62.62,0,0,0-.37.51v1.58a.63.63,0,0,0,.37.52l15,8.66a2,2,0,0,0,1.79,0L169.48,254a.65.65,0,0,0,.37-.52Z" style="fill: rgb(38, 50, 56); transform-origin: 138.175px 261.545px;" id="elo6jynnp6xbg" class="animable"></path>
										<path d="M121.86,278.82l-15-8.63a.54.54,0,0,1,0-1l45.82-26.5a2,2,0,0,1,1.79,0l15,8.67a.54.54,0,0,1,0,1l-45.83,26.46A2,2,0,0,1,121.86,278.82Z" style="fill: rgb(55, 71, 79); transform-origin: 138.165px 260.754px;" id="ell9a4ig89nm" class="animable"></path>
										<path d="M122.7,279v1.61a1.81,1.81,0,0,1-.85-.2l-15-8.67a.63.63,0,0,1-.37-.52v-1.58a.63.63,0,0,0,.37.52l15,8.63A1.81,1.81,0,0,0,122.7,279Z" style="fill: rgb(38, 50, 56); transform-origin: 114.59px 275.125px;" id="elqhosqg38218" class="animable"></path>
										<path d="M109.21,269.54l2-1.16,1.49.89-2,1.16-1.49-.86v0Zm2.33,1.35,1.58-.92,1.5.89-1.59.91-1.48-.85s0,0,0,0Zm.49-3,1.58-.92,1.5.89-1.58.91L112,268v0Zm1.84,4.31,2.55-1.48,1.5.89-2.56,1.48-1.48-.86,0,0Zm0-2.72,1.26-.73,1.5.89-1.26.73-1.49-.86v0Zm.5-3,1.58-.91,1.49.88-1.58.92-1.49-.86v0Zm1.56,1.78,1.26-.72,1.49.88-1.25.73-1.49-.86v0Zm.21,5.25,2-1.16,1.49.89-2,1.16-1.49-.86v0Zm.61-8.41,11.44-6.6,1.5.89-11.45,6.6-1.48-.86h0Zm.43,5.13,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.81-3.16,1.26-.72,1.49.88-1.25.73-1.49-.86v0Zm.48,7.79,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.49-3,1.26-.73,1.49.89-1.26.72L119,272s0,0,0,0Zm.27-2.85,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.82-3.16,1.26-.72,1.49.89-1.26.72-1.48-.85s0,0,0,0Zm.48,7.79,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.49-3,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.85,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm0,8.64,1.58-.92,1.5.89-1.58.91-1.49-.85v0Zm.82-11.79,1.25-.73,1.5.89-1.26.72-1.48-.85v0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.49-3,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.85,1.26-.72,1.49.89-1.25.72-1.49-.86h0Zm.82-3.15,1.25-.73,1.5.89-1.26.73-1.48-.86,0,0Zm.4,11.08,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.08-3.3,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.49-3,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.27-2.84,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.82-3.16,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.4,11.08,1.26-.72,1.49.89-1.25.72-1.49-.85v0Zm.08-3.3,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.49-3,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.27-2.84,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.81-3.16,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.41,11.09,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.07-3.31,1.26-.72,1.5.89-1.26.72-1.49-.86v0ZM129,258.1l1.58-.92,1.5.89-1.58.91-1.49-.85v0Zm.26,7.9,1.26-.72,1.5.89-1.26.72-1.49-.86v0Zm.28-2.84,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.81-3.16,1.26-.72,1.49.88-1.25.73L130.4,260v0Zm.41,11.09,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.07-3.3,1.26-.73,1.49.89-1.25.72-1.49-.85v0Zm.49-3,1.26-.73,1.49.89-1.25.72-1.49-.85s0,0,0,0Zm.07-8.1,1.58-.91,1.49.88-1.58.92-1.49-.86v0Zm.21,5.25,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.81-3.16,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.48,7.79,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.49-3,1.26-.73,1.49.89-1.26.73-1.48-.86v0Zm.27-2.85,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.06,8.6,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.06-14,1.58-.91,1.5.89-1.59.91-1.48-.86s0,0,0,0Zm.7,2.29,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.48-.86v0Zm.49-3,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.85,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.06,8.6,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.38-14.22,2-1.17,1.49.89-2,1.17L136.2,254a0,0,0,0,1,0,0Zm.38,2.47,4-2.29,1.5.88-4,2.3-1.48-.86v0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.48-.86,0,0Zm.49-3,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.84,1.26-.73,1.49.89-1.25.72-1.49-.85v0Zm.06,8.6,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm1.24-4,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.33-10.94,1.26-.72,1.49.88-1.25.73-1.49-.86a0,0,0,0,1,0,0Zm.16,8,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.27-2.84,3-1.73,3.82,2.23-1.47.85-2-1.12a.82.82,0,0,0-.38-.08.9.9,0,0,0-.37.07l-1.15.66-1.48-.85v0Zm.06,8.6,1.26-.73,1.49.89-1.26.73-1.48-.86v0Zm1.24-4,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.33-10.94,1.26-.72,1.49.88-1.26.73-1.48-.86v0Zm.16,8,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm1.22,5.24,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.34-3.46,1.26-.72,1.5.89-1.26.72-1.49-.85v0Zm.34-10.94,1.25-.72,1.5.89-1.26.72-1.48-.86s0,0,0,0Zm.27,2.54,1.25-.73,1.5.89-1.26.72-1.49-.85s0,0,0,0Zm1.11,10.67,1.26-.72,1.49.88-1.25.73L145,263v0Zm.34-3.45,2.23-1.29,1.5.88-2.23,1.29-1.49-.85v0Zm.77-11.19,3.32-1.92,1.49.89-3.31,1.91-1.49-.85s0,0,0,0Zm.37,7.84,1.26-.72,1.49.88-1.25.73-1.49-.86v0Zm.58,5.61,1.26-.72,1.49.89-1.26.72-1.48-.86s0,0,0,0Zm1.38-12.11,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.1,5.31,1.26-.72,1.49.88-1.26.73-1.48-.86v0Zm.27,2.53,1.25-.72,1.5.89-1.26.72-1.48-.86s0,0,0,0Zm.31,3.09,1.25-.73,1.5.89-1.26.72-1.48-.85v0Zm1.11-14.65,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.1,5.31,1.26-.72,1.49.89-1.26.72-1.48-.86s0,0,0,0Zm.16-2.78,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.11,5.32,1.25-.73,1.5.89-1.26.72-1.48-.85,0,0Zm.76,2.81,1.26-.72,1.49.89-1.25.72-1.49-.86s0,0,0,0Zm.66-14.38,1.25-.73,3.83,2.24-1.26.72-3.82-2.2v0Zm.26,2.53,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.27,2.53,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.11,5.32,1.25-.73,1.5.89-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.16-2.79,1.26-.72,1.49.89-1.25.72-1.49-.86s0,0,0,0Zm.6,5.61,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm1.19-9.33,1.26-.72,1.49.89-1.25.72-1.49-.86a0,0,0,0,1,0,0Zm.27,2.54,1.25-.73,1.5.89-1.26.73-1.48-.86v0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.33,3.07L157,256l1.49.89-1.26.73-1.48-.86s0,0,0,0Zm1.19-9.32,1.26-.73L162,248.9l-1.26.73-3.81-2.2v0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm1.36,3.16,1.25-.72.65.4-1.26.73-.63-.38s0,0,0,0Zm.7-4.35,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm1.36,3.16,1.25-.72.65.4-1.26.73-.63-.37s0,0,0,0Zm.69-4.35,1.26-.72,1.5.88L163,251l-1.49-.86v0Zm1.37,3.17,1.25-.73.64.4-1.25.73-.64-.37s0,0,0,0Zm2-1.19,1.26-.72.64.39-1.26.73-.63-.37a0,0,0,0,1,0,0Z" style="fill: rgb(31, 111, 235); transform-origin: 137.985px 260.615px;" id="elb0q0zacxjbe" class="animable"></path>
									</g>
								</g>
							</g>
							<g id="freepik--Papers--inject-127" class="animable" style="transform-origin: 240.963px 327.601px;">
								<g id="freepik--shadow--inject-127">
									<path d="M234.44,349a2.8,2.8,0,0,1-1.39-.34l-23.12-13.34a1.15,1.15,0,0,1,0-2.12l4.13-2.38-2-1.14a1.15,1.15,0,0,1,0-2.12l34-19.64a2.77,2.77,0,0,1,1.38-.34,2.8,2.8,0,0,1,1.39.34L272,321.24a1.25,1.25,0,0,1,.7,1.05,1.26,1.26,0,0,1-.7,1.06l-4.13,2.38,2,1.15a1.15,1.15,0,0,1,0,2.11l-34,19.64A2.77,2.77,0,0,1,234.44,349Z" style="opacity: 0.2; transform-origin: 240.963px 328.29px;" class="animable"></path>
								</g>
								<path d="M270.1,326.14a.83.83,0,0,0-.48-.67l-23.11-13.35a2.58,2.58,0,0,0-2.33,0l-34,19.65a.8.8,0,0,0-.48.67v1.05a.83.83,0,0,0,.48.67l23.12,13.35a2.56,2.56,0,0,0,2.32,0l34-19.65a.8.8,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 239.9px 329.815px;" id="elefcmnz49kz7" class="animable"></path>
								<path d="M210.16,331.77l34-19.65a2.58,2.58,0,0,1,2.33,0l23.11,13.35a.71.71,0,0,1,0,1.34l-34,19.65a2.56,2.56,0,0,1-2.32,0l-23.12-13.35A.71.71,0,0,1,210.16,331.77Z" style="fill: rgb(255, 255, 255); transform-origin: 239.88px 329.29px;" id="el1yfol4h0rg" class="animable"></path>
								<path d="M234.44,346.73v1a2.38,2.38,0,0,1-1.17-.28l-23.11-13.34a.83.83,0,0,1-.48-.67v-1.05a.81.81,0,0,0,.48.67l23.11,13.35A2.49,2.49,0,0,0,234.44,346.73Z" style="fill: rgb(224, 224, 224); transform-origin: 222.06px 340.06px;" id="elenwan7j8cnb" class="animable"></path>
								<path d="M272.25,320.5a.83.83,0,0,0-.48-.67l-23.12-13.35a2.56,2.56,0,0,0-2.32,0l-34,19.65a.81.81,0,0,0-.48.67v1.05a.8.8,0,0,0,.48.67l23.11,13.35a2.58,2.58,0,0,0,2.33,0l34-19.65a.8.8,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 242.05px 324.175px;" id="ely37v6flpfwa" class="animable"></path>
								<path d="M212.31,326.13l34-19.65a2.56,2.56,0,0,1,2.32,0l23.12,13.35a.71.71,0,0,1,0,1.34l-34,19.65a2.56,2.56,0,0,1-2.32,0l-23.12-13.35A.7.7,0,0,1,212.31,326.13Z" style="fill: rgb(255, 255, 255); transform-origin: 242.019px 323.65px;" id="eloeegfep6jx" class="animable"></path>
								<path d="M236.59,341.1v1a2.33,2.33,0,0,1-1.17-.28l-23.11-13.34a.81.81,0,0,1-.48-.67V326.8a.81.81,0,0,0,.48.67l23.11,13.35A2.33,2.33,0,0,0,236.59,341.1Z" style="fill: rgb(224, 224, 224); transform-origin: 224.21px 334.45px;" id="elxvy5kjcpy4e" class="animable"></path>
							</g>
							<g id="freepik--papers--inject-127" class="animable" style="transform-origin: 207.728px 238.828px;">
								<g id="freepik--shadow--inject-127">
									<path d="M201.23,260.21a2.8,2.8,0,0,1-1.39-.34l-23.12-13.35a1.14,1.14,0,0,1,0-2.11l4.13-2.38-2-1.15a1.24,1.24,0,0,1-.71-1,1.25,1.25,0,0,1,.71-1.06l34-19.65a2.91,2.91,0,0,1,1.39-.33,2.84,2.84,0,0,1,1.38.33l23.12,13.35a1.15,1.15,0,0,1,0,2.12L234.66,237l2,1.14a1.15,1.15,0,0,1,0,2.12l-34,19.64A2.79,2.79,0,0,1,201.23,260.21Z" style="opacity: 0.2; transform-origin: 207.728px 239.527px;" class="animable"></path>
								</g>
								<path d="M236.89,237.38a.81.81,0,0,0-.48-.67l-23.12-13.35a2.56,2.56,0,0,0-2.32,0l-34,19.64a.83.83,0,0,0-.48.67v1.06a.82.82,0,0,0,.48.66l23.12,13.35a2.58,2.58,0,0,0,2.33,0l34-19.64a.8.8,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 206.695px 241.05px;" id="eli9ra1a0dvp" class="animable"></path>
								<path d="M176.94,243l34-19.64a2.56,2.56,0,0,1,2.32,0l23.12,13.35a.71.71,0,0,1,0,1.34l-34,19.64a2.58,2.58,0,0,1-2.33,0l-23.12-13.35A.71.71,0,0,1,176.94,243Z" style="fill: rgb(255, 255, 255); transform-origin: 206.657px 240.525px;" id="elxr0zqojkiz" class="animable"></path>
								<path d="M201.22,258v1a2.32,2.32,0,0,1-1.16-.28L177,245.4a.84.84,0,0,1-.49-.67v-1.06a.83.83,0,0,0,.49.67l23.11,13.35A2.32,2.32,0,0,0,201.22,258Z" style="fill: rgb(224, 224, 224); transform-origin: 188.865px 251.335px;" id="ellmsv6exdvt" class="animable"></path>
								<path d="M239,231.74a.81.81,0,0,0-.48-.67l-23.12-13.35a2.56,2.56,0,0,0-2.32,0l-34,19.64a.83.83,0,0,0-.48.67v1.06a.83.83,0,0,0,.48.67l23.12,13.34a2.58,2.58,0,0,0,2.33,0l34-19.64a.8.8,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 208.805px 235.41px;" id="el3ldfso4qla" class="animable"></path>
								<path d="M179.09,237.36l34-19.64a2.56,2.56,0,0,1,2.32,0l23.12,13.35a.71.71,0,0,1,0,1.34l-34,19.64a2.58,2.58,0,0,1-2.33,0l-23.12-13.34A.71.71,0,0,1,179.09,237.36Z" style="fill: rgb(255, 255, 255); transform-origin: 208.8px 234.885px;" id="eluu3n4ycjwt" class="animable"></path>
								<path d="M203.37,252.33v1a2.32,2.32,0,0,1-1.16-.28L179.1,239.76a.82.82,0,0,1-.49-.67V238a.79.79,0,0,0,.49.67l23.11,13.35A2.32,2.32,0,0,0,203.37,252.33Z" style="fill: rgb(224, 224, 224); transform-origin: 190.99px 245.665px;" id="elq7bps2nb8jp" class="animable"></path>
							</g>
							<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 248.303px 197.006px;">
								<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 248.303px 197.006px;">
									<g id="freepik--shadow--inject-127">
										<path d="M215.27,207l16.87,9.83a2,2,0,0,0,1.8,0l47.45-27.36a.55.55,0,0,0,0-1l-16.87-9.82a2,2,0,0,0-1.8,0l-47.45,27.36C214.77,206.2,214.77,206.67,215.27,207Z" style="opacity: 0.2; transform-origin: 248.303px 197.74px;" class="animable"></path>
									</g>
									<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 248.407px 196.071px;">
										<path d="M280.09,186.37a.6.6,0,0,0-.37-.52l-15-8.67a2,2,0,0,0-1.79,0l-45.83,26.5a.64.64,0,0,0-.37.52v1.57a.64.64,0,0,0,.37.52l15,8.67a2,2,0,0,0,1.8,0l45.82-26.45a.62.62,0,0,0,.37-.52C280.1,187.79,280.09,186.55,280.09,186.37Z" style="fill: rgb(38, 50, 56); transform-origin: 248.412px 196.071px;" id="elx4qpkmmhdv" class="animable"></path>
										<path d="M232.1,213.34l-15-8.63c-.49-.28-.49-.74,0-1l45.83-26.5a2,2,0,0,1,1.79,0l15,8.67a.54.54,0,0,1,0,1L233.9,213.34A2,2,0,0,1,232.1,213.34Z" style="fill: rgb(55, 71, 79); transform-origin: 248.394px 195.276px;" id="elqyqq5bfp3go" class="animable"></path>
										<path d="M232.94,213.55v1.61a1.8,1.8,0,0,1-.84-.2l-15-8.67a.63.63,0,0,1-.38-.52v-1.58a.63.63,0,0,0,.38.52l15,8.63A1.8,1.8,0,0,0,232.94,213.55Z" style="fill: rgb(38, 50, 56); transform-origin: 224.83px 209.675px;" id="elpwxmoib6jq" class="animable"></path>
										<path d="M219.46,204.07l2-1.17,1.49.89L221,205l-1.48-.86h0Zm2.33,1.34,1.58-.92,1.5.89-1.59.92-1.48-.86s0,0,0,0Zm.49-3,1.58-.91,1.5.88-1.59.92-1.48-.86s0,0,0,0Zm1.83,4.31,2.56-1.48,1.5.89-2.56,1.48-1.49-.86a0,0,0,0,1,0,0Zm.06-2.72,1.26-.72,1.49.88-1.25.73-1.49-.86a0,0,0,0,1,0,0Zm.49-3,1.58-.91,1.5.89-1.58.91-1.49-.86a0,0,0,0,1,0,0Zm1.57,1.78,1.26-.72L229,203l-1.26.72-1.48-.86s0,0,0,0Zm.21,5.25,2-1.16,1.49.89-2,1.16-1.49-.85v0Zm.61-8.4,11.43-6.61,1.5.89-11.44,6.6-1.49-.85s0,0,0,0Zm.42,5.12,1.26-.72,1.5.88-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.82-3.16,1.26-.72,1.49.89-1.26.72-1.48-.85s0,0,0,0Zm.48,7.79,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.49-3,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.85,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.82-3.15,1.25-.73,1.5.89-1.26.72-1.48-.85s0,0,0,0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.49-3,1.25-.73,1.5.89-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.27-2.84,1.26-.73,1.49.89-1.26.72-1.48-.85s0,0,0,0Zm0,8.63,1.58-.92,1.5.89-1.58.92-1.49-.86v0Zm.82-11.79,1.25-.73,1.5.89-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.48,7.78,1.25-.72,1.5.88-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.49-3,1.25-.72,1.5.88-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm.27-2.84,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.81-3.16,1.26-.73,1.5.89L236,199l-1.49-.86a0,0,0,0,1,0,0Zm.41,11.09,1.26-.73,1.49.89-1.25.72-1.49-.86h0Zm.07-3.31,1.26-.72,1.49.88-1.25.73L235,205.9v0Zm.49-3,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.28-2.84,1.25-.73,1.5.89-1.26.73-1.48-.86a0,0,0,0,1,0,0Zm.81-3.16,1.26-.72,1.49.88-1.25.73-1.49-.86v0Zm.41,11.09,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.07-3.31,1.26-.72,1.49.89-1.25.72-1.49-.86h0Zm.49-3,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.28-2.84,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.81-3.16,1.26-.72,1.49.88-1.25.73-1.49-.86v0ZM239,206.8l1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.07-3.3,1.26-.73,1.49.89-1.25.72-1.49-.85s0,0,0,0Zm.23-10.88,1.58-.92,1.5.89-1.59.92-1.48-.86s0,0,0,0Zm.26,7.91,1.26-.73,1.49.89-1.25.72-1.49-.85s0,0,0,0Zm.27-2.85,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.82-3.16,1.26-.72,1.49.89-1.26.72-1.48-.86s0,0,0,0Zm.41,11.09,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.07-3.3,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.49-3,1.26-.73,1.49.89-1.26.73-1.48-.86s0,0,0,0Zm.06-8.1,1.58-.91,1.5.89-1.58.91-1.49-.86v0Zm.21,5.25,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.82-3.15,1.25-.73,1.5.89-1.26.72-1.48-.85s0,0,0,0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.49-3,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.27-2.85,1.26-.72,1.49.89-1.25.72-1.49-.86h0Zm.06,8.6,1.26-.72,1.49.89-1.26.72-1.48-.86v0Zm.06-14,1.58-.92,1.49.89-1.58.91-1.49-.85s0,0,0,0Zm.7,2.28,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.48,7.78,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.49-3,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.27-2.84,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.06,8.6,1.26-.73,1.49.89-1.26.72-1.48-.85s0,0,0,0Zm.38-14.23,2-1.16,1.5.88-2,1.17-1.48-.86s0,0,0,0Zm.38,2.47,4-2.29,1.5.89-4,2.29-1.49-.86v0Zm.48,7.78,1.25-.72,1.5.88-1.26.73-1.49-.86v0Zm.48-3,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.28-2.84,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm.06,8.6,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm1.23-4,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.34-10.94,1.25-.72,1.5.88-1.26.73-1.48-.86s0,0,0,0Zm.15,8,1.26-.72,1.5.89-1.26.72-1.49-.86v0Zm.28-2.84,3-1.73,3.82,2.23-1.48.85L253.5,192a.77.77,0,0,0-.38-.08.88.88,0,0,0-.36.07l-1.15.67-1.49-.86v0Zm.06,8.6,1.25-.73,1.5.89-1.26.73-1.49-.86a0,0,0,0,1,0,0Zm1.23-4,1.26-.72,1.49.89-1.25.72-1.49-.86h0Zm.34-10.94,1.25-.72,1.5.89-1.26.72-1.48-.86s0,0,0,0Zm.15,8,1.26-.72,1.49.89-1.25.72-1.49-.86h0Zm1.23,5.24,1.26-.72,1.49.88-1.26.73-1.48-.86a0,0,0,0,1,0,0Zm.34-3.45,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.34-10.94,1.25-.73,1.5.89-1.26.72-1.48-.85,0,0Zm.26,2.53,1.26-.73,1.5.89-1.26.73-1.49-.86v0Zm1.12,10.67,1.26-.72,1.49.89-1.26.72-1.48-.86s0,0,0,0Zm.34-3.45,2.23-1.29,1.5.89L257,194.88l-1.48-.86s0,0,0,0Zm.77-11.19,3.31-1.92,1.5.89-3.32,1.92-1.48-.86v0Zm.37,7.84,1.26-.72,1.49.88-1.26.73-1.48-.86s0,0,0,0Zm.58,5.62,1.25-.73,1.5.89-1.26.72-1.48-.85v0Zm1.37-12.12,1.26-.73,1.5.89-1.26.73-1.49-.86v0Zm.11,5.31,1.25-.72,1.5.89-1.26.72-1.48-.86s0,0,0,0ZM259,192l1.25-.73,1.5.89-1.26.72L259,192s0,0,0,0Zm.31,3.08,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm1.11-14.65,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.26,2.53,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm.11,5.32,1.25-.73,1.5.89-1.26.72-1.49-.85s0,0,0,0Zm.16-2.79,1.26-.72,1.49.89-1.25.72-1.49-.86v0Zm.1,5.32,1.26-.73,1.5.89-1.26.73-1.49-.86v0Zm.77,2.82,1.26-.73,1.49.89-1.26.72-1.48-.85v0Zm.65-14.39,1.26-.72,3.82,2.23-1.26.72-3.81-2.2v0Zm.27,2.53L264,181l1.49.89-1.25.72-1.49-.86v0Zm.27,2.53,1.26-.72,1.49.89-1.26.72-1.48-.85v0Zm.1,5.32,1.26-.73,1.5.89-1.26.73-1.49-.86v0Zm.17-2.78,1.25-.73,1.5.89-1.26.73-1.48-.86,0,0Zm.6,5.6,1.25-.73,1.5.89-1.26.73-1.48-.86s0,0,0,0Zm1.19-9.32,1.26-.73,1.49.89-1.26.73-1.48-.86v0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.33,3.07,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm1.19-9.32,1.25-.73,3.83,2.23-1.26.73-3.81-2.2s0,0,0,0Zm.27,2.53,1.25-.73,1.5.89-1.26.73-1.49-.86v0Zm.26,2.53,1.26-.72,1.5.88-1.26.73-1.49-.86v0Zm1.37,3.17,1.25-.73.64.4-1.25.73-.64-.37s0,0,0,0Zm.69-4.36,1.26-.72,1.49.88-1.25.73-1.49-.86v0Zm1.36,3.17,1.26-.73.64.4-1.26.73-.63-.37s0,0,0,0Zm.7-4.36,1.26-.72,1.49.89-1.26.72-1.48-.86h0Zm1.36,3.17,1.26-.72.64.39-1.26.73-.63-.37v0Zm2.06-1.19,1.25-.72.65.4-1.26.72-.63-.37v0Z" style="fill: rgb(31, 111, 235); transform-origin: 248.28px 195.18px;" id="elm4470z2wp4j" class="animable"></path>
									</g>
								</g>
							</g>
							<g id="freepik--papers--inject-127" class="animable" style="transform-origin: 318px 173.347px;">
								<g id="freepik--shadow--inject-127">
									<path d="M311.47,194.73a2.77,2.77,0,0,1-1.38-.34L287,181.05a1.15,1.15,0,0,1,0-2.12l4.12-2.38-2-1.14a1.15,1.15,0,0,1,0-2.12l34-19.64a3,3,0,0,1,2.77,0L349,167a1.15,1.15,0,0,1,0,2.12l-4.12,2.38,2,1.14a1.29,1.29,0,0,1,.71,1.06,1.27,1.27,0,0,1-.71,1.06l-34,19.64A2.8,2.8,0,0,1,311.47,194.73Z" style="opacity: 0.2; transform-origin: 318px 174.021px;" class="animable"></path>
								</g>
								<path d="M347.14,171.9a.81.81,0,0,0-.48-.67l-23.12-13.35a2.58,2.58,0,0,0-2.33,0l-34,19.64a.83.83,0,0,0-.48.68v1.05a.83.83,0,0,0,.48.67l23.12,13.34a2.56,2.56,0,0,0,2.32,0l34-19.64a.84.84,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 316.935px 175.57px;" id="el8zxsj4qfxja" class="animable"></path>
								<path d="M287.19,177.52l34-19.64a2.58,2.58,0,0,1,2.33,0l23.12,13.35a.71.71,0,0,1,0,1.34l-34,19.64a2.56,2.56,0,0,1-2.32,0l-23.12-13.34A.71.71,0,0,1,287.19,177.52Z" style="fill: rgb(255, 255, 255); transform-origin: 316.91px 175.045px;" id="elm7ilbpoevyr" class="animable"></path>
								<path d="M311.47,192.49v1a2.32,2.32,0,0,1-1.16-.28l-23.12-13.34a.83.83,0,0,1-.48-.67v-1.06a.8.8,0,0,0,.48.67l23.12,13.35A2.32,2.32,0,0,0,311.47,192.49Z" style="fill: rgb(224, 224, 224); transform-origin: 299.09px 185.815px;" id="elauc65kwcgnq" class="animable"></path>
								<path d="M349.29,166.26a.81.81,0,0,0-.48-.67l-23.12-13.35a2.58,2.58,0,0,0-2.33,0l-34,19.65a.8.8,0,0,0-.48.67v1.05a.83.83,0,0,0,.48.67l23.12,13.34a2.56,2.56,0,0,0,2.32,0l34-19.64a.84.84,0,0,0,.48-.66Z" style="fill: rgb(235, 235, 235); transform-origin: 319.085px 169.93px;" id="elb8xm86uulth" class="animable"></path>
								<path d="M289.34,171.89l34-19.65a2.58,2.58,0,0,1,2.33,0l23.12,13.35a.71.71,0,0,1,0,1.34l-34,19.64a2.5,2.5,0,0,1-2.32,0l-23.12-13.34A.71.71,0,0,1,289.34,171.89Z" style="fill: rgb(255, 255, 255); transform-origin: 319.067px 169.409px;" id="eljmydhe8poa" class="animable"></path>
								<path d="M313.62,186.85v1.05a2.32,2.32,0,0,1-1.16-.28l-23.12-13.34a.83.83,0,0,1-.48-.67v-1.06a.83.83,0,0,0,.48.68l23.12,13.35A2.42,2.42,0,0,0,313.62,186.85Z" style="fill: rgb(224, 224, 224); transform-origin: 301.24px 180.225px;" id="el5h5id08oqdf" class="animable"></path>
							</g>
							<g id="freepik--Monitor--inject-127" class="animable" style="transform-origin: 289.275px 195.416px;">
								<g id="freepik--shadow--inject-127">
									<path d="M257.94,255.28l-9.32-5.38c-1.4-.81-1.4-2.12,0-2.93l66.91-38.64a5.61,5.61,0,0,1,5.08,0l9.32,5.39c1.4.8,1.4,2.12,0,2.93L263,255.28A5.61,5.61,0,0,1,257.94,255.28Z" style="opacity: 0.2; transform-origin: 289.275px 231.802px;" class="animable"></path>
								</g>
								<g id="freepik--monitor--inject-127" class="animable" style="transform-origin: 286.655px 186.591px;">
									<path d="M248.77,237.77a2.48,2.48,0,0,0,2.38,0l69.36-40.05a3.09,3.09,0,0,0,1.37-2.26l3.67-57.93a2.75,2.75,0,0,0-1-2.27,2.43,2.43,0,0,0-2.38,0l-69.36,40a3.14,3.14,0,0,0-1.37,2.27l-3.66,57.92A2.77,2.77,0,0,0,248.77,237.77Z" style="fill: rgb(55, 71, 79); transform-origin: 286.664px 186.511px;" id="elg9e7v1ydt4b" class="animable"></path>
									<path d="M251.15,237.77l69.36-40a3.09,3.09,0,0,0,1.37-2.27l3.67-57.92c.05-.85-.48-1.2-1.18-.79l-69.36,40a3.09,3.09,0,0,0-1.37,2.27L250,237C249.92,237.83,250.45,238.18,251.15,237.77Z" style="fill: rgb(69, 90, 100); transform-origin: 287.773px 187.28px;" id="eljwhrhkp36" class="animable"></path>
									<path d="M251.86,176.27l2.22,1.48a3.16,3.16,0,0,0-.45,1.3l-3.22,51-.45,7.09c-.05.77.38,1.13,1,.88a2.45,2.45,0,0,1-2.2-.1,2.75,2.75,0,0,1-1-2.28l.44-7.08,3.23-51A3.14,3.14,0,0,1,251.86,176.27Z" style="fill: rgb(38, 50, 56); transform-origin: 250.918px 207.252px;" id="elles2jtmk5j" class="animable"></path>
								</g>
								<polygon points="275.11 231.01 281.56 227.29 282.87 235.96 275.11 231.01" style="fill: rgb(38, 50, 56); transform-origin: 278.99px 231.625px;" id="elzwzqdxwqybo" class="animable"></polygon>
								<path d="M305.37,228,286.47,239a2.4,2.4,0,0,1-1.21.27,2.49,2.49,0,0,1-1-.26h0l-8.33-4.81,0,0a2.42,2.42,0,0,1-1.09-1.92v-.45c0-.71.5-1,1.12-.65l5,2.89,1.12.65-.25-1.27L280,209.07l15.52-9,2.65,1.53,8.08,24.53A2,2,0,0,1,305.37,228Z" style="fill: rgb(69, 90, 100); transform-origin: 290.55px 219.671px;" id="ely39fn20u21d" class="animable"></path>
								<g id="el2si6qh1s78l">
									<g style="opacity: 0.1; transform-origin: 289.09px 205.32px;" class="animable">
										<polygon points="282.65 210.59 298.17 201.57 295.53 200.05 280.01 209.07 282.65 210.59" style="fill: rgb(255, 255, 255); transform-origin: 289.09px 205.32px;" id="elk4rq3q5d0h9" class="animable"></polygon>
									</g>
								</g>
								<path d="M282.65,210.6,280,209.07l1.77,24.3c.15.7-.26,1-.87.62l-5-2.89c-.62-.36-1.12-.06-1.12.65v.45a2.42,2.42,0,0,0,1.09,1.92l0,0,8.33,4.81h0a2.49,2.49,0,0,0,1,.26Z" style="fill: rgb(55, 71, 79); transform-origin: 279.99px 224.13px;" id="elp0ole0nv1nh" class="animable"></path>
							</g>
							<g id="freepik--black-pencil--inject-127" class="animable" style="transform-origin: 274.457px 308.86px;">
								<g id="freepik--shadow--inject-127">
									<path d="M266.11,303.29l21.13,12.17c.81.49-1.59,2-2.53,1.47l-21-12.26-2.08-2.93Z" style="opacity: 0.2; transform-origin: 274.517px 309.391px;" class="animable"></path>
								</g>
								<g id="freepik--Pencil--inject-127" class="animable" style="transform-origin: 273.745px 308.436px;">
									<path d="M264.05,303.88l-2.52-3a.13.13,0,0,1,.12-.2l3.83.85Z" style="fill: rgb(242, 143, 143); transform-origin: 263.495px 302.28px;" id="elxtl7jnbv6qj" class="animable"></path>
									<path d="M261.63,300.7h0l3.83.85-.76,1.23-.41-.25-2.68-1.69Z" style="fill: rgb(255, 168, 167); transform-origin: 263.535px 301.74px;" id="eli4lpa1y8un" class="animable"></path>
									<path d="M261.65,300.7a.13.13,0,0,0-.12.2l.49.58h0a.65.65,0,0,1,.39-.57l.06,0Z" style="fill: rgb(38, 50, 56); transform-origin: 261.99px 301.09px;" id="elgp64jwicmb8" class="animable"></path>
									<path d="M282.84,312l-.31-.18-.37-.23-16.68-10.06a1.79,1.79,0,0,0-1.06.71,1.77,1.77,0,0,0-.37,1.62l16.66,10.06.72.43C281,314.07,282.39,311.72,282.84,312Z" style="fill: rgb(55, 71, 79); transform-origin: 273.414px 307.94px;" id="elh6eig3gvpfh" class="animable"></path>
									<path d="M282.84,312l-.31-.18-.37-.23-16.68-10.06a1.86,1.86,0,0,0-1.22.95l17.4,10.62s0,.06,0,.06C282,312.52,282.59,311.86,282.84,312Z" style="fill: rgb(69, 90, 100); transform-origin: 273.55px 307.345px;" id="el49u4vs82ch4" class="animable"></path>
									<path d="M281.66,311.31l-.69-.42c-.2-.12-.58.22-.92.69-.46.62-.74,1.5-.48,1.67l.51.3.18.11c-.26-.17,0-1.05.48-1.66.33-.45.7-.79.9-.7h0Z" style="fill: rgb(235, 235, 235); transform-origin: 280.565px 312.263px;" id="el4mxo8govpur" class="animable"></path>
									<path d="M281.49,311.21c-.46-.3-1.86,2.05-1.4,2.35l.69.42c-.46-.3.94-2.65,1.4-2.35Z" style="fill: rgb(55, 71, 79); transform-origin: 281.089px 312.582px;" id="ele5dm1zur0n9" class="animable"></path>
									<path d="M282.84,312l-.68-.41c-.45-.29-1.82,2-1.45,2.33l0,0,.69.41C281,314.07,282.39,311.72,282.84,312Z" style="fill: rgb(235, 235, 235); transform-origin: 281.744px 312.947px;" id="elxuwf8qonzvq" class="animable"></path>
									<path d="M261.53,300.75a.11.11,0,0,1,.09-.05A.11.11,0,0,0,261.53,300.75Z" style="fill: rgb(255, 168, 167); transform-origin: 261.575px 300.725px;" id="elmxv71ajfiu8" class="animable"></path>
									<path d="M285.73,313.78,282.84,312c-.19-.12-.58.22-.92.68-.46.62-.75,1.5-.49,1.67h0l2.9,1.76c.58.35,1.43-.74,1.61-1.59A.67.67,0,0,0,285.73,313.78Z" style="fill: rgb(242, 143, 143); transform-origin: 283.655px 314.076px;" id="elxu3n26p90y" class="animable"></path>
									<g id="eldmuhq3pnut">
										<path d="M284.85,314.07a2.11,2.11,0,0,0-.68,1.75.45.45,0,0,0,.26.35c.57.19,1.34-.82,1.51-1.63a.69.69,0,0,0-.19-.75C285.53,313.66,285.19,313.75,284.85,314.07Z" style="opacity: 0.1; transform-origin: 285.071px 314.963px;" class="animable"></path>
									</g>
								</g>
							</g>
							<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 306.04px 287.791px;">
								<g id="freepik--shadow--inject-127">
									<path d="M273,297.77l16.87,9.82a2,2,0,0,0,1.8,0l47.46-27.36a.55.55,0,0,0,0-1l-16.87-9.82a2,2,0,0,0-1.8,0L273,296.73A.55.55,0,0,0,273,297.77Z" style="opacity: 0.2; transform-origin: 306.04px 288.5px;" class="animable"></path>
								</g>
								<g id="freepik--keyboard--inject-127" class="animable" style="transform-origin: 306.195px 286.889px;">
									<path d="M337.87,277.18a.63.63,0,0,0-.37-.52l-15-8.67a2,2,0,0,0-1.79,0l-45.82,26.5a.63.63,0,0,0-.37.52v1.57a.62.62,0,0,0,.37.52l15,8.67a2,2,0,0,0,1.79,0l45.83-26.46a.64.64,0,0,0,.37-.51Z" style="fill: rgb(38, 50, 56); transform-origin: 306.2px 286.88px;" id="elpwnnx8ibbfr" class="animable"></path>
									<path d="M289.88,304.15l-15-8.62a.55.55,0,0,1,0-1L320.69,268a2,2,0,0,1,1.79,0l15,8.67a.55.55,0,0,1,0,1l-45.83,26.45A2,2,0,0,1,289.88,304.15Z" style="fill: rgb(55, 71, 79); transform-origin: 306.18px 286.065px;" id="elib6mmaifu7" class="animable"></path>
									<path d="M290.72,304.37V306a1.86,1.86,0,0,1-.84-.21l-15-8.67a.62.62,0,0,1-.37-.51V295a.62.62,0,0,0,.37.51l15,8.64A1.86,1.86,0,0,0,290.72,304.37Z" style="fill: rgb(38, 50, 56); transform-origin: 282.615px 300.5px;" id="elp06tcan80la" class="animable"></path>
									<path d="M290.84,302.79h0l-1.48-.85,2-1.17,1.54.86-2,1.17Zm-2.33-1.34h0l-1.49-.86,1.58-.91,1.54.86-1.58.91Zm5.14-.29h0l-1.49-.86,1.59-.92,1.53.87-1.58.91Zm-7.47-1.06h0l-1.49-.86,2.56-1.48,1.54.87-2.56,1.47Zm4.71,0h0l-1.49-.85,1.26-.73,1.54.86-1.26.73Zm5.15-.28h0l-1.48-.86,1.58-.91,1.54.86-1.58.91Zm-3.09-.91h0l-1.49-.86,1.26-.73,1.54.86-1.26.73Zm-9.09-.12h0l-1.48-.85,2-1.17,1.54.87-2,1.16Zm14.56-.35s0,0,0,0l-1.49-.85,11.44-6.61,1.54.86-11.44,6.61Zm-8.88-.25h0L288,297.3l1.26-.73,1.54.87-1.26.72Zm5.47-.47h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-13.48-.27h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm5.14-.29h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm4.93-.16h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm5.47-.47h0l-1.49-.86,1.26-.73,1.53.87-1.25.72Zm-13.48-.27h0l-1.49-.86,1.26-.72,1.53.86-1.25.73Zm5.14-.29h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm4.93-.15h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm-15,0h0l-1.48-.86,1.58-.91,1.54.86-1.58.91Zm20.42-.47h0l-1.48-.86,1.26-.72,1.53.86-1.25.72ZM285.65,295h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm5.14-.28h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm4.93-.16h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm5.47-.47h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm-19.2-.24h0l-1.48-.86,1.25-.73,1.54.87-1.26.72Zm5.72,0h0l-1.48-.85,1.25-.73,1.54.86-1.25.73Zm5.14-.28h0l-1.49-.86,1.26-.72,1.53.86-1.25.73Zm4.93-.16h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm5.47-.47h0l-1.48-.86,1.25-.72,1.54.86-1.26.73Zm-19.2-.24h0l-1.48-.86,1.25-.73,1.54.87-1.26.72Zm5.71,0h0l-1.48-.86,1.25-.73,1.54.87-1.26.72Zm5.15-.28h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm4.93-.16h0l-1.48-.86,1.25-.73,1.54.86-1.26.73Zm5.46-.47h0l-1.49-.85,1.26-.73,1.54.86-1.26.73Zm-19.2-.23h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm5.72,0h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm18.84-.13h0l-1.49-.86,1.59-.92,1.53.87-1.58.91ZM297,291.19h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm4.92-.16h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm5.47-.47h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-19.2-.23h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm5.72,0h0l-1.49-.86,1.26-.73,1.54.87-1.26.72ZM299,290h0l-1.48-.86,1.25-.73,1.54.87L299,290Zm14,0h0l-1.48-.86,1.58-.91,1.54.86-1.58.91Zm-9.1-.13h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm5.47-.47h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-13.48-.27h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm5.14-.29h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm4.93-.15h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm-14.89,0h0l-1.48-.86,1.25-.73,1.54.87-1.26.72Zm24.31,0h0l-1.49-.85,1.58-.92,1.54.87-1.58.91Zm-3.95-.4h0l-1.49-.86,1.26-.73,1.53.87-1.25.72ZM298,287.91h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm5.14-.29h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm4.93-.15h0l-1.49-.86,1.26-.72,1.53.86-1.25.73Zm-14.9,0h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm24.65-.22h0l-1.49-.86,2-1.16,1.54.86-2,1.16Zm-4.28-.21h0l-1.48-.86,4-2.29,1.54.87-4,2.28Zm-13.48-.28h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm5.14-.28h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm4.93-.16h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm-14.9,0h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm6.89-.71h0l-1.48-.86,1.25-.73,1.54.86-1.26.73Zm18.95-.19h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm-13.81-.09h0l-1.49-.86,1.26-.72,1.53.86-1.25.73Zm4.93-.16h0l-1.48-.85,1.15-.67a.23.23,0,0,0,.13-.21.25.25,0,0,0-.14-.22L309.86,282l1.47-.85,3.87,2.2-3,1.73Zm-14.9,0h0l-1.49-.86,1.26-.72,1.54.86-1.26.72Zm6.88-.72h0l-1.48-.86,1.25-.73,1.54.87-1.26.72Zm18.95-.19h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm-13.8-.09h0l-1.48-.85,1.26-.73,1.53.86-1.25.73Zm-9.07-.71h0l-1.48-.85,1.25-.73,1.54.86-1.26.73Zm6-.2h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm18.95-.19h0l-1.49-.85,1.26-.73,1.54.86-1.26.73Zm-4.38-.15h0l-1.48-.86,1.26-.72,1.53.86-1.25.72Zm-18.5-.65h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm6-.19h0l-1.49-.86,2.24-1.29,1.53.87L308.31,282Zm19.38-.45h0l-1.49-.86,3.32-1.92,1.54.87-3.32,1.91Zm-13.58-.21h0l-1.48-.86,1.25-.72,1.54.86-1.26.73Zm-9.73-.34h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm21-.79h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm-9.21-.06h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm-4.38-.16h0l-1.49-.86,1.26-.73,1.53.87-1.25.72Zm-5.34-.18h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm25.37-.64h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-4.38-.15h0l-1.48-.85,1.25-.73,1.54.86-1.26.73Zm-9.21-.06h0l-1.49-.85,1.26-.73,1.54.86-1.26.73Zm4.82-.1h0L321.5,278l1.26-.73,1.54.87-1.26.72Zm-9.2-.05h0l-1.48-.86,1.26-.73,1.53.87-1.25.72Zm-4.88-.45h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm24.91-.37h0l-3.82-2.2,1.26-.73,3.87,2.21-1.26.72Zm-4.39-.16h0l-1.49-.85,1.26-.73,1.54.86-1.26.73Zm-4.38-.15h0l-1.49-.86,1.26-.72,1.53.86-1.25.72Zm-9.2-.06h0l-1.48-.86,1.25-.72,1.54.86-1.26.73Zm4.81-.1h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-9.7-.34h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm16.15-.69h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm-4.39-.16h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-4.38-.15h0l-1.48-.86,1.26-.72,1.53.86-1.25.73Zm-5.32-.19h0l-1.49-.86,1.26-.72,1.54.86-1.26.73Zm16.15-.69h0l-3.81-2.2,1.26-.72,3.86,2.2-1.25.73Zm-4.39-.16h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-4.38-.15h0l-1.48-.85,1.25-.73,1.54.86-1.26.73Zm-5.48-.79h0l-.64-.37,1.26-.72.69.37-1.25.72Zm7.53-.4h0l-1.48-.86,1.25-.73,1.54.87-1.26.72ZM317,273h0l-.64-.37,1.25-.72.69.37L317,273Zm7.54-.4h0l-1.49-.86,1.26-.73,1.54.87-1.26.72Zm-5.48-.78h0l-.65-.36,1.26-.73.69.37-1.25.72Zm2.06-1.19h0l-.65-.36,1.26-.73.69.37-1.25.73Z" style="fill: rgb(31, 111, 235); transform-origin: 306.19px 286.17px;" id="elej0us95y2yk" class="animable"></path>
								</g>
							</g>
							<g id="freepik--Device--inject-127" class="animable" style="transform-origin: 272.1px 243.776px;">
								<g id="freepik--shadow--inject-127">
									<path d="M240.27,296l-9.33-5.38c-1.4-.81-1.4-2.12,0-2.93l66.92-38.63a5.59,5.59,0,0,1,5.07,0l9.33,5.38c1.4.81,1.4,2.12,0,2.93L245.34,296A5.59,5.59,0,0,1,240.27,296Z" style="opacity: 0.2; transform-origin: 271.6px 272.53px;" class="animable"></path>
								</g>
								<g id="freepik--monitor--inject-127" class="animable" style="transform-origin: 275.19px 240.337px;">
									<path d="M283.57,284l10.28-6a2.69,2.69,0,0,0,1.59-2.21v-1.43l-9.67-5.49a2.25,2.25,0,0,1-1-1.78V254.45l-18,10.38V277.5a2.26,2.26,0,0,0,1,1.77l8.08,4.71A8.52,8.52,0,0,0,283.57,284Z" style="fill: rgb(55, 71, 79); transform-origin: 281.105px 269.682px;" id="el4lo1jxpgg2a" class="animable"></path>
									<path d="M271.1,279.74v1.43l-3.29-1.9a2.26,2.26,0,0,1-1-1.77V264.83l3.42-2c.07.87.15,1.77.22,2.64l-2.4,1.39v9.9a2.25,2.25,0,0,0,1,1.78Z" style="fill: rgb(38, 50, 56); transform-origin: 268.955px 272px;" id="elcr2u9rxtpdo" class="animable"></path>
									<path d="M268,276.79a2.28,2.28,0,0,0,1,1.77l6.84,4a8.52,8.52,0,0,0,7.68,0l10.28-5.95c2.12-1.22,2.12-3.2,0-4.43l-6.84-4a2.25,2.25,0,0,1-1-1.77v-9.9l-18,10.37Z" style="fill: rgb(69, 90, 100); transform-origin: 281.675px 269.992px;" id="el685qizoisft" class="animable"></path>
									<path d="M289.09,269.39h0L287,268.18a2.25,2.25,0,0,1-1-1.77v-9.9l-18,10.37v9.91a2.28,2.28,0,0,0,1,1.77l2.05,1.18Z" style="fill: rgb(55, 71, 79); transform-origin: 278.545px 268.125px;" id="eli1lyjnrp44l" class="animable"></path>
									<path d="M241.13,289.35a3,3,0,0,0,2.57-.1l69.43-40.09a2.54,2.54,0,0,0,1.18-2.16l-3.66-53.75a2.77,2.77,0,0,0-1.39-2.06,3,3,0,0,0-2.57.09l-69.43,40.09a2.55,2.55,0,0,0-1.19,2.16l3.67,53.76A2.75,2.75,0,0,0,241.13,289.35Z" style="fill: rgb(55, 71, 79); transform-origin: 275.19px 240.268px;" id="elwta9de1j5fs" class="animable"></path>
									<path d="M243.7,289.25l69.43-40.09a2.51,2.51,0,0,0,1.18-2.16l-3.66-53.75c-.06-.79-.67-1.1-1.38-.69l-69.43,40.09a2.51,2.51,0,0,0-1.18,2.16l3.66,53.75C242.37,289.35,243,289.65,243.7,289.25Z" style="fill: rgb(69, 90, 100); transform-origin: 276.485px 240.903px;" id="el1ppx3hm5mx1" class="animable"></path>
									<path d="M236.38,232.31l2.58,1.27a2.42,2.42,0,0,0-.31,1.23l3.23,47.33.45,6.58a.8.8,0,0,0,1.19.77,3.07,3.07,0,0,1-2.38,0,2.75,2.75,0,0,1-1.39-2.07l-.45-6.57-3.23-47.33A2.41,2.41,0,0,1,236.38,232.31Z" style="fill: rgb(38, 50, 56); transform-origin: 239.795px 261.02px;" id="el6zgxjpn50r4" class="animable"></path>
									<path d="M244.32,281.31l-3.12-45.79a1.5,1.5,0,0,1,.71-1.29l65.65-37.91c.43-.24.8-.06.83.41l3.12,45.78a1.52,1.52,0,0,1-.71,1.3l-65.65,37.91C244.72,282,244.35,281.78,244.32,281.31Z" style="fill: rgb(250, 250, 250); transform-origin: 276.355px 239.029px;" id="elualsjvvonw8" class="animable"></path>
									<path d="M301.7,204.9l-53.24,30.74a1.4,1.4,0,0,0-.65,1.18l2.54,37.3c0,.43.37.6.76.37l53.24-30.74a1.39,1.39,0,0,0,.64-1.18l-2.54-37.29A.46.46,0,0,0,301.7,204.9Z" style="fill: rgb(69, 90, 100); transform-origin: 276.4px 239.695px;" id="el7uefyghjrh" class="animable"></path>
									<path d="M254.38,238.55,256,262a.45.45,0,0,1-.21.39l-3.91,2.25c-.13.08-.24,0-.25-.12L250,241.07a.46.46,0,0,1,.22-.39l3.9-2.26A.15.15,0,0,1,254.38,238.55Z" style="fill: rgb(38, 50, 56); transform-origin: 253px 251.52px;" id="elestnr1onwdv" class="animable"></path>
									<path d="M253.9,239.72l-1.29.74a.34.34,0,0,0-.16.3l.1,1.43c0,.11.09.15.18.09l1.29-.74a.34.34,0,0,0,.16-.3l-.09-1.43C254.08,239.7,254,239.66,253.9,239.72Z" style="fill: rgb(55, 71, 79); transform-origin: 253.315px 241px;" id="elwygm0x2tr8r" class="animable"></path>
									<path d="M251.9,240.87l-1.28.74a.38.38,0,0,0-.17.3l.1,1.43c0,.11.09.15.19.09l1.29-.74a.37.37,0,0,0,.16-.3l-.1-1.43C252.08,240.85,252,240.81,251.9,240.87Z" style="fill: rgb(55, 71, 79); transform-origin: 251.32px 242.15px;" id="ela2lyshjld5e" class="animable"></path>
									<path d="M254.05,241.89l-1.29.74a.34.34,0,0,0-.16.3l.09,1.43a.11.11,0,0,0,.19.09l1.29-.74a.33.33,0,0,0,.16-.29l-.09-1.44A.12.12,0,0,0,254.05,241.89Z" style="fill: rgb(55, 71, 79); transform-origin: 253.465px 243.176px;" id="elusumxjtv13g" class="animable"></path>
									<path d="M252.05,243l-1.29.75a.34.34,0,0,0-.16.29l.1,1.43c0,.11.09.15.19.1l1.29-.75a.35.35,0,0,0,.16-.29l-.1-1.44C252.23,243,252.15,243,252.05,243Z" style="fill: rgb(55, 71, 79); transform-origin: 251.47px 244.296px;" id="elt8pamp2ykrn" class="animable"></path>
									<path d="M254.2,244.06l-1.29.75a.34.34,0,0,0-.17.29l.1,1.43c0,.11.09.15.19.1l1.29-.75a.32.32,0,0,0,.16-.29l-.1-1.43C254.38,244.05,254.29,244,254.2,244.06Z" style="fill: rgb(55, 71, 79); transform-origin: 253.61px 245.343px;" id="el9dbvyal105" class="animable"></path>
									<path d="M252.2,245.21l-1.29.75a.35.35,0,0,0-.16.29l.1,1.44c0,.1.09.15.19.09l1.28-.74a.38.38,0,0,0,.17-.3l-.1-1.43C252.38,245.2,252.3,245.16,252.2,245.21Z" style="fill: rgb(55, 71, 79); transform-origin: 251.62px 246.497px;" id="ele9wnrvzprgm" class="animable"></path>
									<path d="M254.34,246.24l-1.29.74a.39.39,0,0,0-.16.3l.1,1.43c0,.11.09.15.19.09l1.29-.74a.37.37,0,0,0,.16-.3l-.1-1.43C254.52,246.22,254.44,246.18,254.34,246.24Z" style="fill: rgb(55, 71, 79); transform-origin: 253.76px 247.52px;" id="elgz8lxunj4n4" class="animable"></path>
									<path d="M252.35,247.39l-1.29.74a.34.34,0,0,0-.16.3l.1,1.43c0,.11.09.15.18.09l1.29-.74a.34.34,0,0,0,.16-.3l-.09-1.43C252.53,247.37,252.45,247.33,252.35,247.39Z" style="fill: rgb(55, 71, 79); transform-origin: 251.765px 248.67px;" id="elr8a49z1m0p" class="animable"></path>
									<path d="M254.49,248.41l-1.29.74a.37.37,0,0,0-.16.3l.1,1.43c0,.11.09.15.19.09l1.29-.74a.39.39,0,0,0,.16-.29l-.1-1.44A.11.11,0,0,0,254.49,248.41Z" style="fill: rgb(55, 71, 79); transform-origin: 253.91px 249.686px;" id="el9qngjytoce4" class="animable"></path>
									<path d="M252.5,249.56l-1.29.75a.32.32,0,0,0-.16.29l.09,1.43a.12.12,0,0,0,.19.1l1.29-.75a.33.33,0,0,0,.16-.29l-.09-1.44A.12.12,0,0,0,252.5,249.56Z" style="fill: rgb(55, 71, 79); transform-origin: 251.915px 250.845px;" id="elww23uuurn3" class="animable"></path>
									<path d="M254.64,250.58l-1.29.75a.32.32,0,0,0-.16.29l.1,1.43c0,.11.09.15.18.1l1.29-.75a.34.34,0,0,0,.17-.29l-.1-1.43C254.82,250.57,254.74,250.53,254.64,250.58Z" style="fill: rgb(55, 71, 79); transform-origin: 254.06px 251.865px;" id="elx60vzuch35f" class="animable"></path>
									<path d="M252.65,251.73l-1.29.75a.36.36,0,0,0-.17.29l.1,1.44a.11.11,0,0,0,.19.09l1.29-.74a.34.34,0,0,0,.16-.3l-.1-1.43C252.83,251.72,252.74,251.68,252.65,251.73Z" style="fill: rgb(55, 71, 79); transform-origin: 252.06px 253.022px;" id="elaygvva5vd5" class="animable"></path>
									<path d="M254.79,252.76l-1.29.74a.34.34,0,0,0-.16.3l.09,1.43a.11.11,0,0,0,.19.09l1.29-.74a.34.34,0,0,0,.16-.3l-.09-1.43A.11.11,0,0,0,254.79,252.76Z" style="fill: rgb(55, 71, 79); transform-origin: 254.205px 254.04px;" id="elykad9yninep" class="animable"></path>
									<path d="M252.79,253.91l-1.29.74a.39.39,0,0,0-.16.3l.1,1.43c0,.11.09.15.19.09l1.29-.74a.37.37,0,0,0,.16-.3L253,254C253,253.89,252.89,253.85,252.79,253.91Z" style="fill: rgb(55, 71, 79); transform-origin: 252.21px 255.19px;" id="ell5j4dyq2ic" class="animable"></path>
									<path d="M254.94,254.93l-1.29.74a.34.34,0,0,0-.16.3l.09,1.43c0,.11.09.15.19.09l1.29-.74a.34.34,0,0,0,.16-.29l-.1-1.44A.11.11,0,0,0,254.94,254.93Z" style="fill: rgb(55, 71, 79); transform-origin: 254.355px 256.211px;" id="el112bnnpipg7" class="animable"></path>
									<path d="M252.94,256.08l-1.29.75a.34.34,0,0,0-.16.29l.1,1.43c0,.11.09.15.19.1l1.29-.75a.37.37,0,0,0,.16-.29l-.1-1.44C253.12,256.07,253,256,252.94,256.08Z" style="fill: rgb(55, 71, 79); transform-origin: 252.36px 257.358px;" id="elcoytir4zf8" class="animable"></path>
									<path d="M255,257.75l-1.92,1.11a.48.48,0,0,0-.24.44l.14,2.14c0,.16.14.22.28.14l1.92-1.11a.52.52,0,0,0,.25-.44l-.15-2.14C255.31,257.73,255.18,257.67,255,257.75Z" style="fill: rgb(224, 224, 224); transform-origin: 254.135px 259.665px;" id="el9hk1vxze8g" class="animable"></path>
									<path d="M252.18,261.52l1.57-.92a.06.06,0,0,1,.07,0l.12,1.75-1.58.91a.07.07,0,0,1-.07,0l-.11-1.75m-.29-.27a.5.5,0,0,0-.13.34l.15,2.14c0,.16.13.22.28.14l1.92-1.11a.52.52,0,0,0,.11-.1.47.47,0,0,0,.13-.34l-.14-2.14a.17.17,0,0,0-.28-.14L252,261.15a.35.35,0,0,0-.11.1Z" style="fill: rgb(31, 111, 235); transform-origin: 253.055px 261.942px;" id="elj2rkl7hllka" class="animable"></path>
									<path d="M295,217l-34.64,20a2.65,2.65,0,0,0-1.23,2.24L260.78,264c.05.81.69,1.13,1.42.7l34.64-20a2.64,2.64,0,0,0,1.23-2.24l-1.68-24.68C296.33,216.92,295.69,216.61,295,217Z" style="fill: rgb(250, 250, 250); transform-origin: 278.6px 240.863px;" id="elm27r7296g8" class="animable"></path>
									<path d="M261.22,241.9a.35.35,0,0,1-.29-.15.27.27,0,0,1,.09-.4l12.44-7.18a.34.34,0,0,1,.44.1.27.27,0,0,1-.09.4l-12.44,7.18A.38.38,0,0,1,261.22,241.9Z" style="fill: rgb(224, 224, 224); transform-origin: 267.415px 238.014px;" id="eltg202kgaslp" class="animable"></path>
									<path d="M261.4,244.66a.34.34,0,0,1-.28-.14.28.28,0,0,1,.09-.41l12.44-7.18a.34.34,0,0,1,.44.1.27.27,0,0,1-.09.4l-12.44,7.19A.42.42,0,0,1,261.4,244.66Z" style="fill: rgb(224, 224, 224); transform-origin: 267.605px 240.774px;" id="elbnv5r53wvr" class="animable"></path>
									<path d="M261.59,247.43a.32.32,0,0,1-.28-.15.27.27,0,0,1,.09-.4l12.44-7.18a.34.34,0,0,1,.44.09.28.28,0,0,1-.09.41l-12.44,7.18A.43.43,0,0,1,261.59,247.43Z" style="fill: rgb(224, 224, 224); transform-origin: 267.795px 243.543px;" id="elg7rpwg4li6" class="animable"></path>
									<path d="M261.78,250.19a.34.34,0,0,1-.28-.14.28.28,0,0,1,.09-.41L274,242.46a.35.35,0,0,1,.44.1.27.27,0,0,1-.09.4l-12.44,7.19A.42.42,0,0,1,261.78,250.19Z" style="fill: rgb(224, 224, 224); transform-origin: 267.97px 246.305px;" id="el9y5be826s0m" class="animable"></path>
									<polygon points="276.13 232.98 293.55 222.92 294.3 233.98 276.88 244.04 276.13 232.98" style="fill: rgb(31, 111, 235); transform-origin: 285.215px 233.48px;" id="elpsqt9w06w3i" class="animable"></polygon>
									<g id="elbp12rb619ma">
										<polygon points="276.13 232.98 293.55 222.92 294.3 233.98 276.88 244.04 276.13 232.98" style="fill: rgb(255, 255, 255); opacity: 0.5; transform-origin: 285.215px 233.48px;" class="animable"></polygon>
									</g>
									<polygon points="278.04 241.94 292.99 233.33 289.42 231.19 286.27 235 282.32 231.89 278.04 241.94" style="fill: rgb(255, 255, 255); transform-origin: 285.515px 236.565px;" id="elf2nlq6hui0e" class="animable"></polygon>
									<path d="M286.76,229.19c.4-.23.74-.06.77.38a1.43,1.43,0,0,1-.66,1.22.48.48,0,0,1-.78-.39A1.42,1.42,0,0,1,286.76,229.19Z" style="fill: rgb(255, 255, 255); transform-origin: 286.81px 229.991px;" id="el9hppl8atisv" class="animable"></path>
									<polygon points="262.14 255.42 294.49 236.75 294.68 239.51 262.33 258.19 262.14 255.42" style="fill: rgb(31, 111, 235); transform-origin: 278.41px 247.47px;" id="eljpbrrs7oxc8" class="animable"></polygon>
									<path d="M262,253a.29.29,0,0,1-.28-.14.27.27,0,0,1,.08-.4l12.45-7.18a.34.34,0,0,1,.44.09.28.28,0,0,1-.09.41l-12.44,7.18A.32.32,0,0,1,262,253Z" style="fill: rgb(224, 224, 224); transform-origin: 268.204px 249.118px;" id="elmcqj657llgc" class="animable"></path>
									<path d="M262.54,261.25a.36.36,0,0,1-.29-.14.28.28,0,0,1,.09-.41l32.4-18.65a.33.33,0,0,1,.44.1.27.27,0,0,1-.09.4l-32.4,18.65A.3.3,0,0,1,262.54,261.25Z" style="fill: rgb(224, 224, 224); transform-origin: 278.715px 251.627px;" id="eli9vkr3dbli8" class="animable"></path>
									<path d="M247.81,236.82a1.39,1.39,0,0,1,.65-1.18L301.7,204.9a.46.46,0,0,1,.75.38l.19,2.86L248,239.69Z" style="fill: rgb(55, 71, 79); transform-origin: 275.225px 222.243px;" id="el6rx0dku16t7" class="animable"></path>
									<path d="M279.53,263.82a2.39,2.39,0,0,0-1.11,2c.05.73.63,1,1.29.64a2.38,2.38,0,0,0,1.11-2C280.77,263.72,280.2,263.44,279.53,263.82Z" style="fill: rgb(31, 111, 235); transform-origin: 279.62px 265.135px;" id="el6vmcgdwzrwi" class="animable"></path>
								</g>
							</g>
							<g id="freepik--Mouse--inject-127" class="animable" style="transform-origin: 343.668px 264.876px;">
								<g id="el0vrgflt5mmnf">
									<path d="M354.72,268.34l-5.92,3.42a6.54,6.54,0,0,1-6.54-.35l-9.77-7.13c-1.74-1.27-1.64-3.17.23-4.25l3-1.72a8.06,8.06,0,0,1,6.87-.21l12.06,6.54C356.53,265.6,356.58,267.26,354.72,268.34Z" style="opacity: 0.2; transform-origin: 343.668px 264.952px;" class="animable"></path>
								</g>
								<path d="M332.33,261.18l6.76,5.28,5,3.91a4.42,4.42,0,0,0,1.59.38,4.32,4.32,0,0,0,2.36-.5l5.3-3.06h0l0,0a3.74,3.74,0,0,0,1.07-5.36l-.16-.21a7,7,0,0,0-4.26-2.7l-2.68-.54c-4.35-.88-7.91-1.31-8.33-1l-6.64,3.85Z" style="fill: rgb(55, 71, 79); transform-origin: 343.721px 264.021px;" id="el6nkp7p7q8ts" class="animable"></path>
								<path d="M336.11,264.14l-.38-.3,6-3.44-5.21-1.62.52-.3,5.22,1.61,3.55-2,.67.13Z" style="fill: rgb(31, 111, 235); transform-origin: 341.105px 261.115px;" id="eltzol00ax0r" class="animable"></path>
								<path d="M343.49,270.05l0,0h0l-4.74-3.43L332.3,262v-.72a.07.07,0,0,1,0-.06h0c.48-.17,3.26.67,6.61,2l2.23.88a7.13,7.13,0,0,1,4.46,6.58v.12a4.42,4.42,0,0,1-1.59-.38Z" style="fill: rgb(38, 50, 56); transform-origin: 338.947px 265.999px;" id="el3aey2qxxoif" class="animable"></path>
							</g>
							<g id="freepik--Files--inject-127" class="animable" style="transform-origin: 212.207px 315.961px;">
								<g id="elkghdlp9cwpq">
									<path d="M174.47,326.16l33.26,19a.83.83,0,0,0,.38.1.7.7,0,0,0,.39-.09l41.28-23.83a.25.25,0,0,0,0-.47l-33.29-19Z" style="opacity: 0.2; transform-origin: 212.207px 323.566px;" class="animable"></path>
								</g>
								<path d="M247.32,320.82a.78.78,0,0,1-.37.57l-38.54,22.24a.76.76,0,0,1-.69,0l-30.07-17.18,0-.73,39.23-22.65L247,320.24A.74.74,0,0,1,247.32,320.82Z" style="fill: rgb(31, 111, 235); transform-origin: 212.485px 323.391px;" id="el8ce0197k5xt" class="animable"></path>
								<g id="ellzyi9qsypd8">
									<path d="M208.1,343l0,.72a.67.67,0,0,0,.35-.07L247,321.39a.81.81,0,0,0,.37-.57.73.73,0,0,0-.31-.58c.18.11.17.3,0,.42L208.45,342.9A.67.67,0,0,1,208.1,343Z" style="opacity: 0.1; transform-origin: 227.735px 331.981px;" class="animable"></path>
								</g>
								<g id="elf2ch4f55e1p">
									<path d="M177.69,325.71l30.07,17.18a.64.64,0,0,0,.34.09.67.67,0,0,0,.35-.08L247,320.66c.19-.12.2-.31,0-.42l-30.09-17.18Z" style="opacity: 0.5; transform-origin: 212.418px 323.02px;" class="animable"></path>
								</g>
								<g id="el0fy6pdm8usl9">
									<path d="M177.69,325.71l0,.73,30.07,17.17a.58.58,0,0,0,.34.09l0-.72a.64.64,0,0,1-.34-.09Z" style="opacity: 0.2; transform-origin: 192.895px 334.705px;" class="animable"></path>
								</g>
								<polygon points="178.14 326.01 217.39 303.37 217.88 293.76 178.63 316.52 178.14 326.01" style="fill: rgb(31, 111, 235); transform-origin: 198.01px 309.885px;" id="elzsl2a1mz2i" class="animable"></polygon>
								<g id="elktlg6rsmn5n">
									<polygon points="178.14 326.01 217.39 303.37 217.88 293.76 178.63 316.52 178.14 326.01" style="opacity: 0.7; transform-origin: 198.01px 309.885px;" class="animable"></polygon>
								</g>
								<path d="M247.16,315.65a1,1,0,0,1,.39.79l-.1,1.95a1.09,1.09,0,0,1-.47.78L208.62,341.3a.88.88,0,0,1-.86,0l-25.44-15.54.15-3,39.23-22.65Z" style="fill: rgb(235, 235, 235); transform-origin: 214.935px 320.761px;" id="eltwga120ogqf" class="animable"></path>
								<path d="M221.7,300.11l25.46,15.54c.23.15.22.38,0,.52l-38.36,22.14a.9.9,0,0,1-.86,0l-25.44-15.53Z" style="fill: rgb(250, 250, 250); transform-origin: 214.914px 319.265px;" id="el4tn91gtzkj5" class="animable"></path>
								<path d="M185.8,323.06a1.06,1.06,0,0,1-.3.1.83.83,0,0,1-.47,0,.35.35,0,0,1-.14-.06c-.23-.14-.19-.39.09-.55a1.14,1.14,0,0,1,.42-.12.87.87,0,0,1,.49.1l0,0C186.11,322.7,186.06,322.91,185.8,323.06Z" style="fill: rgb(38, 50, 56); transform-origin: 185.385px 322.81px;" id="elhfgof5mlext" class="animable"></path>
								<path d="M208.33,338.4l-.15,3a.79.79,0,0,0,.44-.09L247,319.17a1.06,1.06,0,0,0,.47-.78l.1-1.95a1,1,0,0,0-.29-.71c.12.14.08.32-.13.44L208.77,338.3A.8.8,0,0,1,208.33,338.4Z" style="fill: rgb(230, 230, 230); transform-origin: 227.875px 328.566px;" id="el2m6vxvb75xo" class="animable"></path>
								<path d="M181.42,318.82a1.63,1.63,0,0,1,1.77.14,6.24,6.24,0,0,1,2.23,3.86l-.59.09a5.66,5.66,0,0,0-2-3.42,1.39,1.39,0,0,0-.73-.25.77.77,0,0,0-.45.12,2.49,2.49,0,0,0-.81,2.11c0,2,.91,4.26,2.13,5.06a1.33,1.33,0,0,0,.88.25l.62.4,0,0a1.38,1.38,0,0,1-.76.2,1.88,1.88,0,0,1-1-.34c-1.4-.91-2.46-3.36-2.4-5.58A3,3,0,0,1,181.42,318.82Z" style="fill: rgb(38, 50, 56); transform-origin: 182.858px 323.002px;" id="elic6gl4da2x" class="animable"></path>
								<path d="M247.63,314.72a.78.78,0,0,1-.37.57l-38.54,22.24a.73.73,0,0,1-.69,0l-29.89-20.64,0-.73,39.24-22.65,29.91,20.64A.74.74,0,0,1,247.63,314.72Z" style="fill: rgb(31, 111, 235); transform-origin: 212.885px 315.563px;" id="el7cdyzq2g2ga" class="animable"></path>
								<g id="elx9fczep2z7i">
									<path d="M208.41,336.88l0,.73a.78.78,0,0,0,.35-.08l38.54-22.23a.85.85,0,0,0,.37-.58.71.71,0,0,0-.31-.58.23.23,0,0,1,0,.42L208.76,336.8A.67.67,0,0,1,208.41,336.88Z" style="opacity: 0.1; transform-origin: 228.04px 325.875px;" class="animable"></path>
								</g>
								<path d="M178.17,316.15l29.9,20.64a.64.64,0,0,0,.34.09.67.67,0,0,0,.35-.08l38.54-22.24a.23.23,0,0,0,0-.42L217.4,293.5Z" style="fill: rgb(31, 111, 235); transform-origin: 212.803px 315.19px;" id="el3heqnxrvybz" class="animable"></path>
								<g id="elo070oxikxv">
									<path d="M178.17,316.15l0,.73L208,337.52a.78.78,0,0,0,.34.09l0-.73a.64.64,0,0,1-.34-.09Z" style="opacity: 0.2; transform-origin: 193.255px 326.88px;" class="animable"></path>
								</g>
								<polygon points="177.69 325.71 178.17 316.17 178.63 316.52 178.14 326.01 177.69 325.71" style="fill: rgb(31, 111, 235); transform-origin: 178.16px 321.09px;" id="elyjh3a101bed" class="animable"></polygon>
								<g id="elrjsnnpx8weg">
									<polygon points="177.69 325.71 178.17 316.17 178.63 316.52 178.14 326.01 177.69 325.71" style="opacity: 0.2; transform-origin: 178.16px 321.09px;" class="animable"></polygon>
								</g>
								<path d="M179.3,316a.61.61,0,0,1-.31-.49l0-.75L217,293.35a1.64,1.64,0,0,1,1.58,0l29.54,19.25-39.25,22.64Z" style="fill: rgb(31, 111, 235); transform-origin: 213.555px 314.194px;" id="eldntgru63g4" class="animable"></path>
								<g id="el8z1vxr6yjgr">
									<path d="M179.3,316a.61.61,0,0,1-.31-.49l0-.75L217,293.35a1.64,1.64,0,0,1,1.58,0l29.54,19.25-39.25,22.64Z" style="opacity: 0.2; transform-origin: 213.555px 314.194px;" class="animable"></path>
								</g>
								<path d="M179.33,315.3a.52.52,0,0,1,0-1L217,292.62a1.64,1.64,0,0,1,1.58,0L248.1,311.9l-39.24,22.65Z" style="fill: rgb(31, 111, 235); transform-origin: 213.526px 313.484px;" id="elkd1zadt9i5" class="animable"></path>
								<g id="el8h9vjk29m6w">
									<path d="M179.33,315.3a.52.52,0,0,1,0-1L217,292.62a1.64,1.64,0,0,1,1.58,0L248.1,311.9l-39.24,22.65Z" style="opacity: 0.5; transform-origin: 213.526px 313.484px;" class="animable"></path>
								</g>
								<path d="M241.34,306.89l-21.87-14.25a1.67,1.67,0,0,0-1.58,0L183,312.77l-2.84-1.87-.19,3.7.05,0a.5.5,0,0,0,.24.66l25.05,16.32L244.52,309l.21-4Z" style="fill: rgb(235, 235, 235); transform-origin: 212.35px 312.011px;" id="elkqlfyx6nps" class="animable"></path>
								<path d="M180.44,311.26a.52.52,0,0,1,.05-1l37.6-21.71a1.64,1.64,0,0,1,1.58,0l25.06,16.32-39.25,22.64Z" style="fill: rgb(250, 250, 250); transform-origin: 212.408px 307.929px;" id="el2nu013ql28u" class="animable"></path>
								<polygon points="205.48 327.57 205.28 331.61 244.52 308.96 244.73 304.93 205.48 327.57" style="fill: rgb(230, 230, 230); transform-origin: 225.005px 318.27px;" id="elzqouowc5q8" class="animable"></polygon>
								<path d="M206,326a1.32,1.32,0,0,1-.3.1.89.89,0,0,1-.46,0,.39.39,0,0,1-.15-.06c-.23-.14-.19-.39.1-.55a1,1,0,0,1,.41-.12.76.76,0,0,1,.49.09s0,0,0,0C206.29,325.64,206.24,325.85,206,326Z" style="fill: rgb(38, 50, 56); transform-origin: 205.578px 325.746px;" id="elwfmkk7v0mhr" class="animable"></path>
								<path d="M207.83,325.19a2,2,0,0,0-1.09-.37,1.43,1.43,0,0,0-1.16.54,1.92,1.92,0,0,0-.36.73.89.89,0,0,0,.46,0,1.28,1.28,0,0,1,.43-.62,1.25,1.25,0,0,1,1.47.11,4.52,4.52,0,0,1,1.77,3.63c-.07,1.31-1,1.84-2,1.18l-.46.26a1.37,1.37,0,0,0,.2.13,2,2,0,0,0,1.07.37h0a1.43,1.43,0,0,0,1.16-.55,2.33,2.33,0,0,0,.47-1.39A4.89,4.89,0,0,0,207.83,325.19Z" style="fill: rgb(38, 50, 56); transform-origin: 207.506px 327.985px;" id="elllsrtyi4cab" class="animable"></path>
								<path d="M208.86,325.4l-29.54-16.3,0,.52a.9.9,0,0,0,.35.74l29.18,15.77Z" style="fill: rgb(31, 111, 235); transform-origin: 194.09px 317.615px;" id="elqe0c0bu54sa" class="animable"></path>
								<g id="elru6aw03cw4r">
									<path d="M208.86,325.4l-29.54-16.3,0,.52a.9.9,0,0,0,.35.74l29.18,15.77Z" style="opacity: 0.2; transform-origin: 194.09px 317.615px;" class="animable"></path>
								</g>
								<path d="M179.62,309.58c-.42-.27-.4-.7,0-1l37.61-21.72a1.67,1.67,0,0,1,1.58,0l29.7,16.1-39.25,22.65Z" style="fill: rgb(31, 111, 235); transform-origin: 213.911px 306.136px;" id="elm4skvgdvg" class="animable"></path>
								<polygon points="208.82 335.27 248.07 312.63 248.56 303.02 209.31 325.68 208.82 335.27" style="fill: rgb(31, 111, 235); transform-origin: 228.69px 319.145px;" id="elsh7a2xe7ber" class="animable"></polygon>
								<g id="eldmy49wszcbm">
									<polygon points="208.82 335.27 248.07 312.63 248.56 303.02 209.31 325.68 208.82 335.27" style="opacity: 0.1; transform-origin: 228.69px 319.145px;" class="animable"></polygon>
								</g>
								<path d="M216.07,329.24a1.32,1.32,0,0,1-1.1-.6,2.18,2.18,0,0,1-.33-1.4,5.3,5.3,0,0,1,2.39-4,2.05,2.05,0,0,1,1.11-.33c.92,0,1.5.81,1.44,2a5.34,5.34,0,0,1-2.39,4,2.27,2.27,0,0,1-1.11.33Z" style="fill: rgb(69, 90, 100); transform-origin: 217.106px 326.075px;" id="elhb60gq062tb" class="animable"></path>
								<path d="M215.09,327.25c-.06,1.32.78,1.88,1.9,1.23a4.85,4.85,0,0,0,2.14-3.56c.06-1.32-.78-1.87-1.9-1.23A4.86,4.86,0,0,0,215.09,327.25Z" style="fill: rgb(245, 245, 245); transform-origin: 217.11px 326.087px;" id="elu8m5mbs7gsa" class="animable"></path>
								<path d="M222.43,322.56l0,.72c-.07,1.46.86,2.06,2.08,1.36l18.69-10.79a5.36,5.36,0,0,0,2.35-3.91l0-.73c.07-1.45-.86-2.06-2.08-1.36l-18.69,10.8A5.32,5.32,0,0,0,222.43,322.56Z" style="fill: rgb(245, 245, 245); transform-origin: 233.99px 316.246px;" id="elqrhbyncjgsr" class="animable"></path>
								<polygon points="208.36 334.98 208.85 325.43 209.31 325.68 208.82 335.27 208.36 334.98" style="fill: rgb(31, 111, 235); transform-origin: 208.835px 330.35px;" id="el8wolthwqce" class="animable"></polygon>
								<g id="eljmr133hwij">
									<polygon points="208.36 334.98 208.85 325.43 209.31 325.68 208.82 335.27 208.36 334.98" style="opacity: 0.2; transform-origin: 208.835px 330.35px;" class="animable"></polygon>
								</g>
							</g>
							<g id="freepik--monitor--inject-127" class="animable" style="transform-origin: 180.773px 260.003px;">
								<g id="freepik--shadow--inject-127">
									<path d="M151,319l-9.32-5.39c-1.4-.81-1.4-2.12,0-2.93l66.91-38.63a5.61,5.61,0,0,1,5.08,0l9.32,5.38c1.4.81,1.4,2.12,0,2.93L156.06,319A5.68,5.68,0,0,1,151,319Z" style="opacity: 0.2; transform-origin: 182.335px 295.518px;" class="animable"></path>
								</g>
								<g id="freepik--monitor--inject-127" class="animable" style="transform-origin: 176.405px 252.063px;">
									<path d="M138.52,303.25a2.47,2.47,0,0,0,2.39,0l69.35-40a3.13,3.13,0,0,0,1.38-2.27L215.3,203a2.79,2.79,0,0,0-1-2.28,2.47,2.47,0,0,0-2.39,0l-69.35,40a3.15,3.15,0,0,0-1.38,2.27L137.51,301A2.79,2.79,0,0,0,138.52,303.25Z" style="fill: rgb(55, 71, 79); transform-origin: 176.406px 251.985px;" id="elv4v9orm598" class="animable"></path>
									<path d="M140.91,303.25l69.35-40a3.15,3.15,0,0,0,1.38-2.27L215.3,203c0-.85-.48-1.2-1.18-.8l-69.36,40.05a3.12,3.12,0,0,0-1.37,2.26l-3.66,57.93C139.67,303.31,140.2,303.66,140.91,303.25Z" style="fill: rgb(69, 90, 100); transform-origin: 177.513px 252.728px;" id="elyolcfg0fwn" class="animable"></path>
									<path d="M141.62,241.75l2.21,1.48a3,3,0,0,0-.44,1.3l-3.23,51-.45,7.08c-.05.78.39,1.14,1,.89a2.45,2.45,0,0,1-2.2-.1,2.79,2.79,0,0,1-1-2.28l.45-7.08,3.22-51A3,3,0,0,1,141.62,241.75Z" style="fill: rgb(38, 50, 56); transform-origin: 140.668px 272.732px;" id="elgcb50uize6" class="animable"></path>
								</g>
								<polygon points="165.85 294.92 172.3 291.2 173.61 299.87 165.85 294.92" style="fill: rgb(38, 50, 56); transform-origin: 169.73px 295.535px;" id="elvegwb90glmm" class="animable"></polygon>
								<path d="M196.11,292l-18.9,10.91a2.33,2.33,0,0,1-1.21.28,2.65,2.65,0,0,1-1-.26l0,0-8.33-4.8s0,0,0,0a2.42,2.42,0,0,1-1.09-1.93v-.44c0-.71.5-1,1.12-.65l5,2.89,1.12.65-.25-1.27L170.75,273l15.52-9,2.65,1.53L197,290A2,2,0,0,1,196.11,292Z" style="fill: rgb(69, 90, 100); transform-origin: 181.303px 283.596px;" id="eljq8oerjuio" class="animable"></path>
								<g id="elt5col8tjia">
									<g style="opacity: 0.1; transform-origin: 179.83px 269.23px;" class="animable">
										<polygon points="173.39 274.5 188.91 265.48 186.27 263.96 170.75 272.98 173.39 274.5" style="fill: rgb(255, 255, 255); transform-origin: 179.83px 269.23px;" id="el6cx1whbmdji" class="animable"></polygon>
									</g>
								</g>
								<path d="M173.39,274.51,170.75,273l1.77,24.3c.15.7-.26,1-.87.62l-5-2.89c-.62-.36-1.12-.06-1.12.65v.44a2.42,2.42,0,0,0,1.09,1.93s0,0,0,0l8.33,4.8,0,0a2.65,2.65,0,0,0,1,.26Z" style="fill: rgb(55, 71, 79); transform-origin: 170.74px 288.055px;" id="elslp7z37lyv" class="animable"></path>
							</g>
							<g id="freepik--Cup--inject-127" class="animable" style="transform-origin: 96.192px 280.568px;">
								<g id="el2eeo3iade9w">
									<g style="opacity: 0.1; transform-origin: 98.945px 291.038px;" class="animable">
										<path d="M92.21,287.48c-3.75,1.89-3.78,5-.06,7a16.53,16.53,0,0,0,13.53.12c3.75-1.9,3.78-5,.06-7A16.49,16.49,0,0,0,92.21,287.48Z" id="elo09s0nyqqp" class="animable" style="transform-origin: 98.945px 291.038px;"></path>
									</g>
								</g>
								<path d="M89,288.74a5.36,5.36,0,0,1-3-.33c-1.22-.53-2-2-2.81-4.33-.73-2-1.91-7.64-1.08-9.05,1.29-2.19,4-1.86,6.25-1.8l.77,0,0,2.75-.83,0c-1.26,0-3.37-.09-3.71.61-.54,1.36,1,8.61,2.14,9.39a5.61,5.61,0,0,0,3.27,0l1,2.39A8.64,8.64,0,0,1,89,288.74Z" style="fill: rgb(250, 250, 250); transform-origin: 86.4128px 281px;" id="eliijw5uqy6" class="animable"></path>
								<path d="M87.65,269.53c.27-1.09,1.37-2.14,3.3-3a23.11,23.11,0,0,1,16.12.14c1.92.86,3,1.93,3.25,3h.06c.69,7.2-.67,15.53-3.27,20.12a.41.41,0,0,0,0,.09l0,.07h0a5.37,5.37,0,0,1-2.12,2,13.5,13.5,0,0,1-12.22-.11,5.52,5.52,0,0,1-2.08-2h0l0-.06-.06-.11c-2.52-4.63-3.72-13-2.9-20.17Z" style="fill: rgb(240, 240, 240); transform-origin: 99.0162px 279.253px;" id="elwn2hdrq1by" class="animable"></path>
								<path d="M91,266.57c-4.46,1.89-4.49,5-.06,7a23.11,23.11,0,0,0,16.12.14c4.46-1.89,4.49-5,.06-7A23.11,23.11,0,0,0,91,266.57Z" style="fill: rgb(250, 250, 250); transform-origin: 99.03px 270.14px;" id="elsdase0l1w28" class="animable"></path>
								<path d="M92.5,267.8c-3.6,1.23-3.62,3.27,0,4.57a22.83,22.83,0,0,0,13,.12c3.6-1.23,3.62-3.28,0-4.58A22.74,22.74,0,0,0,92.5,267.8Z" style="fill: rgb(224, 224, 224); transform-origin: 99px 270.141px;" id="elhwwljtreo25" class="animable"></path>
								<path d="M92.47,270.56a6.63,6.63,0,0,0-1.81.89,7,7,0,0,0,1.8.92,22.83,22.83,0,0,0,13,.12,7.08,7.08,0,0,0,1.81-.89,6.74,6.74,0,0,0-1.79-.92A22.78,22.78,0,0,0,92.47,270.56Z" style="fill: rgb(55, 71, 79); transform-origin: 98.965px 271.523px;" id="elea8s907ffsr" class="animable"></path>
							</g>
							<g id="freepik--coffee--inject-127" class="animable" style="transform-origin: 341.501px 218.948px;">
								<g id="freepik--shadow--inject-127">
									<path d="M333.28,228.51c-4.67,2.59-4.67,6.78,0,9.37s12.24,2.58,16.91,0,4.67-6.78,0-9.37S338,225.93,333.28,228.51Z" style="opacity: 0.1; transform-origin: 341.735px 233.195px;" class="animable"></path>
								</g>
								<g id="freepik--coffee--inject-127" class="animable" style="transform-origin: 341.44px 217.68px;">
									<path d="M349,213.21a24.52,24.52,0,0,0-14.88.39c-4,1.64-3.94,4.14.14,5.57a24.61,24.61,0,0,0,14.89-.38C353.28,217.14,353.21,214.64,349,213.21Z" style="fill: rgb(31, 111, 235); transform-origin: 341.681px 216.191px;" id="el2njp2j4koln" class="animable"></path>
									<g id="elsbz09rjqch">
										<g style="opacity: 0.3; transform-origin: 341.681px 216.191px;" class="animable">
											<path d="M349,213.21a24.52,24.52,0,0,0-14.88.39c-4,1.64-3.94,4.14.14,5.57a24.61,24.61,0,0,0,14.89-.38C353.28,217.14,353.21,214.64,349,213.21Z" id="ele4hc7mnz62l" class="animable" style="transform-origin: 341.681px 216.191px;"></path>
										</g>
									</g>
									<path d="M352.19,208.06l-21.44.58L333,233.86h0c.12.92,1,1.8,2.66,2.46a19,19,0,0,0,12.86-.33c1.68-.75,2.55-1.68,2.63-2.6h0Z" style="fill: rgb(250, 250, 250); transform-origin: 341.47px 222.671px;" id="elypdqq82jfhd" class="animable"></path>
									<path d="M330.75,208.64l.46,5.12a9.62,9.62,0,0,0,1.82.92c4.68,1.8,12.33,1.6,17.08-.45.2-.08.37-.18.56-.27a11.13,11.13,0,0,0,1.32-.79l.2-5.11Z" style="fill: rgb(235, 235, 235); transform-origin: 341.47px 211.985px;" id="elya5pa3j3rw9" class="animable"></path>
									<path d="M353.57,208.77l-.1-3.86L351,205a11.56,11.56,0,0,0-1.11-.48c-4.85-1.8-12.5-1.6-17.08.44-.38.17-.72.35-1,.53l-2.43.07.1,3.86h0c0,1.26,1.23,2.49,3.57,3.39,4.69,1.8,12.33,1.6,17.08-.45C352.43,211.32,353.6,210,353.57,208.77Z" style="fill: rgb(31, 111, 235); transform-origin: 341.475px 208.665px;" id="ellvq6kf62x1" class="animable"></path>
									<g id="el4bvxk2147qw">
										<g style="opacity: 0.2; transform-origin: 341.475px 208.665px;" class="animable">
											<path d="M353.57,208.77l-.1-3.86L351,205a11.56,11.56,0,0,0-1.11-.48c-4.85-1.8-12.5-1.6-17.08.44-.38.17-.72.35-1,.53l-2.43.07.1,3.86h0c0,1.26,1.23,2.49,3.57,3.39,4.69,1.8,12.33,1.6,17.08-.45C352.43,211.32,353.6,210,353.57,208.77Z" id="elizz86xnwo2b" class="animable" style="transform-origin: 341.475px 208.665px;"></path>
										</g>
									</g>
									<path d="M349.78,201.53c-4.85-1.8-12.49-1.6-17.08.44s-4.51,5.15.18,6.95,12.33,1.61,17.08-.44S354.62,203.32,349.78,201.53Z" style="fill: rgb(31, 111, 235); transform-origin: 341.388px 205.227px;" id="elewdkmi697ic" class="animable"></path>
									<path d="M351.22,204h0l-.85-3.16-6.14.15a25.39,25.39,0,0,0-6.1.16l-5.95.16-.69,3.2h0c-.36,1.17.59,2.36,2.85,3.19a22.34,22.34,0,0,0,14.08-.37C350.73,206.42,351.65,205.19,351.22,204Z" style="fill: rgb(31, 111, 235); transform-origin: 341.369px 204.751px;" id="elyx6lh7r2rx8" class="animable"></path>
									<g id="elcbftjjl7zy">
										<path d="M351.22,204h0l-.85-3.16-6.14.15a25.39,25.39,0,0,0-6.1.16l-5.95.16-.69,3.2h0c-.36,1.17.59,2.36,2.85,3.19a22.34,22.34,0,0,0,14.08-.37C350.73,206.42,351.65,205.19,351.22,204Z" style="opacity: 0.2; transform-origin: 341.369px 204.751px;" class="animable"></path>
									</g>
									<path d="M347.65,198.91a21.59,21.59,0,0,0-12.93.33c-3.48,1.42-3.42,3.56.12,4.79a21.56,21.56,0,0,0,12.93-.34C351.37,202.28,351.32,200.14,347.65,198.91Z" style="fill: rgb(31, 111, 235); transform-origin: 341.292px 201.468px;" id="elxq8fj3seuvj" class="animable"></path>
									<path d="M349.18,201.27a1.14,1.14,0,0,1-.39.46,6.13,6.13,0,0,1-1.47.79,18.92,18.92,0,0,1-6.06,1.08,18.19,18.19,0,0,1-6-.76,5.38,5.38,0,0,1-1.46-.72c-.3-.23-.38-.41-.38-.45s.29-.66,1.78-1.26a17.94,17.94,0,0,1,6-1.07,18.68,18.68,0,0,1,6.1.76C348.78,200.61,349.17,201.15,349.18,201.27Z" style="fill: rgb(31, 111, 235); transform-origin: 341.3px 201.47px;" id="elhpl8qkghssf" class="animable"></path>
									<g id="elrcznunyzwmh">
										<g style="opacity: 0.5; transform-origin: 341.3px 201.47px;" class="animable">
											<path d="M349.18,201.27a1.14,1.14,0,0,1-.39.46,6.13,6.13,0,0,1-1.47.79,18.92,18.92,0,0,1-6.06,1.08,18.19,18.19,0,0,1-6-.76,5.38,5.38,0,0,1-1.46-.72c-.3-.23-.38-.41-.38-.45s.29-.66,1.78-1.26a17.94,17.94,0,0,1,6-1.07,18.68,18.68,0,0,1,6.1.76C348.78,200.61,349.17,201.15,349.18,201.27Z" id="el87y9az3m5sa" class="animable" style="transform-origin: 341.3px 201.47px;"></path>
										</g>
									</g>
									<path d="M348.79,201.73a6.13,6.13,0,0,1-1.47.79,18.92,18.92,0,0,1-6.06,1.08,18.19,18.19,0,0,1-6-.76,5.38,5.38,0,0,1-1.46-.72,5.36,5.36,0,0,1,1.42-.8,18,18,0,0,1,6-1.07,19.05,19.05,0,0,1,6.11.76A5.74,5.74,0,0,1,348.79,201.73Z" style="fill: rgb(31, 111, 235); transform-origin: 341.295px 201.926px;" id="el18hn7xap39g" class="animable"></path>
									<g id="elpapib5a77qq">
										<g style="opacity: 0.2; transform-origin: 341.295px 201.926px;" class="animable">
											<path d="M348.79,201.73a6.13,6.13,0,0,1-1.47.79,18.92,18.92,0,0,1-6.06,1.08,18.19,18.19,0,0,1-6-.76,5.38,5.38,0,0,1-1.46-.72,5.36,5.36,0,0,1,1.42-.8,18,18,0,0,1,6-1.07,19.05,19.05,0,0,1,6.11.76A5.74,5.74,0,0,1,348.79,201.73Z" id="elpgaxssg5g5s" class="animable" style="transform-origin: 341.295px 201.926px;"></path>
										</g>
									</g>
									<g id="el2v5z0kf7at3">
										<g style="opacity: 0.5; transform-origin: 345.635px 202.165px;" class="animable">
											<path d="M345.15,201.91a1.84,1.84,0,0,1,1.43-.32c.27.15.06.52-.46.84a1.87,1.87,0,0,1-1.43.31C344.42,202.59,344.63,202.22,345.15,201.91Z" id="el6ik9ku7znqm" class="animable" style="transform-origin: 345.635px 202.165px;"></path>
										</g>
									</g>
									<path d="M332,225.72c0,.95,1,1.87,2.92,2.55a23.17,23.17,0,0,0,14-.36c1.95-.78,2.91-1.75,2.89-2.7l.37-9.29c0,1-1,2-3.06,2.87a24.61,24.61,0,0,1-14.89.38c-2-.72-3.08-1.7-3.1-2.71Z" style="fill: rgb(31, 111, 235); transform-origin: 341.655px 222.55px;" id="elhlnor7g4az4" class="animable"></path>
									<path d="M338.56,226.1a13.28,13.28,0,0,1-.88-1.56,4.69,4.69,0,0,0-2.48-2.07.31.31,0,0,0-.38.15,3.64,3.64,0,0,0,4.65,4.46c.06,0,.07-.15,0-.18A2.92,2.92,0,0,1,338.56,226.1Z" style="fill: rgb(55, 71, 79); transform-origin: 337.098px 224.86px;" id="elplroxmv4gmn" class="animable"></path>
									<path d="M335.77,222.16a5.38,5.38,0,0,1,1.56.9,4.74,4.74,0,0,1,1.14,1.49,4.49,4.49,0,0,0,1.44,1.81.3.3,0,0,0,.42-.08,3.21,3.21,0,0,0-.85-3.89,3.3,3.3,0,0,0-3.76-.76A.3.3,0,0,0,335.77,222.16Z" style="fill: rgb(55, 71, 79); transform-origin: 338.119px 223.878px;" id="elvbvxvr1hotf" class="animable"></path>
								</g>
							</g>
						</g>
					</g>
					<g id="freepik--Plants--inject-127" class="animable" style="transform-origin: 80.0818px 401.686px;">
						<g id="freepik--Plant--inject-127" class="animable" style="transform-origin: 80.0818px 401.686px;">
							<g id="freepik--Pot--inject-127" class="animable" style="transform-origin: 82.2977px 430.937px;">
								<g id="freepik--pot--inject-127" class="animable" style="transform-origin: 82.2977px 430.937px;">
									<path d="M65.44,451.71c-9-9.31-14.31-32.69-5.52-40.07h44.75c8.79,7.37,3.5,30.75-5.51,40.06l-.25.25c-.1.11-.2.21-.31.31-.25.25-.52.49-.81.73l-.22.18c-.22.17-.44.35-.68.52a13.42,13.42,0,0,1-1.16.75c-7.42,4.33-19.44,4.33-26.86,0h0a11.76,11.76,0,0,1-1.18-.76l-.63-.48-.27-.22c-.28-.23-.54-.46-.78-.7l-.36-.36Z" style="fill: rgb(55, 71, 79); transform-origin: 82.2977px 434.664px;" id="el7av0wjcwtmp" class="animable"></path>
									<path d="M100.78,408.66c10.21,6,10.21,15.63,0,21.59s-26.76,6-37,0-10.21-15.62,0-21.59S90.57,402.7,100.78,408.66Z" style="fill: rgb(69, 90, 100); transform-origin: 82.2744px 419.461px;" id="elg17q9721s05" class="animable"></path>
									<path d="M95.87,411.53c7.5,4.38,7.5,11.48,0,15.85s-19.65,4.38-27.15,0-7.5-11.47,0-15.85S88.38,407.15,95.87,411.53Z" style="fill: rgb(38, 50, 56); transform-origin: 82.295px 419.453px;" id="elb80h62mhwkk" class="animable"></path>
									<path d="M68.72,419.05c7.5-4.37,19.66-4.37,27.15,0a12.43,12.43,0,0,1,4.49,4.17,12.33,12.33,0,0,1-4.49,4.16c-7.49,4.38-19.65,4.38-27.15,0a12.49,12.49,0,0,1-4.49-4.16A12.6,12.6,0,0,1,68.72,419.05Z" style="fill: rgb(250, 250, 250); transform-origin: 82.295px 423.219px;" id="elr8khq16v8k" class="animable"></path>
								</g>
							</g>
							<g id="freepik--Leaves--inject-127" class="animable" style="transform-origin: 80.0818px 386.777px;">
								<path d="M70.68,359.09c2.29,3.78-.42,10.49-6.59,11.93,1.83-2.81,3.36-7.15,1.78-7.68s.38,3.38-2.94,7.21c0,0-1.83,1-4.5-1,3.76-2.85,5.27-8.53,3.93-7.69-.53.33-1.38,3.16-3.62,5s-4.55,2-8.1-.75c2.06-.41,5-1.33,6.66-3.5,1.17-1.56,1.51-2.54-1.26-.69s-3.65,2.62-6.34,2.61-5.11-2.73-5.11-2.73S49,362,50,360.58s-3.61.8-6.7-.34a17.92,17.92,0,0,1-5.19-3s1.33-3.55,2.33-4.44c2.35,1.65,5.29,4,5.72,3.44s-.57-2.33-4-3.89a3,3,0,0,1,2.67-2.2s1.75.19,4.49,4.66c.58.94,1.82,1.75-.43-1.85A12.12,12.12,0,0,0,45.34,349a4.65,4.65,0,0,1,5.78.36c5.14,4.05,5.4,6.14,6.12,5.8.26-.12-.16-1.2-2.14-3.77a16.06,16.06,0,0,0-3.65-3.63s2.06-2.56,5.64-.36,5.83,7,6.07,7c.64.21-.45-3.62-1.91-5.66s-2.1-2.75-2.1-2.75a5.18,5.18,0,0,1,5.91,1.78c2.64,3.17,2.42,8,3.23,7.72s-1-7.82-1-7.82S74.9,354.19,70.68,359.09Z" style="fill: rgb(31, 111, 235); transform-origin: 55.0323px 358.352px;" id="el0uvln3fejwsp" class="animable"></path>
								<g id="eltiu6ljqq6ob">
									<path d="M70.68,359.09c2.29,3.78-.42,10.49-6.59,11.93,1.83-2.81,3.36-7.15,1.78-7.68s.38,3.38-2.94,7.21c0,0-1.83,1-4.5-1,3.76-2.85,5.27-8.53,3.93-7.69-.53.33-1.38,3.16-3.62,5s-4.55,2-8.1-.75c2.06-.41,5-1.33,6.66-3.5,1.17-1.56,1.51-2.54-1.26-.69s-3.65,2.62-6.34,2.61-5.11-2.73-5.11-2.73S49,362,50,360.58s-3.61.8-6.7-.34a17.92,17.92,0,0,1-5.19-3s1.33-3.55,2.33-4.44c2.35,1.65,5.29,4,5.72,3.44s-.57-2.33-4-3.89a3,3,0,0,1,2.67-2.2s1.75.19,4.49,4.66c.58.94,1.82,1.75-.43-1.85A12.12,12.12,0,0,0,45.34,349a4.65,4.65,0,0,1,5.78.36c5.14,4.05,5.4,6.14,6.12,5.8.26-.12-.16-1.2-2.14-3.77a16.06,16.06,0,0,0-3.65-3.63s2.06-2.56,5.64-.36,5.83,7,6.07,7c.64.21-.45-3.62-1.91-5.66s-2.1-2.75-2.1-2.75a5.18,5.18,0,0,1,5.91,1.78c2.64,3.17,2.42,8,3.23,7.72s-1-7.82-1-7.82S74.9,354.19,70.68,359.09Z" style="opacity: 0.2; transform-origin: 55.0323px 358.352px;" class="animable"></path>
								</g>
								<path d="M75.64,353.65a7.89,7.89,0,0,1,5.06-4.23,5.57,5.57,0,0,1,6,2.28c.08.14,0,.44-.16.23-1.45-2-4.07-2-6.21-1.26a7.82,7.82,0,0,0-4.44,5.09,28,28,0,0,0-1.2,7.5,63.17,63.17,0,0,0,0,8.14c.71,10.86,3.66,21.42,6,32,1.28,5.92,1.88,17.77,2.33,23.81,0,.54-.79,1-.86.32-1.15-12-4.19-29.48-6.52-41.28a100.27,100.27,0,0,1-2.13-17.43C73.36,363.92,73.32,358.13,75.64,353.65Z" style="fill: rgb(31, 111, 235); transform-origin: 80.0911px 388.558px;" id="elgnm3lk646xn" class="animable"></path>
								<g id="elkao5opdey2">
									<path d="M75.64,353.65a7.89,7.89,0,0,1,5.06-4.23,5.57,5.57,0,0,1,6,2.28c.08.14,0,.44-.16.23-1.45-2-4.07-2-6.21-1.26a7.82,7.82,0,0,0-4.44,5.09,28,28,0,0,0-1.2,7.5,63.17,63.17,0,0,0,0,8.14c.71,10.86,3.66,21.42,6,32,1.28,5.92,1.88,17.77,2.33,23.81,0,.54-.79,1-.86.32-1.15-12-4.19-29.48-6.52-41.28a100.27,100.27,0,0,1-2.13-17.43C73.36,363.92,73.32,358.13,75.64,353.65Z" style="opacity: 0.6; transform-origin: 80.0911px 388.558px;" class="animable"></path>
								</g>
								<path d="M69,364.88c5.15.05,9.29,4,11.73,8.19,2.78,4.8,4.47,10.36,5.8,15.72a99.44,99.44,0,0,1,2.7,17.92,61.84,61.84,0,0,1,0,9.43c-.29,3-.75,6.59-2.48,9.16-.14.21-.3-.19-.27-.31.58-2.54,1.38-5,1.71-7.56a49.84,49.84,0,0,0,.28-7.63,99.91,99.91,0,0,0-1.81-15.89,83.51,83.51,0,0,0-4.51-15.31c-1.8-4.44-4.33-8.93-8.76-11.19a10,10,0,0,0-7.65-.81,7.69,7.69,0,0,0-5.11,5.32c0,.17-.21,0-.2-.14A8.48,8.48,0,0,1,69,364.88Z" style="fill: rgb(31, 111, 235); transform-origin: 74.9198px 395.118px;" id="el2erd7ntrkw4" class="animable"></path>
								<g id="el6yo25au667j">
									<path d="M69,364.88c5.15.05,9.29,4,11.73,8.19,2.78,4.8,4.47,10.36,5.8,15.72a99.44,99.44,0,0,1,2.7,17.92,61.84,61.84,0,0,1,0,9.43c-.29,3-.75,6.59-2.48,9.16-.14.21-.3-.19-.27-.31.58-2.54,1.38-5,1.71-7.56a49.84,49.84,0,0,0,.28-7.63,99.91,99.91,0,0,0-1.81-15.89,83.51,83.51,0,0,0-4.51-15.31c-1.8-4.44-4.33-8.93-8.76-11.19a10,10,0,0,0-7.65-.81,7.69,7.69,0,0,0-5.11,5.32c0,.17-.21,0-.2-.14A8.48,8.48,0,0,1,69,364.88Z" style="opacity: 0.5; transform-origin: 74.9198px 395.118px;" class="animable"></path>
								</g>
								<path d="M74.42,402.67c-1.61-6.09-3.71-12.34-7.91-17.18a20.83,20.83,0,0,0-3.72-3.36A10.24,10.24,0,0,0,58.5,380c-.17,0-.18.33-.08.41a42.34,42.34,0,0,1,4.5,3.23,24.33,24.33,0,0,1,3.41,4,43.55,43.55,0,0,1,5,10.87,114.24,114.24,0,0,1,3.29,11.78c.93,4.26,1.62,8.55,2.37,12.85,0,.21.25.17.25,0A74,74,0,0,0,74.42,402.67Z" style="fill: rgb(31, 111, 235); transform-origin: 67.7981px 401.641px;" id="elp6cvyq3bpf" class="animable"></path>
								<g id="elsqa13gdt9xd">
									<path d="M74.42,402.67c-1.61-6.09-3.71-12.34-7.91-17.18a20.83,20.83,0,0,0-3.72-3.36A10.24,10.24,0,0,0,58.5,380c-.17,0-.18.33-.08.41a42.34,42.34,0,0,1,4.5,3.23,24.33,24.33,0,0,1,3.41,4,43.55,43.55,0,0,1,5,10.87,114.24,114.24,0,0,1,3.29,11.78c.93,4.26,1.62,8.55,2.37,12.85,0,.21.25.17.25,0A74,74,0,0,0,74.42,402.67Z" style="opacity: 0.6; transform-origin: 67.7981px 401.641px;" class="animable"></path>
								</g>
								<path d="M91.84,384.48c1.74-2.89,8.67-5.65,18.32-2.67a26.54,26.54,0,0,1,9.39,5.3c-6.33-1.77-14.92-3.48-16.11-1.27s2.5,1.75,5.85,1.57a38,38,0,0,1,11.41.78,19.74,19.74,0,0,1,6.39,7.92c-6-4.64-14.18-6-17.88-5.35s-4.07,2.12-1.11,2.07,8.8-.36,21.28,6.93c0,0,2.18,2.74,1.44,8.64-1.82-2.93-5.12-5-8-6.63a44.52,44.52,0,0,0-9.41-4.19c-.58-.16-1.76-.47-2.26.05s0,1.34.42,1.72a11.13,11.13,0,0,0,3.37,1.65c1.57.52,3.09,1.21,4.6,1.87a47.84,47.84,0,0,1,7.35,3.64c1.24.83,2.91,5.3,1.45,9.26-3.14-1.68-8.87-8-13.95-9.09s.45,1.72,4.06,4.52c3.1,2.41,8.54,7.25,8.54,7.25a83.23,83.23,0,0,1-5,9.41,31.92,31.92,0,0,1-10.29-3.22s-.19-10.6-2.33-12.27-1.47,2.74-.39,6.76,1.31,5.49,1.31,5.49-10.51-1.36-13.51-6c.52-2.07,2.5-7.27,3.69-10.76,1.26-3.68,3.09-6.51,2.18-7.52s-3,1.61-4.8,5a43.24,43.24,0,0,0-3.7,10.94s-6.54-3.54-8.3-6.3c-.83-6.79,4.58-12.51,5.6-13.3s.57-4.52-4.36-1.2a10.86,10.86,0,0,0-5,9.07s-4.92-2.12-5.62-7.15C75.28,389.33,80.48,383,91.84,384.48Z" style="fill: rgb(31, 111, 235); transform-origin: 103.638px 404.193px;" id="elwiy3bpjyu4" class="animable"></path>
								<g id="elqxwb1jag9h">
									<path d="M91.84,384.48c1.74-2.89,8.67-5.65,18.32-2.67a26.54,26.54,0,0,1,9.39,5.3c-6.33-1.77-14.92-3.48-16.11-1.27s2.5,1.75,5.85,1.57a38,38,0,0,1,11.41.78,19.74,19.74,0,0,1,6.39,7.92c-6-4.64-14.18-6-17.88-5.35s-4.07,2.12-1.11,2.07,8.8-.36,21.28,6.93c0,0,2.18,2.74,1.44,8.64-1.82-2.93-5.12-5-8-6.63a44.52,44.52,0,0,0-9.41-4.19c-.58-.16-1.76-.47-2.26.05s0,1.34.42,1.72a11.13,11.13,0,0,0,3.37,1.65c1.57.52,3.09,1.21,4.6,1.87a47.84,47.84,0,0,1,7.35,3.64c1.24.83,2.91,5.3,1.45,9.26-3.14-1.68-8.87-8-13.95-9.09s.45,1.72,4.06,4.52c3.1,2.41,8.54,7.25,8.54,7.25a83.23,83.23,0,0,1-5,9.41,31.92,31.92,0,0,1-10.29-3.22s-.19-10.6-2.33-12.27-1.47,2.74-.39,6.76,1.31,5.49,1.31,5.49-10.51-1.36-13.51-6c.52-2.07,2.5-7.27,3.69-10.76,1.26-3.68,3.09-6.51,2.18-7.52s-3,1.61-4.8,5a43.24,43.24,0,0,0-3.7,10.94s-6.54-3.54-8.3-6.3c-.83-6.79,4.58-12.51,5.6-13.3s.57-4.52-4.36-1.2a10.86,10.86,0,0,0-5,9.07s-4.92-2.12-5.62-7.15C75.28,389.33,80.48,383,91.84,384.48Z" style="opacity: 0.2; transform-origin: 103.638px 404.193px;" class="animable"></path>
								</g>
								<path d="M62,369.12c5-1,12.15,0,16,3.95s4.21,8.9,3.24,11.48-2.47-3.8-6.43-6.74-5.79-2.64-2.41.48S80.24,387.2,81,391s-2.65,8.09-3.79,7.83.07-4.16-1.92-9.58-5.77-8.91-8.66-9.67-2.13.16,1.8,4.32,8.6,12.71,7,16.89-5.74,5.71-6.85,5.36,1.45-5.59-.89-12.23-7-11.23-9.39-9.69-.5,3.33,2,8.26,5.86,14.85,4.87,18.09-6.92,5.58-7.84,4.61,1.65-6.51,0-13.5-4.68-9.64-4.87-6.75,4.15,16.43,3.09,18.77-11.83,6.28-11.83,6.28-5.88-6.85-6.28-8.94,4.35-10.78,7-13.31-.8-2.62-3-.9-6.88,8.07-7.18,11.28c-2.94-3.16-5.59-7.21-3.18-10s9.75-7.46,14.09-9.16,7.44-6.13,1.65-4.79-15.71,7.06-17.33,11.21c-.84-3.64-.24-13.93,6.08-15.84s13-1.67,15-2.42-.65-2.42-5.48-2.51-11.25.43-13.63,3.66c0-4,.4-8.07,7.79-8.41s10.67,2.59,11.8,1.72-4.75-3.74-9.06-3.84a10.1,10.1,0,0,1,10.48-3.81A17,17,0,0,1,62,369.12Z" style="fill: rgb(31, 111, 235); transform-origin: 55.443px 391.82px;" id="ely47c174s7on" class="animable"></path>
								<path d="M86.32,351.05c1.3-3.63,6.63-5.06,9.23-4-2.73.78-5,3.07-3.46,2.34,3.19-1.54,5.19-2.24,9-1.62a6.36,6.36,0,0,1,4.89,3.95,22.89,22.89,0,0,0-7.69-.77c-1.24.12-4.5.76-.47.62,6.36-.23,8.81,2,8.81,2a8.59,8.59,0,0,1,2.47,4.55c-3.44-3.44-8.87-3.83-10.88-4s-3.8,1-.49,1.1,9.42,2.21,11.37,5.46.78,8.45.78,8.45-7.8-10-10.53-8.71c0,1.56,8.06,6.5,9.49,9.36s-1.3,9-1.3,9-7.32-.26-9.25-1.43-1.48-9.17-2.52-10.34-3.56,5-2.13,10.21c0,0-3.64.65-4.42-2.21s.39-8.84,1.95-11.57-1-2.08-2.34,0-3,6.64-2.47,9.62c0,0-4.29-1.82-5.46-4s2.6-8.32,4.55-9.88.13-2.73-1.82-1.17-5.07,6.24-5.33,8.58c-2.32-4-2.69-9.21.83-13.1C82.82,349.29,86.32,351.05,86.32,351.05Z" style="fill: rgb(31, 111, 235); transform-origin: 93.3749px 362.744px;" id="el08y4jf53925" class="animable"></path>
								<g id="ell320m3pqco8">
									<path d="M86.32,351.05c1.3-3.63,6.63-5.06,9.23-4-2.73.78-5,3.07-3.46,2.34,3.19-1.54,5.19-2.24,9-1.62a6.36,6.36,0,0,1,4.89,3.95,22.89,22.89,0,0,0-7.69-.77c-1.24.12-4.5.76-.47.62,6.36-.23,8.81,2,8.81,2a8.59,8.59,0,0,1,2.47,4.55c-3.44-3.44-8.87-3.83-10.88-4s-3.8,1-.49,1.1,9.42,2.21,11.37,5.46.78,8.45.78,8.45-7.8-10-10.53-8.71c0,1.56,8.06,6.5,9.49,9.36s-1.3,9-1.3,9-7.32-.26-9.25-1.43-1.48-9.17-2.52-10.34-3.56,5-2.13,10.21c0,0-3.64.65-4.42-2.21s.39-8.84,1.95-11.57-1-2.08-2.34,0-3,6.64-2.47,9.62c0,0-4.29-1.82-5.46-4s2.6-8.32,4.55-9.88.13-2.73-1.82-1.17-5.07,6.24-5.33,8.58c-2.32-4-2.69-9.21.83-13.1C82.82,349.29,86.32,351.05,86.32,351.05Z" style="opacity: 0.35; transform-origin: 93.3749px 362.744px;" class="animable"></path>
								</g>
							</g>
						</g>
					</g>
					<g id="freepik--character-1--inject-127" class="animable" style="transform-origin: 345.491px 326.889px;">
						<g id="freepik--character--inject-127" class="animable" style="transform-origin: 345.491px 326.889px;">
							<path d="M336.41,383.28c0-.21,3.91-11.6,3.91-11.6s-3.38,1.18-10.41-.23c-3.57-.72-7.1-4.28-7.1-4.28s-1.7,8.07-2.46,11.09c-.77-.38-1.33,1.49-2.82,2-2.58.82-7,.21-11.29-.36-1.12-.14-2.37-.49-3.53-.62h-.07c-3.63,0-7.12,2.41-3.18,5.26s10.21,3.65,16,4.34C322.39,389.66,335.59,390.17,336.41,383.28Z" style="fill: rgb(177, 102, 104); transform-origin: 319.02px 378.243px;" id="elqpuvlv803ks" class="animable"></path>
							<path d="M287.28,385.91a7,7,0,0,0-.28,3.7c.21,1.33,3.78,5.65,9.77,8.06s13.2,2.17,16.63,2.94,6.63,2.51,10.66,3.24,9.54,1.18,12.24-2.11a4.17,4.17,0,0,0,.39-3.17C336,397.92,287.28,385.91,287.28,385.91Z" style="fill: rgb(38, 50, 56); transform-origin: 311.856px 395.141px;" id="el0swd2t8ueqd" class="animable"></path>
							<path d="M320.35,378.26c-.3-1.36-2,1.37-3,1.55-2.81.54-6.91,0-11.22-.26-5.43-.29-11.63-1.82-15.22-.32s-4.65,5.64.65,10.88c1.53,1.52,3.22,3.49,5,4.4,5.81,3.05,12.34,2.51,16.36,3.25,5.24,1,8.54,4,13.35,4.23,6.22.35,9.58-.65,10.67-4.08s-.18-7.1.39-11.05c.39-2.71.25-3.54-.93-3.58,0,0-.32.9-3,1.29-4.73.71-5.69-1.21-10.75,3.1-1.55,1.32-2.46.52-2.68.23-.76-1-1-2.51-.55-4.92C319.77,381.25,320,380.06,320.35,378.26Z" style="fill: rgb(55, 71, 79); transform-origin: 312.712px 389.969px;" id="elknsb8w85l6" class="animable"></path>
							<path d="M296.57,394.51s-.55-14,7.43-15.13l1,.08s-.06-1-3.1-1.54c-5.86-1.11-9.65-.77-12.41,1.49a7.67,7.67,0,0,0-2.26,6.35C287.71,388.51,290.42,391.9,296.57,394.51Z" style="fill: rgb(250, 250, 250); transform-origin: 296.091px 385.908px;" id="el4s1ptvaoo5a" class="animable"></path>
							<path d="M301.5,386.81s1.24-6.33,5.88-7.19a4.78,4.78,0,0,1,2.3.17s-5.12.58-6.49,7.25c0,0-.16.46-1,.36C301.89,387.37,301.4,387.33,301.5,386.81Z" style="fill: rgb(250, 250, 250); transform-origin: 305.583px 383.483px;" id="elmn28exp77dh" class="animable"></path>
							<path d="M313.25,380s-.06-.15-1-.19a4.85,4.85,0,0,0-1.16.07,8.13,8.13,0,0,0-5.3,7.51s-.14.48.71.48,1-.42,1-.42S307.88,381.81,313.25,380Z" style="fill: rgb(250, 250, 250); transform-origin: 309.516px 383.836px;" id="elj0hqqwrmy8j" class="animable"></path>
							<path d="M324.73,332c-1.62,12.86-4.14,42.94-4.14,42.94s9,7.07,17.7,4.22l14.28-43.93Z" style="fill: rgb(55, 71, 79); transform-origin: 336.58px 355.919px;" id="elf8eovs0zc0e" class="animable"></path>
							<path d="M305.51,401.07c0-.21,3.91-11.59,3.91-11.59s-3.38,1.17-10.41-.24c-3.57-.71-7.1-4.28-7.1-4.28s-1.7,8.07-2.46,11.1c-.77-.38-1.33,1.48-2.82,1.95-2.58.82-7.05.21-11.29-.35-1.12-.15-2.37-.49-3.54-.62h-.07c-3.62,0-7.12,2.41-3.17,5.27s10.21,3.64,16,4.33C291.49,407.46,304.69,408,305.51,401.07Z" style="fill: rgb(177, 102, 104); transform-origin: 288.118px 396.034px;" id="elu4ojyi0k4md" class="animable"></path>
							<path d="M256.38,403.7a7,7,0,0,0-.28,3.7c.21,1.33,3.78,5.65,9.77,8.07s13.2,2.16,16.63,2.93,6.63,2.51,10.66,3.24,9.54,1.19,12.24-2.1a4.2,4.2,0,0,0,.39-3.18C305.12,415.72,256.38,403.7,256.38,403.7Z" style="fill: rgb(38, 50, 56); transform-origin: 280.956px 412.932px;" id="elsd8z2uyzp0s" class="animable"></path>
							<path d="M289.45,396.06c-.3-1.36-2,1.37-3,1.55-2.82.53-6.91,0-11.22-.26-5.43-.29-11.63-1.82-15.22-.33s-4.65,5.65.65,10.89c1.53,1.51,3.22,3.48,5,4.4,5.81,3.05,12.34,2.51,16.36,3.25,5.24,1,8.54,4,13.35,4.23,6.22.34,9.57-.66,10.66-4.09s-.17-7.09.4-11.05c.39-2.71.25-3.53-.93-3.58,0,0-.32.9-3,1.3-4.73.7-5.69-1.21-10.75,3.09-1.56,1.33-2.46.52-2.68.23-.76-1-1-2.5-.55-4.92C288.87,399,289.11,397.86,289.45,396.06Z" style="fill: rgb(55, 71, 79); transform-origin: 281.812px 407.767px;" id="el22crwelvljd" class="animable"></path>
							<path d="M265.67,412.31s-.55-14,7.43-15.13l1,.07s-.06-1-3.11-1.53c-5.85-1.11-9.65-.78-12.4,1.49a7.65,7.65,0,0,0-2.26,6.34C256.81,406.3,259.52,409.69,265.67,412.31Z" style="fill: rgb(250, 250, 250); transform-origin: 265.191px 403.707px;" id="elbypypm4hpmo" class="animable"></path>
							<path d="M270.6,404.6s1.24-6.33,5.88-7.18a4.86,4.86,0,0,1,2.3.16s-5.13.59-6.49,7.25c0,0-.16.47-1,.36C271,405.16,270.5,405.12,270.6,404.6Z" style="fill: rgb(250, 250, 250); transform-origin: 274.684px 401.278px;" id="els78sq5lonhk" class="animable"></path>
							<path d="M282.35,397.8s-.06-.15-1-.19a4.48,4.48,0,0,0-1.16.08,8.13,8.13,0,0,0-5.3,7.5s-.14.49.71.48,1-.42,1-.42S277,399.6,282.35,397.8Z" style="fill: rgb(250, 250, 250); transform-origin: 278.616px 401.636px;" id="elq7i1ruuc3b" class="animable"></path>
							<path d="M285.68,397.72s-.2-.16-.93-.12c-.91.07-1.1.26-1.1.26s-4.58,2.44-4.31,7.35c0,0,0,.52.86.46s.82-.57.82-.57A8,8,0,0,1,285.68,397.72Z" style="fill: rgb(250, 250, 250); transform-origin: 282.504px 401.634px;" id="el9hws0og7br7" class="animable"></path>
							<path d="M298.31,338.58c-2,11.46-5.77,37.66-7.95,51.86,1.43,4.21,12.18,8,17.41,5.17,5.63-17,15.77-23.52,14.75-51.54Z" style="fill: rgb(69, 90, 100); transform-origin: 306.476px 367.585px;" id="el0cu7ri4jwk2m" class="animable"></path>
							<path d="M322,354.16c-2.45-1.64-7.47-7.26-8.39-9.67,0,4.3,4,10.68,7.52,13S322,354.16,322,354.16Z" style="fill: rgb(55, 71, 79); transform-origin: 318.283px 351.27px;" id="elz693goxahxs" class="animable"></path>
							<g id="freepik--chair--inject-127" class="animable" style="transform-origin: 370.25px 382.635px;">
								<path d="M337.44,442a5.49,5.49,0,0,0,.13,1.19,2.73,2.73,0,0,0,1.07,1.66l0,0a3.12,3.12,0,0,0,3.3-.26,10,10,0,0,0,4.55-7.87,3.22,3.22,0,0,0-1.28-2.88h0l-.22-.13h0a2.86,2.86,0,0,0-1.93-.08,5.1,5.1,0,0,0-1.12.49A10,10,0,0,0,337.44,442Z" style="fill: rgb(69, 90, 100); transform-origin: 341.972px 439.352px;" id="eld2aerhi9nf8" class="animable"></path>
								<path d="M338.68,432.24a4.16,4.16,0,0,1,2-.61A2.31,2.31,0,0,1,342,432l.12.06,2.93,1.69a2.86,2.86,0,0,0-1.93-.08,5.1,5.1,0,0,0-1.12.49,10,10,0,0,0-4.54,7.86,5.49,5.49,0,0,0,.13,1.19,2.73,2.73,0,0,0,1.07,1.66l-3-1.74-.05,0-.25-.16a.14.14,0,0,1-.08-.05,3.34,3.34,0,0,1-1.11-2.79A10,10,0,0,1,338.68,432.24Z" style="fill: rgb(38, 50, 56); transform-origin: 339.604px 438.25px;" id="elxgny5h11kc" class="animable"></path>
								<path d="M386.6,415.57a5.35,5.35,0,0,0,.13,1.19,2.75,2.75,0,0,0,1.07,1.67l0,0a3.13,3.13,0,0,0,3.3-.25,10,10,0,0,0,4.55-7.88,3.23,3.23,0,0,0-1.28-2.88h0l-.22-.13h0a2.8,2.8,0,0,0-1.93-.07,5.55,5.55,0,0,0-1.12.48A10,10,0,0,0,386.6,415.57Z" style="fill: rgb(69, 90, 100); transform-origin: 391.132px 412.934px;" id="elsiuvhet6q7h" class="animable"></path>
								<path d="M387.84,405.81a4.18,4.18,0,0,1,2-.62,2.39,2.39,0,0,1,1.28.36l.12.06,2.93,1.69a2.8,2.8,0,0,0-1.93-.07,5.55,5.55,0,0,0-1.12.48,10,10,0,0,0-4.54,7.86,5.35,5.35,0,0,0,.13,1.19,2.75,2.75,0,0,0,1.07,1.67l-3-1.74-.05,0-.25-.15-.08-.05a3.37,3.37,0,0,1-1.11-2.79A10,10,0,0,1,387.84,405.81Z" style="fill: rgb(38, 50, 56); transform-origin: 388.724px 411.81px;" id="el7ntjzlmk0r2" class="animable"></path>
								<path d="M377.83,452.33a5.42,5.42,0,0,1-.13,1.19,2.75,2.75,0,0,1-1.07,1.67l0,0a3.12,3.12,0,0,1-3.3-.26,10,10,0,0,1-4.55-7.87A3.22,3.22,0,0,1,370,444.2h0l.22-.13h0a2.86,2.86,0,0,1,1.93-.08,5.62,5.62,0,0,1,1.12.49A10,10,0,0,1,377.83,452.33Z" style="fill: rgb(69, 90, 100); transform-origin: 373.297px 449.702px;" id="elasa63n2brdm" class="animable"></path>
								<path d="M376.59,442.56a4.16,4.16,0,0,0-2-.61,2.39,2.39,0,0,0-1.28.36l-.12.06-2.93,1.69a2.86,2.86,0,0,1,1.93-.08,5.62,5.62,0,0,1,1.12.49,10,10,0,0,1,4.54,7.86,5.42,5.42,0,0,1-.13,1.19,2.75,2.75,0,0,1-1.07,1.67l3-1.75.05,0,.25-.15.08-.06a3.34,3.34,0,0,0,1.11-2.79A10,10,0,0,0,376.59,442.56Z" style="fill: rgb(38, 50, 56); transform-origin: 375.706px 448.57px;" id="eluce1b91ugwd" class="animable"></path>
								<path d="M409.74,436.7a5.49,5.49,0,0,1-.13,1.19,2.73,2.73,0,0,1-1.07,1.66l0,0a3.12,3.12,0,0,1-3.3-.26,10,10,0,0,1-4.55-7.87,3.22,3.22,0,0,1,1.28-2.88h0l.22-.13h0a2.86,2.86,0,0,1,1.93-.08,5.62,5.62,0,0,1,1.12.49A10,10,0,0,1,409.74,436.7Z" style="fill: rgb(69, 90, 100); transform-origin: 405.208px 434.052px;" id="elhnv0u5j7fcj" class="animable"></path>
								<path d="M408.5,426.93a4.16,4.16,0,0,0-2-.61,2.31,2.31,0,0,0-1.28.36l-.12.06-2.93,1.69a2.86,2.86,0,0,1,1.93-.08,5.62,5.62,0,0,1,1.12.49,10,10,0,0,1,4.54,7.86,5.49,5.49,0,0,1-.13,1.19,2.73,2.73,0,0,1-1.07,1.66l3-1.74.05,0,.25-.16a.14.14,0,0,0,.08-.05,3.34,3.34,0,0,0,1.12-2.79A10,10,0,0,0,408.5,426.93Z" style="fill: rgb(38, 50, 56); transform-origin: 407.621px 432.935px;" id="el4oqvdblqmtx" class="animable"></path>
								<path d="M348.06,417.53a5.49,5.49,0,0,1-.13,1.19,2.7,2.7,0,0,1-1.08,1.66l0,0a3.14,3.14,0,0,1-3.3-.26,10,10,0,0,1-4.54-7.87,3.22,3.22,0,0,1,1.28-2.88h0l.22-.13h0a2.8,2.8,0,0,1,1.93-.08,4.84,4.84,0,0,1,1.11.49A10,10,0,0,1,348.06,417.53Z" style="fill: rgb(69, 90, 100); transform-origin: 343.528px 414.878px;" id="elkomwf1x8v6b" class="animable"></path>
								<path d="M346.81,407.76a4.1,4.1,0,0,0-2-.61,2.41,2.41,0,0,0-1.28.35l-.11.07-2.93,1.69a2.8,2.8,0,0,1,1.93-.08,4.84,4.84,0,0,1,1.11.49,10,10,0,0,1,4.55,7.86,5.49,5.49,0,0,1-.13,1.19,2.7,2.7,0,0,1-1.08,1.66l3-1.74.05,0,.26-.16a.25.25,0,0,0,.08,0,3.34,3.34,0,0,0,1.11-2.79A10,10,0,0,0,346.81,407.76Z" style="fill: rgb(38, 50, 56); transform-origin: 345.936px 413.765px;" id="eltj3dtz2o46" class="animable"></path>
								<polygon points="375.19 407.92 391.41 403.27 391.41 406.73 375.19 416.09 375.19 407.92" style="fill: rgb(235, 235, 235); transform-origin: 383.3px 409.68px;" id="eljfco7wkr57" class="animable"></polygon>
								<polygon points="375.19 407.92 373.58 406.94 389.86 402.5 391.41 403.27 375.19 407.92" style="fill: rgb(245, 245, 245); transform-origin: 382.495px 405.21px;" id="eley460n2owtu" class="animable"></polygon>
								<polygon points="368.17 418.26 345.16 409.64 345.16 406.14 368.17 408.19 368.17 418.26" style="fill: rgb(235, 235, 235); transform-origin: 356.665px 412.2px;" id="elen561p5wyk" class="animable"></polygon>
								<polygon points="345.16 406.14 346.85 405.69 369.53 407.15 368.17 408.19 345.16 406.14" style="fill: rgb(245, 245, 245); transform-origin: 357.345px 406.94px;" id="el27uy56fb9tf" class="animable"></polygon>
								<path d="M367.55,417.15c0,2.61,8,2.61,8,0V387h-8Z" style="fill: rgb(250, 250, 250); transform-origin: 371.55px 403.054px;" id="elcc08us9qedt" class="animable"></path>
								<path d="M373.55,411.24,378,439.8h-3l-4.12-28.56A5,5,0,0,0,373.55,411.24Z" style="fill: rgb(245, 245, 245); transform-origin: 374.44px 425.52px;" id="el3lsyl90yng6" class="animable"></path>
								<polygon points="370.82 411.24 370.82 419.08 374.94 444.63 374.94 439.8 370.82 411.24" style="fill: rgb(235, 235, 235); transform-origin: 372.88px 427.935px;" id="el97ncsj6skn" class="animable"></polygon>
								<rect x="374.94" y="439.8" width="3.01" height="4.84" style="fill: rgb(224, 224, 224); transform-origin: 376.445px 442.22px;" id="elj9rd9358xtc" class="animable"></rect>
								<polygon points="369.45 418.85 339.79 434.4 339.79 429.61 369.45 411.26 369.45 418.85" style="fill: rgb(235, 235, 235); transform-origin: 354.62px 422.83px;" id="elwxeudltm99" class="animable"></polygon>
								<path d="M369.45,411.26a3.29,3.29,0,0,1-1.9-.86l-29.61,18,1.85,1.25Z" style="fill: rgb(245, 245, 245); transform-origin: 353.695px 420.025px;" id="el9f7q2bub5bm" class="animable"></path>
								<polygon points="339.79 434.4 339.79 429.61 337.94 428.36 337.94 433 339.79 434.4" style="fill: rgb(224, 224, 224); transform-origin: 338.865px 431.38px;" id="el2he9f0jenuv" class="animable"></polygon>
								<polygon points="406.85 428.9 375.16 418.05 375.16 410.94 406.85 424.12 406.85 428.9" style="fill: rgb(235, 235, 235); transform-origin: 391.005px 419.92px;" id="elkcan62z2q6" class="animable"></polygon>
								<path d="M375.16,410.94c.26-.07.37-.53.39-1.23l32.24,14-.94.39Z" style="fill: rgb(245, 245, 245); transform-origin: 391.475px 416.905px;" id="el3k32cennl0a" class="animable"></path>
								<polygon points="407.79 423.73 407.79 428.36 406.85 428.9 406.85 424.12 407.79 423.73" style="fill: rgb(224, 224, 224); transform-origin: 407.32px 426.315px;" id="elvkduyvg5l" class="animable"></polygon>
								<g id="el8eb3g00td0b">
									<rect x="374.94" y="439.8" width="3.01" height="4.84" style="opacity: 0.1; transform-origin: 376.445px 442.22px;" class="animable"></rect>
								</g>
								<g id="el7b4u0fvd7v8">
									<polygon points="339.79 434.4 339.79 429.61 337.94 428.36 337.94 433 339.79 434.4" style="opacity: 0.1; transform-origin: 338.865px 431.38px;" class="animable"></polygon>
								</g>
								<g id="eld91sdugeo0p">
									<polygon points="407.79 423.73 407.79 428.36 406.85 428.9 406.85 424.12 407.79 423.73" style="opacity: 0.1; transform-origin: 407.32px 426.315px;" class="animable"></polygon>
								</g>
								<path d="M377.16,366.29H366V399c0,3.22,11.21,3.22,11.21,0Z" style="fill: rgb(240, 240, 240); transform-origin: 371.605px 383.853px;" id="elf17sim39oh" class="animable"></path>
								<path d="M422.63,361.94v3l-49.22,28.42a6.35,6.35,0,0,1-6.31,0L317.87,365v-3Z" style="fill: rgb(55, 71, 79); transform-origin: 370.25px 378.07px;" id="elrde8pd5y14a" class="animable"></path>
								<g id="elg124ispw1ph">
									<rect x="388.15" y="316.67" width="5.24" height="26" style="fill: rgb(55, 71, 79); transform-origin: 390.77px 329.67px; transform: rotate(180deg);" class="animable"></rect>
								</g>
								<path d="M390.81,310.82l27.45,15.85-10.48,6-31.42-18.14,6.49-3.75A7.92,7.92,0,0,1,390.81,310.82Z" style="fill: rgb(31, 111, 235); transform-origin: 397.31px 321.199px;" id="elsedk4s49gq" class="animable"></path>
								<g id="elafrag1b5yuk">
									<path d="M390.81,310.82l27.45,15.85-10.48,6-31.42-18.14,6.49-3.75A7.92,7.92,0,0,1,390.81,310.82Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 397.31px 321.199px;" class="animable"></path>
								</g>
								<polygon points="376.36 317.38 376.36 314.57 407.78 332.71 407.78 335.52 376.36 317.38" style="fill: rgb(31, 111, 235); transform-origin: 392.07px 325.045px;" id="el6z2u5c9l8be" class="animable"></polygon>
								<g id="elfem6ik2965">
									<polygon points="376.36 317.38 376.36 314.57 407.78 332.71 407.78 335.52 376.36 317.38" style="opacity: 0.3; transform-origin: 392.07px 325.045px;" class="animable"></polygon>
								</g>
								<polygon points="418.26 326.67 418.26 329.48 407.78 335.52 407.78 332.71 418.26 326.67" style="fill: rgb(31, 111, 235); transform-origin: 413.02px 331.095px;" id="elt1cp4wg1q6m" class="animable"></polygon>
								<g id="ellmu33aveg5h">
									<polygon points="418.26 326.67 418.26 329.48 407.78 335.52 407.78 332.71 418.26 326.67" style="opacity: 0.15; transform-origin: 413.02px 331.095px;" class="animable"></polygon>
								</g>
								<path d="M317.88,361.94l49.17,28.4a6.4,6.4,0,0,0,6.39,0l49.19-28.4a10.5,10.5,0,0,0-5.23-9.07L413,350.33c-.27-.17-.54-.31-.84-.49l-36-20.76a11.73,11.73,0,0,0-11.82,0l-41.22,23.79-.1.08-.1.07A10.41,10.41,0,0,0,317.88,361.94Z" style="fill: rgb(31, 111, 235); transform-origin: 370.255px 359.338px;" id="elk04um0cxbv" class="animable"></path>
								<g id="el2e0cbt7q2dn">
									<path d="M422.63,361.94a10.5,10.5,0,0,0-5.23-9.07L413,350.33c6.18,3.64,8.72,6.09,4.39,8.58-5.22,3-34,19.63-43.82,25.31a6.7,6.7,0,0,1-3.3.89v6.05a6.58,6.58,0,0,0,3.3-.88Z" style="opacity: 0.15; transform-origin: 396.45px 370.745px;" class="animable"></path>
								</g>
								<g id="elce80m0uv0ob">
									<path d="M366.94,384.22c-8.27-4.79-29.86-17.28-38.6-22.28-9.14-5.23-6.28-8.26-5.43-8.92a10.41,10.41,0,0,0-5,8.92l49.06,28.34a6.52,6.52,0,0,0,3.31.88v-6.05A6.66,6.66,0,0,1,366.94,384.22Z" style="opacity: 0.3; transform-origin: 344.095px 372.09px;" class="animable"></path>
								</g>
							</g>
							<g id="freepik--bottom--inject-127" class="animable" style="transform-origin: 354.893px 345.129px;">
								<path d="M410.05,332.74s-33.94-11.44-46.77-15.12c-8-2.31-18.73-4.07-24.82-3.25-4.9.66-12.31,4.78-13.4,14.71-.16,1.22-.33,3-.33,3s18.34,9.79,36.63,11S410.05,332.74,410.05,332.74Z" style="fill: rgb(55, 71, 79); transform-origin: 367.39px 328.667px;" id="eljk6kvkg7o" class="animable"></path>
								<path d="M410.05,332.74c2.27,10.08,2.56,19.81-4.82,26.51-5.37,4.87-27.43,22.06-45.38,15.28-14.84-5.6-30.94-15.76-38.3-20.66-2.59-1.72-7.95-9.38-7.95-9.38s-15.49-4.76-15.28-6c2.08-12.38,9.45-17.42,22.41-15.42,14.41,2.23,45.6,14.68,45.6,14.68Z" style="fill: rgb(69, 90, 100); transform-origin: 354.893px 349.368px;" id="elwwsiw0k2zp" class="animable"></path>
							</g>
							<g id="freepik--top--inject-127" class="animable" style="transform-origin: 361.428px 273.93px;">
								<g id="freepik--character--inject-127" class="animable" style="transform-origin: 392.364px 273.93px;">
									<path d="M407.47,254.88c9.22-1.87,16.38,2.11,17.6,10.61,1,6.73-1,19-3.36,30,0,0-5.41,1.66-16-3Z" style="fill: rgb(31, 111, 235); transform-origin: 415.522px 275.102px;" id="eli6nq3x4wewl" class="animable"></path>
									<g id="eloylegzsxw9">
										<path d="M407.47,254.88c9.22-1.87,16.38,2.11,17.6,10.61,1,6.73-1,19-3.36,30,0,0-5.41,1.66-16-3Z" style="opacity: 0.2; transform-origin: 415.522px 275.102px;" class="animable"></path>
									</g>
									<path d="M374.23,224.72s-6.34,7.65-5.89,8.34,4,2.31,4,2.31Z" style="fill: rgb(154, 74, 77); transform-origin: 371.274px 230.045px;" id="el335kwo2tbql" class="animable"></path>
									<path d="M378.37,212c-3.35,1.14-4.52,3.63-4.52,9.17,0,1.86.29,4.84-.5,7.25-2.2,6.72-1.66,18.2.15,20.34,1.22,1.45,9.06,3.81,12.93,3.19,4.84-.78,15.56-4.24,21.25-11,6.69-8,9.34-19.46,3.49-24.83C402.93,208.48,382,210.74,378.37,212Z" style="fill: rgb(177, 102, 104); transform-origin: 393.156px 231.368px;" id="eln05u5jz194i" class="animable"></path>
									<path d="M404.91,252.1l-.36-6.19-19.19,3.38.42,4.3c.24,2.41.43,4.9-1.17,5.84-2.85,1.67-7.58,4.26-10.76,6.19,0,0,13.89,1.7,24.35,0s9-10.75,9-10.75C406.64,254.85,405.05,255.05,404.91,252.1Z" style="fill: rgb(177, 102, 104); transform-origin: 390.56px 256.143px;" id="eliedluvt3be" class="animable"></path>
									<path d="M373.85,249a22.67,22.67,0,0,0,11.79,3.07l-.28-2.81a28.53,28.53,0,0,1-6.78.62A14.45,14.45,0,0,1,373.85,249Z" style="fill: rgb(154, 74, 77); transform-origin: 379.745px 250.537px;" id="elzfxvg9rach" class="animable"></path>
									<path d="M371.25,211.93a4.84,4.84,0,0,0-1,2.49,4.26,4.26,0,0,0,1.94,3.91,3.66,3.66,0,0,0,4.26-.38,4.74,4.74,0,0,0,1.11,4.34,3.36,3.36,0,0,0,4.26.4c-.38.8-.7,1.9,0,2.45a1.51,1.51,0,0,0,.61.26,3.21,3.21,0,0,0,1.89-.12,3,3,0,0,0-.8,2.65c.08.31,1.61,2.23,1.69,2,0,0,2-5.38,6.55-4.24s4.67,7.19,1.37,10.53-5.89,1.71-5.93,1.69c0,1-.11,6.18,4.18,9.18,2.63,1.85,9.54,2.25,13.19.06,1.35-2.4,3.6-5.48,5.34-8.17,5.83-9,7.75-14.6,6.74-19.68-.69-3.47-2.24-5.45-4.87-6.11-.14-3.3-.61-5.83-4.15-8.88-5.57-4.78-12-5.19-17-5a27.63,27.63,0,0,1-5.14.14c-1.47-.2-2.86-.81-4.32-1.07a4.56,4.56,0,0,0-4.16,1c-1.07,1.14-1.07,2.93-.68,4.44a7.34,7.34,0,0,0-3.43,1,4.08,4.08,0,0,0-1.95,2.88,2.85,2.85,0,0,0,1.62,2.93A5.43,5.43,0,0,0,371.25,211.93Z" style="fill: rgb(38, 50, 56); transform-origin: 393.572px 223.438px;" id="el1230k9ka1g5a" class="animable"></path>
									<path d="M422.57,268.66c-1.8-8.74-6.41-10.6-8.3-11.62a24.64,24.64,0,0,0-5.68-2.36l-1.43.16c.06,1-3.18,5.3-10.94,6-6.25.53-10.11-.66-11.61-1.37-2.85,1.65-6.8,3.43-13.5,6.91-5.79,3-10.41,6.61-10.8,17.1-1.11,29.57-.9,41.71-.9,54,.7,2.44,7.8,13,25.28,12.09C414,348,414.78,336,414.78,336c-.47-10.93.21-13.3,1.5-19.62C417.81,304.73,426.45,287.57,422.57,268.66Z" style="fill: rgb(250, 250, 250); transform-origin: 391.478px 302.153px;" id="elv6l85fnotue" class="animable"></path>
								</g>
								<path d="M338.1,254.16c.63-.77,1.78-.76,2.76-.95,1.82-.34,3.37-1.51,5.15-2a9.41,9.41,0,0,1,6.05.5,14,14,0,0,1,1.9.94,26,26,0,0,1,3.3,2.34c4.33,3.54,6.95,10.45,11.19,14.08,1.58,1.35,3.06,2.58,4.52,3.81l-12.68,8.28a69.41,69.41,0,0,0-4.88-5c-2.51-2.05-5.28-2.51-8.3-3.42a44.68,44.68,0,0,1-6.07-2.53c-.7-.33-1.47-.75-1.68-1.49a1.9,1.9,0,0,1,1.55-2.12,16.25,16.25,0,0,1,3.34,0c.82,0,2.9.8,3.5.06.32-.39.09-1-.16-1.39-1.58-2.71-3.64-5.67-6.63-6.87a6.61,6.61,0,0,1-2.57-1.49A2.18,2.18,0,0,1,338.1,254.16Z" style="fill: rgb(177, 102, 104); transform-origin: 355.356px 266.043px;" id="eltirsw67o3b" class="animable"></path>
								<path d="M299.62,294.63a26.3,26.3,0,0,0,2.49,3.19c3.71,4.19,8.33,6.5,13.54,10.75,4.2,3.43,10.72,8.47,16.2,12.78a88.37,88.37,0,0,0,18,10.81c5.29,2.2,8.34,2.61,12.52-5.8,2-4.12,3.79-8.38,5.63-12.6q4.13-9.42,8.14-18.87s9.22-17.13-6.06-27.9c-7.52,3.28-10.39,4.62-13.53,12.85-3.76,9.86-10.25,29.41-10.25,29.41a142.88,142.88,0,0,1-16-9c-1.95-1.25-4.41-2.24-5.68-4.24a27.4,27.4,0,0,1-2.15-4.3c-.56-1.33-.91-2.74-1.54-4a34.64,34.64,0,0,0-4.7-6.46,2.25,2.25,0,0,0-1.21-.91,1.48,1.48,0,0,0-1.4.66c-.71,1-.27,2.06-.08,3.16.22,1.26.44,2.51.65,3.77a32.16,32.16,0,0,1-9.31-10.86,5.85,5.85,0,0,0-.7-1.12,1.63,1.63,0,0,0-1.16-.58c-1.11,0-2.38,1.66-2.54,2.66a16.34,16.34,0,0,1-.41,3.05c-.35.92-1.07,1.62-1.47,2.51a15,15,0,0,0-.94,2.86,10.09,10.09,0,0,0,1,6.31A14.71,14.71,0,0,0,299.62,294.63Z" style="fill: rgb(177, 102, 104); transform-origin: 338.21px 300.251px;" id="elshr9j8142pg" class="animable"></path>
								<path d="M371.11,266.34c-6.47,1.83-11.24,4.18-15.09,13.32s-8.57,24-8.57,24-.4,3.17,6.63,8.42a14.67,14.67,0,0,0,14.1,2.12l7.17-16.41S386.4,276.06,371.11,266.34Z" style="fill: rgb(31, 111, 235); transform-origin: 363.328px 290.736px;" id="elh1hl8at60t" class="animable"></path>
							</g>
							<path d="M409.69,370.94a5.77,5.77,0,0,0,2.81-4.64l1.21-19.16c.09-1.4-.79-2-2-1.35l-8.82,4.92a5.07,5.07,0,0,0-2.3,3.72l-1.22,19.45c-.14,2.11-.52,2.63-1.66,3.65v1.67a11.5,11.5,0,0,0,4.08-3.71Z" style="fill: rgb(38, 50, 56); transform-origin: 405.713px 362.364px;" id="eltwi5fqpry4d" class="animable"></path>
							<path d="M410.88,371.6a5.77,5.77,0,0,0,2.81-4.64l1.21-19.16c.09-1.4-.79-2-2-1.35l-8.82,4.91a5.09,5.09,0,0,0-2.3,3.73l-1.23,19.45a5.73,5.73,0,0,1-2.84,4.66l5.27-3.06Z" style="fill: rgb(55, 71, 79); transform-origin: 406.308px 362.694px;" id="el4s3b27g1frt" class="animable"></path>
							<path d="M428.24,260.25c-.5-.33-2.51-1.71-3-2.05-1.77-1.18-4.32-1.06-7.23.61l-26.06,15c-5.85,3.37-10.94,11.78-11.39,18.78l-4.42,70,3,2.05,47.23-27.26,4.43-70C431,263.93,430,261.43,428.24,260.25Z" style="fill: rgb(31, 111, 235); transform-origin: 403.483px 311.029px;" id="eltel4ztvefs" class="animable"></path>
							<g id="elcysxh3qf3ep">
								<path d="M428.24,260.25c-.5-.33-2.51-1.71-3-2.05-1.77-1.18-4.32-1.06-7.23.61l-26.06,15c-5.85,3.37-10.94,11.78-11.39,18.78l-4.42,70,3,2.05,47.23-27.26,4.43-70C431,263.93,430,261.43,428.24,260.25Z" style="opacity: 0.2; transform-origin: 403.483px 311.029px;" class="animable"></path>
							</g>
							<path d="M428,311.6l2.79-44.15c.22-3.52-.78-6-2.57-7.2-.5-.33-2.51-1.71-3-2.05-1.77-1.18-4.32-1.06-7.23.61l-26.06,15a23.26,23.26,0,0,0-7.84,8.24Z" style="fill: rgb(31, 111, 235); transform-origin: 407.455px 284.509px;" id="elps7bts1dvfs" class="animable"></path>
							<g id="elc7lkitk9x95">
								<path d="M428,311.6l2.79-44.15c.22-3.52-.78-6-2.57-7.2-.5-.33-2.51-1.71-3-2.05-1.77-1.18-4.32-1.06-7.23.61l-26.06,15a23.26,23.26,0,0,0-7.84,8.24Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 407.455px 284.509px;" class="animable"></path>
							</g>
							<path d="M428.24,260.26c-1.77-1.18-4.31-1.07-7.22.61l-26.06,15c-5.85,3.37-10.94,11.77-11.39,18.78l-4.42,70,4.17,2.79,47.23-27.26,4.43-70c.22-3.45-.74-5.91-2.47-7.12C431.79,262.61,429,260.73,428.24,260.26Z" style="fill: rgb(69, 90, 100); transform-origin: 407.081px 313.458px;" id="el4ywv9vpxvv" class="animable"></path>
							<g id="elbau849ojpdq">
								<path d="M387.12,284.12a24.46,24.46,0,0,0-3.55,10.55l-4.42,70,4.17,2.79,47.23-27.26,1.64-25.81Z" style="fill: rgb(55, 71, 79); opacity: 0.3; transform-origin: 405.67px 325.79px;" class="animable"></path>
							</g>
							<path d="M383.32,367.47l4.42-70c.45-7,5.54-15.4,11.39-18.77l26.06-15c5.85-3.37,10.23-.42,9.79,6.58l-4.43,70Z" style="fill: rgb(38, 50, 56); transform-origin: 409.165px 314.888px;" id="elt24tifa3ncl" class="animable"></path>
							<polygon points="336.64 350.2 341.88 350.2 341.88 376.21 336.76 374.61 336.64 350.2" style="fill: rgb(55, 71, 79); transform-origin: 339.26px 363.205px;" id="elamhq9lw8ii6" class="animable"></polygon>
							<path d="M339.3,344.34l27.44,15.85-10.47,6.05L324.84,348.1l6.5-3.75A8,8,0,0,1,339.3,344.34Z" style="fill: rgb(31, 111, 235); transform-origin: 345.79px 354.762px;" id="elbs4nbv08tnj" class="animable"></path>
							<g id="elvabr4z6med">
								<path d="M339.3,344.34l27.44,15.85-10.47,6.05L324.84,348.1l6.5-3.75A8,8,0,0,1,339.3,344.34Z" style="fill: rgb(255, 255, 255); opacity: 0.2; transform-origin: 345.79px 354.762px;" class="animable"></path>
							</g>
							<polygon points="324.84 350.91 324.84 348.1 356.27 366.24 356.27 369.05 324.84 350.91" style="fill: rgb(31, 111, 235); transform-origin: 340.555px 358.575px;" id="elqbxi9ctbjbm" class="animable"></polygon>
							<g id="el49e13ywq9el">
								<polygon points="324.84 350.91 324.84 348.1 356.27 366.24 356.27 369.05 324.84 350.91" style="opacity: 0.3; transform-origin: 340.555px 358.575px;" class="animable"></polygon>
							</g>
							<polygon points="366.74 360.19 366.74 363.01 356.27 369.05 356.27 366.24 366.74 360.19" style="fill: rgb(31, 111, 235); transform-origin: 361.505px 364.62px;" id="el348ytfvqv9v" class="animable"></polygon>
							<g id="el6kq2gfon3ho">
								<polygon points="366.74 360.19 366.74 363.01 356.27 369.05 356.27 366.24 366.74 360.19" style="opacity: 0.15; transform-origin: 361.505px 364.62px;" class="animable"></polygon>
							</g>
						</g>
					</g>
					<g id="freepik--speech-bubble--inject-127" class="animable" style="transform-origin: 255.901px 69.7575px;">
						<g id="freepik--speech-bubble--inject-127" class="animable" style="transform-origin: 255.901px 69.7575px;">
							<g id="freepik--speech-bubble--inject-127" class="animable" style="transform-origin: 255.901px 69.7575px;">
								<path d="M276.38,33.8,240.77,54.39c-.8.46-1.45,1.59-1.45,3.76V102.1a2.69,2.69,0,0,0,1.46,2.41l2.11,1.21a3.24,3.24,0,0,0,2.91,0l35.62-20.59a3.21,3.21,0,0,0,1.45-2.52V37.5A3.2,3.2,0,0,0,281.41,35l-2.12-1.21A3.24,3.24,0,0,0,276.38,33.8Z" style="fill: rgb(31, 111, 235); transform-origin: 261.095px 69.7575px;" id="ele05gsyp7tdi" class="animable"></path>
								<g id="el3o8ik8hlkpu">
									<path d="M276.38,33.8,240.77,54.39c-.8.46-1.45,1.59-1.45,3.76V102.1a2.69,2.69,0,0,0,1.46,2.41l2.11,1.21a3.24,3.24,0,0,0,2.91,0l35.62-20.59a3.21,3.21,0,0,0,1.45-2.52V37.5A3.2,3.2,0,0,0,281.41,35l-2.12-1.21A3.24,3.24,0,0,0,276.38,33.8Z" style="opacity: 0.2; transform-origin: 261.095px 69.7575px;" class="animable"></path>
								</g>
								<path d="M282.85,37.23c-.11-.73-.71-1-1.44-.58l-35.6,20.6a2.9,2.9,0,0,0-1,1.08l-5-2.91a2.83,2.83,0,0,1,1-1l35.62-20.6a3.27,3.27,0,0,1,2.91,0L281.41,35A3.22,3.22,0,0,1,282.85,37.23Z" style="fill: rgb(31, 111, 235); transform-origin: 261.33px 45.9042px;" id="el72b0l2qhuok" class="animable"></path>
								<g id="el3y5kmrpqhny">
									<path d="M282.85,37.23c-.11-.73-.71-1-1.44-.58l-35.6,20.6a2.9,2.9,0,0,0-1,1.08l-5-2.91a2.83,2.83,0,0,1,1-1l35.62-20.6a3.27,3.27,0,0,1,2.91,0L281.41,35A3.22,3.22,0,0,1,282.85,37.23Z" style="fill: rgb(255, 255, 255); opacity: 0.3; transform-origin: 261.33px 45.9042px;" class="animable"></path>
								</g>
								<path d="M234,88c-.91-.54-4.2-2.39-4.42-2.52a1.35,1.35,0,0,1-.29-2.07l10.06-12.74,5,2.92L233.73,85.93A1.37,1.37,0,0,0,234,88Z" style="fill: rgb(31, 111, 235); transform-origin: 236.641px 79.335px;" id="elmye22gz0i3d" class="animable"></path>
								<g id="el1hfi8r45cy9">
									<path d="M234,88c-.91-.54-4.2-2.39-4.42-2.52a1.35,1.35,0,0,1-.29-2.07l10.06-12.74,5,2.92L233.73,85.93A1.37,1.37,0,0,0,234,88Z" style="fill: rgb(255, 255, 255); opacity: 0.3; transform-origin: 236.641px 79.335px;" class="animable"></path>
								</g>
								<path d="M281.42,36.66,245.8,57.25a3.21,3.21,0,0,0-1.45,2.51v13.8L233.72,85.93a1.31,1.31,0,0,0,.83,2.23l9.8.54h0v16.17c0,.93.65,1.3,1.45.84l35.62-20.59a3.21,3.21,0,0,0,1.45-2.52V37.5C282.87,36.57,282.22,36.19,281.42,36.66Z" style="fill: rgb(31, 111, 235); transform-origin: 258.104px 71.1828px;" id="el6dray4vysjn" class="animable"></path>
							</g>
							<g id="freepik--Gear--inject-127" class="animable" style="transform-origin: 263.741px 71.6784px;">
								<path d="M257.52,92.35a.56.56,0,0,1-.42-.18.9.9,0,0,1-.19-.78l.7-4.55a.42.42,0,0,0-.06-.31,5.62,5.62,0,0,1-1.48-.88.14.14,0,0,0-.13,0L252.77,89a.6.6,0,0,1-.55.2.81.81,0,0,1-.59-.5l-1.41-3.06a1.33,1.33,0,0,1,.06-1.26l2.84-4a1.36,1.36,0,0,0,.12-.73,16.38,16.38,0,0,1-.11-2.45.47.47,0,0,0-.07-.28l-2.8-.12a.61.61,0,0,1-.46-.25.93.93,0,0,1-.13-.78l1.16-5.14a1.25,1.25,0,0,1,.74-.93l3.19-.88a.8.8,0,0,0,.3-.37,27.39,27.39,0,0,1,1.44-3,1.09,1.09,0,0,0,0-.7l-1-3.1a1.43,1.43,0,0,1,.17-1.26L259,56.25a.66.66,0,0,1,.56-.25.79.79,0,0,1,.61.38l1.56,2.39a.22.22,0,0,0,.09.09A11.94,11.94,0,0,1,264,57.42a1,1,0,0,0,.31-.48l1.4-4.74a1.14,1.14,0,0,1,.83-.84l3.36-.4a.57.57,0,0,1,.48.18.9.9,0,0,1,.2.79l-.7,4.54a.36.36,0,0,0,.06.31,5.45,5.45,0,0,1,1.47.89.11.11,0,0,0,.13,0l3.17-3.35a.61.61,0,0,1,.56-.19.82.82,0,0,1,.58.49l1.42,3.06a1.36,1.36,0,0,1-.06,1.26l-2.85,4a1.28,1.28,0,0,0-.11.73,17.9,17.9,0,0,1,.11,2.46.49.49,0,0,0,.06.27l2.81.12h0a.59.59,0,0,1,.45.25.9.9,0,0,1,.13.78l-1.15,5.15a1.26,1.26,0,0,1-.75.93l-3.18.88a.75.75,0,0,0-.31.37,27.32,27.32,0,0,1-1.44,3,1.12,1.12,0,0,0,0,.69l1,3.1a1.5,1.5,0,0,1-.16,1.27l-3.32,4.07a.71.71,0,0,1-.56.26.8.8,0,0,1-.61-.38l-1.56-2.4c0-.06-.08-.08-.09-.08a13,13,0,0,1-2.17,1.44,1,1,0,0,0-.31.47l-1.39,4.75A1.13,1.13,0,0,1,261,92l-3.36.4ZM256,84.87a.89.89,0,0,1,.57.21,5.07,5.07,0,0,0,1.28.77,1,1,0,0,1,.51,1.11l-.7,4.54a.38.38,0,0,0,0,.1l3.23-.39a.57.57,0,0,0,.21-.3l1.39-4.74a1.64,1.64,0,0,1,.68-.93,13.51,13.51,0,0,0,2.08-1.37.65.65,0,0,1,.54-.14.85.85,0,0,1,.6.4l1.56,2.4,3.29-4a.77.77,0,0,0,0-.57l-1-3.1a1.8,1.8,0,0,1,.09-1.28,28,28,0,0,0,1.4-2.92,1.44,1.44,0,0,1,.79-.8l3.19-.88a.69.69,0,0,0,.21-.37l1.16-5.15a.72.72,0,0,0,0-.13l-2.79-.14c-.41,0-.71-.45-.69-1a16.39,16.39,0,0,0-.11-2.35,2.08,2.08,0,0,1,.24-1.25l2.85-4a.66.66,0,0,0,0-.52L275.19,55h0l-3.09,3.26a.83.83,0,0,1-1.14,0,4.74,4.74,0,0,0-1.29-.77,1,1,0,0,1-.5-1.11l.7-4.55v-.09l-3.23.38a.61.61,0,0,0-.2.31L265,57.15a1.64,1.64,0,0,1-.69.92,12.42,12.42,0,0,0-2.07,1.38.71.71,0,0,1-.55.14.9.9,0,0,1-.59-.41l-1.57-2.4-3.29,4a.82.82,0,0,0,0,.57l1,3.09a1.83,1.83,0,0,1-.09,1.29,28.8,28.8,0,0,0-1.4,2.91,1.4,1.4,0,0,1-.8.8l-3.18.88a.68.68,0,0,0-.22.38l-1.15,5.15a.17.17,0,0,0,0,.12l2.78.15c.41,0,.71.45.7,1a14.87,14.87,0,0,0,.11,2.34,2.07,2.07,0,0,1-.25,1.25l-2.85,4a.67.67,0,0,0,0,.52l1.42,3.06v0l3.09-3.27A.82.82,0,0,1,256,84.87Z" style="fill: rgb(250, 250, 250); transform-origin: 263.741px 71.6784px;" id="elbub7fwmfidr" class="animable"></path>
							</g>
							<path d="M263.75,71.77a4.72,4.72,0,0,0,2-3.76c0-1.45-.89-2.11-2-1.49a4.73,4.73,0,0,0-2,3.77C261.78,71.74,262.66,72.4,263.75,71.77Zm3.41,1.34c-.2-1.13-1-1.55-2-1l-2.87,1.66a4.8,4.8,0,0,0-2,3.28l-.26,1.79c-.12.79.3,1.23.85.91l5.65-3.26a2.07,2.07,0,0,0,.86-1.9Zm1-5.52a3.38,3.38,0,0,0,1.41-2.7c0-1-.63-1.52-1.41-1.07a3.36,3.36,0,0,0-1.42,2.7C266.78,67.56,267.41,68,268.2,67.59Zm2.45.71a.88.88,0,0,0-1.42-.7l-2.06,1.19a3.24,3.24,0,0,0-1.33,1.9A1.63,1.63,0,0,1,268,72l2.25-1.3a1.52,1.52,0,0,0,.61-1.37Zm-9,4.81a.83.83,0,0,0-1.32-.38l-2.06,1.19a3.46,3.46,0,0,0-1.43,2.36l-.19,1.28c-.08.57.22.89.62.66l2.24-1.29A7.11,7.11,0,0,1,261.65,73.11Zm-2.35-.38A3.39,3.39,0,0,0,260.71,70c0-1-.63-1.52-1.41-1.07a3.43,3.43,0,0,0-1.42,2.71C257.88,72.7,258.52,73.18,259.3,72.73Z" style="fill: rgb(250, 250, 250); transform-origin: 263.732px 71.756px;" id="el1zir1chb8af" class="animable"></path>
						</g>
					</g>
					<defs>
						<filter id="active" height="200%">
							<feMorphology in="SourceAlpha" result="DILATED" operator="dilate" radius="2"></feMorphology>

							<feFlood flood-color="#32DFEC" flood-opacity="1" result="PINK"></feFlood>
							<feComposite in="PINK" in2="DILATED" operator="in" result="OUTLINE"></feComposite>
							<feMerge>
								<feMergeNode in="OUTLINE"></feMergeNode>
								<feMergeNode in="SourceGraphic"></feMergeNode>
							</feMerge>
						</filter>
						<filter id="hover" height="200%">
							<feMorphology in="SourceAlpha" result="DILATED" operator="dilate" radius="2"></feMorphology>
							<feFlood flood-color="currentColor" flood-opacity="0.5" result="PINK"></feFlood>
							<feComposite in="PINK" in2="DILATED" operator="in" result="OUTLINE"></feComposite>
							<feMerge>
								<feMergeNode in="OUTLINE"></feMergeNode>
								<feMergeNode in="SourceGraphic"></feMergeNode>
							</feMerge>
							<feColorMatrix type="matrix" values="0   0   0   0   0                0   1   0   0   0                0   0   0   0   0                0   0   0   1   0 "></feColorMatrix>
						</filter>
					</defs>
				</svg>
			</div>
		</div>
	</div>
	<!-- v-image-content @::end  -->

	<!--begin::Authentication - Sign-up -->
	<div class="col-lg-6 d-flex flex-column flex-lg-row flex-column-fluid">
		<!--begin::Body-->
		<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 w-lg-75 p-10 order-2 order-lg-1">
			<!--begin::Form-->
			<div class="d-flex flex-center flex-column flex-lg-row-fluid">
				<!--begin::Wrapper-->
				<div class="w-100 w-lg-500px">
					<!--begin::Form-->
					<form class="v-auth-form form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST" action="#">
						<!--begin::Heading-->
						<div class="text-start mb-10">
							<!--begin::Title-->
							<img alt="Logo" src="./assets/media/logos/demo-logo.svg" class="h-60px me-3" />
							<!--end::Title-->
						</div>
						<!--begin::Heading-->
						<div class="mb-6 text-start v-auth-page-container">
							<h1 class="fw-bolder v-auth-page-title mb-1">Welcome to StellarShift!</h1>
							<!-- <span class="v-subtext">Let's get you started, shall we 😃</span> -->
							<span class="v-subtext">Create an account to seamlessly manage your workforce!</span>
						</div>
						<!--begin::Login options-->
						<div class="row g-3">
							<!--begin::Col-->
							<div class="col-12">
								<!--begin::Google link=-->
								<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
									<img alt="Google logo" src="./assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3" />Sign in
									with Google</a>
								<!--end::Google link=-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Login options-->
						<!--begin::Separator-->
						<div class="separator separator-content my-10">
							<span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
						</div>
						<!--end::Separator-->
						<div class="fv-row mb-6">
							<!--begin::Email-->
							<input type="text" aria-label="user-fname" placeholder="First Name" name="fname" v-model="firstname" autocomplete="off" class="form-control bg-transparent" />
							<!--end::Email-->
						</div>
						<div class="fv-row mb-6">
							<!--begin::Email-->
							<input type="text" aria-label="user-lname" placeholder="Last Name" name="lname" v-model="lastname" autocomplete="off" class="form-control bg-transparent" />
							<!--end::Email-->
						</div>
						<!--begin::Input group=-->
						<div class="fv-row mb-6">
							<!--begin::Email-->
							<input type="text" aria-label="user-email" placeholder="Email" name="email" autocomplete="off" v-model="email" class="form-control bg-transparent" />
							<!--end::Email-->
						</div>
						<!--begin::Input group-->
						<div class="fv-row mb-6" data-kt-password-meter="true">
							<!--begin::Wrapper-->
							<div class="mb-1">
								<!--begin::Input wrapper-->
								<div class="position-relative mb-3 v-password-input">
									<input class="form-control bg-transparent" type="password" placeholder="Password" name="password" v-model="password" autocomplete="off" @keyup="password_meter" />
									<button type="button" class="v-toggle-password-visibility">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
											<path fill="currentColor" d="M9.75 12a2.25 2.25 0 1 1 4.5 0a2.25 2.25 0 0 1-4.5 0" />
											<path fill="currentColor" fill-rule="evenodd" d="M2 12c0 1.64.425 2.191 1.275 3.296C4.972 17.5 7.818 20 12 20c4.182 0 7.028-2.5 8.725-4.704C21.575 14.192 22 13.639 22 12c0-1.64-.425-2.191-1.275-3.296C19.028 6.5 16.182 4 12 4C7.818 4 4.972 6.5 3.275 8.704C2.425 9.81 2 10.361 2 12m10-3.75a3.75 3.75 0 1 0 0 7.5a3.75 3.75 0 0 0 0-7.5" clip-rule="evenodd" />
										</svg>
									</button>
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
						</div>
						<!--end::Input group=-->
						<!--end::Input group=-->
						<div class="fv-row mb-6 v-password-input">
							<!--begin::Repeat Password-->
							<input placeholder="Repeat Password" name="confirm-password" type="password" v-model="confirm_password" autocomplete="off" class="form-control bg-transparent" />
							<!--end::Repeat Password-->
							<button type="button" class="v-toggle-password-visibility">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
									<path fill="currentColor" d="M9.75 12a2.25 2.25 0 1 1 4.5 0a2.25 2.25 0 0 1-4.5 0" />
									<path fill="currentColor" fill-rule="evenodd" d="M2 12c0 1.64.425 2.191 1.275 3.296C4.972 17.5 7.818 20 12 20c4.182 0 7.028-2.5 8.725-4.704C21.575 14.192 22 13.639 22 12c0-1.64-.425-2.191-1.275-3.296C19.028 6.5 16.182 4 12 4C7.818 4 4.972 6.5 3.275 8.704C2.425 9.81 2 10.361 2 12m10-3.75a3.75 3.75 0 1 0 0 7.5a3.75 3.75 0 0 0 0-7.5" clip-rule="evenodd" />
								</svg>
							</button>
						</div>
						<!--end::Input group=-->
						<!--begin::Accept-->
						<div class="fv-row mb-6">
							<label class="form-check form-check-inline">
								<input class="form-check-input" aria-label="terms-policy" type="checkbox" name="toc" value="1" />
								<span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I Accept the <a href="#" class="ms-1 link-primary">Terms & Conditions</a></span>
							</label>
						</div>
						<!--end::Accept-->
						<!--begin::Submit button-->
						<div class="d-grid mb-10">
							<button type="submit" id="kt_sign_up_submit" @click.prevent="register" class="btn btn3 v-action">
								<!--begin::Indicator label-->
								<span class="indicator-label">Create Account</span>
								<!--end::Indicator label-->
							</button>
						</div>
						<!--end::Submit button-->
						<!--begin::Sign up-->
						<div class="text-gray-500 text-center fw-semibold fs-6">
							Already have an Account?
							<a href="./sign-in.php" class="link-primary fw-semibold">Sign in</a>
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
	<!--end::Authentication - Sign-up-->
</div>
<!--end::Root-->

<?php include './auth_footer.php'; ?>
<script>
	const passwordInputs = document.querySelectorAll(".form .v-password-input");

	const passVisibleIcon = `
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path fill="currentColor" d="M9.75 12a2.25 2.25 0 1 1 4.5 0a2.25 2.25 0 0 1-4.5 0" />
					<path
						fill="currentColor"
						fill-rule="evenodd"
						d="M2 12c0 1.64.425 2.191 1.275 3.296C4.972 17.5 7.818 20 12 20c4.182 0 7.028-2.5 8.725-4.704C21.575 14.192 22 13.639 22 12c0-1.64-.425-2.191-1.275-3.296C19.028 6.5 16.182 4 12 4C7.818 4 4.972 6.5 3.275 8.704C2.425 9.81 2 10.361 2 12m10-3.75a3.75 3.75 0 1 0 0 7.5a3.75 3.75 0 0 0 0-7.5"
						clip-rule="evenodd" />
				</svg>
			`;

	const passInVisibleIcon = `
			    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
			        <g fill="currentColor">
			            <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38a1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194zm4.472 4.47l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092A4 4 0 0 0 7.752 6.69" clip-rule="evenodd"/>
			            <path d="m10.748 13.93l2.523 2.523a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678"/>
			        </g>
			    </svg>
			`;

	if (passwordInputs.length) {
		passwordInputs.forEach((passWithToggle) => {
			const hideShowButton = passWithToggle.querySelector(".v-toggle-password-visibility");
			const passInput = passWithToggle.querySelector(".form-control");
			if (hideShowButton && passInput) {
				passInput.addEventListener("input", function() {
					const typeOfInput = passInput.type;
					if (typeOfInput === 'text') {
						hideShowButton.innerHTML = passInVisibleIcon;
					} else {
						hideShowButton.innerHTML = passVisibleIcon;

					}
				})
				hideShowButton.addEventListener("click", function() {
					const typeOfInput = passInput.type;
					if (typeOfInput === "password") {
						passInput.type = "text";
						hideShowButton.innerHTML = passInVisibleIcon;
					} else {
						passInput.type = "password";
						hideShowButton.innerHTML = passVisibleIcon;
					}
				});
			}
		});
	}
</script>