var isHomepage = false;

jQuery( document ).ready( function ( $ ) {

    $(".google-iframe-container").find('iframe').css({'width':'100%', 'height':'315px'});
    $(".youtube-iframe-container").find('iframe').css({'width':'100%', 'height':'315px'});

    $( "a[href='#btngotop']" ).click( function () {
        $( "html, body" ).animate( {
            scrollTop: 0
        }, "slow" );
        return false;
    } );

    // $( ".owl-carousel" ).owlCarousel( {
    //     loop: false,
    //     margin: 10,
    //     items: 4,
    //     pagination: false
    // } );

    calculateTowerBannerExposure();
    $( window ).on( 'resize', calculateTowerBannerExposure );

    // Responsive Menu Events
    $(".menu-toggle").click(function () {
        $(".main-menu-wrapper").slideToggle();
        $(".menu-toggle").toggleClass('opened');
        return false;
    });
    $(".hide-mobile-menu").click(function () {
        $(".main-menu-wrapper").slideToggle();
        $(".menu-toggle").toggleClass('opened');
        return false;
    });
    $(window).resize(function () {
        if ($(".menu-toggle").hasClass("opened")) {
            $(".main-menu-wrapper").css("display", "block");
        } else {
            $(".menu-toggle").css("display", "none");
        }
    });
} );

/**
 * Calculates the precise position of left and right tower banners.
 */
var calculateTowerBannerExposure = function () {
    var currWidth = $( window ).width();
    if ( currWidth > 1420 ) {
        if ( isHomepage ) {
            $( '#banner-left' ).css( 'top', '650px' );
            $( '#banner-right' ).css( 'top', '650px' );
        }
        $( '#banner-left' ).css( 'display', 'inherit' );
        $( '#banner-left' ).css( 'left', (((currWidth - 1170) / 2) - 120) + 'px' );
        $( '#banner-right' ).css( 'display', 'inherit' );
        $( '#banner-right' ).css( 'left', (1170 + ((currWidth - 1170) / 2)) + 'px' );
    } else {
        $( '#banner-left, #banner-right' ).css( 'display', 'none' );
    }
};

/**
 * Shows or hides company-related fields on the screen.
 */
var toggleCompanyUserFields = function () {
    var userTypeId = $( '#user_type_id' ).val();
    if ( userTypeId == 1 ) {
        $( '#firmflds' ).css( 'display', 'none' );
        $( '#director' ).attr( 'disabled', true );
        $( '#vat_number' ).attr( 'disabled', true );
        $( '#company_address' ).attr( 'disabled', true );
    } else {
        $( '#firmflds' ).css( 'display', 'inherit' );
        $( '#director' ).attr( 'disabled', false );
        $( '#vat_number' ).attr( 'disabled', false );
        $( '#company_address' ).attr( 'disabled', false );
    }
};

var map;
var geocoder;
var markers = [];
var markerImage;
var drawingMode = false;
var drawingAllowed = false;
var polylines = [];
var polylineCoordinates = [];
var mapSearchObjects = [];
var firstLatLng, lastLatLng, maxLat, maxLng, minLat, minLng;
var markerInfoWindow;

/**
 * Picks up coordinates after drag ends for a pin.
 */
dragendHandleEvent = function ( event ) {
    $( '#lat' ).val( event.latLng.lat() );
    $( '#lng' ).val( event.latLng.lng() );
};

/**
 * Initializes the map.
 */
var initMap = function () {

    var myLatLng = {
        lat: 42.72,
        lng: 25.17
    };
    var zoom = 8;

    var address = 'България';
    var city = $( '#city_id' ).find( 'option:selected' ).text();
    var neighbourhood = ($( '#neighbourhood_id' ).val() ) ? $( '#neighbourhood_id' ).find( 'option:selected' ).text() : '';
    var street = $( '#street' ).val();

    if ( (city) && (city != '') ) {
        address += ', ' + city;
        if ( (neighbourhood) && (neighbourhood != '') ) {
            address += ', ' + neighbourhood;
        }
        if ( (street) && (street != '') ) {
            address += ', ' + street;
        }
        console.log( 'address', address );

        geocoder = new google.maps.Geocoder();
        geocoder.geocode( {
            'address': address
        }, function ( results, status ) {
            if ( status == google.maps.GeocoderStatus.OK ) {
                var myOptions = {
                    zoom: 15,
                    center: results[ 0 ].geometry.location,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false
                };
                map = new google.maps.Map( document.getElementById( 'map' ), myOptions );

                var mapMarker = new google.maps.Marker( {
                    map: map,
                    position: results[ 0 ].geometry.location,
                    draggable: true
                } );

                mapMarker.addListener( 'dragend', dragendHandleEvent );

                $( '#lat' ).val( results[ 0 ].geometry.location.lat() );
                $( '#lng' ).val( results[ 0 ].geometry.location.lng() );
            }
        } );
    } else {
        map = new google.maps.Map( document.getElementById( 'map' ), {
            zoom: zoom,
            center: myLatLng,
            scrollwheel: false
        } );
    }
};

