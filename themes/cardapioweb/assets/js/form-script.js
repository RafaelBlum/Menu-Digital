$(function () {

    /* ========================================= */
    /*        AJAX FORM SEARCH / LOAD            */
    /* ========================================= */
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {

                /* ==== redirect on page */
                if (response.redirect) {
                    window.location.href = response.redirect;
                }else{
                    load.fadeOut(200);
                }

                /* ==== message flash */
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            }
        });
    });


    /* ========================================= */
    /*      VALIDATE AND VIEW USER PASSWORD      */
    /* ========================================= */

    /* ==== get the password entered */
    const passwordInput = document.querySelector(".input_valid input");
    /* ==== get tag class */
    const icon = document.querySelector(".ri-checkbox-blank-circle-line");
    /* ==== get content from each line of the list */
    const requirementList = document.querySelectorAll(".requirement-list li");

    /* ==== create the regex validations */
    const requirements = [
        {regex: /.{8,}/, index: 0},         // Minimum fo 8 characters
        {regex: /[0-9]/, index: 1},         // At least 1 number
        {regex: /[a-z]/, index: 2},         // At least 1 lowercase letter
        {regex: /[^A-Za-z0-9]/, index: 3},  // At least 1 special symbol (!...$)
        {regex: /[A-Z]/, index: 4},         // At least 1 uppercase letter
    ];

    /* ==== result input event creation */
    passwordInput.addEventListener("keyup", function (event) {

        requirements.forEach(item => {
            const isValid = item.regex.test(event.target.value);
            const requirementItem = requirementList[item.index];

            if(isValid){
                requirementItem.classList.add("valid");
                requirementItem.firstElementChild.className = "ri-checkbox-circle-line";
            }else{
                requirementItem.classList.remove("valid");
                requirementItem.firstElementChild.className = "ri-checkbox-blank-circle-line";
            }

        });
    });


});