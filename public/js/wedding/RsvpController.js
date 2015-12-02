RsvpController = function() {
    /**
     * Search for a code
     * @returns void
     */
    this.search = function() {
        var code = $("#code").val();
        if(code.length==0){
            RsvpController.showError();
            return false;
        }
        $.ajax({
            url: "/rsvp/search-for-code/"+code,
            method: "POST",
            dataType: "json",
        }).done(function(callback) {
            var obj = jQuery.parseJSON(callback.model);
            console.log(obj);
            if(obj.model=="CODE NOT FOUND"){
                RsvpController.showError();
            }else{
                $("#name").html(obj.name);
                if(obj.plusOneName==""){
                    obj.plusOneName = "GUEST";
                }
                if(obj.plusOneName=="NO GUEST"){
                    $("#guestContainer").hide();
                }else{
                    $("#guestContainer").show();
                }
                $("#guestName").html(obj.plusOneName);
                //do the radios
                if(obj.plusOneStatus=="Unknown"){
                    $( "input[name='rsvpType'][value='Coming']").prop('checked', true);
                }else if(obj.plusOneStatus=="Coming"){
                    $( "input[name='rsvpType'][value='Coming']").val("Coming");
                }else if(obj.plusOneStatus=="Not coming"){
                    $( "input[name='rsvpType'][value='Not coming']").prop('checked', true);
                }
                if(obj.plusOneStatus=="Unknown"){
                    $( "input[name='rsvpTypePlusOne'][value='Coming']").prop('checked', true);
                }else if(obj.plusOneStatus=="Coming"){
                    $( "input[name='rsvpTypePlusOne'][value='Coming']").val("Coming");
                }else if(obj.plusOneStatus=="Not coming"){
                    $( "input[name='rsvpTypePlusOne'][value='Not coming']").prop('checked', true);
                }
                $("#rsvpContainer").show();
            }
        });
    };
    
    /**
     * Show the error message if no code was found
     * @returns void
     */
    this.showError = function() {
        $("#errorModal").modal('show');setTimeout(function(){$("#errorModal").modal('hide');}, 6000);
    };
};
RsvpController = new RsvpController();