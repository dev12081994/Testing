</section>
        
        
            <!-- Vendor -->
        <!-- <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script> -->
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>


        <!-- Specific Page Vendor -->
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-appear/jquery.appear.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>



        <script src="<?php echo base_url(); ?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

        <script src="<?php echo base_url(); ?>assets/vendor/pnotify/pnotify.custom.js"></script>


        <script src="<?php echo base_url(); ?>assets/vendor/jquery-autosize/jquery.autosize.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-validation/jquery.validate.js"></script>

        <script src="<?php echo base_url(); ?>assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/flot/jquery.flot.categories.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
        

        <script src="<?php echo base_url(); ?>assets/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/fuelux/js/spinner.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/dropzone/dropzone.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-markdown/js/markdown.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/lib/codemirror.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/addon/selection/active-line.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/addon/edit/matchbrackets.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/mode/javascript/javascript.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/mode/xml/xml.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/codemirror/mode/css/css.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/summernote/summernote.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/ios7-switch/ios7-switch.js"></script>

        <!-- Theme Base, Components and Settings -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
        
        <!-- Theme Custom -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
        
        <!-- Theme Initialization Files -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>
        

        <!-- Examples -->
       <script src="<?php echo base_url(); ?>assets/javascripts/forms/examples.advanced.form.js" /></script>

        <script src="<?php echo base_url(); ?>assets/javascripts/ui-elements/examples.modals.js"></script>
        <script src="<?php echo base_url(); ?>assets/javascripts/forms/examples.validation.js"></script>


        <script src="<?php echo base_url(); ?>assets/javascripts/tables/examples.datatables.default.js"></script>
       
        
        <!-- Custom Script -->


    <script type="text/javascript">
            
        // For Restriction Of More Than One Click 
        $('form').on('submit',function(){
            $('button[type="submit"]').html("Please Wait...").attr('disabled',true);
        });

        $('.onlynumval').on('keydown',function(e){ 
            var ingnore_key_codes = [190,48,49,50,51,52,53,54,55,56,57,58,93,94,95,96,97,98,99,100,101,102,103,104,105,110,8,13,46,37,39,9];
            
            if ($.inArray(e.keyCode, ingnore_key_codes) < 0){
                e.preventDefault();
            }
        }); 

        $(document).ready(function(){
            setTimeout(function(){
                $('.alert').fadeOut(1000);
            },3000);
        });

        // Notifications 

        function showNotification(noti_data) // type='success','info','error'
        {
            var obj=JSON.parse(noti_data);
            new PNotify({
                title: obj.title,
                text: obj.msg,
                type: obj.type
            });
        }


        //Datepicker
        $( function() {        
            $('.mydatepicker').datepicker({
                language:  'fr',
                todayBtn:  1,
                clearBtn:  1,
                autoclose: 1,
                todayHighlight: 1,      
                minView: 2,
                format: "dd-mm-yyyy",
                forceParse: 0
            });
        });


        function printDiv(div_id) 
        {
            $('.h').addClass("hide");
            var frame1 = $('<iframe />');
            var divToPrint = document.getElementById(div_id);
            frame1[0].name = "frame1";
            frame1.css({"position": "absolute", "top": "-1000000px"});
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html>');
            frameDoc.document.write('<head>');
            frameDoc.document.write('<title></title>');

            frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />');
            frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />');
            frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />');
            frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />');
            
            
            frameDoc.document.write('</head>');
            frameDoc.document.write('<body>');
            frameDoc.document.write(divToPrint.innerHTML);
            frameDoc.document.write('</body>');
            frameDoc.document.write('</html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
            $('.h').removeClass("hide");
            return true;
        }


        </script>
       
    </body>
</html>