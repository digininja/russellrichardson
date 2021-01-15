function initMap() {
  if(document.getElementById('map') === null){
    return;
  }
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: {lat: 53.397836, lng: -1.429798},
    styles: [{
        "featureType": "landscape",
        "stylers": [{
            "hue": "#FFBB00"
          },
          {
            "saturation": 43.400000000000006
          },
          {
            "lightness": 37.599999999999994
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "road.highway",
        "stylers": [{
            "hue": "#FFC200"
          },
          {
            "saturation": -61.8
          },
          {
            "lightness": 45.599999999999994
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "road.arterial",
        "stylers": [{
            "hue": "#FF0300"
          },
          {
            "saturation": -100
          },
          {
            "lightness": 51.19999999999999
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "road.local",
        "stylers": [{
            "hue": "#FF0300"
          },
          {
            "saturation": -100
          },
          {
            "lightness": 52
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "water",
        "stylers": [{
            "hue": "#0078FF"
          },
          {
            "saturation": -13.200000000000003
          },
          {
            "lightness": 2.4000000000000057
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "poi",
        "stylers": [{
            "hue": "#00FF6A"
          },
          {
            "saturation": -1.0989010989011234
          },
          {
            "lightness": 11.200000000000017
          },
          {
            "gamma": 1
          }
        ]
      }
    ]
  });

  var pin = {
      path: 'M6.5,0A6.5,6.5,0,0,0,0,6.5,6.69,6.69,0,0,0,.18,8,6.85,6.85,0,0,0,.5,9l.37.73L6.5,20,12.13,9.73A6.24,6.24,0,0,0,12.5,9a6.85,6.85,0,0,0,.32-1A6.69,6.69,0,0,0,13,6.5,6.5,6.5,0,0,0,6.5,0Zm0,10A3.49,3.49,0,0,1,3.35,8,3.45,3.45,0,0,1,3,6.5a3.5,3.5,0,0,1,7,0A3.45,3.45,0,0,1,9.65,8,3.49,3.49,0,0,1,6.5,10Z',
      fillColor: '#8cd600',
      fillOpacity: 1,
      strokeWeight: 0,
      scale: 2,
      anchor: new google.maps.Point(5, 15),
    }

  var marker = new google.maps.Marker({
    position: map.getCenter(),
    icon: pin,
    map: map
  });
}
