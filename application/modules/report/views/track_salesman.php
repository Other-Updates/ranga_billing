<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Salesman Tracking</h3>
            </div>
            <div class="col-md-6">                
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Salesman Tracking</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="card">
      <div class="card-body">
          <!-- <form id="reference_form"> -->
              <div class="row g-4">
                  <div class="col-md-3">
                      <label class="form-label" for="validationCustom04">Salesman</label>
                      <select class="form-select salesman" name ="type" id="validationCustom04" >
                          <option selected="" value="">Choose...</option>
                          <?php foreach($salesman as $sale){ ?>
                          <option value="<?php echo $sale['iUserId'] ?>"><?php echo $sale['vName'] ?></option>
                          <?php } ?>
                      </select>
                      <div class="invalid-feedback">Please select a valid state.</div>
                  </div>
                  <div class="col-md-3">
                      <label class="form-label" for="validationCustom04">Date</label>
                      <input class="form-control date_data datepicker-here" id="validationCustom03" value="<?php echo date('d/m/Y'); ?>" type="text" name="name" placeholder="" >
                      <div class="invalid-feedback">Please select a valid state.</div>
                  </div>
                  <div class="col-md-3">
                      <label class="form-label mnone col-md-12"><br></label>
                      <button class="btn btn-primary location" id="submit" type="button"><i class="icofont icofont-ui-search"></i></button>
                      <button class="btn btn-danger reset" type="submit"><i class="icofont icofont-refresh"></i></button>
                      <button class="btn btn-succuss"><i class="fas fa-file-export"></i></button>
                  </div>
              </div><br>
              <div class="row g-4">
                  <div id="map_new" style="width:5000px;height:500px;"></div>
                  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiQBQX1WUXbTIwOPS9JxoZXxxO-0H1qC8&callback=initMap&v=weekly"async></script> -->
                   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiQBQX1WUXbTIwOPS9JxoZXxxO-0H1qC8&v=weekly"async></script>
              </div>
      </div>
  </div>
  
   

  
</div>
<script>
  $(document).on('click','#submit',function(){
    var salesman = $('.salesman').val();
    var date = $('.date_data').val();
    $.ajax({
          type: "POST",
          url: "<?php echo base_url() ?>report/attendance_report/get_salesman_location",
          data:{salesman:salesman,date:date},
          success: function(data){
            console.log(data);
              data = JSON.parse(data);
              initMap(data);
              // $('.add_address').val(data.vAddress);
          }
      });
  })
  let map;
    function initMap(latLongData = '') {
        console.log(latLongData);
        map = new google.maps.Map(document.getElementById("map_new"), {
          center: { lat: -34.397, lng: 150.644 },
          zoom: 8,
        });
        infoWindow = new google.maps.InfoWindow();

        const locationButton = document.createElement("button");

        // locationButton.textContent = "Pan to Current Location";
        locationButton.classList.add("custom-map-control-button");
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        // locationButton.addEventListener("click", () => {
          // Try HTML5 geolocation.
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                const pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
                };

                // infoWindow.setPosition(pos);
                // infoWindow.setContent("Location found.");
                infoWindow.open(map);
                map.setCenter(pos);
              },
              () => {
                // handleLocationError(true, infoWindow, map.getCenter());
              }
            );
          } else {
            // Browser doesn't support Geolocation
            // handleLocationError(false, infoWindow, map.getCenter());
          }
        // });
        
      const flightPath = new google.maps.Polyline({
        path: latLongData,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2,
      });
      var last_location_count = latLongData.length - 1;
      var bounds = new google.maps.LatLngBounds();
      if(latLongData!=''){
      var first_point = new google.maps.LatLng(latLongData[0]['lat'], latLongData[0]['lng']);
      var last_point = new google.maps.LatLng(latLongData[last_location_count]['lat'], latLongData[last_location_count]['lng']);
      }
      else{
      var first_point = new google.maps.LatLng('8.353286612629022', '77.58025642963817');
      var last_point = new google.maps.LatLng('8.353286612629022', '77.57830760823511');
      }
      bounds.extend(first_point);
      bounds.extend(last_point);
      map.fitBounds(bounds);
      flightPath.setMap(map);
      

    }
    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
      infoWindow.setPosition(pos);
      infoWindow.setContent(
      browserHasGeolocation
        ? "Error: The Geolocation service failed."
        : "Error: Your browser doesn't support geolocation."
      );
      infoWindow.open(map);
    }
</script>