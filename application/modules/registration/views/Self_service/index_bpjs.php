<style>

/*==================================================
 * Effect 8
 * ===============================================*/
.effect8
{
    /* position:relative; */
    -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
       -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
}


</style>
<div class="row">
    <div class="col-xs-2">
        &nbsp;
    </div>
    <div class="col-xs-8">
        <div class="widget-box effect8">
            <div class="widget-header">
                <h4 class="widget-title">KODE PERJANJIAN/BOOKING</h4>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div>
                        <label for="form-field-mask-1">
                            Silahkan masukan Kode Booking atau Kode Perjanjian anda.
                        </label>

                        <div>
                            <input class="form-control" type="text"id="kodeBooking" name="kodeBooking" style="font-size:40px;height: 55px !important; width: 100%; text-align: center !important">
                        </div>
                    </div>
                    <div style="width: 100%; margin-top: 10px; text-align: center">
                        
                        <button style="height: 35px !important; font-size: 14px" class="btn btn-sm btn-primary" type="button" id="btnSearchKodeBooking">
                            <i class="ace-icon fa fa-search bigger-110"></i>
                            Cek Kode Booking/Perjanjian
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>

        <hr>
        <div id="message_result_kode_booking"></div>

        <div class="row" style="display: none" id="resultSearchKodeBooking">

            <!-- hidden parameter -->
            <input type="hidden" name="pnomr" id="pnomr">
            <input type="hidden" name="noSuratSKDP" id="noSuratSKDP">
            <input type="hidden" name="kode_poli_bpjs" id="kode_poli_bpjs">

            <div class="col-xs-12 col-sm-9">
                <h4 class="blue">
                    <span class="middle"><span id="kb_no_mr">No.MR</span> - <span id="kb_nama_pasien">Nama Pasien</span></span>
                </h4>

                <div class="profile-user-info">
                    <div class="profile-info-row">
                        <div class="profile-info-name"> Tujuan Poli/Klinik </div>

                        <div class="profile-info-value">
                            <span id="kb_poli_tujuan">Poli/Klinik</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Dokter </div>

                        <div class="profile-info-value">
                        <span id="kb_dokter">Nama Dokter</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Tanggal/Jam Kunjungan </div>

                        <div class="profile-info-value">
                            <span id="kb_tgl_kunjungan"></span> <span id="kb_jam_praktek"></span> 
                            <span id="is_available"></span>
                            
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-xs-12 col-sm-3 center">
                <a href="#" class="btn btn-success btn-app radius-4" onclick="nextProcess('Self_service/form_rujukan')" style="width: 100% !important; height: 100% !important; margin-top: 35px">
                    <i class="ace-icon fa fa-arrow-right bigger-350" style="padding: 20px"></i>
                </a>
            </div>


        </div>

        

        
    </div>
    <div class="col-xs-2">
        &nbsp;
    </div>