/**
 * Initializes the map.
 */
var initMapCoordinates = function () {
    var lat = $( '#lat' ).val();
    var lng = $( '#lng' ).val();
    var myLatLng = {
        lat: lat * 1,
        lng: lng * 1
    };
    map = new google.maps.Map( document.getElementById( 'map' ), {
        zoom: 15,
        center: myLatLng,
        scrollwheel: false
    } );

    var mapMarker = new google.maps.Marker( {
        map: map,
        position: new google.maps.LatLng( lat, lng ),
        draggable: true
    } );

    mapMarker.addListener( 'dragend', dragendHandleEvent );
};

/**
 * Initializes the data picker.
 *
 * @param elementId
 * @param daysSkip
 */
initDatePicker = function ( elementId, daysSkip ) {
    if ( daysSkip == false ) {
        daysSkip = 0;
    }
    var defaultDate = new Date();
    var startDate = new Date();

    // Prevent late check-ins.
    if (defaultDate.getHours() > 13) {
        daysSkip += 1;
    }

    // Prevents assignments on Sat and Sun.
    if (defaultDate.getDay() == 5) {
        daysSkip += 2;
    } else if (defaultDate.getDay() == 6) {
        daysSkip += 1;
    }

    // Sets default start and selected values.
    defaultDate.setHours( 13, 0, 0, 0 );
    startDate.setHours( 0, 0, 0, 0 );
    defaultDate.setDate( defaultDate.getDate() + daysSkip );
    startDate.setDate( startDate.getDate() + daysSkip );

    $( elementId ).datetimepicker( {
        inline: true,
        value: defaultDate,
        dayOfWeekStart: 1,
        minDate: startDate,
        beforeShowDay: function ( date ) {
            var day = date.getDay();
            return [ (day != 0), '' ];
        },
        allowTimes: [
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30',
            '17:00',
            '17:30',
            '18:00',
            '18:30',
            '19:00',
            '19:30',
            '20:00'
        ]
    } );
};

calendarDateTimePicker = function ( elementId, defaultDate ) {
    if ( defaultDate == undefined ) {
        var defaultDate = new Date();
    }
    else {
        var defaultDate = defaultDate;
    }

    $( elementId ).datetimepicker( {
        inline: true,
        value: defaultDate,
        dayOfWeekStart: 1
    } );
};

photographerDateTimePicker = function ( elementId, defaultDate ) {
    if ( defaultDate == undefined ) {
        var defaultDate = new Date();
    }
    else {
        var defaultDate = defaultDate;
    }

    $( elementId ).datetimepicker( {
        inline: false,
        value: defaultDate,
        dayOfWeekStart: 1
    } );
};


TransactionsDateTimePicker = function ( elementId, defaultDate) {        
    if ( defaultDate == undefined ) {
        var defaultDate = new Date();
    }
    else {
        var defaultDate = defaultDate;
    }

    $( elementId ).datetimepicker( {
        inline: true,
        value: defaultDate,
        dayOfWeekStart: 1,
        format:'Y-m-d',
        timepicker: false,
    } );
};
/**
 * Initializes the map to search within.
 */
