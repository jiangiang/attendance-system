/**
 *  JS Script
 */
var urlPost, who_click;
var urlGetDetails = "json_PackageDetails/";
var urlUpdate = 'update';
var urlCreate = 'create';
var formID = 'FormPackage';
var modalID = '#ModalPackage';
var btnSubmit = '#btn-Submit';
var tblList = '#tbl-MainTable';
var activationURL = 'delete';
var activationBtnID = '.btn-deactivate';
var rowTargetID = 'td#row_TargetID';
var activationFormID = 'PackageActivationForm';

$( document ).ready( function () {

    $( modalID ).on( 'shown.bs.modal', function () {
        if ( who_click == 'NewAssignment' )
            $( '#ClassVenue' ).focus();
        else if ( who_click == 'UpdateAssignment' )
            $( '#ClassInstructor' ).focus();
    } );

    // To make sure it is clean for the next show
    $( modalID ).on( 'hidden.bs.modal', function () {
        $( '#help-block' ).remove();
        document.getElementById( formID ).reset();
    } );

    $( '#btn-Create' ).on( 'click', function () {
        urlPost = urlCreate;
        who_click = 'NewAssignment';
        $( "#ClassVenue" ).prop( 'disabled', false );
        $( "#ClassLevel" ).prop( 'disabled', false );
        $( '#ClassDay' ).prop( 'disabled', false );
        $( '#ModalTitle' ).text( ' New Assignment' );
        $( modalID ).modal( 'show' );
        $( btnSubmit ).text( 'Create' );
    } );

    $( '.btn-update' ).on( 'click', function () {
        urlPost = urlUpdate
        who_click = 'UpdateAssignment';
        $( '#ClassName' ).prop( "readonly", true );
        $( '#ModalTitle' ).text( ' Update Course Info' );
        $( btnSubmit ).text( ' Update!' );
        $( btnSubmit ).prop( "disabled", false );
        $( modalID ).modal( 'show' );
    } );

    $( tblList ).on( 'click', '.btn-update', function () {

        var id = $( this ).closest( 'tr' ).children( rowTargetID ).text();
        var url = urlGetDetails + id;

        $.getJSON( url, function ( data ) {
            console.log( data );
        } ).done( function ( data ) {

            $( "#PackageID" ).val( data.package_id );
            $( "#PackageName" ).val( data.package_name );
            $( "#PackagePrice" ).val( data.price );
            $( "#PackageCommission" ).val( data.commission );
            $( "#PackageTerm" ).val( data.term );
            $( "#PackageDuration" ).val( data.duration );
            $( '#PackageValidity' ).val( data.validity );
            $( '#IsAllowCustomDate' ).val( data.IsAllowCustomDate ).prop( "selected", true );
            $( '#IsRequiredAttendance' ).val( data.IsRequiredAttendance ).prop( "selected", true );

        } ).fail( function ( data ) {
            console.log( "Fail, Please contact administrator. last URL: " + url );
        } );
    } );


    // De-Activate
    $( tblList ).on( 'click', activationBtnID, function () {
        var id = $( this ).closest( 'tr' ).children( rowTargetID ).text();
        $( '#TargetID' ).val( id );

        var r = confirm( "Deactivate this ?" );
        if ( r == true ) {
            $( '#' + activationFormID ).submit();
        }
    } );

    // Submission
    $( '#' + formID ).validate( {
        rules: {
            capacity: {
                number: true
            }
        },
        submitHandler: function ( form, event ) {

            $( '#help-block' ).remove();
            $( btnSubmit ).prop( 'disabled', true );
            $( btnSubmit ).text( 'In Progress' );
            var formData = $( form ).serialize();

            $.ajax( {
                type: 'POST',
                url: urlPost,
                data: formData,
                dataType: 'json',
                encode: true
            } )
            // using the done promise callback
                .done( function ( data ) {
                    console.log( data );
                    if ( data.error ) {
                        $( btnSubmit ).prop( 'disabled', false );
                        $( btnSubmit ).text( 'Retry' );
                        $( '#help-block' ).remove();
                        $( '#statusMsg' ).append( '<div class="alert alert-danger" id="help-block">' + data.message + '</div>' );
                    } else {// Success !
                        document.getElementById( formID ).reset();
                        $( '#help-block' ).remove();
                        $( '#statusMsg' ).append( '<div class="alert alert-success" id="help-block">' + data.message + '</div>' );
                        setTimeout( function () {
                            $( modalID ).modal( 'hide' );
                        }, 100 );
                        setTimeout( function () {
                            location.reload();
                        }, 100 );

                    }
                } ).fail( function ( data ) {
                $( '#help-block' ).remove();
                $( '#statusMsg' ).append( '<div class="alert alert-danger" id="help-block"> Please contact administrator.</div>' );
                $( btnSubmit ).prop( 'disabled', false );
                $( btnSubmit ).text( 'Retry' );
                console.log( "Fail, Please contact administrator. last URL: " + urlPost );
            } );
            event.preventDefault();

        }
    } );

    $( "#"+activationFormID ).validate( {
        submitHandler: function ( form, event ) {
            var formData = $( form ).serialize();

            $.ajax( {
                type: 'POST',
                url: activationURL,
                data: formData,
                dataType: 'json',
                encode: true
            } ).done( function ( data ) {
                console.log( data );
                if ( !data.error ) {
                    setTimeout( function () {
                        location.reload();
                    }, 100 );
                } else {
                    alert( "Fail to deactivate." );
                }
            } ).fail( function ( data ) {
                console.log( "Fail, Please contact administrator. last URL: " + urlPost );
            } );
            event.preventDefault();

        }
    } );

} );