</div>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function () {

    var today = getDateToday();
    console.log(today);
	$('#btnSearchKodeBooking').click(function (e) {
      e.preventDefault();
      findKodeBooking();
    });

    // screen keyboard
    $('#kodeBooking').keyboard({

        // set this to ISO 639-1 language code to override language set by the layout
        // http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
        // language defaults to "en" if not found
        language     : null,  // string or array
        rtl          : false, // language direction right-to-left

        // *** choose layout ***
        layout       : 'qwerty',
        customLayout : { 'normal': ['{cancel}'] },

        position : {
        // optional - null (attach to input/textarea) or a jQuery object
        // (attach elsewhere)
        of : null,
        my : 'center top',
        at : 'center top',
        // used when "usePreview" is false
        // (centers keyboard at bottom of the input/textarea)
        at2: 'center bottom'
        },

        // allow jQuery position utility to reposition the keyboard on window resize
        reposition : true,

        // preview added above keyboard if true, original input/textarea used if false
        // always disabled for contenteditable elements
        usePreview : true,

        // if true, the keyboard will always be visible
        alwaysOpen : false,

        // give the preview initial focus when the keyboard becomes visible
        initialFocus : false,
        // Avoid focusing the input the keyboard is attached to
        noFocus : false,

        // if true, keyboard will remain open even if the input loses focus.
        stayOpen : false,

        // Prevents the keyboard from closing when the user clicks or
        // presses outside the keyboard. The `autoAccept` option must
        // also be set to true when this option is true or changes are lost
        userClosed : false,

        // if true, keyboard will not close if you press escape.
        ignoreEsc : false,

        // if true, keyboard will only closed on click event instead of mousedown or
        // touchstart. The user can scroll the page without closing the keyboard.
        closeByClickEvent : false,

        // *** change keyboard language & look ***
        display : {
        // \u2714 = check mark - same action as accept
        'a'      : '\u2714:Accept (Shift-Enter)',
        'accept' : 'Accept:Accept (Shift-Enter)',
        'alt'    : 'AltGr:Alternate Graphemes',
        // \u232b = outlined left arrow with x inside
        'b'      : '\u232b:Backspace',
        'bksp'   : 'Bksp:Backspace',
        // \u2716 = big X, close - same action as cancel
        'c'      : '\u2716:Cancel (Esc)',
        'cancel' : 'Cancel:Cancel (Esc)',
        // clear num pad
        'clear'  : 'C:Clear',
        'combo'  : '\u00f6:Toggle Combo Keys',
        // decimal point for num pad (optional);
        // change '.' to ',' for European format
        'dec'    : '.:Decimal',
        // down, then left arrow - enter symbol
        'e'      : '\u21b5:Enter',
        'empty'  : '\u00a0', // &nbsp;
        'enter'  : 'Enter:Enter',
        // \u2190 = left arrow (move caret)
        'left'   : '\u2190',
        // caps lock
        'lock'   : '\u21ea Lock:Caps Lock',
        'next'   : 'Next',
        'prev'   : 'Prev',
        // \u2192 = right arrow (move caret)
        'right'  : '\u2192',
        // \u21e7 = thick hollow up arrow
        's'      : '\u21e7:Shift',
        'shift'  : 'Shift:Shift',
        // \u00b1 = +/- sign for num pad
        'sign'   : '\u00b1:Change Sign',
        'space'  : '&nbsp;:Space',

        // \u21e5 = right arrow to bar; used since this virtual
        // keyboard works with one directional tabs
        't'      : '\u21e5:Tab',
        // \u21b9 is the true tab symbol (left & right arrows)
        'tab'    : '\u21e5 Tab:Tab',
        // replaced by an image
        'toggle' : ' ',

        // added to titles of keys
        // accept key status when acceptValid:true
        'valid': 'valid',
        'invalid': 'invalid',
        // combo key states
        'active': 'active',
        'disabled': 'disabled'
        },

        // Message added to the key title while hovering, if the mousewheel plugin exists
        wheelMessage : 'Use mousewheel to see other keys',

        css : {
        // input & preview
        input          : 'ui-widget-content ui-corner-all',
        // keyboard container
        container      : 'ui-widget-content ui-widget ui-corner-all ui-helper-clearfix',
        // keyboard container extra class (same as container, but separate)
        popup: '',
        // default state
        buttonDefault  : 'ui-state-default ui-corner-all',
        // hovered button
        buttonHover    : 'ui-state-hover',
        // Action keys (e.g. Accept, Cancel, Tab, etc); replaces "actionClass"
        buttonAction   : 'ui-state-active',
        // used when disabling the decimal button {dec}
        buttonDisabled : 'ui-state-disabled',
        // empty button class name {empty}
        buttonEmpty    : 'ui-keyboard-empty'
        },

        // *** Useability ***
        // Auto-accept content when clicking outside the keyboard (popup will close)
        autoAccept : false,
        // Auto-accept content even if the user presses escape
        // (only works if `autoAccept` is `true`)
        autoAcceptOnEsc : false,

        // Prevents direct input in the preview window when true
        lockInput : false,

        // Prevent keys not in the displayed keyboard from being typed in
        restrictInput : false,
        // Additional allowed characters while restrictInput is true
        restrictInclude : '', // e.g. 'a b foo \ud83d\ude38'

        // Check input against validate function, if valid the accept button
        // is clickable; if invalid, the accept button is disabled.
        acceptValid : true,
        // Auto-accept when input is valid; requires `acceptValid`
        // set `true` & validate callback
        autoAcceptOnValid : false,

        // if acceptValid is true & the validate function returns a false, this option
        // will cancel a keyboard close only after the accept button is pressed
        cancelClose : true,

        // Use tab to navigate between input fields
        tabNavigation : false,

        // press enter (shift-enter in textarea) to go to the next input field
        enterNavigation : true,
        // mod key options: 'ctrlKey', 'shiftKey', 'altKey', 'metaKey' (MAC only)
        // alt-enter to go to previous; shift-alt-enter to accept & go to previous
        enterMod : 'altKey',

        // if true, the next button will stop on the last keyboard input/textarea;
        // prev button stops at first
        // if false, the next button will wrap to target the first input/textarea;
        // prev will go to the last
        stopAtEnd : true,

        // Set this to append the keyboard immediately after the input/textarea it
        // is attached to. This option works best when the input container doesn't
        // have a set width and when the "tabNavigation" option is true
        appendLocally : false,

        // Append the keyboard to a desired element. This can be a jQuery selector
        // string or object
        appendTo : 'body',

        // If false, the shift key will remain active until the next key is (mouse)
        // clicked on; if true it will stay active until pressed again
        stickyShift : true,

        // caret placed at the end of any text when keyboard becomes visible
        caretToEnd : false,

        // Prevent pasting content into the area
        preventPaste : false,

        // caret stays this many pixels from the edge of the input
        // while scrolling left/right; use "c" or "center" to center
        // the caret while scrolling
        scrollAdjustment : 10,

        // Set the max number of characters allowed in the input, setting it to
        // false disables this option
        maxLength : false,

        // allow inserting characters @ caret when maxLength is set
        maxInsert : true,

        // Mouse repeat delay - when clicking/touching a virtual keyboard key, after
        // this delay the key will start repeating
        repeatDelay : 500,

        // Mouse repeat rate - after the repeatDelay, this is the rate (characters
        // per second) at which the key is repeated. Added to simulate holding down
        // a real keyboard key and having it repeat. I haven't calculated the upper
        // limit of this rate, but it is limited to how fast the javascript can
        // process the keys. And for me, in Firefox, it's around 20.
        repeatRate : 20,

        // resets the keyboard to the default keyset when visible
        resetDefault : false,

        // Event (namespaced) on the input to reveal the keyboard. To disable it,
        // just set it to an empty string ''.
        openOn : 'focus',

        // When the character is added to the input
        keyBinding : 'mousedown touchstart',

        // enable/disable mousewheel functionality
        // enabling still depends on the mousewheel plugin
        useWheel : true,

        // combos (emulate dead keys)
        // http://en.wikipedia.org/wiki/Keyboard_layout#US-International
        // if user inputs `a the script converts it to à, ^o becomes ô, etc.
        useCombos : true,

        // *** Methods ***
        // Callbacks - add code inside any of these callback functions as desired
        initialized   : function(e, keyboard, el) {},
        beforeVisible : function(e, keyboard, el) {},
        visible       : function(e, keyboard, el) {},
        beforeInsert  : function(e, keyboard, el, textToAdd) { return textToAdd; },
        change        : function(e, keyboard, el) {},
        beforeClose   : function(e, keyboard, el, accepted) {},
        accepted      : function(e, keyboard, el) {},
        canceled      : function(e, keyboard, el) {},
        restricted    : function(e, keyboard, el) {},
        hidden        : function(e, keyboard, el) {},

        // called instead of base.switchInput
        switchInput : function(keyboard, goToNext, isAccepted) {},

        // used if you want to create a custom layout or modify the built-in keyboard
        create : function(keyboard) { return keyboard.buildKeyboard(); },

        // build key callback (individual keys)
        buildKey : function( keyboard, data ) {
        /*
        data = {
            // READ ONLY
            // true if key is an action key
            isAction : [boolean],
            // key class name suffix ( prefix = 'ui-keyboard-' ); may include
            // decimal ascii value of character
            name     : [string],
            // text inserted (non-action keys)
            value    : [string],
            // title attribute of key
            title    : [string],
            // keyaction name
            action   : [string],
            // HTML of the key; it includes a <span> wrapping the text
            html     : [string],
            // jQuery selector of key which is already appended to keyboard
            // use to modify key HTML
            $key     : [object]
        }
        */
        return data;
        },

        // this callback is called just before the "beforeClose" to check the value
        // if the value is valid, return true and the keyboard will continue as it
        // should (close if not always open, etc)
        // if the value is not value, return false and the clear the keyboard value
        // ( like this "keyboard.$preview.val('');" ), if desired
        // The validate function is called after each input, the "isClosing" value
        // will be false; when the accept button is clicked, "isClosing" is true
        validate : function(keyboard, value, isClosing) {
        return true;
        }

    });

});

