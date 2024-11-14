<! DOCTYPE html>
<html>
<head>
  <title></title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/dashboard.css') }}">
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
  <div>
    <div class=" px-12 mb-8 mt-8">
      <div class="text-center" style="display:flex;">
        <div style="display:flex;">
        <div class="text-center">
        <div class="image left-section" style="float:left;">
          <img src="{{ asset('/images/logo.png') }}" alt="Eyewitness" style="height: 70px;margin: 10px 10px 10px 0px;">
        </div>
        <div class="image right-section"  style="float:left">
          <div class="heading" style=" text-align: left; font-size: 25px; font-weight: bold;letter-spacing: 0px; margin-top:0px; color: #302369; text-transform: uppercase;opacity: 1;font-family: Poppins;">
            Eyewitness
          </div>
          <div class="website" style="text-align: left;font-size: 20px;  letter-spacing: 0px; color: #515151;opacity: 1;">
            8012 South Ashland Ave
          </div>
          <div class="website"  style=" text-align: left;font-size: 20px; letter-spacing: 0px; color: #515151; opacity: 1; ">
            Chicago, IL 60620
          </div>
          <div class="website" style=" text-align: left; font-size: 20px; letter-spacing: 0px; color: #515151;opacity: 1;">
           (888)-575-5598
          </div>
          <div class="email" style=" text-align: left; font-size: 20px;letter-spacing: 0px;color: #515151;opacity: 1;">
            info@swssgroup.com
          </div>
        </div>
      </div>
      <div style=" text-align: right;float:right; width:100%; margin-top:30px; font-size: 22px; letter-spacing: 0px;color: #302369;opacity: 1;">
            License #: 122-001312
      </div>

      </div>

      </div>
</div>
<div style="display:flex;padding-left:200px;width:50%;margin-top: 0rem;text-align: center;font: normal normal 600 26px Source Sans Pro;letter-spacing: 0px;color: #3B86FF;opacity: 1;color:#3B86FF;"> Incident Report</div>
<div class="lower_section" style="margin-top:0rem;width: 100%;">

     @foreach($main_array as $key => $getData)
     
   <div>
     

       
      <table class="w-full client-hourly-report" style="margin-top: 20px;">
          <thead>
            <tr  style="background-color:#AD9E58;height: 66px; border-radius: 5px 5px 0px 0px; margin-bottom: 45px;" >
            <th style="padding-left: 20px; color: #ffffff !important; font-family: Poppins;
                      font-size: 20px;padding-left:15px; class="text-center pl-3">Date/Time</th>
            <th style="padding-left: 20px; color: #ffffff !important; font-family: Poppins;
                       font-size: 20px; padding-left:15px; class="text-center">Incident Type </th>
            <th style="padding-left: 20px; color: #ffffff !important; font-family: Poppins;
                       font-size: 20px; padding-left:15px; class="text-center">Officer</th>
            <th style="padding-left: 20px; color: #ffffff !important; font-family: Poppins;
                       font-size: 20px; padding-left:15px; class="text-center">Location</th>
            <th style="padding-left: 20px; color: #ffffff !important; font-family: Poppins;
                       font-size: 20px;padding-left:15px; class="text-center">Description</th>

            </tr>
          </thead> 
          <tbody>
              @foreach($getData['incident'] as $key => $reportdata)
              <tr style="background:#ffffff !important;border-bottom:13px solid rgb(250 250 250);">
                <td  style="text-align:center;border-left:2px solid #2C1977;border-bottom:1px solid #000000;">{{ $reportdata['date']}} {{  $reportdata['time'] }}</td>

                <td  style="border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important; border-bottom:13px solid rgb(250 250 250);">{{  $reportdata['sub_report']['report_type_name'] }}</td>

                <td style="border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important; border-bottom:13px solid rgb(250 250 250);">{{ $reportdata['employee'][0]['firstname'] }} {{ $reportdata['employee'][0]['lastname'] }} {{ $reportdata['employee'][0]['phone'] }}</td>
                <td style="border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important; border-bottom:13px solid rgb(250 250 250);">{{  $reportdata['location'] }}</td>
                <td  style="border-top: 1px solid #828282;padding-left: 22px;background:#ffffff !important; border-bottom:13px solid rgb(250 250 250);"> 
                   <img style="width:200px;max-width: 200px;" src="http://3.133.223.72/{{ $reportdata['report_image'] }}"/><br> {{  $reportdata['description'] }}
                </td>
              </tr>
              @endforeach
          </tbody>
        </table>
         
        </div>

       @endforeach

      </div>
</div>
</body>
</html>