initSearchMap = function () {

    markerImage = '/img/pin_ogledi.png';

    markerInfoWindow = new google.maps.InfoWindow( {
        maxWidth: 320
    } );

    var address = 'България';
    var city = $( '#city_id' ).find( 'option:selected' ).text();
    var neighbourhood = $( '#neighbourhood_id' ).find( 'option' );

    if ( (city) && (city != '') ) {
        address += ', ' + city;
        if ( (neighbourhood) && (neighbourhood.length == 1) ) {
                address += ', ' + neighbourhood[ 0 ].text;
        }
        console.log( 'address', address );

        geocoder = new google.maps.Geocoder();
        geocoder.geocode( {
            'address': address
        }, function ( results, status ) {
            if ( status == google.maps.GeocoderStatus.OK ) {
                var myOptions = {
                    zoom: (neighbourhood.length == 1) ? 15 : 13,
                    center: results[ 0 ].geometry.location,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    draggable: true
                };
                map = new google.maps.Map( document.getElementById( 'map' ), myOptions );

                map.controls[ google.maps.ControlPosition.TOP_CENTER ].push( generateHandControlButton() );
                map.controls[ google.maps.ControlPosition.TOP_CENTER ].push( clearHandControlButton() );
                generatePropertyMarkers();

                google.maps.event.addListener( map, 'mousedown', function ( event ) {
                    if ( drawingAllowed ) {
                        toggleDrawingMode();
                        clearAllMarkers();
                        clearAllPolylines();
                        clearAllMapSearchObjects();
                        firstLatLng = event.latLng;
                        lastLatLng = event.latLng;
                        polylineCoordinates.push( { lat: event.latLng.lat(), lng: event.latLng.lng() } );
                        setRectangleBounds( event.latLng.lat(), event.latLng.lng() );
                    }
                } );

                google.maps.event.addListener( map, 'mousemove', function ( event ) {
                    if ( drawingMode ) {
                        drawPolyline( event.latLng );
                        lastLatLng = event.latLng;
                        polylineCoordinates.push( { lat: event.latLng.lat(), lng: event.latLng.lng() } );
                        setRectangleBounds( event.latLng.lat(), event.latLng.lng() );
                    }
                } );
            } else {

            }
        } );

    } else {
        console.log( 'NO SEARCH NO MAP?' );
    }
};

/**
 * Turns on and off drawing mode.
 */
var toggleDrawingMode = function () {
    drawingMode = !drawingMode;
    map.setOptions( {
        draggable: !drawingMode
    } );
};

/**
 * Draws a polyline, based on last and current coordinates.
 *
 * @param toLatLng
 */
var drawPolyline = function ( toLatLng ) {

    // specify the polyline options
    var polyline = new google.maps.Polyline( {
        path: [ lastLatLng, toLatLng ],
        strokeColor: "#777777",
        strokeOpacity: 0.8,
        strokeWeight: 3
    } );

    // add the polyline to the map
    polyline.setMap( map );

    // push the polyline to our global array
    polylines.push( polyline );

    google.maps.event.addListener( polyline, 'mouseup', function ( event ) {
        toggleDrawingMode();
        lastLatLng = firstLatLng;
        drawPolyline( event.latLng );
        polylineCoordinates.push( { lat: event.latLng.lat(), lng: event.latLng.lng() } );
        setRectangleBounds( event.latLng.lat(), event.latLng.lng() );

        var mapObject = new google.maps.Polygon( {
            paths: polylineCoordinates,
            strokeColor: '#777777',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#BBBBBB',
            fillOpacity: 0.35
        } );
        mapObject.setMap( map );
        mapSearchObjects.push( mapObject );

        generatePropertyMarkers();

        drawingAllowed = !drawingAllowed;
        map.setOptions( {
            draggableCursor: null
        } );

        $( '#region-draw-button' ).css( 'background-color', '#FFF' ).css( 'color', '#777' );

    } );
};

/**
 * Clears all markers on the screen.
 */
var clearAllMarkers = function () {
    for ( i = 0; i < markers.length; i++ ) {
        markers[ i ].setMap( null );
    }
    markers = [];
};

/**
 * Clears all lines on the screen.
 */
var clearAllPolylines = function () {
    for ( i = 0; i < polylines.length; i++ ) {
        polylines[ i ].setMap( null );
    }
    polylines = [];
    polylineCoordinates = [];
    maxLat = null;
    maxLng = null;
    minLat = null;
    minLng = null;
};

/**
 * Clears all map objects on the screen.
 */
var clearAllMapSearchObjects = function () {
    for ( i = 0; i < mapSearchObjects.length; i++ ) {
        mapSearchObjects[ i ].setMap( null );
    }
    mapSearchObjects = [];
};

/**
 * Sets rectangle borders of the drawing.
 *
 * @param thisLat
 * @param thisLng
 */
var setRectangleBounds = function ( thisLat, thisLng ) {
    if ( (maxLat === null) || (maxLat < thisLat) ) {
        maxLat = thisLat;
    }
    if ( (minLat === null) || (minLat > thisLat) ) {
        minLat = thisLat;
    }
    if ( (maxLng === null) || (maxLng < thisLng) ) {
        maxLng = thisLng;
    }
    if ( (minLng === null) || (minLng > thisLng) ) {
        minLng = thisLng;
    }
};

/**
 * Clear whatever has been drawn on the search map.
 *
 * @returns {Element}
 */
