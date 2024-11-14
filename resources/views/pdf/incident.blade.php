
<! DOCTYPE html>
<html>
<head>
	<title></title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/dashboard.css') }}">
    <style>
            .section1 .reporttype{
                top: 0px;
                text-align: left;
                font: normal normal normal 19px Montserrat;
                letter-spacing: 0px;
                color: #000000;
				font-weight: 700;
            }
            .reportypevalue span{
                    text-align: left;
                    font: normal normal normal 19px Montserrat;
                    letter-spacing: 0px;
                    color: #302369;
					font-weight: 500;
                    text-transform: capitalize;
            }
		
    </style>
</head>
<body>
<div class=" px-4 mb-8 mt-8">
<div class="text-center" style="display:flex;">
        <div class="image left-section" style="width:100%;display:block;padding-left: 1.25rem;
    padding-right: 1.25rem;">
          <img src="{{ asset('/images/logo.png') }}" alt="Eyewitness" style="height: 70px;margin: 10px 20px 10px 0px;">
        </div>
        
      </div>
			<div class="upper_section px-3">
				<div class="section1 flex mb-3">
					<span class="reporttype">Report type: </span>
					<span class="reportypevalue ml-2">
						<span>
                        {{ $title }}
							</span>
						</span>
				</div>
				<div class="section1 mb-3">
					<span class="flex">
					<span class="reporttype">Officer Name: </span>
					<span class="reportypevalue ml-2">
						<span>
                        {{ $officer_name}}	
						</span>
					</span>
					</span>
				</div>
				<div class="section1 mb-3">
					<span class="flex">
					<span class="reporttype">ID No: </span>
					<span class="reportypevalue ml-2">
						<span>
                        {{ $id }}
						</span>
					</span>
					</span>
				</div>
				<div class="section1 mb-3">
					<span class="flex">
					<span class="reporttype">Report Type: </span>
					<span class="reportypevalue ml-2">
						<span>
                        {{ $report_type_name}}
						</span>
					</span>
					</span>
					
				</div>
				<div class="section1 mb-3">
					<span class="flex">
					<span class="reporttype">Description: </span>
					<span class="reportypevalue ml-2">
						<span>
                        {{ $description }}
						</span>
					</span>
					</span>
					
				</div>

			</div>
</div>
</body>
</html>