function nextProcess(link){
    $('#load-content-page').load(link+'?kode='+$('#kodeBooking').val());
}

function findKodeBooking(){
    var kodeBooking = $('#kodeBooking').val();
    var today = getDateToday();

      $.ajax({
        url: '../Templates/References/findKodeBooking',
        type: "post",
        data: {kode:kodeBooking},
        dataType: "json",
        beforeSend: function() {
        //   achtungShowLoader();  
        },
        success: function(response) {
        //   achtungHideLoader();
          if(response.status==200){
            var obj = response.data;
            var no_mr = obj.no_mr;

            /*show hidden*/
            $('#resultSearchKodeBooking').show('fast');
            $('#message_result_kode_booking').html('');
			
			$('html,body').animate({
					scrollTop: $("#resultSearchKodeBooking").offset().top},
					'slow');

            /*text*/
            $('#kb_no_mr').text(obj.no_mr);
            $('#pnomr').val(obj.no_mr);
            $('#kb_nama_pasien').text(obj.nama);
            $('#kb_tgl_kunjungan').text(obj.tgl_kunjungan);
            $('#kb_poli_tujuan').text(obj.poli);
            $('#kb_dokter').text(obj.nama_dr);
            $('#kb_jam_praktek').text(obj.jam_praktek);
            $('#noSuratSKDP').val(kodeBooking);
            $('#kode_poli_bpjs').val(obj.kode_poli_bpjs);
            console.log(today);
            console.log(obj.tgl_kunjungan);

            if(today == obj.tgl_kunjungan){
                $('#is_available').html('<span class="label label-success arrowed-in-right">available</span>');
            }else{
                $('#is_available').html('<span class="label label-danger arrowed-in-right">not available</span>');
            }
          //   $('#noMR').val(response.data.no_mr);

          }else{
            // $.achtung({message: data.message, timeout:5});
            $('#resultSearchKodeBooking').hide('fast');
            $('#message_result_kode_booking').html('<div class="center"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

          }
          
        }
      });

}


</script>