var clearHandControlButton = function () {
    var containerDiv = document.createElement( 'div' );
    containerDiv.style.backgroundColor = '#FFF';
    containerDiv.style.color = '#777';
    containerDiv.style.border = '2px solid #777';
    containerDiv.style.borderRadius = '3px';
    containerDiv.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    containerDiv.style.cursor = 'pointer';
    containerDiv.style.marginBottom = '22px';
    containerDiv.style.padding = '5px';
    containerDiv.style.textAlign = 'center';
    
    containerDiv.title = window.texts && window.texts.clear ? window.texts.clear : 'ИЗЧИСТИ';
    containerDiv.innerHTML = '<span class="fa fa-remove"></span>&nbsp;' + containerDiv.title;
    containerDiv.index = 1;
    containerDiv.id = 'region-clear-button';

    containerDiv.addEventListener( 'click', function () {
        clearAllMarkers();
        clearAllPolylines();
        clearAllMapSearchObjects();
    } );

    return containerDiv;
};

/**
 * Generates the drawing and hand button, placed on the map.
 * Toggles drawing or navigating modes.
 *
 * @returns {Element}
 */
var generateHandControlButton = function () {
    var containerDiv = document.createElement( 'div' );
    containerDiv.style.backgroundColor = '#FFF';
    containerDiv.style.color = '#777';
    containerDiv.style.border = '2px solid #777';
    containerDiv.style.borderRadius = '3px';
    containerDiv.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    containerDiv.style.cursor = 'pointer';
    containerDiv.style.marginBottom = '22px';
    containerDiv.style.padding = '5px';
    containerDiv.style.textAlign = 'center';
    //containerDiv.className = 'fa fa-pencil';

    containerDiv.title = window.texts && window.texts.selectRegion ? window.texts.selectRegion : 'ОГРАДИ РЕГИОН';
    containerDiv.innerHTML = '<span class="fa fa-pencil"></span>&nbsp;' + containerDiv.title;
    containerDiv.index = 1;
    containerDiv.id = 'region-draw-button';

    containerDiv.addEventListener( 'click', function () {
        drawingAllowed = !drawingAllowed;
        if ( drawingAllowed ) {
            map.setOptions( {
                draggableCursor: 'crosshair'
            } );
            this.style.backgroundColor = '#777777';
            this.style.color = '#FFF';
        } else {
            map.setOptions( {
                draggableCursor: null
            } );
            this.style.backgroundColor = '#FFF';
            this.style.color = '#777';
        }
    } );

    return containerDiv;
};

var randomImages = [
    'img/offers/front-prodava-3staen-sofiq-400-2016091318283421.jpg',
    'img/offers/front-prodava-1staen-sofiq-460-2016100710042338.jpg'
];

/**
 * Sets the event to display result marker window with information.
 *
 * @param map
 * @param markerInfoWindow
 * @param marker
 * @param markerInfoId
 * @param imageName
 * @param offerTypeName
 * @param propertyTypeName
 * @param offerCity
 * @param offerRegion
 * @param offerPrice
 * @param currencyName
 */
var markerInfoWindowEvent = function ( map, markerInfoWindow, marker, markerInfoId, imageName, offerTypeName, propertyTypeName, offerCity, offerRegion, offerPrice, currencyName ) {
    google.maps.event.addListener( marker, 'click', function () {
        markerInfoWindow.setContent( '<div style="width: 315px; height: 90px; overflow: hidden">'
            + '<a href="/bg/offer/' + markerInfoId + '" target="_blank"><img src="https://ogledi.bg/media/offers/' + markerInfoId + '/front-' + imageName + '" style="display: block; float: left; width: 125px" border="0" /></a>'
            + '<div style="margin: 0 10px; float: left; width: 165px;">'
            + offerTypeName + ' ' + propertyTypeName + '<br/>'
            + offerCity + ', ' + offerRegion + '<br/>'
            + Math.round( offerPrice ) + ' ' + currencyName
            + '</div></div>' );
        markerInfoWindow.open( map, marker );
    } );
};

/**
 * Generates markers on the map by querying for offers by the selected parameters.
 */
