$(document).ready(function() {

    /* ======= Twitter Bootstrap hover dropdown ======= */   
    /* Ref: https://github.com/CWSpear/bootstrap-hover-dropdown */ 
    /* apply dropdownHover to all elements with the data-hover="dropdown" attribute */
    
    $('[data-hover="dropdown"]').dropdownHover();
    
    /* ======= Fixed header when scrolled ======= */    
    $(window).on('scroll load', function() {
         
         if ($(window).scrollTop() > 0) {
             $('#header').addClass('scrolled');
         }
         else {
             $('#header').removeClass('scrolled');
             
         }
    });
    
    
    /* ======= jQuery Placeholder ======= */
    /* Ref: https://github.com/mathiasbynens/jquery-placeholder */
    
    $('input, textarea').placeholder();    
    
    /* ======= jQuery FitVids - Responsive Video ======= */
    /* Ref: https://github.com/davatron5000/FitVids.js/blob/master/README.md */
    
    $(".video-container").fitVids();
    
    /* ======= FAQ accordion ======= */
    function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find('.panel-title a')
        .toggleClass('active')
        .find("i.fa")
        .toggleClass('fa-plus-square fa-minus-square');
    }
    $('.panel').on('hidden.bs.collapse', toggleIcon);
    $('.panel').on('shown.bs.collapse', toggleIcon);    
    
    
    /* ======= Header Background Slideshow - Flexslider ======= */    
    /* Ref: https://github.com/woothemes/FlexSlider/wiki/FlexSlider-Properties */
    
    $('.bg-slider').flexslider({
        animation: "fade",
        directionNav: false, //remove the default direction-nav - https://github.com/woothemes/FlexSlider/wiki/FlexSlider-Properties
        controlNav: false, //remove the default control-nav
        slideshowSpeed: 8000
    });
	
	/* ======= Stop Video Playing When Close the Modal Window ====== */
    $("#modal-video .close").on("click", function() {
        $("#modal-video iframe").attr("src", $("#modal-video iframe").attr("src"));        
    });
     
    
     /* ======= Testimonial Bootstrap Carousel ======= */
     /* Ref: http://getbootstrap.com/javascript/#carousel */
    $('#testimonials-carousel').carousel({
      interval: 8000 
    });
    
    
    /* ======= Style Switcher ======= */    
    $('#config-trigger').on('click', function(e) {
        var $panel = $('#config-panel');
        var panelVisible = $('#config-panel').is(':visible');
        if (panelVisible) {
            $panel.hide();          
        } else {
            $panel.show();
        }
        e.preventDefault();
    });
    
    $('#config-close').on('click', function(e) {
        e.preventDefault();
        $('#config-panel').hide();
    });
    
    
    $('#color-options a').on('click', function(e) { 
        var $styleSheet = $(this).attr('data-style');
		$('#theme-style').attr('href', $styleSheet);	
				
		var $listItem = $(this).closest('li');
		$listItem.addClass('active');
		$listItem.siblings().removeClass('active');
		
		e.preventDefault();
		
	});


    /* ======= Sign up ======= */

    $('.form-control').focus(function(){
        $('.signup-error').css('display','none');
        $(this).css('border','1px solid #999999');
    })

    $("#signup-btn").click(function(){
        var email=$("#signup-email").val();
        var password=$("#signup-password").val();
        var caregiver;
        if ($('#care-yes').is(':checked')){
            caregiver='yes';
        } else {
            caregiver='no';
        }

        if (email==''){
            $("#signup-email").css("border","1px solid red");
            $("#email-error").css("display","block");
            return false;
        }
        else if (password==''){
            $("#signup-password").css("border","1px solid red");
            $("#password-error").css("display","block");
            return false;
        } else {
            if (caregiver=='yes'){
                if (confirm('Are you sure you want to be a caregiver?')){
                    window.location.href="registration_caregiver.html";
                    return false;
                }
            } else if(caregiver=='no') {
                if (confirm('Are you sure you want to signup in to join a family/friend group?')){
                    window.location.href="registration_family.html";
                    return false;
                }
            }
        }
    })

    $("#elder-user").click(function(){
        $(".device-Info").css("display","block");
    })

    $("#admin-user,#other-user").click(function(){
        $(".device-Info").css("display","none");
    })

    function uploadImage(input){
        if (input.files && input.files[0]){
            var reader = new FileReader();
            //var img = document.createElement("img");
            reader.onload = function(e){
                $('#family-image-upload').src=reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function sameInfo(regis,pay){
        var info=$(regis).val();
        $(pay).val(info);
    }
    $("#same-info-check").click(function () {

        sameInfo("#regis-Fname","#regis-pay-Fname");
        sameInfo("#regis-Lname","#regis-pay-Lname");
        sameInfo("#regis-email","#regis-pay-email");
        sameInfo("#regis-phone","#regis-pay-phone");
        sameInfo("#regis-add1","#regis-pay-add1");
        sameInfo("#regis-add2","#regis-pay-add2");
        sameInfo("#regis-city","#regis-pay-city");
        sameInfo("#regis-state","#regis-pay-state");
        sameInfo("#regis-zip","#regis-pay-zip");
    })
    $("#pro-user,#nopro-user").click(function(){
        $("#pay-roll").css("display","block");
    })
    $("#vount-user").click(function(){
        $("#pay-roll").css("display","none");
    })

    function validate(){
        var output = true;

        return output;
    }

    $("#next").click(function(){
        var output = validate()
        if (output){
            var current = $(".active");
            var next = $(".active").next("li");
            if (next.length > 0) {
                $("#"+current.attr("id")+"-field").hide();
                $("#"+next.attr("id")+"-field").show();
                $("#back").show();
                $("#finish").hide();
                $(".active").removeClass("active");
                next.addClass("active");
                if ($(".active").attr("id")==$(".family-step li").last().attr("id")) {
                    $("#next").hide();
                    $("#finish").show();
                }
            }
        }
    })

    $("#back").click(function(){
        var current = $(".active");
        var prev = $(".active").prev("li");
        if (prev.length > 0) {
            $("#"+current.attr("id")+"-field").hide();
            $("#"+prev.attr("id")+"-field").show();
            $("#next").show();
            $("#finish").hide();
            $(".active").removeClass("active");
            prev.addClass("active");
            if ($(".active").attr("id")==$("li").first().attr("id")) {
                $("#back").hide();
            }
        }
    })




});