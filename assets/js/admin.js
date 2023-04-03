/*--------------------------------------------------------------
# Admin Js Start
--------------------------------------------------------------*/
( function ( $ ) {

	/**
   * # Alerts
   *
   * @param {string}  type      Accepts 'success', 'info', 'warning' and 'danger'.
   * @param {string}  msg       Message to the user.
   * @param {bool}    loader    Display CSS loading animation. (true/false).
   * @param {bool}    dismiss   Dismissible alert. (true/false).
   *
   * @returns html element of alert
   */
  function wooStreamAlert( type, msg, loader, dismiss ) {

    var loaderElem = '';
    var dismissElem = '';

    if ( typeof loader == 'undefined' || loader == false ) {
      // do nothing...
    } else if ( loader == true ) {
      loaderElem = '<div class="woost-loader"></div> ';
    }

    if ( typeof dismiss == 'undefined' || dismiss == false ) {
      // do nothing...
    } else if ( dismiss == true ) {
      dismissElem = '<button type="button" class="close" aria-label="Close">'+
        '<span aria-hidden="true">&times;</span>'+
      '</button>';
    }

    return '<div class="woost-alert woost-alert-'+type+' woost-alert-dismissible" role="alert">'+
      dismissElem+
      loaderElem+msg+
    '</div>';

  }

  // Close alert
  $( '.woost-alerts' ).on( 'click', '.woost-alert-dismissible span', function () {
    $( this ).closest( '.woost-alert' ).fadeOut( 400, function () {
      $( this ).remove();
    } );
  } );

  /**
   * # Data Loading Animation
   *
   * @param {string} message  The message that needs to be displayed to the user.
   *
   * @returns html element of loading animation.
   */
  function wooStreamLoadingData( message ) {

    if ( typeof message == 'undefined' ) {
      message = woostObj.dataLoadingMsg;
    }

    return '<div class="woost-loader"></div> <div class="woost-loading-txt">'+message+'</div>';

  }

  /**
   * # Fetch facebook user/page ID & token
   */
  $( '.woost-fb .woost-fetch' ).on( 'click', function () {

    var appId = $( '#woost_fb_app_id' ).val();
    var appSecret = $( '#woost_fb_app_secret' ).val();
    var accessToken = $( '#woost_fb_access_token' ).val();

    $.ajax( {
      url: ajaxurl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_fetch_token',
        app_id: appId,
        app_secret: appSecret,
        access_token: accessToken,
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-fb .button' ).prop( 'disabled', true );
        $( '.woost-fb .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );
          $( '#woost_fb_page_id' ).val( data.pageId );
          $( '#woost_fb_permanent_token' ).val( data.permanentToken );

        }

        location.href = '#woost-alerts';

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-fb .button' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-fb .button' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();
      }
    } );

  } );

  /**
   * # Test facebook api connection
   */
  $( '.woost-fb .woost-test' ).on( 'click', function () {

    var pageId = $( '#woost_fb_page_id' ).val();
    var permanentToken = $( '#woost_fb_permanent_token' ).val();

    $.ajax( {
      url: ajaxurl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_test_fb_connection',
        page_id: pageId,
        permanent_token: permanentToken,
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-fb .button' ).prop( 'disabled', true );
        $( '.woost-fb .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );

        }

        location.href = '#woost-alerts';

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-fb .button' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-fb .button' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();
      }
    } );

  } );

  /**
   * # Load streaming requests
   *
   * @param {string}  currElement   Class, ID or this can be passed.
   */
  function wooStreamLoadRequests(currElement) {

    var pageNo = 1;

    if (typeof currElement == 'undefined') {
      // do nothing...
    } else {

      pageNo = Number( $(currElement).text() );

      // Check for previous button click
      if ( $(currElement).hasClass( 'woost-nav-prev' ) )  {

        pageNo = Number( $( '.woost-nav-curr' ).text() );

        if ( pageNo == 1 ) {
          return false;
        } else {
          pageNo--;
        }

      }

      // Check for next button click
      if ( $(currElement).hasClass( 'woost-nav-next' ) )  {

        pageNo = Number( $( '.woost-nav-curr' ).text() );
        lastPage = Number( $( '.woost-nav-next' ).prev().text() );

        if ( pageNo == lastPage ) {
          return false;
        } else {
          pageNo++;
        }

      }

    }

    $.ajax( {
      url: ajaxurl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_load_requests',
        page_no: pageNo,
        nonce: woostObj.nonce
      },
      beforeSend: function () {

        $('.woost-table tbody').html('<tr>'+
          '<td colspan="4">'+wooStreamLoadingData()+'</td>'+
        '</tr>');
        $('.woost-paging-nav').html('');

      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else if ( data.success == '' ) {

          $('.woost-table tbody').html('<tr>'+
            '<td colspan="4">'+woostObj.noDataMsg+'</td>'+
          '</tr>');

        } else {

          $( '.woost-table tbody' ).html( data.success );
          $( '.woost-paging-nav' ).html( data.paging );

        }

      },
      error: function (error) {
        console.log(error);
        $('.woost-table tbody').html('<tr>'+
          '<td colspan="4">'+woostObj.noDataMsg+'</td>'+
        '</tr>');
      }
    } );

  }

  wooStreamLoadRequests();

  /**
   * # Accept or reject stream activation request
   */
  if ( woostObj.base.indexOf('_woo-stream-requests') !== -1 ) {

    $( '.woost-table' ).on( 'click', '.button', function () {

      var actionType = '';
      var currElement = $(this);
      var username = currElement.closest('tr').find('.woost-username a').text();
      var userId = currElement.parent().data('id');

      if ( $(this).hasClass('woost-accept') ) actionType = 'accept';
      if ( $(this).hasClass('woost-reject') ) actionType = 'reject';

      $.ajax({
        url: ajaxurl,
        method: 'POST',
        dataType: 'json',
        data: {
          action: 'woo_stream_process_request',
          username: username,
          user_id: userId,
          action_type: actionType,
          nonce: woostObj.nonce
        },
        beforeSend: function () {
          currElement.parent().find('.button').prop( 'disabled', true ).hide();
          currElement.parent().find('.woost-loader').show();
        },
        success: function (data) {

          if ( data.error != '' ) {

            $( '.woost-alerts' ).html( wooStreamAlert( 'warning', data.error, false, true ) );

          } else {

            $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );

          }

          currElement.closest('tr').remove();

          setTimeout( function () {
            $( '.woost-alerts' ).html('');
          }, 30000);

          if ( $( '.woost-table tbody tr' ).length == 0 ) {

            $('.woost-table tbody').html('<tr>'+
              '<td colspan="4">'+woostObj.noDataMsg+'</td>'+
            '</tr>');

          }

        },
        error: function (error) {
          console.log(error);
          currElement.parent().find('.button').prop( 'disabled', false ).show();
          currElement.parent().find('.woost-loader').hide();
        }
      });

    } );

  }

  /**
   * # Select2
   */
  $('.woost-select2').select2();

} )( jQuery );