var generatePropertyMarkers = function () {

    clearAllMarkers();
    clearAllPolylines();

    var neighbourhoodArr = [];
    var neighbourhood = $( '#neighbourhood_id' ).find( 'option' );
    if ( (neighbourhood) && (neighbourhood.length > 0) ) {
        for ( var i = 0; i < neighbourhood.length; i++ ) {
            neighbourhoodArr.push( neighbourhood[ i ].value );
        }
    }

    var propertyFeaturesArr = [];
    var propertyFeatures = $( '.property-feature-items' );
    if ( (propertyFeatures) && (propertyFeatures.length > 0) ) {
        for ( var i = 0; i < propertyFeatures.length; i++ ) {
            if ( propertyFeatures[ i ].checked ) {
                propertyFeaturesArr.push( propertyFeatures[ i ].value );
            }
        }
    }

    var params = {
        'offer_type_id': $( '#offer_type_id' ).val(),
        'property_type_id': $( '#property_type_id' ).val(),
        'city_id': $( '#city_id' ).val(),
        'neighbourhood_id[]': neighbourhoodArr,
        'keyword': $( '#keyword' ).val(),
        'minprice': $( '#minprice' ).val(),
        'maxprice': $( '#maxprice' ).val(),
        'minsqm': $( '#minsqm' ).val(),
        'maxsqm': $( '#maxsqm' ).val(),
        'floor_from': $( '#floor_from' ).val(),
        'floor_to': $( '#floor_to' ).val(),
        'construction_year_from': $( '#construction_year_from' ).val(),
        'construction_year_to': $( '#construction_year_to' ).val(),
        'yard_from': $( '#yard_from' ).val(),
        'yard_to': $( '#yard_to' ).val(),
        'heating_system_id': $( '#heating_system_id' ).val(),
        'property_features[]': propertyFeaturesArr,
        'agency_id': $( '#agency_id' ).val()
    };

    $.post( '/en/search-data', params, function ( data ) {
        if ( data[ 'count' ] > 0 ) {
            for ( var i = 0; i < data[ 'count' ]; i++ ) {
                var resLat = data[ 'data' ][ i ][ 'lat' ] * 1;
                var resLng = data[ 'data' ][ i ][ 'lng' ] * 1;
                if ( mapSearchObjects.length > 0 ) {
                    if ( google.maps.geometry.poly.containsLocation( new google.maps.LatLng( resLat, resLng )
                            , mapSearchObjects[ 0 ] ) ) {
                        var mapMarker = new google.maps.Marker( {
                            map: map,
                            position: {
                                lat: resLat,
                                lng: resLng
                            },
                            icon: markerImage
                        } );
                        markerInfoWindowEvent( map, markerInfoWindow, mapMarker,
                            data[ 'data' ][ i ][ 'id' ],
                            data[ 'data' ][ i ][ 'image' ],
                            data[ 'data' ][ i ][ 'offerTypeName' ],
                            data[ 'data' ][ i ][ 'propertyTypeName' ],
                            data[ 'data' ][ i ][ 'cityName' ],
                            data[ 'data' ][ i ][ 'neighbourhoodName' ],
                            data[ 'data' ][ i ][ 'price' ],
                            data[ 'data' ][ i ][ 'currencyShortName' ] );
                        markers.push( mapMarker );
                    }
                } else {
                    var mapMarker = new google.maps.Marker( {
                        map: map,
                        position: {
                            lat: resLat,
                            lng: resLng
                        },
                        icon: markerImage
                    } );
                    markerInfoWindowEvent( map, markerInfoWindow, mapMarker,
                        data[ 'data' ][ i ][ 'id' ],
                        data[ 'data' ][ i ][ 'image' ],
                        data[ 'data' ][ i ][ 'offerTypeName' ],
                        data[ 'data' ][ i ][ 'propertyTypeName' ],
                        data[ 'data' ][ i ][ 'cityName' ],
                        data[ 'data' ][ i ][ 'neighbourhoodName' ],
                        data[ 'data' ][ i ][ 'price' ],
                        data[ 'data' ][ i ][ 'currencyShortName' ] );
                    markers.push( mapMarker );
                }
            }
        }
    } );
};

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each( a, function () {
        if ( o[ this.name ] ) {
            if ( !o[ this.name ].push ) {
                o[ this.name ] = [ o[ this.name ] ];
            }
            o[ this.name ].push( this.value || '' );
        } else {
            o[ this.name ] = this.value || '';
        }
    } );
    return o;
};

/**
 * Initializes the preview map.
 */
var initPreviewMapCoordinates = function () {
    var lat = $( '#lat' ).val();
    var lng = $( '#lng' ).val();
    var myLatLng = {
        lat: lat * 1,
        lng: lng * 1
    };
    map = new google.maps.Map( document.getElementById( 'map' ), {
        zoom: 15,
        center: myLatLng,
        scrollwheel: false
    } );

    var mapMarker = new google.maps.Marker( {
        map: map,
        position: new google.maps.LatLng( lat, lng ),
        draggable: false
    } );
    
};